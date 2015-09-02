<?php

require_once "../vendor/autoload.php";

use Dubizzle\Search;

if(!empty($_GET)){
    $uae = new Search($_GET);
    $query = $uae->search();
    $query->fetch();
    $results = $query->get_results();
    echo json_encode($results);
}else{
    echo json_encode([]);
}

?>
