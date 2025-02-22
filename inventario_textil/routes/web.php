<?php
// routes/web.php

// Cargar el enrutador (suponiendo que estés utilizando AltoRouter o algún otro)
require_once('../vendor/autoload.php');
$router = new AltoRouter();

// Rutas de autenticación
$router->map('POST', '/login', 'AuthController@login'); // POST para iniciar sesión
$router->map('GET', '/logout', 'AuthController@logout'); // GET para cerrar sesión

// Rutas de productos
$router->map('GET', '/productos', 'ProductoController@index'); // Mostrar productos
$router->map('GET', '/productos/create', 'ProductoController@createForm'); // Formulario para crear producto
$router->map('POST', '/productos', 'ProductoController@create'); // Crear un producto
$router->map('GET', '/productos/[i:id]/edit', 'ProductoController@editForm'); // Formulario para editar producto
$router->map('POST', '/productos/[i:id]', 'ProductoController@update'); // Actualizar un producto
$router->map('GET', '/productos/[i:id]/delete', 'ProductoController@delete'); // Eliminar producto

// Rutas de pedidos
$router->map('GET', '/pedidos', 'PedidoController@index');

// Rutas de usuarios (solo admin)
$router->map('GET', '/usuarios', 'UsuarioController@index');

// Comprobar la URL solicitada y ejecutar el controlador correspondiente
$match = $router->match();

if ($match) {
    // Obtiene la acción y el controlador que se mapeó
    list($controller, $action) = explode('@', $match['target']);
    
    // Instanciar el controlador y llamar la acción
    require_once("../controllers/{$controller}.php");
    $controllerInstance = new $controller();
    
    if (isset($match['params'])) {
        // Llamar el método del controlador con los parámetros de la URL
        call_user_func_array([$controllerInstance, $action], $match['params']);
    } else {
        // Llamar el método del controlador sin parámetros
        call_user_func([$controllerInstance, $action]);
    }
} else {
    echo "No se encontró la ruta solicitada.";
}
?>
