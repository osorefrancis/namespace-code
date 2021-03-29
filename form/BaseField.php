<?php

namespace app\core\form;

use app\core\Model;

abstract class BaseField
{
  public Model $model;
  public string $attribute;


  /**
   * field constructor
   * 
   * @param \app\core\Model $model
   * @param string  $attribute
   */

  public function __construct(Model $model, string $attribute)
  {
    $this->model = $model;
    $this->attribute = $attribute;
  }

  abstract public function renderInput(): string;

  public function __toString()
  {
    return sprintf(
      '<div class="form-floating mb-3">
        %s
        <label for="%s">%s</label>
        <div class="invalid-feedback">%s</div>
      </div>',
      $this->renderInput(),
      $this->attribute,
      $this->model->getLabel($this->attribute),
      $this->model->getFirstError($this->attribute),

    );
  }
}
