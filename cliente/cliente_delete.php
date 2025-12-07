<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Validar que los datos lleguen por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cliente.php");
    exit();
}

// Validar que llegue el parámetro
if (!isset($_POST["identificacionEliminar"]) || empty($_POST["identificacionEliminar"])) {
    header("Location: cliente.php");
    exit();
}

// Sanitizar la identificación
$identificacionEliminar = mysqli_real_escape_string($conn, $_POST["identificacionEliminar"]);

// Validar que sea un número
if (!is_numeric($identificacionEliminar)) {
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
                <p>La identificación proporcionada no es válida.</p>
                <a href='cliente.php' class='btn btn-danger mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
    mysqli_close($conn);
    exit();
}

// Verificar si el cliente existe antes de eliminar
$query_verificar = "SELECT identificacion, primer_nombre, primer_apellido FROM cliente WHERE identificacion = '$identificacionEliminar'";
$resultado_verificar = mysqli_query($conn, $query_verificar);

if (mysqli_num_rows($resultado_verificar) == 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Cliente No Encontrado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Cliente no encontrado</h4>
                <p>No se encontró ningún cliente con la identificación <strong>$identificacionEliminar</strong>.</p>
                <a href='cliente.php' class='btn btn-warning mt-3'>
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
$query = "DELETE FROM cliente WHERE identificacion = '$identificacionEliminar'";

// Ejecutar consulta
$result = mysqli_query($conn, $query);

if ($result) {
    // Si fue exitosa, redirigirse con mensaje de éxito
    header("Location: cliente.php");
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
                <p>Ha ocurrido un error al eliminar el registro: " . htmlspecialchars(mysqli_error($conn)) . "</p>
                <a href='cliente.php' class='btn btn-danger mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
}

mysqli_close($conn);
?>