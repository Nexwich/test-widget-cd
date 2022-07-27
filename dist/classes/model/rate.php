<?php

namespace model;

class rate extends model {
  public $store = 'cb_rates';
  protected $fields = [
    [
      "name" => "code",
      "title" => "Код валюты",
      "type" => "cm_string",
      "require" => true
    ],
    [
      "name" => "rate",
      "title" => "Текущий курс",
      "type" => "cm_string",
      "require" => true
    ],
    [
      "name" => "date",
      "title" => "Дата",
      "type" => "cm_datetime",
      "require" => true,
    ]
  ];

  public function insert () {
    // Проверить на наличие текущей валюты в текущей дате
    $data = $this->get_data();
    $items = $this->get_items('`date` = "'.$data['date'].'" AND `code` = "'.$data['code'].'"');

    if (!$items) {
      parent::insert();
    }

    return $this;
  }
}
