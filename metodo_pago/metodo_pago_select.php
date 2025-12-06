<?php

// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL a la BD
$query = "
    SELECT 
        numero,
        tipo,
        saldo,
        `dueño` AS dueno,
        activa
    FROM metodo_pago
";

// Ejecutar la consulta
$resultadoMetodoPago = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
