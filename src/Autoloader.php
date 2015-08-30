<?php
if (!function_exists('autoload')) {
function autoload($className){
    $module_container = dirname(__FILE__).DIRECTORY_SEPARATOR;
    $module = $className;
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= $className.'.php';
    $fileName =  $module_container.$fileName;
    for($i = 0; $i < 5; $i++){
    //echo $fileName."<br/>";
        if(file_exists($fileName)){
            require_once $fileName;
            break;
        }else
            $fileName = "..".DIRECTORY_SEPARATOR.$fileName;
    }
}
spl_autoload_register('\autoload');
}
?>
