<?php

namespace app\core;

use Exception;
use app\core\View;
use app\core\Router;
use app\models\User;
use app\core\db\DbModel;
use app\core\Request;
use app\core\db\Database;
use app\core\Response;
use app\core\Controller;
use app\core\db\Database as DbDatabase;

/**
 * Class Application
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core
 */
class Application
{
  public static string $ROOT_DIR;

  public string $layout = 'main';
  public string $userClass;
  public Router $router;
  public Request $request;
  public Database $db;
  public Session $session;
  public Response $response;
  public ?UserModel $user;
  public View $view;


  public static Application $app;
  public ?Controller $controller = null;


  public function __construct($rootPath, array $config)
  {
    $this->userClass = $config['userClass'];
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->session = new Session();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
    $this->view = new View();


    $this->db = new Database($config['db']);

    $primaryValue = $this->session->get('user');
    if ($primaryValue) {
      $primaryKey = $this->userClass::primaryKey();
      $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
    }
    // $this->user = null;
  }

  /**
   * @return Controller
   */


  public function getController(): Controller
  {
    return $this->controller;
  }

  /**
   * @param Controller\$controller
   *
   */

  public function setController(Controller $controller): void
  {
    $this->controller = $controller;
  }

  public function login(UserModel $user)
  {
    $this->user = $user;
    $primaryKey = $user->primaryKey();
    $primaryValue = $user->{$primaryKey};
    $this->session->set('user', $primaryValue);
    return true;
  }

  public function logout()
  {
    $this->user = null;
    $this->session->remove('user');
  }

  public static function isGuest()
  {
    return !self::$app->user;
  }

  public function run()
  {
    try {
      echo $this->router->resolve();
    } catch (Exception $e) {
      $this->response->setStatusCode($e->getCode());
      echo $this->router->renderView('_error', [
        'exception' => $e
      ]);
    }
  }
}
