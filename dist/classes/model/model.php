<?php

namespace model;

use core\db;

class model {
  protected $id = '_id'; // Название поля для идентификатора в хранилище
  protected $fields = []; // Поля

  protected $store;
  protected $items = [];
  protected $data = [];

  /**
   * Получить список полей
   * @return array
   */
  public function get_fields () {
    return $this->fields;
  }

  /**
   * Получить id выбранного объекта
   * @return string|int
   */
  public function get_id () {
    if (!empty($this->data[$this->id])) return $this->data[$this->id];
    return false;
  }

  /**
   * Получить данные объекта
   * @return array
   */
  public function get_data () {
    return $this->data;
  }

  /**
   * Получить список объектов
   * @return array
   */
  public function get_items ($where = null) {
    $db = new db();
    $sql = 'SELECT * FROM `' . $this->store . '`' . (!empty($where) ? ' WHERE ' . $where : null);
    $this->items = $db->fetch_assoc($sql);

    return ($this->items ?: []);
  }

  /**
   * Получить значение поля
   * @param string $name Название поля
   * @return mixed
   */
  public function get ($name) {
    return (!empty($this->data[$name]) ? $this->data[$name] : null);
  }

  /**
   * Изменить значение поля
   * @param string $name Название поля
   * @param string $value Значение поля
   * @return $this
   */
  public function set_value ($name, $value) {
    $this->data[$name] = $value;
    return $this;
  }

  /**
   * Изменить значения полей
   * @param array $values Массив значений для установки
   * @return $this
   */
  public function set_values ($values) {
    foreach ($values as $name => $value) {
      $this->set_value($name, $value);
    }

    return $this;
  }

  /**
   * Создать объект
   * @return $this
   */
  public function insert () {
    $db = new db();

    $set = [];
    foreach ($this->data as $field_name => $field_value) {
      $set[] = '`' . $field_name . '` = "' . $field_value . '"';
    }

    $sql = 'INSERT INTO `' . $this->store . '` SET ' . join(', ', $set);
    if (!$db->query($sql)) {
      var_dump('error ' . $db->error());
    }

    $this->set_value($this->id, $db->insert_id());

    return $this;
  }

  /**
   * Сохранить объект
   * @return $this
   */
  public function save () {
    $this->insert();

    return $this;
  }

  /**
   * Обработать объект по полям
   * @param array $data Входное данные
   * @return $this
   */
  public function prepare ($data) {
    foreach ($this->fields as $model_field) {
      $value = isset($data[$model_field['name']]) ? $data[$model_field['name']] : null;
      $current_value = $this->get($model_field['name']);

      if (!$value and $model_field['require'] and !$current_value) {
        var_dump('Поле «' . $model_field['title'] . '» обязательно к заполнению');
        exit;
      }

      if ($value === null and $current_value) {
        $this->set_value($model_field['name'], $current_value);
        continue;
      };

      $field_class_name = '\field\\' . $model_field['type'];
      $field = new $field_class_name($model_field);
      $is = $field->prepare($value);

      if (!$is and $model_field['require']) {
        var_dump('Поле «' . $model_field['title'] . '» неверно заполнено');
        exit;
      }

      $this->set_value($field->get_name(), $field->get_value());
    }

    return $this;
  }
}
