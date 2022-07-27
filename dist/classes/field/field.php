<?php

namespace field;

abstract class field {
  protected $name;
  protected $value;
  protected $settings;

  /**
   * @param mixed $field Данные по полю
   */
  public function __construct ($field) {
    $this->settings = $field;
    $this->name = $field['name'];
  }

  /**
   * Получить имя поля
   * @return string
   */
  public function get_name () {
    return $this->name;
  }

  /**
   * Получить значение поля
   * @return mixed
   */
  public function get_value () {
    return $this->value;
  }

  /**
   * Обработать данные
   * @param mixed $value Входное значение
   * @return mixed
   */
  public function prepare ($value) {
    $this->value = $value;
    return $this->value;
  }
}
