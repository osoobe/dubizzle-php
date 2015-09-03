<?php

require_once "../vendor/autoload.php";

use Dubizzle\Search;

$params = [
    "keyword"=>'altima',
    "country"=>'uae',
    "city"=>'dubai',
    "section"=>'motors',
    "category"=>'cars',
    "make"=>'nissan',
    "min_year"=>2007,
    "num_results"=>'all'];

$uae = new Search($params, 50);

$query = $uae->search();
$query->fetch();
$results = $query->get_results();

$result_count = count($results);
$total_price = 0;
foreach($results as $result){
    $total_price += $result["price"];
}

echo "Num. Results:   ".$result_count;
echo "<br/>";
echo "<br/>";
echo "Average price:  ".(intval($total_price / $result_count)); # Prints 39239.94

?>
