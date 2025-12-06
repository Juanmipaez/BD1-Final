<?php
require('../config/conexion.php');

$identificacion   = mysqli_real_escape_string($conn, $_POST["identificacion"]);
$primer_nombre    = mysqli_real_escape_string($conn, $_POST["primer_nombre"]);
$segundo_nombre   = mysqli_real_escape_string($conn, $_POST["segundo_nombre"]);
$primer_apellido  = mysqli_real_escape_string($conn, $_POST["primer_apellido"]);
$segundo_apellido = mysqli_real_escape_string($conn, $_POST["segundo_apellido"]);
$telefono         = mysqli_real_escape_string($conn, $_POST["telefono"]);
$pago_efectivo    = isset($_POST["pago_efectivo"]) ? 1 : 0;

$query = "
    INSERT INTO cliente
    (identificacion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono, pago_efectivo)
    VALUES
    ('$identificacion', '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido', '$telefono', $pago_efectivo)
";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if ($result):
    header("Location: cliente.php");
else:
    echo "Ha ocurrido un error al crear el cliente";
endif;

mysqli_close($conn);
