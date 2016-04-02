<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 27.03.16
 * Time: 9:10
 */

namespace Framework\Response;

use Framework\DI\Service;

class ResponseRedirect extends Response
{
    protected $code;
    protected $url;

    /**
     * ResponseRedirect constructor.
     * @param string $url
     * @param int $code
     * @param null $msg
     */
    public function __construct($url = '', $code = 301, $msg = null)
    {
        parent::__construct('', 'text/html', $code);
        $this->code = $code;
        $this->url  = $url;

        if (!is_null($msg)) {
            Service::get('session')->addFlush('error', $msg);
        }
    }

    /**
     * Send
     */
    public function send()
    {
        header('Location: ' . $this->url, true, $this->code);
    }

    public function getType()
    {
        return 'Redirect';
    }

    /**
     * Set return url
     *
     * @param $url
     */
    public function setReturnUrl($url)
    {
        Service::get('session')->returnUrl = $url;
    }

}