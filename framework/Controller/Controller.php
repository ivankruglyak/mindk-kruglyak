<?php

namespace Framework\Controller;

use Framework\DI\Service;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;

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
	public function render($layout, $data = array())
	{
        $folder 	 = get_class($this);
        $expl   	 = explode('\\', $folder);
        $app    	 = $expl[0];
        $splice 	 = array_splice($expl, 0, 1);
        $folder 	 = join('/', $expl) . '/';
        $view_folder = str_replace('Controller', '', $folder);
        $fullpath 	 = realpath('../src/' . $app .'/views' . $view_folder . $layout . '.php');

        $renderer 	 = Service::get('renderer');

        $renderer->assign($data);
		$content 	 = $renderer->render($fullpath);

		return new Response($content);
	}

	/**
	 * Get request
	 *
	 * @return null
	 */
	public function getRequest()
	{
		return Service::get('request');
	}

	/**
	 * Redirect
	 *
	 * @param $url
	 * @param null $message
	 * @param int $code
	 * @return ResponseRedirect
	 */
	public function redirect($url, $message = null, $code = 301)
	{
		if (isset($message)) {
			Service::get('session')->addFlush($message, 'success');
		}

        $responseRedirect =  new ResponseRedirect($url, null, $message);
		return $responseRedirect;
	}
} 