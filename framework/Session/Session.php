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

	public function __construct(){
		session_start();
//		if (!array_key_exists('messages', $_SESSION)) {
//			$_SESSION['messages'] = array('info'=>array(), 'error'=>array(), 'success'=>array());
//		}
	}

	public function __set($name, $val){
		$_SESSION[$name] = $val;
	}

	public function __get($name)
	{
		$result = null;
		if (array_key_exists($name, $_SESSION)) {
			$result = $_SESSION[$name];
		}
		return $result;
	}

	public function addFlush($type, $message)
	{
		$_SESSION['messages'][$type][] = $message;
//		$this->messages[$type][] = $message;
	}

	public function getFlush()
	{
		$messages = $_SESSION['messages'];
		$_SESSION['messages'] = array();
//		$this->messages = array();
		return $messages;
	}
} 