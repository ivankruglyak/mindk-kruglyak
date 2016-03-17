<?php
/**    
 * Session.php
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


namespace Framework\Session;


class Session {

	public $messages = [];

	public function __construct(){
		session_start();
	}

	public function __set($name, $val){

	}

	public function __get($name){

	}

	public function addFlash($type, $message){
		$_SESSION['messages'][$type][] = $message;
	}
} 