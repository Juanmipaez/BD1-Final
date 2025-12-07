<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL para obtener todas las actualizaciones ordenadas
$query = "
    SELECT 
        numero_cuenta_ahorros,
        fecha_cambio,
        siguiente_actualizacion,
        detalles,
        ejecutor
    FROM actualizacion
    ORDER BY fecha_cambio DESC, numero_cuenta_ahorros ASC
";

// Ejecutar la consulta
$resultadoActualizacion = mysqli_query($conn, $query);

// Verificar si hubo error en la consulta
if (!$resultadoActualizacion) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Cerrar la conexión
mysqli_close($conn);
?>