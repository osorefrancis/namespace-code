<?php

namespace app\core\form;

use app\core\Model;
use app\core\form\InputField;

/**
 * class Form
 * 
 * @author Francis Osore <francisosore8@gmail.com>
 * @package app\core\form
 */

class Form
{
  public static function begin($action, $method)
  {
    echo sprintf('<form action="%s" method ="%s">', $action, $method);

    return new Form();
  }

  public static function end()
  {
    echo '</form>';
  }

  public function field(Model $model, $attribute)
  {
    return new InputField($model, $attribute);
  }
}
