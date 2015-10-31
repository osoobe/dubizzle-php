<?php

namespace Dubizzle;

use PHPHtmlParser\Dom;
use HTMLPurifier;

require_once 'lib/util.php';
require_once 'lib/region.php';

class Category{
    public static $uae;

    public $data = [];



    public function get_models($key = null){
        $model_url = "https://dubai.dubizzle.com/classified/get_category_models/"
                    .$key."/";
        $data = $this->get_data($model_url, ["site" => 2, "s" => "MT"]);
        $this->parseJSON($data);
        return $this->models;
    }

    private function parseHTML(){
        # Clean HTML.
        $purifier = new HTMLPurifier();
        $clean_html = $purifier->purify($html);

        # Build a HTML parser to search for items.
        $this->dom = new Dom;
        $this->dom->load($clean_html);
    }

    private function get_body_type(){
        $this->parseHTML();
        $labels = $this->dom->find("#div_body_type ul li label");
        $body_types = [];
        foreach($labels as $label){
            $body_type = [
                "name" => $label->text,
                "id" => $label->find("input[name='body_type']")->getAttribute("value")
            ];
            array_push($body_types, $body_type);
        }
        return $body_types;
    }

        /**
    * Get values of the object
    *
    * @return mixed, value that is stored in a class variable
    */
    public function __get($name) {
        switch($name){
            case "models":
                return $this->data["models"];
                break;
            case "body_types":
                return $this->get_body_type();
                break;
        }
        return null;
    }


    /**  As of PHP 5.1.0  */
    public function __isset($name){
        return isset($this->$name);
    }

    private function parseJSON($data){
        $this->data = json_decode($data, true);
    }

    /**
     * Trigger search for items.
     * @return Dubizzle\Results - the query result.
     */
    public function get_data($url, $params){
        $headers = [
            'User-Agent' =>'SkyNet Version 4.4 Revision 12',
            'Description' => 'https://github.com/Cyph0n/dubizzle'
        ];
        $curl = curl_query($url, $params, $headers);
        return $curl->response;
    }

}

Category::$uae = $uae;

?>
