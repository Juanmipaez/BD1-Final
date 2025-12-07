<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL para obtener todos los clientes ordenados por identificación
$query = "SELECT * FROM cliente ORDER BY identificacion ASC";

// Ejecutar la consulta
$resultadoCliente = mysqli_query($conn, $query);

// Verificar si hubo error en la consulta
if (!$resultadoCliente) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Cerrar la conexión
mysqli_close($conn);
?>