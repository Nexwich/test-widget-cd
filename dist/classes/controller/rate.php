<?php

namespace controller;

class rate extends controller {
  public $model_name = '\model\rate';

  /**
   * Показать список
   */
  protected function action_get () {
    $data = $this->model->get_items();

    $this->view->generate('result.php', ['data' => $data]);
  }

  /**
   * Показать курсы на сегодня
   */
  protected function action_get_current () {
    // Получить настройки
    $json_settings = file_get_contents(__DIR__ . '/../../data/settings.json');
    $settings = json_decode($json_settings, true);

    // Выбрать и преобразовать валюты из хранилища
    $result = [];
    foreach ($settings['showRates'] as $code) {
      $where = '`code` = "'.$code.'"';
      $order = ' ORDER BY `date` DESC LIMIT 2';
      $data = $this->model->get_items($where . $order);

      $difference = ($data[0]['rate'] - $data[1]['rate']);
      $data[0]['difference'] = $difference;

      $result[] = $data[0];
    }

    // Вывести ответ
    $this->view->generate('widget.php', ['data' => $result]);
  }

  /**
   * Добавить запись
   */
  protected function action_save_cb () {
    $current_date = !empty($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Получить настройки
    $json_settings = file_get_contents(__DIR__ . '/../../data/settings.json');
    $settings = json_decode($json_settings, true);

    // Получит валюты ЦБ
    $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d/m/Y', strtotime($current_date));
    $xml_cb = simplexml_load_file($url);
    $json_cb = json_encode($xml_cb);
    $array_cb = json_decode($json_cb, true);

    // Записать валюты в хранилище
    foreach ($array_cb['Valute'] as $val) {
      if (array_search($val['CharCode'], $settings['getRates']) !== false) {
        $model = new $this->model_name();
        $model->prepare([
          'code' => $val['CharCode'],
          'rate' => str_replace(',', '.', $val['Value']),
          'date' => date('Y-m-d', strtotime($array_cb['@attributes']['Date'])),
        ]);
        $model->save();
      }
    }

    // Вывести ответ
    $this->view->generate('result.php', ['data' => ['Валюты добавлены']]);
  }
}
