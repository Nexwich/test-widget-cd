<?php

namespace core;

use mysqli;

class db {
  protected $connect;
  protected $host = 'localhost';
  protected $user = 'h201969455_cb';
  protected $password = '2UXdx-ZJ';
  protected $database = 'h201969455_cb';

  public function __construct () {
    $this->connect = new mysqli($this->host, $this->user, $this->password, $this->database);

    return $this;
  }

  /**
   * Запрос в БД
   */
  public function query ($sql) {
    return $this->connect->query($sql);
  }

  /**
   * Ошибка запроса
   */
  public function error () {
    return $this->connect->error;
  }

  /**
   * ID добавленной строки
   */
  public function insert_id () {
    return $this->connect->insert_id;
  }

  /**
   * Массив строк из БД
   * @return array
   */
  public function fetch_assoc ($sql) {
    $result = $this->query($sql);

    $array = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $array[] = $row;
    }

    return $array;
  }
}
