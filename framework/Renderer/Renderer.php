<?php

namespace Framework\Renderer;

/**
 * Class Renderer
 * @package Framework\Renderer
 */
class Renderer {

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

		//@TODO: set all required vars and closures..

		return $this->render($this->main_template, compact('content'), false);
	}

	/**
	 * Render specified template file with data provided
	 *
	 * @param   string  Template file path (full)
	 * @param   mixed   Data array
	 * @param   bool    To be wrapped with main template if true
	 *
	 * @return  text/html
	 */
	public function render($template_path, $data = array(), $wrap = true){

		extract($data);
		// @TODO: provide all required vars or closures...

		ob_start();
		include( $template_path );
		$content = ob_end_clean();

		if($wrap){
			$content = $this->renderMain($content);
		}

		return $content;
	}
} 