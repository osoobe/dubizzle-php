<?php

require_once "../vendor/autoload.php";

use Dubizzle\Search;

$params = array(
"country" => 'uae',
"city" => 'all',
"section" => 'motors',
"category" => 'cars',
"make" => 'chevrolet',
"model" => '1239', //Corvette
"body_type"=>'347', //Coupe
"min_year" => '2011',
"max_year" => '2011',
"num_results" => '100',
"added_days"=>'');

$uae = new Search($params, 50);
echo $uae->query_url();
echo "<br/><br/>";
$query = $uae->search();
$query->fetch();
$results = $query->get_results();
foreach($results as $item){
    ?>
    <p>
        <label style="display: inline-block; width: 100px">Title:</label>
        <?php echo $item["title"]; ?>
    </p>
    <p>
        <label style="display: inline-block; width: 100px">URL:</label>
        <?php echo $item["url"]; ?></p>
    <p>
        <label style="display: inline-block; width: 100px">Location:</label>
        <?php echo $item["location"]; ?></p>
    <p>
        <label style="display: inline-block; width: 100px">Date:</label>
        <?php echo $item["date"]; ?></p>
    <p>
        <label style="display: inline-block; width: 100px">Category:</label>
        <?php echo $item["category"]; ?></p>
    <p>
        <label style="display: inline-block; width: 100px">Price:</label>
        <?php echo $item["price"]; ?></p>
    <p>
        <label style="display: inline-block; width: 100px">Currency:</label>
        <?php echo $item["currency"]; ?></p>
    <hr/>
    <hr/>
    <?php
}
?>
