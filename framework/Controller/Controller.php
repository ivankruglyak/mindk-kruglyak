<?php

namespace Framework\Controller;

use Framework\DI\Service;
use Framework\Response\Response;

/**
 * Class Controller
 * Controller prototype
 *
 * @package Framework\Controller
 */
abstract class Controller {

	/**
	 * Rendering method
	 *
	 * @param   string  Layout file name
	 * @param   mixed   Data
	 *
	 * @return  Response
	 */
	public function render($layout, $data = array()){

		$view_folder = str_replace('Controller', '', get_class($this));
		$fullpath = realpath('../views/' . $view_folder . $layout);

		$renderer = Service::get('renderer');

		$content = $renderer->render($fullpath, $data);

		return new Response($content);
	}

	public function getRequest()
	{
		return Service::get('request');
	}
} 