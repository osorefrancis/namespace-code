<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

/**
 * Class Controller
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */
class Controller
{
  public string $layout = 'main';

  public string $actions = '';

  /**
   * @var \app\core\middlewares\BaseMiddleware[]
   */

  protected array $middlewares = [];


  public function setLayout($layout)
  {
    $this->layout = $layout;
  }

  public function render($view, $params = [])
  {
    return Application::$app->router->renderView($view, $params);
  }

  public function registerMiddleware(BaseMiddleware $middleware)
  {
    $this->middlewares[] = $middleware;
  }

  /**
   * @return \app\core\middlewares\BaseMiddleware[]
   * 
   */
  public function getMiddlewares(): array
  {
    return $this->middlewares;
  }
}
