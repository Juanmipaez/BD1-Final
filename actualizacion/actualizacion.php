<?php
include "../includes/header.php";
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-arrow-repeat me-2"></i>Gestión de Actualizaciones
        </h1>
    </div>

    <!-- FORMULARIO DE INSERCIÓN -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Agregar Nueva Actualización</h5>
        </div>
        <div class="card-body">
            <form action="actualizacion_insert.php" method="post" class="needs-validation" novalidate>
                
                <div class="row">
                    <!-- Cuenta de Ahorros -->
                    <div class="col-md-6 mb-3">
                        <label for="numero_cuenta_ahorros" class="form-label fw-semibold">
                            <i class="bi bi-bank me-1"></i>Número de Cuenta de Ahorros *
                        </label>
                        <select name="numero_cuenta_ahorros" id="numero_cuenta_ahorros" class="form-select" required>
                            <option value="" selected disabled>Seleccionar cuenta...</option>
                            <?php
                            // Traer solo métodos de pago tipo CUENTA
                            require("../metodo_pago/metodo_pago_select.php");
                            
                            $hay_cuentas = false;
                            if ($resultadoMetodoPago && $resultadoMetodoPago->num_rows > 0):
                                foreach ($resultadoMetodoPago as $filaMP):
                                    if (strtolower($filaMP["tipo"]) === "cuenta"):
                                        $hay_cuentas = true;
                            ?>
                                        <option value="<?= htmlspecialchars($filaMP["numero"]); ?>">
                                            <?= htmlspecialchars($filaMP["numero"]); ?> - 
                                            Banco: <?= htmlspecialchars($filaMP["banco"]); ?> - 
                                            Saldo: $<?= number_format($filaMP["saldo"], 2); ?>
                                        </option>
                            <?php
                                    endif;
                                endforeach;
                            endif;
                            
                            if (!$hay_cuentas):
                            ?>
                                <option value="" disabled>No hay cuentas de ahorros disponibles</option>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione una cuenta de ahorros.
                        </div>
                        <?php if (!$hay_cuentas): ?>
                        <small class="text-danger">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            No hay cuentas de ahorros registradas. <a href="../metodo_pago/metodo_pago.php">Crear una aquí</a>
                        </small>
                        <?php endif; ?>
                    </div>

                    <!-- Ejecutor -->
                    <div class="col-md-6 mb-3">
                        <label for="ejecutor" class="form-label fw-semibold">
                            <i class="bi bi-person-fill me-1"></i>Ejecutor (Cliente) *
                        </label>
                        <select name="ejecutor" id="ejecutor" class="form-select" required>
                            <option value="" selected disabled>Seleccionar cliente...</option>
                            <?php
                            require("../cliente/cliente_select.php");
                            if ($resultadoCliente && $resultadoCliente->num_rows > 0):
                                foreach ($resultadoCliente as $filaC):
                            ?>
                                    <option value="<?= htmlspecialchars($filaC["identificacion"]); ?>">
                                        <?= htmlspecialchars($filaC["primer_nombre"] . ' ' . $filaC["primer_apellido"]); ?> - 
                                        C.C. <?= htmlspecialchars($filaC["identificacion"]); ?>
                                    </option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un ejecutor.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Fecha de Cambio -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_cambio" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Fecha de Cambio *
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="fecha_cambio" 
                               name="fecha_cambio" 
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese la fecha de cambio.
                        </div>
                    </div>

                    <!-- Siguiente Actualización -->
                    <div class="col-md-6 mb-3">
                        <label for="siguiente_actualizacion" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Siguiente Actualización *
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="siguiente_actualizacion" 
                               name="siguiente_actualizacion" 
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese la fecha de siguiente actualización.
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="mb-3">
                    <label for="detalles" class="form-label fw-semibold">
                        <i class="bi bi-card-text me-1"></i>Detalles *
                    </label>
                    <textarea class="form-control" 
                              id="detalles" 
                              name="detalles" 
                              rows="3"
                              maxlength="255"
                              placeholder="Describa los detalles de la actualización..."
                              required></textarea>
                    <div class="form-text">Máximo 255 caracteres</div>
                    <div class="invalid-feedback">
                        Por favor ingrese los detalles de la actualización.
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary" <?= !$hay_cuentas ? 'disabled' : '' ?>>
                        <i class="bi bi-check-circle me-1"></i>Agregar Actualización
                    </button>
                </div>

            </form>
        </div>
    </div>

    <?php
    require("actualizacion_select.php");
    if ($resultadoActualizacion && $resultadoActualizacion->num_rows > 0):
    ?>

    <!-- TABLA DE ACTUALIZACIONES -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>Lista de Actualizaciones
                <span class="badge bg-light text-success"><?= $resultadoActualizacion->num_rows ?></span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Nº Cuenta</th>
                            <th scope="col" class="text-center">Fecha Cambio</th>
                            <th scope="col" class="text-center">Siguiente Actualización</th>
                            <th scope="col" class="text-center">Detalles</th>
                            <th scope="col" class="text-center">Ejecutor</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultadoActualizacion as $fila): ?>
                        <tr>
                            <td class="text-center fw-semibold">
                                <i class="bi bi-bank2 me-1"></i><?= htmlspecialchars($fila["numero_cuenta_ahorros"]); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <?= date('d/m/Y', strtotime($fila["fecha_cambio"])); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    <?= date('d/m/Y', strtotime($fila["siguiente_actualizacion"])); ?>
                                </span>
                            </td>
                            <td class="text-start">
                                <small><?= htmlspecialchars($fila["detalles"]); ?></small>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($fila["ejecutor"]); ?>
                            </td>
                            <td class="text-center">
                                <form action="actualizacion_delete.php" method="post" class="d-inline">
                                    <input type="hidden" name="numeroEliminar" value="<?= htmlspecialchars($fila["numero_cuenta_ahorros"]); ?>">
                                    <input type="hidden" name="fecha_cambioEliminar" value="<?= htmlspecialchars($fila["fecha_cambio"]); ?>">
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Está seguro de eliminar esta actualización?')">
                                        <i class="bi bi-trash-fill me-1"></i>Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php else: ?>
    
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            No hay actualizaciones registradas. ¡Agrega la primera usando el formulario de arriba!
        </div>
    </div>

    <?php endif; ?>

</div>

<!-- Script de validación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de Bootstrap
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

    // Validación de fechas
    const fechaCambio = document.getElementById('fecha_cambio');
    const siguienteActualizacion = document.getElementById('siguiente_actualizacion');

    if (fechaCambio && siguienteActualizacion) {
        siguienteActualizacion.addEventListener('change', function() {
            if (fechaCambio.value && siguienteActualizacion.value) {
                if (new Date(siguienteActualizacion.value) <= new Date(fechaCambio.value)) {
                    alert('La fecha de siguiente actualización debe ser posterior a la fecha de cambio');
                    siguienteActualizacion.value = '';
                }
            }
        });
    }
});
</script>

<?php
include "../includes/footer.php";
?>