<?php

namespace field;

class cm_datetime extends field {
  public function prepare ($value) {
    $this->value = date('Y-m-d H:i:s', strtotime($value));
    return $this->value;
  }
}
