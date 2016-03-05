<?php
/**    
 * Response.php
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


namespace Framework\Response;


class Response {

	protected $headers = array();

	public $code = 200;
	public $content = '';
	public $type = 'text/html';

	private static $msgs = array(
		200 => 'Ok',
	    404 => 'Not found'
	);

	public function __construct($content = '', $type = 'text/html', $code = 200){
		$this->code = $code;
		$this->content = $content;
		$this->type = $type;
		$this->setHeader('Content-Type', $this->type);
	}

	public function send(){

		$this->sendHeaders();
		$this->sendBody();
	}

	public function setHeader($name, $value){
		$this->headers[$name] = $value;
	}

	public function sendHeaders(){
		header($_SERVER['SERVER_PROTOCOL'].' '.$this->code.' '.self::$msgs[$this->code]);

		foreach($this->headers as $key => $value){
			header(sprintf("%s: %s", $key, $value));
		}
	}

	public function sendBody(){
		echo $this->content;
	}
} 