<?php

// Crear conexión con la BD
require('../config/conexion.php');

// Sacar la PK de la entidad
$numeroEliminar = isset($_POST["numeroEliminar"]) ? (int)$_POST["numeroEliminar"] : 0;

if ($numeroEliminar <= 0) {
    die("Número inválido para eliminar.");
}

// Query SQL a la BD
$query = "DELETE FROM metodo_pago WHERE numero = $numeroEliminar";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if ($result): 
    header("Location: metodo_pago.php");
else:
    echo "Ha ocurrido un error al eliminar este registro";
endif;

mysqli_close($conn);
