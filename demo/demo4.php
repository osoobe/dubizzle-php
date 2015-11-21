<?php
ini_set('display_errors',1);
ini_set('html_errors',1);
require_once "../vendor/autoload.php";

use Dubizzle\Search;

$params = array(
"country" => 'uae',
"city" => 'all',
"section" => 'motors',
"category" => 'cars',
"make" => 'toyota',
"model" => '1588',  //Corola
"body_type"=>'348',  //Sedan
"min_year" => '2007',
"max_year" => '2007',
"num_results" => 'all',
"added_days"=>'all');


$uae = new Search($params, 50);
$query = $uae->search();
$query->fetch();
$results = $query->get_results();
$result_count = count($results);
echo "URL:            ".$uae->query_url();
echo "<br/>";
echo "Num. per page:  ".$uae->num_results_on_page;
echo "<br/>";
echo "Num. Results:   ".$result_count;
echo "<br/>";
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
