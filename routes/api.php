<?php

/** @var \Laravel\Lumen\Routing\Router $router */


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
/**
 *  Publica
 * Rutas de prefijo api api publicas
 *
 */

$router->group(['prefix' => 'catalogos'], function () use ($router) {

    $router->post('/new/unidad_medida','CatalogoController@store_unidad_medida');
    $router->put('/update/unidad_medida/{id}','CatalogoController@update_unidad_medida');
    $router->delete('/delete/unidad_medida/{id}','CatalogoController@delete_unidad_medida');
    $router->post('/unidad_medidas', 'CatalogoController@unidad_medidas');
    $router->get('/unidad_medida/{id}', 'CatalogoController@unidad_medida');

    $router->post('/new/cat_producto','CatalogoController@store_cat_producto');
    $router->put('/update/cat_producto/{id}','CatalogoController@update_cat_producto');
    $router->delete('/delete/cat_producto/{id}','CatalogoController@delete_cat_producto');
    $router->post('/cat_productos', 'CatalogoController@cat_productos');
    $router->get('/cat_producto/{id}', 'CatalogoController@cat_producto');

    $router->post('/new/producto','CatalogoController@store_producto');
    $router->put('/update/producto/{id}','CatalogoController@update_producto');
    $router->delete('/delete/producto/{id}','CatalogoController@delete_producto');
    $router->post('/productos', 'CatalogoController@productos');
    $router->get('/producto/{id}', 'CatalogoController@producto');

    $router->post('/new/proveedor','CatalogoController@store_proveedor');
    $router->put('/update/proveedor/{id}','CatalogoController@update_proveedor');
    $router->delete('/delete/proveedor/{id}','CatalogoController@delete_proveedor');
    $router->post('/proveedors', 'CatalogoController@proveedors');
    $router->get('/proveedor/{id}', 'CatalogoController@proveedor');

});

$router->group(['prefix' => 'cliente'], function () use ($router) {

    $router->post('/new/cliente','ClienteController@store_cliente');
    $router->put('/update/cliente/{id}','ClienteController@update_cliente');
    $router->delete('/delete/cliente/{id}','ClienteController@delete_cliente');
    $router->post('/clientes', 'ClienteController@clientes');
    $router->get('/cliente/{id}', 'ClienteController@cliente');

});


$router->group(['prefix' => 'pedido'], function () use ($router) {

    $router->post('/new/pedido','PedidoController@store_pedido');
    $router->put('/update/pedido/{id}','PedidoController@update_pedido');
    $router->delete('/delete/pedido/{id}','PedidoController@delete_pedido');
    $router->post('/pedidos', 'PedidoController@pedidos');
    $router->get('/pedido/{id}', 'PedidoController@pedido');

});


$router->get('/', function () {
    return response()->json(['apiPedido' => '0.0.1']);
});

$router->get('/{route:.*}/', function () {
    return response()->json(['message' => 'ruta no existe']);
});
