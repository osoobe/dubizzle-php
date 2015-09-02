<?php

require_once "../vendor/autoload.php";

use Dubizzle\Category;


$c = new Category();

if($_GET["type"] == "make"){
    $car_ids = Category::$uae["categories"]["options"]['cars'];
    $data = $c->get_models($car_ids);
    echo json_encode($data);

}elseif($_GET["type"] == "model" && isset($_GET["make"])){
    $make = Category::$uae["makes"]["options"][$_GET["make"]];
    $data = $c->get_models($make);
    echo json_encode($data);

}elseif($_GET["type"] == "body_type" && isset($_GET["model"])){

    if($_GET["model"] == "--"){
        $_GET["model"] = 1855;
    }
    $c->get_models($_GET["model"]);
    echo json_encode($c->body_types);

}




?>
