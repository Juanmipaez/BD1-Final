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
    DELETE FROM actualizacion<?php
require('../config/conexion.php');

// Validar que los datos lleguen por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: actualizacion.php");
    exit();
}

// Validar que lleguen ambos parámetros de la PK compuesta
if (!isset($_POST["numeroEliminar"]) || empty($_POST["numeroEliminar"]) ||
    !isset($_POST["fecha_cambioEliminar"]) || empty($_POST["fecha_cambioEliminar"])) {
    header("Location: actualizacion.php");
    exit();
}

// Sanitizar los datos
$numeroEliminar = mysqli_real_escape_string($conn, $_POST["numeroEliminar"]);
$fecha_cambioEliminar = mysqli_real_escape_string($conn, $_POST["fecha_cambioEliminar"]);

// Validar que la fecha sea válida
if (!strtotime($fecha_cambioEliminar)) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error de Validación</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Error de validación</h4>
                <p>La fecha proporcionada no es válida.</p>
                <a href='actualizacion.php' class='btn btn-danger mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
    mysqli_close($conn);
    exit();
}

// Verificar si la actualización existe antes de eliminar
$query_verificar = "
    SELECT numero_cuenta_ahorros, fecha_cambio, detalles 
    FROM actualizacion 
    WHERE numero_cuenta_ahorros = '$numeroEliminar' 
    AND fecha_cambio = '$fecha_cambioEliminar'
";
$resultado_verificar = mysqli_query($conn, $query_verificar);

if (mysqli_num_rows($resultado_verificar) == 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Actualización No Encontrada</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Actualización no encontrada</h4>
                <p>No se encontró ninguna actualización con los datos proporcionados:</p>
                <ul>
                    <li>Cuenta: <strong>$numeroEliminar</strong></li>
                    <li>Fecha: <strong>$fecha_cambioEliminar</strong></li>
                </ul>
                <a href='actualizacion.php' class='btn btn-warning mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
    mysqli_close($conn);
    exit();
}

// Query SQL para eliminar
$query = "
    DELETE FROM actualizacion
    WHERE numero_cuenta_ahorros = '$numeroEliminar'
    AND fecha_cambio = '$fecha_cambioEliminar'
";

// Ejecutar consulta
$result = mysqli_query($conn, $query);

if ($result) {
    // Si fue exitosa, redirigirse
    header("Location: actualizacion.php");
} else {
    // Si hubo un error, mostrar mensaje
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error de Base de Datos</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4 class='alert-heading'><i class='bi bi-x-circle-fill me-2'></i>Error de base de datos</h4>
                <p>Ha ocurrido un error al eliminar la actualización: " . htmlspecialchars(mysqli_error($conn)) . "</p>
                <a href='actualizacion.php' class='btn btn-danger mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
}

mysqli_close($conn);
?>
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
