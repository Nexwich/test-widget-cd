<?php

namespace view;

class view {

  /**
   * Выводит шаблон
   */
  function generate ($template_view, $data = null) {
    if(is_array($data)) {
      extract($data);
    }

    require 'templates/' . $template_view;
  }
}
