<?php

namespace Framework\Renderer;

class Renderer
{
    private $main = '';

    public function __construct ($main_layout_path)
    {
        $this->main = $main_layout_path;
    }

    public function rendererMain($content)
    {
        return $this->render($this->main, array('content' => $content), false);
    }

    public function render($path, $data = array(), $with_main = true)
    {
        extract($data);
        ob_start();
        include($path);
        $content = ob_get_clean();
        if ($with_main){
            $content = $this->rendererMain($content);
        }
        return $content;
    }
}