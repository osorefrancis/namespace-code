<?php

namespace app\core\exception;

use Exception;

/**
 * Class ForbiddenException
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */

class ForbiddenException extends \Exception
{
  protected $message = 'You don\t have permission to access this page';
  protected $code = 403;
}
