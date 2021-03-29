<?php

namespace app\core\form;

class TextAreaField extends BaseField
{

  public function renderInput(): string
  {
    return sprintf(
      '<textarea name="%s" class="form-control%s" placeholder="%s" style="height: 200px">%s</textarea>',
      $this->attribute,
      $this->model->hasError($this->attribute) ? 'is-invalid' : '',
      $this->model->getLabel($this->attribute),
      $this->model->{$this->attribute},
    );
  }
}
