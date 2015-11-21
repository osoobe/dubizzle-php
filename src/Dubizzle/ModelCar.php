<?php

namespace Dubizzle;
use PHPHtmlParser\Dom;
use HTMLPurifier;
use Curl\MultiCurl;
require_once 'lib/util.php';

class ModelCar{
    
    public $url;
    
    public static function multiple_fetch(array $urls){
        $pages = [];
        $multi_curl = new MultiCurl();
        $multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $multi_curl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
        $multi_curl->setOpt(CURLOPT_TIMEOUT, 0);
        $multi_curl->success(function($page) {
            $dcar = new static();
            $dcar->url = $page->url;
            $dcar->parse_page($page->response);
            array_push($pages, $page);
        });
        foreach($urls as $url){
            $multi_curl->addGet($url);
        }
        $multi_curl->start();
        return $pages;
    }
    
    public function fetch_page($url){
        $this->url = $url;
        $curl = curl_query($this->url);
        $data = $this->parse_page($curl->response);
    }
    
    public function parse_page($html){
        # Clean HTML.
        $purifier = new HTMLPurifier();
        $clean_html = $purifier->purify($html);

        # Build a HTML parser to search for items.
        $this->dom = new Dom;
        $this->dom->load($clean_html);
        
        $this->title = $this->dom->find("h1 span.title")->text;
        $this->price = $this->dom->find("h1 span span")->text;
        
        $this->parse_li_s($this->dom->find('ul.important-fields li'));
        
        $ul2 = $this->dom->find('ul.important-fields')->nextSibling();
        if(!empty($ul2)){
            $this->parse_li_s($ul2->find("li"));
            $ul3 = $ul2->nextSibling();
            if(!empty($ul3)){
                $this->parse_li_s($ul3->find("li"));
            }
        }
    }
    
    public function parse_li_s($li_s){
        foreach($li_s as $li){
            $col = str_replace(" ", "_", strtolower(trim($li->find("span")->text)));
            $this->$col = trim($li->find("strong")->text);
        }
    }
    
    /**  As of PHP 5.1.0  */
    public function __isset($name){
        return isset($this->$name);
    }

    /**
    * Get values of the object
    *
    * @return mixed, value that is stored in a class variable
    */
    public function __get($name) {
        if(isset($this->$name)){
            return $this->$name;
        }
        return null;
    }
}

?>