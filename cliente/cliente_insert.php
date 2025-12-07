<?php
require('../config/conexion.php');

// Validar que los datos lleguen por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cliente.php");
    exit();
}

// Sanitizar y validar los datos
$identificacion = mysqli_real_escape_string($conn, trim($_POST["identificacion"]));
$primer_nombre = mysqli_real_escape_string($conn, trim($_POST["primer_nombre"]));
$segundo_nombre = mysqli_real_escape_string($conn, trim($_POST["segundo_nombre"]));
$primer_apellido = mysqli_real_escape_string($conn, trim($_POST["primer_apellido"]));
$segundo_apellido = mysqli_real_escape_string($conn, trim($_POST["segundo_apellido"]));
$telefono = mysqli_real_escape_string($conn, trim($_POST["telefono"]));
$pago_efectivo = isset($_POST["pago_efectivo"]) ? 1 : 0;

// Validaciones adicionales del lado del servidor
$errores = [];

// Validar identificación
if (!is_numeric($identificacion) || $identificacion < 0 || $identificacion > 9999999999) {
    $errores[] = "La identificación debe ser un número válido entre 0 y 9999999999";
}

// Validar teléfono
if (!is_numeric($telefono) || $telefono < 0) {
    $errores[] = "El teléfono debe ser un número válido mayor o igual a 0";
}

// Validar campos requeridos
if (empty($primer_nombre)) {
    $errores[] = "El primer nombre es obligatorio";
}
if (empty($primer_apellido)) {
    $errores[] = "El primer apellido es obligatorio";
}

// Validar longitud de campos
if (strlen($primer_nombre) > 50) {
    $errores[] = "El primer nombre no puede exceder 50 caracteres";
}
if (strlen($segundo_nombre) > 50) {
    $errores[] = "El segundo nombre no puede exceder 50 caracteres";
}
if (strlen($primer_apellido) > 50) {
    $errores[] = "El primer apellido no puede exceder 50 caracteres";
}
if (strlen($segundo_apellido) > 50) {
    $errores[] = "El segundo apellido no puede exceder 50 caracteres";
}

// Si hay errores, mostrarlos
if (!empty($errores)) {
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
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Errores de validación:</h4>
                <ul>";
    foreach ($errores as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>
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

// Verificar si la identificación ya existe
$query_verificar = "SELECT identificacion FROM cliente WHERE identificacion = '$identificacion'";
$resultado_verificar = mysqli_query($conn, $query_verificar);

if (mysqli_num_rows($resultado_verificar) > 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Cliente Duplicado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Cliente ya existe</h4>
                <p>Ya existe un cliente registrado con la identificación <strong>$identificacion</strong>.</p>
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

// Verificar si el teléfono ya existe
$query_verificar_tel = "SELECT telefono FROM cliente WHERE telefono = '$telefono'";
$resultado_verificar_tel = mysqli_query($conn, $query_verificar_tel);

if (mysqli_num_rows($resultado_verificar_tel) > 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Teléfono Duplicado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Teléfono ya registrado</h4>
                <p>Ya existe un cliente registrado con el teléfono <strong>$telefono</strong>.</p>
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

// Query de inserción
$query = "
    INSERT INTO cliente
    (identificacion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono, pago_efectivo)
    VALUES
    ('$identificacion', '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido', '$telefono', $pago_efectivo)
";

$result = mysqli_query($conn, $query);

if ($result) {
    // Redireccionar con mensaje de éxito
    header("Location: cliente.php");
} else {
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
                <p>Ha ocurrido un error al crear el cliente: " . htmlspecialchars(mysqli_error($conn)) . "</p>
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