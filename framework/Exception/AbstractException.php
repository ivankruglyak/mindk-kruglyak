<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 31.03.16
 * Time: 14:34
 */

namespace Framework\Exception;


class AbstractException extends \Exception
{
    public function __construct($message = "", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}