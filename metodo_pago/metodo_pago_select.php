<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL para obtener todos los métodos de pago con información del banco
$query = "
    SELECT 
        numero,
        tipo,
        correo,
        saldo,
        dueño AS dueno,
        banco,
        clave,
        cvv,
        fecha_vencimiento,
        titular,
        activa
    FROM metodo_pago
    ORDER BY numero ASC
";

// Ejecutar la consulta
$resultadoMetodoPago = mysqli_query($conn, $query);

// Verificar si hubo error en la consulta
if (!$resultadoMetodoPago) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Cerrar la conexión
mysqli_close($conn);
?>