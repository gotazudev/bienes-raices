<?php

// Importar la conexion
require 'includes/app.php';
$db = conectarDB();

// Crear email y pass
$email = 'correo@correo.com';
$password = '123456';

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Query para crear el usuario

$query = "INSERT INTO usuarios (email, password) VALUES ('$email','$passwordHash');";
echo $query;

// Agregarlo a la BD
mysqli_query($db, $query);

?>