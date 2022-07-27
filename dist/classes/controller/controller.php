<?php

namespace controller;

abstract class controller {
  protected $model_name = '\model\model';
  protected $view_name = '\view\view';

  protected $model;
  protected $view;

  public function __construct () {
    $this->model = new $this->model_name();
    $this->view = new $this->view_name();
  }

  /**
   * Выбрать действие
   * @param string $action_name Название действия
   */
  public function execute ($action_name) {
    $action_name = 'action_' . $action_name;
    $this->$action_name();
  }
}
