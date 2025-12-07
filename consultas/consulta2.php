<?php
include "../includes/header.php";
?>

<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-search me-2"></i>Consulta 2
        </h1>
        <p class="lead text-muted">Cuenta de ahorros con mayor clave nunca actualizada</p>
    </div>

    <!-- Descripción de la consulta -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción de la Consulta</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">
                <strong>Objetivo:</strong> Mostrar todos los datos de la cuenta de ahorros que tiene <strong>mayor clave</strong> 
                y que <strong>nunca ha sido actualizada</strong>.
            </p>
            <p class="mb-0 mt-2">
                <strong>Criterio de desempate:</strong> En caso de empate en la clave, se muestra la cuenta con mayor número.
            </p>
        </div>
    </div>

    <?php
    // Crear conexión con la BD
    require('../config/conexion.php');

    // Query SQL - Cuenta de ahorros con mayor clave que nunca ha sido actualizada
    $query = "
        SELECT 
            mp.numero,
            mp.correo,
            mp.saldo,
            mp.tipo,
            mp.banco,
            mp.clave,
            mp.dueño
        FROM metodo_pago mp
        WHERE mp.tipo = 'cuenta'
        AND mp.clave IS NOT NULL
        AND NOT EXISTS (
            SELECT 1 
            FROM actualizacion a 
            WHERE a.numero_cuenta_ahorros = mp.numero
        )
        ORDER BY mp.clave DESC, mp.numero DESC
        LIMIT 1
    ";

    // Ejecutar la consulta
    $resultadoC2 = mysqli_query($conn, $query);

    if (!$resultadoC2) {
        echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($conn) . "</div>";
        mysqli_close($conn);
        include "../includes/footer.php";
        exit();
    }

    mysqli_close($conn);
    ?>

    <?php if($resultadoC2 && $resultadoC2->num_rows > 0): ?>

    <!-- TABLA DE RESULTADOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>Resultado de la Consulta
                <span class="badge bg-light text-success"><?= $resultadoC2->num_rows ?> registro(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Número</th>
                            <th scope="col" class="text-center">Correo</th>
                            <th scope="col" class="text-center">Saldo</th>
                            <th scope="col" class="text-center">Tipo</th>
                            <th scope="col" class="text-center">Banco</th>
                            <th scope="col" class="text-center">Clave</th>
                            <th scope="col" class="text-center">Dueño (ID)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultadoC2 as $fila): ?>
                        <tr>
                            <td class="text-center fw-bold">
                                <i class="bi bi-hash me-1"></i><?= htmlspecialchars($fila["numero"]); ?>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($fila["correo"]); ?>
                            </td>
                            <td class="text-center text-success fw-bold">
                                $<?= number_format($fila["saldo"], 2); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">
                                    <i class="bi bi-bank me-1"></i><?= htmlspecialchars(ucfirst($fila["tipo"])); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-building me-1"></i><?= htmlspecialchars($fila["banco"]); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-lock-fill me-1"></i><?= htmlspecialchars($fila["clave"]); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-person me-1"></i><?= htmlspecialchars($fila["dueño"]); ?>
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
            <strong>Consulta ejecutada exitosamente.</strong> Se encontró la cuenta de ahorros con mayor clave que nunca ha sido actualizada.
        </div>
    </div>

    <?php else: ?>

    <!-- Mensaje cuando no hay resultados -->
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
        <div>
            <strong>No se encontraron resultados.</strong> No hay cuentas de ahorro que cumplan con las condiciones especificadas (nunca actualizadas con clave válida).
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