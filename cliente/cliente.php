<?php
include "../includes/header.php";
?>

<!-- TÍTULO -->
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-people-fill me-2"></i>Gestión de Clientes
        </h1>
    </div>

    <!-- FORMULARIO DE INSERCIÓN -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Agregar Nuevo Cliente</h5>
        </div>
        <div class="card-body">
            <form action="cliente_insert.php" method="post" class="needs-validation" novalidate>
                
                <div class="row">
                    <!-- Identificación -->
                    <div class="col-md-6 mb-3">
                        <label for="identificacion" class="form-label fw-semibold">
                            <i class="bi bi-card-text me-1"></i>Identificación *
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="identificacion" 
                               name="identificacion" 
                               min="0" 
                               max="9999999999"
                               placeholder="Ej: 1234567890"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese una identificación válida.
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label fw-semibold">
                            <i class="bi bi-telephone-fill me-1"></i>Teléfono *
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="telefono" 
                               name="telefono" 
                               min="0"
                               placeholder="Ej: 3001234567"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese un teléfono válido.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Primer Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="primer_nombre" class="form-label fw-semibold">
                            Primer Nombre *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="primer_nombre" 
                               name="primer_nombre" 
                               maxlength="50"
                               placeholder="Ej: Juan"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese el primer nombre.
                        </div>
                    </div>

                    <!-- Segundo Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="segundo_nombre" class="form-label fw-semibold">
                            Segundo Nombre
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="segundo_nombre" 
                               name="segundo_nombre" 
                               maxlength="50"
                               placeholder="Ej: Carlos">
                    </div>
                </div>

                <div class="row">
                    <!-- Primer Apellido -->
                    <div class="col-md-6 mb-3">
                        <label for="primer_apellido" class="form-label fw-semibold">
                            Primer Apellido *
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="primer_apellido" 
                               name="primer_apellido" 
                               maxlength="50"
                               placeholder="Ej: Pérez"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese el primer apellido.
                        </div>
                    </div>

                    <!-- Segundo Apellido -->
                    <div class="col-md-6 mb-3">
                        <label for="segundo_apellido" class="form-label fw-semibold">
                            Segundo Apellido
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="segundo_apellido" 
                               name="segundo_apellido" 
                               maxlength="50"
                               placeholder="Ej: González">
                    </div>
                </div>

                <!-- Pago en Efectivo -->
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="pago_efectivo" 
                               name="pago_efectivo">
                        <label class="form-check-label fw-semibold" for="pago_efectivo">
                            <i class="bi bi-cash-coin me-1"></i>Habilitar pago en efectivo
                        </label>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Agregar Cliente
                    </button>
                </div>

            </form>
        </div>
    </div>

    <?php
    // Importar el código del otro archivo (hace el SELECT * FROM cliente)
    require("cliente_select.php");

    // Verificar si llegan datos
    if ($resultadoCliente && $resultadoCliente->num_rows > 0):
    ?>

    <!-- TABLA DE CLIENTES -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>Lista de Clientes 
                <span class="badge bg-light text-success"><?= $resultadoCliente->num_rows ?></span>
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
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultadoCliente as $fila): ?>
                        <tr>
                            <td class="text-center fw-semibold"><?= htmlspecialchars($fila["identificacion"]); ?></td>
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
                                <form action="cliente_delete.php" method="post" class="d-inline">
                                    <input type="hidden" name="identificacionEliminar" value="<?= htmlspecialchars($fila["identificacion"]); ?>">
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Está seguro de eliminar al cliente <?= htmlspecialchars($fila["primer_nombre"] . ' ' . $fila["primer_apellido"]); ?>?')">
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
    
    <!-- MENSAJE CUANDO NO HAY DATOS -->
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            No hay clientes registrados. ¡Agrega el primero usando el formulario de arriba!
        </div>
    </div>

    <?php endif; ?>

</div>

<!-- Script de validación de Bootstrap -->
<script>
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php
include "../includes/footer.php";
?>