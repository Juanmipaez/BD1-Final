<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar los datos del formulario
$numero_cuenta_ahorros = $_POST["numero_cuenta_ahorros"];
$fecha_cambio          = $_POST["fecha_cambio"];
$siguiente_actualizacion = $_POST["siguiente_actualizacion"];
$detalles              = $_POST["detalles"];
$ejecutor              = $_POST["ejecutor"];

// Escapar strings (básico)
$numero_cuenta_ahorros = mysqli_real_escape_string($conn, $numero_cuenta_ahorros);
$fecha_cambio          = mysqli_real_escape_string($conn, $fecha_cambio);
$siguiente_actualizacion = mysqli_real_escape_string($conn, $siguiente_actualizacion);
$detalles              = mysqli_real_escape_string($conn, $detalles);

// Query SQL a la BD
$query = "
    INSERT INTO actualizacion
    (numero_cuenta_ahorros, fecha_cambio, siguiente_actualizacion, detalles, ejecutor)
    VALUES
    ('$numero_cuenta_ahorros', '$fecha_cambio', '$siguiente_actualizacion', '$detalles', '$ejecutor')
";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Redirigir al usuario a la misma pagina
if ($result):
	header("Location: actualizacion.php");
else:
	echo "Ha ocurrido un error al crear la actualización";
endif;

mysqli_close($conn);
