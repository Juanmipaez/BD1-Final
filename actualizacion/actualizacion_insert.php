<?php
require('../config/conexion.php');

// Validar que los datos lleguen por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: actualizacion.php");
    exit();
}

// Obtener y sanitizar datos
$numero_cuenta_ahorros = isset($_POST["numero_cuenta_ahorros"]) ? mysqli_real_escape_string($conn, trim($_POST["numero_cuenta_ahorros"])) : '';
$fecha_cambio = isset($_POST["fecha_cambio"]) ? mysqli_real_escape_string($conn, trim($_POST["fecha_cambio"])) : '';
$siguiente_actualizacion = isset($_POST["siguiente_actualizacion"]) ? mysqli_real_escape_string($conn, trim($_POST["siguiente_actualizacion"])) : '';
$detalles = isset($_POST["detalles"]) ? mysqli_real_escape_string($conn, trim($_POST["detalles"])) : '';
$ejecutor = isset($_POST["ejecutor"]) ? mysqli_real_escape_string($conn, trim($_POST["ejecutor"])) : '';

// Array para almacenar errores
$errores = [];

// Validaciones básicas
if (empty($numero_cuenta_ahorros)) {
    $errores[] = "Debe seleccionar una cuenta de ahorros";
}

if (empty($fecha_cambio)) {
    $errores[] = "La fecha de cambio es obligatoria";
}

if (empty($siguiente_actualizacion)) {
    $errores[] = "La fecha de siguiente actualización es obligatoria";
}

if (empty($detalles)) {
    $errores[] = "Los detalles son obligatorios";
}

if (strlen($detalles) > 255) {
    $errores[] = "Los detalles no pueden exceder 255 caracteres";
}

if (empty($ejecutor) || !is_numeric($ejecutor)) {
    $errores[] = "Debe seleccionar un ejecutor válido";
}

// Validar que la siguiente actualización sea posterior a la fecha de cambio
if (!empty($fecha_cambio) && !empty($siguiente_actualizacion)) {
    if (strtotime($siguiente_actualizacion) <= strtotime($fecha_cambio)) {
        $errores[] = "La fecha de siguiente actualización debe ser posterior a la fecha de cambio";
    }
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

// Verificar que la cuenta de ahorros existe y es de tipo CUENTA
$query_cuenta = "SELECT numero, tipo FROM metodo_pago WHERE numero = '$numero_cuenta_ahorros'";
$resultado_cuenta = mysqli_query($conn, $query_cuenta);

if (mysqli_num_rows($resultado_cuenta) == 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Cuenta No Encontrada</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Cuenta no encontrada</h4>
                <p>La cuenta de ahorros seleccionada no existe en la base de datos.</p>
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

// Verificar que el método de pago sea de tipo CUENTA
$fila_cuenta = mysqli_fetch_assoc($resultado_cuenta);
if (strtolower($fila_cuenta["tipo"]) !== "cuenta") {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Tipo Incorrecto</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Tipo de método de pago incorrecto</h4>
                <p>Solo se pueden agregar actualizaciones a <strong>cuentas de ahorros</strong>. El método de pago seleccionado es de tipo <strong>" . htmlspecialchars($fila_cuenta["tipo"]) . "</strong>.</p>
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

// Verificar que el ejecutor existe
$query_ejecutor = "SELECT identificacion FROM cliente WHERE identificacion = '$ejecutor'";
$resultado_ejecutor = mysqli_query($conn, $query_ejecutor);

if (mysqli_num_rows($resultado_ejecutor) == 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Ejecutor No Encontrado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Ejecutor no encontrado</h4>
                <p>El cliente seleccionado como ejecutor no existe en la base de datos.</p>
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

// Verificar si ya existe una actualización con la misma PK compuesta
$query_duplicado = "
    SELECT numero_cuenta_ahorros, fecha_cambio 
    FROM actualizacion 
    WHERE numero_cuenta_ahorros = '$numero_cuenta_ahorros' 
    AND fecha_cambio = '$fecha_cambio'
";
$resultado_duplicado = mysqli_query($conn, $query_duplicado);

if (mysqli_num_rows($resultado_duplicado) > 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Actualización Duplicada</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Actualización ya existe</h4>
                <p>Ya existe una actualización registrada para la cuenta <strong>$numero_cuenta_ahorros</strong> en la fecha <strong>$fecha_cambio</strong>.</p>
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

// Query de inserción
$query = "
    INSERT INTO actualizacion
    (numero_cuenta_ahorros, fecha_cambio, siguiente_actualizacion, detalles, ejecutor)
    VALUES
    ('$numero_cuenta_ahorros', '$fecha_cambio', '$siguiente_actualizacion', '$detalles', '$ejecutor')
";

// Ejecutar la consulta
$result = mysqli_query($conn, $query);

if ($result) {
    header("Location: actualizacion.php");
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
                <p>Ha ocurrido un error al crear la actualización: " . htmlspecialchars(mysqli_error($conn)) . "</p>
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