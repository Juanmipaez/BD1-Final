<?php
include "../includes/header.php";
?>

<div class="container-fluid px-4">
    <div class="mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-search me-2"></i>Búsqueda 1
        </h1>
        <p class="lead text-muted">Actualizaciones por rango de fechas</p>
    </div>

    <!-- Descripción de la búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Descripción de la Búsqueda</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">
                <strong>Objetivo:</strong> Mostrar todas las actualizaciones cuya <strong>fecha_cambio</strong> 
                esté comprendida entre f1 (inclusive) y f2 (inclusive), acompañadas de todos los datos del 
                <strong>ejecutor (cliente)</strong> y de la <strong>cuenta de ahorros</strong> correspondiente.
            </p>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-range me-2"></i>Parámetros de Búsqueda</h5>
        </div>
        <div class="card-body">
            <form action="busqueda1.php" method="post" class="needs-validation" novalidate>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha1" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Fecha Inicial (f1) *
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="fecha1" 
                               name="fecha1" 
                               value="<?= isset($_POST['fecha1']) ? htmlspecialchars($_POST['fecha1']) : '' ?>"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese la fecha inicial.
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="fecha2" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Fecha Final (f2) *
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="fecha2" 
                               name="fecha2" 
                               value="<?= isset($_POST['fecha2']) ? htmlspecialchars($_POST['fecha2']) : '' ?>"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese la fecha final.
                        </div>
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

        $fecha1 = mysqli_real_escape_string($conn, $_POST["fecha1"]);
        $fecha2 = mysqli_real_escape_string($conn, $_POST["fecha2"]);

        // Validar que fecha2 >= fecha1
        if (strtotime($fecha2) < strtotime($fecha1)) {
            echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2 fs-4'></i>
                    <div>
                        <strong>Error:</strong> La fecha final (f2) debe ser mayor o igual a la fecha inicial (f1).
                    </div>
                  </div>";
            mysqli_close($conn);
            include "../includes/footer.php";
            exit();
        }

        // Query SQL - Actualizaciones con datos del ejecutor y cuenta de ahorros
        $query = "
            SELECT 
                a.numero_cuenta_ahorros,
                a.fecha_cambio,
                a.siguiente_actualizacion,
                a.detalles,
                c.identificacion AS ejecutor_id,
                c.primer_nombre AS ejecutor_primer_nombre,
                c.segundo_nombre AS ejecutor_segundo_nombre,
                c.primer_apellido AS ejecutor_primer_apellido,
                c.segundo_apellido AS ejecutor_segundo_apellido,
                c.telefono AS ejecutor_telefono,
                c.pago_efectivo AS ejecutor_pago_efectivo,
                mp.numero AS cuenta_numero,
                mp.correo AS cuenta_correo,
                mp.saldo AS cuenta_saldo,
                mp.tipo AS cuenta_tipo,
                mp.banco AS cuenta_banco,
                mp.clave AS cuenta_clave,
                mp.dueño AS cuenta_dueno
            FROM actualizacion a
            INNER JOIN cliente c ON a.ejecutor = c.identificacion
            INNER JOIN metodo_pago mp ON a.numero_cuenta_ahorros = mp.numero
            WHERE a.fecha_cambio BETWEEN '$fecha1' AND '$fecha2'
            ORDER BY a.fecha_cambio DESC, a.numero_cuenta_ahorros ASC
        ";

        $resultadoB1 = mysqli_query($conn, $query);

        if (!$resultadoB1) {
            echo "<div class='alert alert-danger'>Error en la consulta: " . mysqli_error($conn) . "</div>";
            mysqli_close($conn);
            include "../includes/footer.php";
            exit();
        }

        mysqli_close($conn);

        // Verificar si hay resultados
        if($resultadoB1 && $resultadoB1->num_rows > 0):
    ?>

    <!-- TABLA DE RESULTADOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>Resultados de la Búsqueda
                <span class="badge bg-light text-success"><?= $resultadoB1->num_rows ?> registro(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th colspan="4" class="text-center bg-primary">DATOS DE LA ACTUALIZACIÓN</th>
                            <th colspan="7" class="text-center bg-info">DATOS DEL EJECUTOR (CLIENTE)</th>
                            <th colspan="7" class="text-center bg-warning text-dark">DATOS DE LA CUENTA DE AHORROS</th>
                        </tr>
                        <tr>
                            <!-- Actualización -->
                            <th scope="col" class="text-center">Nº Cuenta</th>
                            <th scope="col" class="text-center">Fecha Cambio</th>
                            <th scope="col" class="text-center">Sig. Actualización</th>
                            <th scope="col" class="text-center">Detalles</th>
                            <!-- Ejecutor -->
                            <th scope="col" class="text-center">Identificación</th>
                            <th scope="col" class="text-center">Primer Nombre</th>
                            <th scope="col" class="text-center">Segundo Nombre</th>
                            <th scope="col" class="text-center">Primer Apellido</th>
                            <th scope="col" class="text-center">Segundo Apellido</th>
                            <th scope="col" class="text-center">Teléfono</th>
                            <th scope="col" class="text-center">Pago Efectivo</th>
                            <!-- Cuenta -->
                            <th scope="col" class="text-center">Número</th>
                            <th scope="col" class="text-center">Correo</th>
                            <th scope="col" class="text-center">Saldo</th>
                            <th scope="col" class="text-center">Tipo</th>
                            <th scope="col" class="text-center">Banco</th>
                            <th scope="col" class="text-center">Clave</th>
                            <th scope="col" class="text-center">Dueño</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultadoB1 as $fila): ?>
                        <tr>
                            <!-- Datos de Actualización -->
                            <td class="text-center fw-semibold"><?= htmlspecialchars($fila["numero_cuenta_ahorros"]); ?></td>
                            <td class="text-center">
                                <span class="badge bg-info"><?= date('d/m/Y', strtotime($fila["fecha_cambio"])); ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark"><?= date('d/m/Y', strtotime($fila["siguiente_actualizacion"])); ?></span>
                            </td>
                            <td class="text-start"><small><?= htmlspecialchars($fila["detalles"]); ?></small></td>
                            
                            <!-- Datos del Ejecutor -->
                            <td class="text-center fw-bold"><?= htmlspecialchars($fila["ejecutor_id"]); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["ejecutor_primer_nombre"]); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["ejecutor_segundo_nombre"] ?? '-'); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["ejecutor_primer_apellido"]); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["ejecutor_segundo_apellido"] ?? '-'); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["ejecutor_telefono"]); ?></td>
                            <td class="text-center">
                                <?php if ($fila["ejecutor_pago_efectivo"]): ?>
                                    <span class="badge bg-success">Sí</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Datos de la Cuenta -->
                            <td class="text-center"><?= htmlspecialchars($fila["cuenta_numero"]); ?></td>
                            <td class="text-center"><?= htmlspecialchars($fila["cuenta_correo"]); ?></td>
                            <td class="text-center text-success fw-bold">$<?= number_format($fila["cuenta_saldo"], 2); ?></td>
                            <td class="text-center"><span class="badge bg-info"><?= htmlspecialchars(ucfirst($fila["cuenta_tipo"])); ?></span></td>
                            <td class="text-center"><?= htmlspecialchars($fila["cuenta_banco"]); ?></td>
                            <td class="text-center"><span class="badge bg-warning text-dark"><?= htmlspecialchars($fila["cuenta_clave"]); ?></span></td>
                            <td class="text-center"><?= htmlspecialchars($fila["cuenta_dueno"]); ?></td>
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
            <strong>Búsqueda completada.</strong> Se encontraron <?= $resultadoB1->num_rows ?> actualización(es) 
            entre el <?= date('d/m/Y', strtotime($fecha1)); ?> y el <?= date('d/m/Y', strtotime($fecha2)); ?>.
        </div>
    </div>

    <?php else: ?>

    <!-- Mensaje cuando no hay resultados -->
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
        <div>
            <strong>No se encontraron resultados.</strong> No hay actualizaciones registradas entre 
            el <?= date('d/m/Y', strtotime($fecha1)); ?> y el <?= date('d/m/Y', strtotime($fecha2)); ?>.
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
    const form = document.querySelector('.needs-validation');
    const fecha1 = document.getElementById('fecha1');
    const fecha2 = document.getElementById('fecha2');

    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else if (fecha1.value && fecha2.value) {
                if (new Date(fecha2.value) < new Date(fecha1.value)) {
                    event.preventDefault();
                    alert('La fecha final (f2) debe ser mayor o igual a la fecha inicial (f1)');
                }
            }
            form.classList.add('was-validated');
        }, false);
    }
});
</script>

<?php
include "../includes/footer.php";
?>