<?php

namespace app\core;


/**
 * Class Response
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */
class Response
{
  public function setStatusCode(int $code)
  {
    http_response_code($code);
  }

  public function redirect(string $url)
  {
    header('location: ' . $url);
  }
}
