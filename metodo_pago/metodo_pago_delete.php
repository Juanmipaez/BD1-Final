<?php
require('../config/conexion.php');

// Validar que los datos lleguen por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: metodo_pago.php");
    exit();
}

// Validar que llegue el parámetro
if (!isset($_POST["numeroEliminar"]) || empty($_POST["numeroEliminar"])) {
    header("Location: metodo_pago.php");
    exit();
}

// Sanitizar el número
$numeroEliminar = mysqli_real_escape_string($conn, $_POST["numeroEliminar"]);

// Validar que sea un número
if (!is_numeric($numeroEliminar) || $numeroEliminar < 0) {
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
                <p>El número proporcionado no es válido.</p>
                <a href='metodo_pago.php' class='btn btn-danger mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
    mysqli_close($conn);
    exit();
}

// Verificar si el método de pago existe antes de eliminar
$query_verificar = "SELECT numero, tipo FROM metodo_pago WHERE numero = '$numeroEliminar'";
$resultado_verificar = mysqli_query($conn, $query_verificar);

if (mysqli_num_rows($resultado_verificar) == 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Método de Pago No Encontrado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Método de pago no encontrado</h4>
                <p>No se encontró ningún método de pago con el número <strong>$numeroEliminar</strong>.</p>
                <a href='metodo_pago.php' class='btn btn-warning mt-3'>
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
$query = "DELETE FROM metodo_pago WHERE numero = '$numeroEliminar'";

// Ejecutar consulta
$result = mysqli_query($conn, $query);

if ($result) {
    // Si fue exitosa, redirigirse con mensaje de éxito
    header("Location: metodo_pago.php");
} else {
    // Si hubo un error, mostrar mensaje
    $error_msg = mysqli_error($conn);
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
                <p>Ha ocurrido un error al eliminar el método de pago.</p>";
    
    // Verificar si el error es por restricción de clave foránea
    if (strpos($error_msg, 'foreign key constraint') !== false || strpos($error_msg, 'FOREIGN KEY') !== false) {
        echo "<p class='mt-2'><strong>No se puede eliminar este método de pago porque está siendo utilizado en otras transacciones o registros.</strong></p>";
    } else {
        echo "<p class='mt-2'>Error técnico: " . htmlspecialchars($error_msg) . "</p>";
    }
    
    echo "      <a href='metodo_pago.php' class='btn btn-danger mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
}

mysqli_close($conn);
?>