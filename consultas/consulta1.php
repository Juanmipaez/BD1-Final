<?php
include "../includes/header.php";
?>

<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-search me-2"></i>Consulta 1
        </h1>
        <p class="lead text-muted">Cliente con más actualizaciones a cuentas ajenas</p>
    </div>

    <!-- Descripción de la consulta -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción de la Consulta</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">
                <strong>Objetivo:</strong> Mostrar todos los datos del cliente que más actualizaciones ha hecho 
                a cuentas de ahorro que <strong>no son pertenecientes a él</strong>.
            </p>
            <p class="mb-0 mt-2">
                <strong>Criterio de desempate:</strong> En caso de empate, se muestra el cliente con menor identificación.
            </p>
        </div>
    </div>

    <?php
    // Crear conexión con la BD
    require('../config/conexion.php');

    // Query SQL - Cliente con más actualizaciones a cuentas ajenas
    $query = "
        SELECT 
            c.identificacion,
            c.primer_nombre,
            c.segundo_nombre,
            c.primer_apellido,
            c.segundo_apellido,
            c.telefono,
            c.pago_efectivo,
            COUNT(*) as total_actualizaciones
        FROM cliente c
        INNER JOIN actualizacion a ON c.identificacion = a.ejecutor
        INNER JOIN metodo_pago mp ON a.numero_cuenta_ahorros = mp.numero
        WHERE mp.dueño != c.identificacion
        GROUP BY c.identificacion, c.primer_nombre, c.segundo_nombre, 
                 c.primer_apellido, c.segundo_apellido, c.telefono, c.pago_efectivo
        ORDER BY total_actualizaciones DESC, c.identificacion ASC
        LIMIT 1
    ";

    // Ejecutar la consulta
    $resultadoC1 = mysqli_query($conn, $query);

    if (!$resultadoC1) {
        echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($conn) . "</div>";
        mysqli_close($conn);
        include "../includes/footer.php";
        exit();
    }

    mysqli_close($conn);
    ?>

    <?php if($resultadoC1 && $resultadoC1->num_rows > 0): ?>

    <!-- TABLA DE RESULTADOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>Resultado de la Consulta
                <span class="badge bg-light text-success"><?= $resultadoC1->num_rows ?> registro(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Identificación</th>
                            <th scope="col" class="text-center">Primer Nombre</th>
                            <th scope="col" class="text-center">Segundo Nombre</th>
                            <th scope="col" class="text-center">Primer Apellido</th>
                            <th scope="col" class="text-center">Segundo Apellido</th>
                            <th scope="col" class="text-center">Teléfono</th>
                            <th scope="col" class="text-center">Pago Efectivo</th>
                            <th scope="col" class="text-center">Total Actualizaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultadoC1 as $fila): ?>
                        <tr>
                            <td class="text-center fw-bold">
                                <i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($fila["identificacion"]); ?>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($fila["primer_nombre"]); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["segundo_nombre"] ?? '-'); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["primer_apellido"]); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["segundo_apellido"] ?? '-'); ?></td>
                            <td class="text-center">
                                <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($fila["telefono"]); ?>
                            </td>
                            <td class="text-center">
                                <?php if ($fila["pago_efectivo"]): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Sí
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle me-1"></i>No
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary fs-6">
                                    <?= htmlspecialchars($fila["total_actualizaciones"]); ?> actualizaciones
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="alert alert-success d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
        <div>
            <strong>Consulta ejecutada exitosamente.</strong> Se encontró el cliente con más actualizaciones a cuentas ajenas.
        </div>
    </div>

    <?php else: ?>

    <!-- Mensaje cuando no hay resultados -->
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
        <div>
            <strong>No se encontraron resultados.</strong> No hay clientes que hayan realizado actualizaciones a cuentas de ahorro que no les pertenecen.
        </div>
    </div>

    <?php endif; ?>

    <!-- Botón para volver -->
    <div class="mb-4">
        <a href="../index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver al inicio
        </a>
    </div>

</div>

<?php
include "../includes/footer.php";
?>