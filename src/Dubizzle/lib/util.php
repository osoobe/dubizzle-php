<?php

use Curl\Curl;

/**
 * Get array value.
 * @param  array $args     Array of data.
 * @param  string $key     Index or key for the array.
 * @param  mixed $default=null Default value to return if the key wasn't found.
 * @return mixed value of the given key/index or the given default value.
 */
function get_arg(array $args, $key, $default=null){
    if(isset($args[$key])){
        return $args[$key];
    }
    return $default;
}


/**
 * Perform a single curl request.
 * @param  string $url       URL for the search.
 * @param  array $params=[]  Parameters for the search
 * @param  array $headers=[] Headers for the search.
 * @return Curl\Curl Curl object that contains the result information.
 */
function curl_query($url, array $params=[], array $headers=[]){
    $curl = new Curl();
    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
    $curl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
    $curl->setOpt(CURLOPT_TIMEOUT, 0);
    foreach($headers as $header => $value){
        $curl->setHeader($header, $value);
    }
    $curl->get($url, $params);
    //var_dump($curl);
    if ($curl->error) {
        echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
    }
    return $curl;
}

function get_curl_response_url($request_header){
    $request_line = explode(" ", $request_header->offsetGet("Request-Line"))[1];
    if(empty($request_line)){
        return "";
    }
    $base_url = $request_header->offsetGet("Host");
    return (strpos($base_url, 'https') !== false)? $base_url.$request_line : "https://$base_url".$request_line;
}


?>
