<?php

namespace Dubizzle;

require_once 'lib/util.php';

class Search{

    /**
     * Build search
     * @param array $args=[]  Parameters for the search.
     * @param int $num_results=50 number of results to show.
     */
    public function __construct(array $args=[], $num_results=30){
        # General parameters
        $keyword = get_arg($args, 'keyword', '');
        $city = get_arg($args, 'city', 'all');
        $section = get_arg($args, 'section', 'all');
        $category = get_arg($args, 'category', 'all');
        $min_price = get_arg($args, 'min_price', '');
        $max_price = get_arg($args, 'max_price', '');
        $added_days = get_arg($args, 'added_days', '');

        # Motors only
        $make = get_arg($args, 'make', 'all');
        $min_year = get_arg($args, 'min_year', '');
        $max_year = get_arg($args, 'max_year', '');
        $min_kms = get_arg($args, 'min_kms', '');
        $max_kms = get_arg($args, 'max_kms', '');
        $seller = get_arg($args, 'seller', 'all');
        $fuel = get_arg($args, 'fuel', 'all');
        $cylinders = get_arg($args, 'cylinders', 'all');
        $transmission = get_arg($args, 'transmission', 'all');

        $this->num_results = $num_results;
        $this->detailed = false;

        $body_type = get_arg($args, "body_type", null);
        $model = get_arg($args, "model", null);

        if(isset(Category::$uae['makes']['options'][$make])){
            $make = Category::$uae['makes']['options'][$make];
        }
        if(isset(Category::$uae['cities']['options'][$city])){
            $city = Category::$uae['cities']['options'][$city];
        }
        if(isset(Category::$uae['sections']['options'][$section])){
            $section = Category::$uae['sections']['options'][$section];
        }
        if(isset(Category::$uae['categories']['options'][$category])){
            $category = Category::$uae['categories']['options'][$category];
        }
        if(isset(Category::$uae['motors_options']['seller'][$seller])){
            $seller = Category::$uae['motors_options']['seller'][$seller];
        }
        if(isset(Category::$uae['motors_options']['fuel'][$fuel])){
            $fuel = Category::$uae['motors_options']['fuel'][$fuel];
        }
        if(isset(Category::$uae['motors_options']['cylinders'][$cylinders])){
            $cylinders = Category::$uae['motors_options']['seller'][$cylinders];
        }
        if(isset(Category::$uae['motors_options']['transmission'][$transmission])){
            $transmission = Category::$uae['motors_options']['transmission'][$transmission];
        }

        $this->params = [
            Category::$uae['cities']['code'] => $city,
            Category::$uae['sections']['code'] => $section,
            Category::$uae['categories']['code'] => $category,
            Category::$uae['makes']['code'] => $make,
            'keywords' => $keyword,
            'price__gte' => $min_price,
            'price__lte' => $max_price,
            'added__gte' => $added_days,
            'year__gte' => $min_year,
            'year__lte' => $max_year,
            'kilometers__gte' => $min_kms,
            'kilometers__lte' => $max_kms,
            'seller_type' => $seller,
            'fuel_type' => $fuel,
            'no._of_cylinders' => $cylinders,
            'transmission_type' => $transmission
        ];

        if($body_type != null){
            $this->params["body_type"] = $body_type;
        }
        if($model != null){
            $this->params["c2"] = $model;
        }
    }

    public function query_url(){
        return Category::$uae['base_url']."?".http_build_query($this->params);
    }

    /**
     * Trigger search for items.
     * @return Dubizzle\Results - the query result.
     */
    public function search(){
        $headers = [
            'User-Agent' =>'SkyNet Version 4.4 Revision 12',
            'Description' => 'https://github.com/Cyph0n/dubizzle'
        ];
        $curl = curl_query(Category::$uae['base_url'], $this->params, $headers);
        $result_url = get_curl_response_url($curl->requestHeaders);
        return new Results($curl->response, $this->num_results, $result_url, true);
    }

}

?>
