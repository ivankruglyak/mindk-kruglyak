<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 27.03.16
 * Time: 8:46
 */

namespace Framework\Exception;

/**
 * Class MainException
 * @package Framework\Exception
 */
abstract class MainException extends \Exception
{
    /**
     * @inheritDoc
     */
    public function __construct($message = "", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}