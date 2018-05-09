<?php


Route::controllers([
	'validacion' => 'validacion\ValidacionController',
    'validado/fotos'=>'FotoController',
    'validado/albumes'=>'AlbumController',
    'validado/usuario'=>'UsuarioController',
    'validado'=>'InicioController',
    '/' => 'BienvenidaController',
]);
