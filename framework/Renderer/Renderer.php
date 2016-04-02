<?php

namespace Framework\Renderer;

use Framework\DI\Service;
use Framework\Exception\RendererException;

/**
 * Class Renderer
 * @package Framework\Renderer
 */
class Renderer {

    protected $data = array();

	/**
	 * @var string  Main wrapper template file location
	 */
	protected $main_template = '';

	/**
	 * Class instance constructor
	 *
	 * @param $main_template_file
	 */
	public function __construct($main_template_file){

		$this->main_template = $main_template_file;
	}

	/**
	 * Render main template with specified content
	 *
	 * @param $content
	 *
	 * @return html/text
	 */
	public function renderMain($content){
        $this->assign('content', $content);
		//@TODO: set all required vars and closures..
		return $this->render($this->main_template, false);
	}

    /**
     * Render specified template file with data provided
     *
     * @param $template_path
     * @param bool $wrap
     * @return html|string
     * @throws RendererException
     */
	public function render($template_path, $wrap = true)
	{
		$include = function($controller, $action, $params = array()){
			$response = Service::get('application')->callControllerAction($controller, $action, $params);
			echo $response->getContent();
		};

		$getRoute = function($name) {
			return Service::get('router')->buildRoute($name);
		};

		$generateToken = function(){
			echo '<input type="hidden" name="_token" value="' . Service::get('security')->getHash() . '">';
		};

		if (empty($template_path)) {
			throw new RendererException('Layout doesn\'t set');
		}
        ob_start();
        extract($this->getData());

		include($template_path);
		$content = ob_get_clean();

		if($wrap){
			$content = $this->renderMain($content);
		}

		return $content;
	}

    /**
     * Assign
     *
     * @param $key
     * @param null $value
     * @return $this
     */
    public function assign($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}