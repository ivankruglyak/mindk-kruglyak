<?php
/**    
 * Request.php
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


namespace Framework\Request;


class Request {

	public function getMethod(){
		return $_SERVER['REQUEST_METHOD'];
	}

	public function isPost(){
		return ($this->getMethod()=='POST');
	}

	public function isGet(){
		return ($this->getMethod()=='GET');
	}

	public function getHeaders($header = null){

		$data = apache_request_headers();

		if(!empty($header)){
			$data = array_key_exists($header, $data) ? $data[$header] : null;
		}

		return $data;
	}

	public function post($varname = '', $filter = 'STRING'){

		return filter($_POST[$varname], $filter);
	}

	protected function filter($value, $filter = 'STRING'){
		// @TODO: ...
	}
}
