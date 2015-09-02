<?php

require_once "../vendor/autoload.php";

use Dubizzle\Search;
use Dubizzle\Category;


$category = new Category();
?>

<style>
ul{
    padding: 0;
}
ul li{
  list-style: none;
  display: inline-block
}
ul li a{
    display: inline-block;
    min-width: 80px;
    padding: 20px;
    color: black;
    text-decoration: none;
}
select{
    display: block;
    width: 100%;
    margin: 10px auto;
}
.selectors{
    width: 475px;
}
.form-capture{
    display: none;
}
.form-capture.active{
    display: block;
}

.form-capture{
    background: blue;
}
a.form-selector.active{
    background: blue;
    color: white;
}
fieldset{
    margin: 0;
}
ul{
    margin-bottom: 0;
}
select{
    background: #FFF none repeat scroll 0% 0%;
    color: #333;
    border-radius: 5px;
    padding: 8px 15px;
    text-transform: uppercase;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 7px;
    width: 100%;
    border: medium none;
}
button{
    background: green;
    color: white;
    border-radius: 5px;
    padding: 8px 20px;
    text-transform: uppercase;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 7px;
    border: medium none;
    margin: 0 auto;
    display: block;
    cursor: pointer;
}
</style>

<div id="container">
    <ul>
        <li>
            <a  class="form-selector active" href="#select_car">Select You Car</a>
        </li>
        <li>
            <a  class="form-selector" href="#select_model">Model & Condition</a>
        </li>
    </ul>
    <div class="selectors">
        <form>
            <fieldset id="select_car" class="form-capture active">
                <select id="make" name="make">
                <?php
                    foreach(Category::$uae["makes"]["options"] as $key => $value){
                ?>
                    <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                <?php
                    }
                ?>
                </select>
                <select disabled id="model" name="model">
                    <option value="--">All Models</option>
                </select>

                <select disabled id="year" name="year">
                <?php
                    $year = intval(date("Y"));
                    for($i = 0; $i < 20; $i++){
                        $y = $year - $i;
                    ?>
                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                    <?php
                    }
                ?>
                </select>
                <div>
                    <button id="first-btn" type="button" disabled >Continue</button>
                </div>
            </fieldset>

            <fieldset id="select_model" class="form-capture" >
                <select id="body_type" name="body_type" disabled>
                    <option value="--">All Models</option>
                </select>
                <select id="kilometers" name="kilometers" disabled>
                    <?php
                    $upper = 0;
                    for($i = 0; $i < 8; $i++){
                        $lower = $i * 20000;
                        $upper = ($i + 1) * 20000;
                        ?>
                        <option value="<?php echo $lower.'-'.$upper; ?>">
                            <?php echo $lower.' - '.$upper; ?>
                        </option>
                    <?php
                    }
                    ?>
                    <option value="<?php echo $upper.'-'.(20000 * 10); ?>">
                        <?php echo $upper.'-'.(20000 * 10); ?>
                    </option>
                </select>
                <div>
                    <button id="second-btn" type="button" disabled >Continue</button>
                </div>
            </fieldset>
        </form>

    </div>


    <div id="live_results"></div>
</div>
<script src="static/jquery.min.js"></script>
<script src="static/demo3.js"></script>
