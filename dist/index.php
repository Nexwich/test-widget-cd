<?php
spl_autoload_register(function ($class_name) {
  $src_path = explode('\\', $class_name);
  $final_class_name = array_pop($src_path);
  $path = join('/', $src_path);
  $class_root = __DIR__ . '/classes/' . (!empty($path) ? $path.'/' : null) . $final_class_name . '.php';

  // var_dump($class_name, $src_path, $final_class_name, $path, $class_root);
  include $class_root;
});

$db = new \core\db();

// По умолчанию
$controller_name = 'rate';
$action_name = 'get';

// Страницы
$json_routes = file_get_contents(__DIR__ . '/data/routes.json');
$routes = json_decode($json_routes, true);

foreach ($routes as $row) {
  if (strripos($_SERVER['REQUEST_URI'], $row['route']) !== false) {
    $controller_name = $row['class'];
    $action_name = $row['action'];

    break;
  }
}

if (!preg_match("/^[\w]+$/", $controller_name)) {
  die ('incorrect controller name');
}

$controller_class = '\controller\\' . $controller_name;

// Создать экземпляр и вывести вид
$controller = new $controller_class($db);
echo $controller->execute($action_name);
