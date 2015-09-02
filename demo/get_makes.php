<?php

require_once "../vendor/autoload.php";

use Dubizzle\Category;


$c = new Category();
$models = $c->get_makes(Category::$uae["categories"]["options"]['cars']);

//var_dump($models["models"]);
if(isset($_GET["format"]) && $_GET["format"] == "json"){
    echo json_encode($models);
}else{
    foreach($models as $model){
        echo "<hr/>";
        echo "Model ID:       $model[0]";
        echo "<br/>";
        echo "Model Name:     $model[1]";
        echo "<br/><hr/>";
    }
}

?>
