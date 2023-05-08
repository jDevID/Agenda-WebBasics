<?php
# Charge les classes PHP quand nécessaire
function load($class){
    $pathModels = __DIR__ . '/' . $class . '.class.php';
    $pathDAL = __DIR__ . '/../data/' .$class . '.class.php';
    if(file_exists($pathModels)){
        require_once($pathModels);
    }
    elseif(file_exists($pathDAL)){
        require_once($pathDAL);
    }
}
# inscrire load() en tant que fonction auto-loading
spl_autoload_register("load");

?>
