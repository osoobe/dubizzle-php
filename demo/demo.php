<?php
ini_set('display_errors',1);
ini_set('html_errors',1);

require_once "../vendor/autoload.php";

use Dubizzle\Search;
use Dubizzle\Category;

?>

<h3>Dubizzle query demo</h3>

<form method="get">
    <label style="display: inline-block; width: 100px">City:</label>
    <select name="city">
        <?php
        foreach(Category::$uae["cities"]["options"] as $key => $value){
            ?>
        <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
        <?php
        }
        ?>
    </select>
    <br/>
    <br/>


    <label style="display: inline-block; width: 100px">Section:</label>
    <select name="section">
        <?php
        foreach(Category::$uae["sections"]["options"] as $key => $value){
            ?>
        <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
        <?php
        }
        ?>
    </select>
    <br/>
    <br/>


    <label style="display: inline-block; width: 100px">Output Format:</label>
    <select name="format">
        <option value="html">HTML</option>
        <option value="json">JSON</option>
    </select>
    </br>
    </br>
    <input value="Submit" type="submit" />

</form>

<?php
if(isset($_GET["city"]) && isset($_GET["section"]) && isset($_GET["format"]) ){
    $params = ["country"=>'uae', "city"=>$_GET["city"], "section"=>$_GET["section"]];
    $uea = new Search($params);
    $query = $uea->search();
    $query->fetch();

    $results = $query->get_results();
    if($_GET["format"] == "json"){
        echo json_encode($results);
    }else{
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
    }
}
?>
