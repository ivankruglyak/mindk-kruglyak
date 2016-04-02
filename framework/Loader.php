<?php
/**
 * Loader class
 */
class Loader
{
    protected static $instance = null;
    protected static $namespaces = array();

    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private static function load($classname){
        $namespaces = self::$namespaces;
        $expl = explode('\\', $classname);
        foreach ($namespaces as $namespace => $nspath) {
            if ($expl[0] . '\\' == $namespace) {
                $reg_path = $nspath;
                break;
            }
        }
        $path = str_replace($expl[0],'',$classname);
        $dir  = !empty($reg_path) ? $reg_path : __DIR__;
        $path = $dir . str_replace("\\","/", $path) . '.php';
        if(file_exists($path)){
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
    public static function addNamespacePath($namespace, $path){
        self::$namespaces[$namespace] = $path;
    }
}
Loader::getInstance();