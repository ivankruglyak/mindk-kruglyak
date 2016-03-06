<?php
/**    
 * Service.php
 * 
 * PHP version 5
 *
 * @category   Category Name
 * @package    Package Name
 * @subpackage Subpackage name
 * @author     dimmask <ddavidov@mindk.com>
 * @copyright  2011-2013 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com Commercial
 * @link       http://mindk.com
 */


namespace Framework\DI;


class Service {

	protected static $services = array();

	public static function set($service_name, $obj){
		self::$services[$service_name] = $obj;
	}

	public static function get($service_name){
		return empty(self::$services[$service_name]) ? null : self::$services[$service_name];
	}
} 