<?php

namespace Dubizzle;

use PHPHtmlParser\Dom;
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
        # Initialize a HTML cleaner object.
        $tidy = new \tidy;
        $config = array(
                   'indent'         => true,
                   'output-xhtml'   => true,
                   'wrap'           => 200);
        # Fix HTML errors.
        $tidy->parseString($html, $config, 'utf8');
        $tidy->cleanRepair();

        # Get the clean HTML string.
        $tidyHTML = tidy_get_output($tidy);

        # Build a HTML parser to search for items.
        $this->dom = new Dom;
        $this->dom->load($tidyHTML);

        $this->num_results = $num_results;
        $this->url = $url;
        $this->detailed = false;
    }

    /**
     * Get items from HTML.
     * This is mainly used to capture other items that is not on the initial
     * result page.
     * @param  string $html HTML string.
     * @return array    List of items that was found on the current html page.
     */
    public static function get_more_results($html){
        # Initialize a HTML cleaner object.
        $tidy = new \tidy;
        // Specify configuration
        $config = array(
                   'indent'         => true,
                   'output-xhtml'   => true,
                   'wrap'           => 200);
        # Fix HTML errors.
        $tidy->parseString($html, $config, 'utf8');
        $tidy->cleanRepair();

        # Get the clean HTML string.
        $tidyHTML = tidy_get_output($tidy);

        # Build a HTML parser to search for items.
        $dom = new Dom;
        $dom->load($tidyHTML);

        # Get result items from the HTML.
        $items = $dom->find(".listing-item");
        if(empty($items)){
            return [];
        }
        return $items;
    }

    /**
     * Convert HTML elements for each item into qualitative data.
     * @param  array $items List of items (Elements).
     * @return array List of associated array for item information for a specifc page.
     */
    private static function parse_items(array $items){
        $results = [];
        foreach($items as $item){
            $title = $item->find('.title a')[0];
            try{
                $item_data = [
                    "title" => $title->text,
                    "date" => $item->find('.date')[0]->text,
                    "url" => $title->getAttribute("href"),
                    "location" => $item->find('.location')[0]->text
                ];

                $item_data["price"] = $item->find('.price')[0]->text;

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
    public function fetch(){
        # Track time;
        $this->time = time();

        # Get all the cars on a single result page.
        $items = $this->dom->find(".listing-item");
        if(empty($items)){
            return [];
        }
        $this->results = static::parse_items($items);
        $num_results_on_page = count($items);

        # Get the total number of pages.
        try{
            $last_page_query = $this->dom->find('.paging_forward #last_page')->getAttribute("href");
            preg_match("/page=(\d+)/", $last_page_query, $num_pages_query);
            $this->num_pages = intval(explode("=", $num_pages_query[0])[1]);
        }catch(Exception $e){
            $this->num_pages = 1;
        }

        # Make sure num_results is less than total results
        $this->total_results = $num_results_on_page * $this->num_pages;
        if($this->num_results > $this->total_results || $this->num_results == "all"){
            $this->num_results = $this->total_results;
        }

        $page_needed = intval($this->num_results / $num_results_on_page);
        $page_urls = [$this->url];
        $pages = $this->dom->find(".pages .page-links");
        for($i = 0; $i < $page_needed; $i++){
            array_push($page_urls, $this->url."".$pages[$i]->getAttribute("href"));
        }
        # Fetch additional pages.
        $this->getOtherPages($page_urls);
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
