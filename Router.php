<?php

namespace app\core;

use app\core\exception\NotFoundException;

/**
 * Class Router
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */
class Router
{
  public Request $request;
  public Response $response;

  protected array $routes = [];

  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function get($path, $callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  public function post($path, $callback)
  {
    $this->routes['post'][$path] = $callback;
  }

  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->method();
    $callback = $this->routes[$method][$path] ?? false;

    if ($callback === false) {
      $this->response->setStatusCode(404);
      throw new NotFoundException();
    }

    if (is_string($callback)) {
      return Application::$app->view->renderView($callback);
    }

    if (is_array($callback)) {
      /**
       * @var \app\core\Controller $controller
       */
      $controller = new $callback[0]();
      $controller->action = $callback[1];
      Application::$app->controller = $controller;
      $middlewares = $controller->getMiddlewares();

      foreach ($middlewares as $middleware) {
        $middleware->execute();
      }

      $callback[0] = $controller;
    }

    return call_user_func($callback, $this->request, $this->response);
  }

  public function renderView($view, $params = [])
  {
    return Application::$app->view->renderView($view, $params);
  }
}

  // public function renderContent($viewContent)
  // {
  //   return Application::$app->view->renderView($view, $params);

  // }

  // protected function layoutContent()
  // {
  //   $layout = Application::$app->layout;
  //   if (Application::$app->controller) {
  //     $layout = Application::$app->controller->layout;
  //   }

  //   ob_start();
  //   include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
  //   return ob_get_clean();
  // }

//   protected function renderOnlyView($view, $params)
//   {
//     foreach ($params as $key => $value) {
//       $$key = $value;
//     }
//     // echo '<pre>';
//     // var_dump($name);
//     // echo '</pre>';
//     // exit;

//     ob_start();
//     include_once Application::$ROOT_DIR . "/views/$view.php";
//     return ob_get_clean();
//   }
// }
