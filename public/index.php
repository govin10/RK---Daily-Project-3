<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'tracking';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

$controllerClass = "App\\Controllers\\" . ucfirst($controller) . "Controller";

if (class_exists($controllerClass)) {
    $controllerObject = new $controllerClass();
    if ($id) {
        $controllerObject->$action($id);
    } else {
        $controllerObject->$action();
    }
} else {
    echo "Controller not found";
}
?>