<?php
require('../config/conexion.php');

// Validar que los datos lleguen por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: metodo_pago.php");
    exit();
}

// Obtener y sanitizar datos básicos
$tipo_metodo = isset($_POST["tipo_metodo"]) ? strtolower(trim($_POST["tipo_metodo"])) : '';
$numero = isset($_POST["numero"]) ? mysqli_real_escape_string($conn, trim($_POST["numero"])) : '';
$correo = isset($_POST["correo"]) ? mysqli_real_escape_string($conn, trim($_POST["correo"])) : '';
$saldo = isset($_POST["saldo"]) ? floatval($_POST["saldo"]) : 0;
$cliente = isset($_POST["cliente"]) ? mysqli_real_escape_string($conn, trim($_POST["cliente"])) : '';

// Array para almacenar errores
$errores = [];

// Validaciones básicas
if (empty($numero) || !is_numeric($numero) || $numero < 0) {
    $errores[] = "El número debe ser un valor numérico válido mayor o igual a 0";
}

if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Debe proporcionar un correo electrónico válido";
}

if (strlen($correo) > 100) {
    $errores[] = "El correo no puede exceder 20 caracteres";
}

if ($saldo < 0) {
    $errores[] = "El saldo debe ser mayor o igual a 0";
}

if (empty($cliente) || !is_numeric($cliente)) {
    $errores[] = "Debe seleccionar un cliente válido";
}

if (!in_array($tipo_metodo, ['cuenta', 'tarjeta'])) {
    $errores[] = "El tipo de método de pago debe ser 'cuenta' o 'tarjeta'";
}

// Variables para los campos específicos
$banco = null;
$clave = null;
$cvv = null;
$fecha_vencimiento = null;
$titular = null;
$activa = null;

// Validaciones y asignaciones específicas según el tipo
if ($tipo_metodo === 'cuenta') {
    // Para CUENTA: banco y clave son obligatorios
    $banco = isset($_POST["banco"]) ? mysqli_real_escape_string($conn, trim($_POST["banco"])) : '';
    $clave = isset($_POST["clave"]) ? intval($_POST["clave"]) : null;
    
    if (empty($banco)) {
        $errores[] = "Para una cuenta de ahorros, el banco es obligatorio";
    }
    
    if (strlen($banco) > 50) {
        $errores[] = "El nombre del banco no puede exceder 50 caracteres";
    }
    
    if (is_null($clave) || $clave < 0 || $clave > 9999) {
        $errores[] = "Para una cuenta de ahorros, la clave es obligatoria y debe ser un número de 4 dígitos (0-9999)";
    }
    
} elseif ($tipo_metodo === 'tarjeta') {
    // Para TARJETA: cvv, fecha_vencimiento y titular son obligatorios
    $cvv = isset($_POST["cvv"]) ? intval($_POST["cvv"]) : null;
    $fecha_vencimiento = isset($_POST["fecha_vencimiento"]) ? mysqli_real_escape_string($conn, trim($_POST["fecha_vencimiento"])) : '';
    $titular = isset($_POST["titular"]) ? mysqli_real_escape_string($conn, trim($_POST["titular"])) : '';
    $activa = isset($_POST["activa"]) ? 1 : 0;
    
    if (is_null($cvv) || $cvv < 0 || $cvv > 999) {
        $errores[] = "Para una tarjeta, el CVV es obligatorio y debe ser un número de 3 dígitos (0-999)";
    }
    
    if (empty($fecha_vencimiento)) {
        $errores[] = "Para una tarjeta, la fecha de vencimiento es obligatoria";
    }
    
    if (empty($titular)) {
        $errores[] = "Para una tarjeta, el titular es obligatorio";
    }
    
    if (strlen($titular) > 40) {
        $errores[] = "El nombre del titular no puede exceder 40 caracteres";
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

// Verificar si el número ya existe
$query_verificar = "SELECT numero FROM metodo_pago WHERE numero = '$numero'";
$resultado_verificar = mysqli_query($conn, $query_verificar);

if (mysqli_num_rows($resultado_verificar) > 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Método de Pago Duplicado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Método de pago ya existe</h4>
                <p>Ya existe un método de pago registrado con el número <strong>$numero</strong>.</p>
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

// Verificar si el cliente existe
$query_cliente = "SELECT identificacion FROM cliente WHERE identificacion = '$cliente'";
$resultado_cliente = mysqli_query($conn, $query_cliente);

if (mysqli_num_rows($resultado_cliente) == 0) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Cliente No Encontrado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4 class='alert-heading'><i class='bi bi-exclamation-triangle-fill me-2'></i>Cliente no encontrado</h4>
                <p>El cliente seleccionado no existe en la base de datos.</p>
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

// Construir la consulta SQL según el tipo
if ($tipo_metodo === 'cuenta') {
    $query = "
        INSERT INTO metodo_pago
        (numero, correo, saldo, tipo, banco, clave, cvv, fecha_vencimiento, titular, activa, dueño)
        VALUES
        ('$numero', '$correo', $saldo, '$tipo_metodo', '$banco', $clave, NULL, NULL, NULL, NULL, '$cliente')
    ";
} else { // tarjeta
    $fecha_sql = empty($fecha_vencimiento) ? 'NULL' : "'$fecha_vencimiento'";
    $query = "
        INSERT INTO metodo_pago
        (numero, correo, saldo, tipo, banco, clave, cvv, fecha_vencimiento, titular, activa, dueño)
        VALUES
        ('$numero', '$correo', $saldo, '$tipo_metodo', NULL, NULL, $cvv, $fecha_sql, '$titular', $activa, '$cliente')
    ";
}

// Ejecutar la consulta
$result = mysqli_query($conn, $query);

if ($result) {
    header("Location: metodo_pago.php");
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
                <p>Ha ocurrido un error al crear el método de pago: " . htmlspecialchars(mysqli_error($conn)) . "</p>
                <a href='metodo_pago.php' class='btn btn-danger mt-3'>
                    <i class='bi bi-arrow-left me-1'></i>Volver
                </a>
            </div>
        </div>
    </body>
    </html>";
}

mysqli_close($conn);
?>