<?php

require('../config/conexion.php');

$query = "SELECT * FROM actualizacion";

$resultadoActualizacion = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
