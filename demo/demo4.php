<?php

require_once "../vendor/autoload.php";

use Dubizzle\Search;

$params = array(
"country" => 'all',
"city" => 'all',
"section" => 'motors',
"category" => 'cars',
"make" => 'audi',
"model" => '1187', //A4
"body_type"=>'348', //Sedan
"min_year" => '2005',
"max_year" => '2005',
"num_results" => 'all');

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
