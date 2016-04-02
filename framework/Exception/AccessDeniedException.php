<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 31.03.16
 * Time: 14:29
 */

namespace Framework\Exception;


class AccessDeniedException extends \Exception
{
    public function __construct($message = "", \Exception $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}