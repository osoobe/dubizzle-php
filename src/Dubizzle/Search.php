<?php

namespace Dubizzle;

require_once 'lib/util.php';
require_once 'lib/region.php';

class Search{

    public static $uae;

    /**
     * Build search
     * @param array $args=[]  Parameters for the search.
     * @param int $num_results=50 number of results to show.
     */
    public function __construct(array $args=[], $num_results=50){
        # General parameters
        $keyword = get_arg($args, 'keyword', '');
        $city = get_arg($args, 'city', 'all');
        $section = get_arg($args, 'section', 'all');
        $category = get_arg($args, 'category', 'all');
        $min_price = get_arg($args, 'min_price', '');
        $max_price = get_arg($args, 'max_price', '');
        $added_days = get_arg($args, 'added_days', 30);

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

        $this->params = [
            static::$uae['cities']['code'] => static::$uae['cities']['options'][$city],
            static::$uae['sections']['code'] => static::$uae['sections']['options'][$section],
            static::$uae['categories']['code'] => static::$uae['categories']['options'][$category],
            static::$uae['makes']['code'] => static::$uae['makes']['options'][$make],
            'keywords' => $keyword,
            'price__gte' => $min_price,
            'price__lte' => $max_price,
            'added__gte' => $added_days,
            'year__gte' => $min_year,
            'year__lte' => $max_year,
            'kilometers__gte' => $min_kms,
            'kilometers__lte' => $max_kms,
            'seller_type' => static::$uae['motors_options']['seller'][$seller],
            'fuel_type' => static::$uae['motors_options']['fuel'][$fuel],
            'no._of_cylinders' => static::$uae['motors_options']['cylinders'][$cylinders],
            'transmission_type' => static::$uae['motors_options']['transmission'][$transmission]
        ];
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
        $curl = curl_query(static::$uae['base_url'], $this->params, $headers);
        return new Results($curl->response, $this->num_results, static::$uae['base_url'], true);
    }

}

UAE::$uae = $uae;

?>
