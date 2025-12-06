<?php

// Crear conexión con la BD
require('../config/conexion.php');

// Sacar la PK de la entidad (identificacion)
$identificacionEliminar = $_POST["identificacionEliminar"];

// Query SQL a la BD
$query = "DELETE FROM cliente WHERE identificacion = '$identificacionEliminar'";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if ($result):
    // Si fue exitosa, redirigirse de nuevo a la página de la entidad
    header("Location: cliente.php");
else:
    echo "Ha ocurrido un error al eliminar este registro";
endif;

mysqli_close($conn);