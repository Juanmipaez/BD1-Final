<?php
include "../includes/header.php";
?>

<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-search me-2"></i>Búsqueda 2
        </h1>
        <p class="lead text-muted">Cliente y sus tarjetas inactivas</p>
    </div>

    <!-- Descripción de la búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción de la Búsqueda</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">
                <strong>Objetivo:</strong> Mostrar todos los datos de un cliente junto con todos los datos de las 
                <strong>tarjetas de crédito</strong> que le pertenecen y que tengan el atributo <strong>activa = false</strong>.
            </p>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Parámetros de Búsqueda</h5>
        </div>
        <div class="card-body">
            <form action="busqueda2.php" method="post" class="needs-validation" novalidate>
                
                <div class="mb-3">
                    <label for="cedula" class="form-label fw-semibold">
                        <i class="bi bi-card-text me-1"></i>Cédula del Cliente *
                    </label>
                    <input type="number" 
                           class="form-control" 
                           id="cedula" 
                           name="cedula" 
                           min="0"
                           placeholder="Ej: 1234567890"
                           value="<?= isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : '' ?>"
                           required>
                    <div class="invalid-feedback">
                        Por favor ingrese una cédula válida.
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <?php
    // Verificar si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST'):

        require('../config/conexion.php');

        $cedula = mysqli_real_escape_string($conn, $_POST["cedula"]);

        // Validar que la cédula sea un número válido
        if (!is_numeric($cedula) || $cedula < 0) {
            echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2 fs-4'></i>
                    <div>
                        <strong>Error:</strong> La cédula debe ser un número válido mayor o igual a 0.
                    </div>
                  </div>";
            mysqli_close($conn);
            include "../includes/footer.php";
            exit();
        }

        // Primero obtener datos del cliente
        $queryCliente = "
            SELECT 
                identificacion,
                primer_nombre,
                segundo_nombre,
                primer_apellido,
                segundo_apellido,
                telefono,
                pago_efectivo
            FROM cliente
            WHERE identificacion = '$cedula'
        ";

        $resultadoCliente = mysqli_query($conn, $queryCliente);

        if (!$resultadoCliente) {
            echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($conn) . "</div>";
            mysqli_close($conn);
            include "../includes/footer.php";
            exit();
        }

        // Verificar si el cliente existe
        if (mysqli_num_rows($resultadoCliente) == 0) {
            echo "<div class='alert alert-warning d-flex align-items-center' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2 fs-4'></i>
                    <div>
                        <strong>Cliente no encontrado.</strong> No existe un cliente con la cédula <strong>$cedula</strong>.
                    </div>
                  </div>";
            mysqli_close($conn);
            include "../includes/footer.php";
            exit();
        }

        $datosCliente = mysqli_fetch_assoc($resultadoCliente);

        // Obtener tarjetas inactivas del cliente
        $queryTarjetas = "
            SELECT 
                numero,
                correo,
                saldo,
                tipo,
                cvv,
                fecha_vencimiento,
                titular,
                activa
            FROM metodo_pago
            WHERE dueño = '$cedula'
            AND tipo = 'tarjeta'
            AND activa = 0
            ORDER BY saldo DESC
        ";

        $resultadoTarjetas = mysqli_query($conn, $queryTarjetas);

        if (!$resultadoTarjetas) {
            echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($conn) . "</div>";
            mysqli_close($conn);
            include "../includes/footer.php";
            exit();
        }

        mysqli_close($conn);
    ?>

    <!-- DATOS DEL CLIENTE -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-person-fill me-2"></i>Datos del Cliente
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-card-text text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Identificación</small>
                            <strong class="fs-5"><?= htmlspecialchars($datosCliente["identificacion"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-telephone text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Teléfono</small>
                            <strong><?= htmlspecialchars($datosCliente["telefono"]); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Nombre Completo</small>
                            <strong>
                                <?= htmlspecialchars($datosCliente["primer_nombre"]); ?> 
                                <?= htmlspecialchars($datosCliente["segundo_nombre"] ?? ''); ?> 
                                <?= htmlspecialchars($datosCliente["primer_apellido"]); ?> 
                                <?= htmlspecialchars($datosCliente["segundo_apellido"] ?? ''); ?>
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-coin text-success fs-4 me-2"></i>
                        <div>
                            <small class="text-muted d-block">Pago en Efectivo</small>
                            <?php if ($datosCliente["pago_efectivo"]): ?>
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

    <?php if($resultadoTarjetas && $resultadoTarjetas->num_rows > 0): ?>

    <!-- TARJETAS INACTIVAS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="bi bi-credit-card-2-front me-2"></i>Tarjetas Inactivas
                <span class="badge bg-light text-danger"><?= $resultadoTarjetas->num_rows ?> tarjeta(s)</span>
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
                            <th scope="col" class="text-center">CVV</th>
                            <th scope="col" class="text-center">Fecha Vencimiento</th>
                            <th scope="col" class="text-center">Titular</th>
                            <th scope="col" class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultadoTarjetas as $tarjeta): ?>
                        <tr>
                            <td class="text-center fw-bold">
                                <i class="bi bi-hash me-1"></i><?= htmlspecialchars($tarjeta["numero"]); ?>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($tarjeta["correo"]); ?>
                            </td>
                            <td class="text-center text-success fw-bold">
                                $<?= number_format($tarjeta["saldo"], 2); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">
                                    <i class="bi bi-credit-card me-1"></i><?= htmlspecialchars(ucfirst($tarjeta["tipo"])); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">***</span>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date('d/m/Y', strtotime($tarjeta["fecha_vencimiento"])); ?>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($tarjeta["titular"]); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Inactiva
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
            <strong>Búsqueda completada.</strong> Se encontraron <?= $resultadoTarjetas->num_rows ?> tarjeta(s) inactiva(s) 
            perteneciente(s) al cliente con cédula <?= htmlspecialchars($cedula); ?>.
        </div>
    </div>

    <?php else: ?>

    <!-- Mensaje cuando no hay tarjetas inactivas -->
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>
            <strong>Sin tarjetas inactivas.</strong> El cliente con cédula <?= htmlspecialchars($cedula); ?> 
            no tiene tarjetas de crédito inactivas registradas.
        </div>
    </div>

    <?php
        endif;
    endif;
    ?>

</div>

<!-- Script de validación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>

<?php
include "../includes/footer.php";
?>