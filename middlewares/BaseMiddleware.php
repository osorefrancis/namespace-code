<?php


namespace app\core\middlewares;

/**
 * Class BaseMiddleware
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */

abstract class BaseMiddleware
{
  abstract public function execute();
}
