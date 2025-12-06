<?php

// Crear conexión con la BD
require('../config/conexion.php');

// Sacar los datos del formulario
$tipo_metodo = isset($_POST["tipo_metodo"]) ? trim($_POST["tipo_metodo"]) : '';
$numero      = isset($_POST["numero"]) ? (int)$_POST["numero"] : 0;
$saldo       = isset($_POST["saldo"]) ? (float)$_POST["saldo"] : 0;
$cliente     = isset($_POST["cliente"]) ? (int)$_POST["cliente"] : 0;

$banco = isset($_POST["banco"]) ? trim($_POST["banco"]) : '';
$clave = isset($_POST["clave"]) ? $_POST["clave"] : '';

$cvv               = isset($_POST["cvv"]) ? trim($_POST["cvv"]) : '';
$fecha_vencimiento = isset($_POST["fecha_vencimiento"]) ? $_POST["fecha_vencimiento"] : '';
$titular           = isset($_POST["titular"]) ? trim($_POST["titular"]) : '';
$activa            = isset($_POST["activa"]) ? 1 : 0;

// Normalizar tipo a mayúsculas
$tipo = strtoupper($tipo_metodo);

// Validación básica
if ($numero <= 0 || $saldo < 0 || $cliente <= 0) {
    die("Datos básicos inválidos (número, saldo o dueño).");
}

if ($tipo === 'CUENTA') {

    // Para CUENTA: banco y clave NOT NULL, el resto debe ir en NULL
    if ($banco === '' || $clave === '') {
        die("Para una CUENTA debes proporcionar banco y clave.");
    }

    $banco = mysqli_real_escape_string($conn, $banco);
    $clave = (int)$clave;

    $query = "
        INSERT INTO metodo_pago
        (numero, tipo, saldo, banco, clave, cvv, fecha_vencimiento, titular, activa, `dueño`)
        VALUES
        ($numero, '$tipo', $saldo, '$banco', $clave, NULL, NULL, NULL, NULL, $cliente)
    ";

} elseif ($tipo === 'TARJETA') {

    // Para TARJETA: cvv, fecha_vencimiento, titular, activa NOT NULL; banco/clave NULL
    if ($cvv === '' || $fecha_vencimiento === '' || $titular === '') {
        die("Para una TARJETA debes proporcionar CVV, fecha de vencimiento y titular.");
    }

    $cvv               = mysqli_real_escape_string($conn, $cvv);
    $fecha_vencimiento = mysqli_real_escape_string($conn, $fecha_vencimiento);
    $titular           = mysqli_real_escape_string($conn, $titular);

    $query = "
        INSERT INTO metodo_pago
        (numero, tipo, saldo, banco, clave, cvv, fecha_vencimiento, titular, activa, `dueño`)
        VALUES
        ($numero, '$tipo', $saldo, NULL, NULL, '$cvv', '$fecha_vencimiento', '$titular', $activa, $cliente)
    ";

} else {
    die("Tipo de método de pago no válido.");
}

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Redirigir al usuario a la misma página
if ($result):
    header("Location: metodo_pago.php");
else:
    echo "Ha ocurrido un error al crear el método de pago";
endif;

mysqli_close($conn);
