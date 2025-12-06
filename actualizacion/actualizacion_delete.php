<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar la PK de la entidad (compuesta)
$numeroEliminar       = $_POST["numeroEliminar"];
$fecha_cambioEliminar = $_POST["fecha_cambioEliminar"];

// Escapar (por seguridad)
$numeroEliminar       = mysqli_real_escape_string($conn, $numeroEliminar);
$fecha_cambioEliminar = mysqli_real_escape_string($conn, $fecha_cambioEliminar);

// Query SQL a la BD
$query = "
    DELETE FROM actualizacion
    WHERE numero_cuenta_ahorros = '$numeroEliminar'
      AND fecha_cambio = '$fecha_cambioEliminar'
";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if ($result): 
    header ("Location: actualizacion.php");
else:
    echo "Ha ocurrido un error al eliminar este registro";
endif;
 
mysqli_close($conn);
