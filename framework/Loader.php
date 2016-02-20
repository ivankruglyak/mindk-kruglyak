<?php

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 04.02.16
 * Time: 9:19
 */
class Loader {

    protected static $instance = null;
    protected static $namespaces = array();

    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    public static function load($classname){
//        var_dump($classname); die;
//        foreach (self::$namespaces as $namespace => $_path) {
//            self::incl($_path . '/' . str_replace("\\","", $namespace) . '.php');
//        }
        // @TODO: Add here some registered $namespaces processing...
        $path = self::getPath($classname);
        self::incl($path);
        var_dump(get_included_files()); die;
    }

    private static function getPath($classname)
    {
        $path = str_replace('Framework','',$classname);
        $path = __DIR__ . str_replace("\\","/", $path) . '.php';
        return $path;

    }

    private static function incl($path)
    {
//        var_dump($path);
        if (file_exists($path) && in_array($path, get_included_files())) {
            include_once($path);
        }
    }

    private function __construct(){
        // Init
        spl_autoload_register(array(__CLASS__, 'load'));
    }
    private function __clone(){
        // lock
    }

    /**
     * Add namespace path
     *
     * @param $namespace
     * @param $path
     */
    public static function addNamespacePath($namespace, $path){
        self::$namespaces[$namespace] = $path;
    }
}
Loader::getInstance();