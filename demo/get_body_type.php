<?php

require_once "../vendor/autoload.php";

use Dubizzle\Category;


$c = new Category();
$models = $c->get_models(Category::$uae["makes"]["options"]['audi']);


if(isset($_GET["format"]) && $_GET["format"] == "json"){
    echo json_encode($c->body_types);
}else{
    foreach($c->body_types as $type){
        echo "<hr/>";
        var_dump($type);
        echo "<br/><hr/>";
    }
}

?>
