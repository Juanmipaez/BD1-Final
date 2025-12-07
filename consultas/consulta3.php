<?php
include "../includes/header.php";
?>

<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-search me-2"></i>Consulta 3
        </h1>
        <p class="lead text-muted">Tarjeta activa con mayor saldo y datos del dueño</p>
    </div>

    <!-- Descripción de la consulta -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción de la Consulta</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">
                <strong>Objetivo:</strong> Mostrar todos los datos de la tarjeta de crédito con <strong>mayor saldo</strong> 
                que tenga el atributo <strong>activa = true</strong>, junto con todos los datos de su dueño (cliente).
            </p>
            <p class="mb-0 mt-2">
                <strong>Criterio de desempate:</strong> En caso de empate en el saldo, se muestra la tarjeta con mayor número.
            </p>
        </div>
    </div>

    <?php
    // Crear conexión con la BD
    require('../config/conexion.php');

    // Query SQL - Tarjeta activa con mayor saldo junto con datos del dueño
    $query = "
        SELECT 
            mp.numero AS tarjeta_numero,
            mp.correo AS tarjeta_correo,
            mp.saldo AS tarjeta_saldo,
            mp.tipo AS tarjeta_tipo,
            mp.cvv AS tarjeta_cvv,
            mp.fecha_vencimiento AS tarjeta_fecha_vencimiento,
            mp.titular AS tarjeta_titular,
            mp.activa AS tarjeta_activa,
            c.identificacion AS cliente_identificacion,
            c.primer_nombre AS cliente_primer_nombre,
            c.segundo_nombre AS cliente_segundo_nombre,
            c.primer_apellido AS cliente_primer_apellido,
            c.segundo_apellido AS cliente_segundo_apellido,
            c.telefono AS cliente_telefono,
            c.pago_efectivo AS cliente_pago_efectivo
        FROM metodo_pago mp
        INNER JOIN cliente c ON mp.dueño = c.identificacion
        WHERE mp.tipo = 'tarjeta'
        AND mp.activa = 1
        ORDER BY mp.saldo DESC, mp.numero DESC
        LIMIT 1
    ";

    // Ejecutar la consulta
    $resultadoC3 = mysqli_query($conn, $query);

    if (!$resultadoC3) {
        echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($conn) . "</div>";
        mysqli_close($conn);
        include "../includes/footer.php";
        exit();
    }

    mysqli_close($conn);
    ?>

    <?php if($resultadoC3 && $resultadoC3->num_rows > 0): ?>

    <?php $fila = mysqli_fetch_assoc($resultadoC3); ?>

    <!-- DATOS DE LA TARJETA -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-credit-card me-2"></i>Datos de la Tarjeta
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hash text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Número</small>
                            <strong class="fs-5"><?= htmlspecialchars($fila["tarjeta_numero"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Correo</small>
                            <strong><?= htmlspecialchars($fila["tarjeta_correo"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-currency-dollar text-success fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Saldo</small>
                            <strong class="text-success fs-4">$<?= number_format($fila["tarjeta_saldo"], 2); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-credit-card-2-front text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Tipo</small>
                            <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($fila["tarjeta_tipo"])); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-lock-fill text-warning fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">CVV</small>
                            <strong>***</strong> <small class="text-muted">(Oculto por seguridad)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-event text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Fecha de Vencimiento</small>
                            <strong><?= date('d/m/Y', strtotime($fila["tarjeta_fecha_vencimiento"])); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-badge text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Titular</small>
                            <strong><?= htmlspecialchars($fila["tarjeta_titular"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Estado</small>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Activa
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DATOS DEL DUEÑO (CLIENTE) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-person-fill me-2"></i>Datos del Dueño (Cliente)
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-card-text text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Identificación</small>
                            <strong class="fs-5"><?= htmlspecialchars($fila["cliente_identificacion"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-telephone text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Teléfono</small>
                            <strong><?= htmlspecialchars($fila["cliente_telefono"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Primer Nombre</small>
                            <strong><?= htmlspecialchars($fila["cliente_primer_nombre"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Segundo Nombre</small>
                            <strong><?= htmlspecialchars($fila["cliente_segundo_nombre"] ?? '-'); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Primer Apellido</small>
                            <strong><?= htmlspecialchars($fila["cliente_primer_apellido"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Segundo Apellido</small>
                            <strong><?= htmlspecialchars($fila["cliente_segundo_apellido"] ?? '-'); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-coin text-success fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Pago en Efectivo</small>
                            <?php if ($fila["cliente_pago_efectivo"]): ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Habilitado
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle me-1"></i>No habilitado
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="alert alert-success d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
        <div>
            <strong>Consulta ejecutada exitosamente.</strong> Se encontró la tarjeta activa con mayor saldo junto con los datos completos de su dueño.
        </div>
    </div>

    <?php else: ?>

    <!-- Mensaje cuando no hay resultados -->
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
        <div>
            <strong>No se encontraron resultados.</strong> No hay tarjetas activas registradas en el sistema.
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