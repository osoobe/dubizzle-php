<?php

namespace Dubizzle;

use PHPHtmlParser\Dom;
use HTMLPurifier;
use Curl\MultiCurl;

class Results{

    private $results = [];
    private $total_results = 0;
    private $num_pages = 0;

    /**
     * Parse the search result and build an array of the result.
     * @param string $html        HTML string
     * @param int $num_results    Number of results to be displayed
     * @param sting $url          Base url of the search result page.
     */
    public function __construct($html, $num_results, $url){
        # Clean HTML.
        $purifier = new HTMLPurifier();
        $clean_html = $purifier->purify($html);

        # Build a HTML parser to search for items.
        $this->dom = new Dom;
        $this->dom->load($clean_html);

        $this->num_results = $num_results;
        $this->url = $url;
        $this->detailed = false;
    }

    /**
     * Get items from HTML.
     * This is mainly used to capture other items that is not on the initial
     * result page.
     * @param  string $html HTML string.
     * @return PHPHtmlParser\Dom[] - List of items that was found on the current html page.
     */
    public static function get_more_results($html){
        # Clean HTML.
        $purifier = new HTMLPurifier();
        $clean_html = $purifier->purify($html);

        # Build a HTML parser to search for items.
        $dom = new Dom;
        $dom->load($clean_html);

        # Get result items from the HTML.
        $items = $dom->find(".listing-item");
        if(empty($items)){
            return [];
        }
        return $items;
    }

    /**
     * Convert HTML elements for each item into qualitative data.
     * @param  PHPHtmlParser\Dom[] $items List of items (Elements).
     * @return array List of associated array for item information for a specifc page.
     */
    private static function parse_items($items){
        $results = [];
        foreach($items as $item){
            $title = $item->find('.title a')[0];
            if(empty($title)){
                $title = $item->find('.featured-ad-title a')[0];
            }
            try{
                $item_data = [
                    "title" => $title->text,
                    "date" => $item->find('.date')[0]->text,
                    "url" => $title->getAttribute("href"),
                    "location" => $item->find('.location')[0]->text
                ];

                $price = trim($item->find('.price')[0]->text);

                $m = explode(" ", $price);
                if(count($m) > 1){
                    $item_data["price"] = intval(str_replace(",", "", $m[1]));
                    $item_data["currency"] = $m[0];
                }else{
                    $item_data["price"] = 0;
                    $item_data["currency"] = "UNKNOWN";
                }

                try{
                    $category = $item->find('.description .breadcrumbs')[0];
                    if(is_object($category)){
                        $item_data["category"] = $category->text;
                    }else{
                        $item_data["category"] = $category;
                    }
                }catch(Exception $e){
                    $item_data["category"] = "";
                }

                $photo_block = $item->find(".thumb div");
                if(!empty($photo_block)){
                    $style = $photo_block->getAttribute("style");
                    preg_match("/http.*\.\w*/", $style, $result);
                    if(!empty($result)){
                        $item_data["photo"] = $result[0];
                    }else{
                        $item_data["photo"] = "";
                    }
                }else{
                    $item_data["photo"] = "";
                }


                array_push($results, $item_data);
            }catch(Exception $e){
                continue;
            }
        }
        return $results;
    }

    /**
     * Build the list of associated array for item information.
     * This function also parse the initial result page and fetch additional
     * pages to build the list of associated arrary for item information.
     */
    public function fetch($start_page = 2){
        # Track time;
        $this->time = time();

        # Get all the cars on a single result page.
        $items = $this->dom->find(".listing-item");
        if(!empty($items)){
            $this->results = static::parse_items($items);
        }
        $featured_items = $this->dom->find(".featured-item");
        if(!empty($featured_items)){
            $this->results = array_merge($this->results,
                                         static::parse_items($featured_items));
        }
        if(empty($this->results)){
            return [];
        }

        $num_results_on_page = count($items);


        # Get the total number of pages.
        $last_page_query = $this->dom->find('.paging_forward #last_page')->getAttribute("href");
        if(empty($last_page_query)){
            $last_page_query = $this->dom->find('.paging_forward a')[1]->getAttribute("href");
        }

        preg_match("/page=(\d+)/", $last_page_query, $num_pages_query);
        if(isset($num_pages_query[0][1])){
            $this->num_pages = intval(explode("=", $num_pages_query[0])[1]);
        }else{
            $this->num_pages = 1;
        }

        # Make sure num_results is less than total results
        $this->total_results = $num_results_on_page * $this->num_pages;
        if($this->num_results > $this->total_results || $this->num_results == "all"){
            $this->num_results = $this->total_results;
        }
        if($num_results_on_page > 0){
            $page_needed = intval($this->num_results / $num_results_on_page );
        }else{
            $page_needed = 0;
        }

        $page_urls = [];
        $base_url = preg_replace("/page=(\d+)/", "", $last_page_query);
        for($i = $start_page; $i <= $page_needed && $i <= $this->num_pages; $i++){
            array_push($page_urls, $this->url."&page=$i");
        }

        # Fetch additional pages.
        $this->getOtherPages($page_urls);

        $first_page = preg_replace("/page=(\d+)/", "page=1", $last_page_query);
        array_unshift($page_urls, $first_page);
    }


    /**
     * Get additional items that are not on the initial result page.
     * Note: threading/multiprocessing is implemented to do multiple curl
     * request at once.
     * @param array $page_urls List of page url;
     */
    public function getOtherPages(array $page_urls){
        $multi_curl = new MultiCurl();
        $multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $multi_curl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
        $multi_curl->setOpt(CURLOPT_TIMEOUT, 0);
        $multi_curl->success(function($page) {
            $items = static::get_more_results($page->response);
            $results = static::parse_items($items);
            $this->results = array_merge($this->results, $results);
        });
        foreach($page_urls as $page_url){
            $multi_curl->addGet($page_url);
        }
        $multi_curl->start();
    }

    /**
     * Get the result.
     * @return array List of associated array for item information for all pages.
     */
    public function get_results(){
        return $this->results;
    }
}


?>
