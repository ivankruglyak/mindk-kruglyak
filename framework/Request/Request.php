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

		return $_POST[$varname];
		//@TODO: Decomment the string below and comment the steing in above after filter realization
//		return $this->filter($_POST[$varname], $filter);
	}

	protected function filter($value, $filter = 'STRING'){
        //return false;
		// @TODO: To Vanya. Finish filtering: clearing input invisible characters,
		// sanitizing and injection protection
		$md5_val= md5($value);
		$md5_inv = $this->random_word(rand(4,8));
		$filter = strval($md5_val . md5($md5_inv));
		return $filter;
	}
	public function random_word($word_len)
	{
		$symbols = "QwertyuiOPASdfgHjklZxCvbNm";
		$word = '';
        $i = 0;
		while ($i < $word_len){
			$word .= $symbols[mt_rand(0, strlen($symbols)-1)];
			$i ++;
		}
		return $word;
	}

	public function getUri()
	{
		return $_SERVER['REQUEST_URI'];
	}
}
