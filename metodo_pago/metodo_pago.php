<?php
include "../includes/header.php";
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="fw-bold text-primary">
            <i class="bi bi-credit-card-fill me-2"></i>Gestión de Métodos de Pago
        </h1>
    </div>

    <!-- FORMULARIO DE INSERCIÓN -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Agregar Nuevo Método de Pago</h5>
        </div>
        <div class="card-body">
            <form action="metodo_pago_insert.php" method="post" class="needs-validation" novalidate id="formMetodoPago">
                
                <div class="row">
                    <!-- Tipo de Método -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo_metodo" class="form-label fw-semibold">
                            <i class="bi bi-list-ul me-1"></i>Tipo de Método de Pago *
                        </label>
                        <select name="tipo_metodo" id="tipo_metodo" class="form-select" required>
                            <option value="" selected disabled>Seleccionar tipo...</option>
                            <option value="cuenta">Cuenta de Ahorros</option>
                            <option value="tarjeta">Tarjeta</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un tipo de método de pago.
                        </div>
                    </div>

                    <!-- Número -->
                    <div class="col-md-6 mb-3">
                        <label for="numero" class="form-label fw-semibold">
                            <i class="bi bi-hash me-1"></i>Número *
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="numero" 
                               name="numero" 
                               min="0"
                               placeholder="Ej: 123456789012345"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese un número válido.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Correo -->
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label fw-semibold">
                            <i class="bi bi-envelope-fill me-1"></i>Correo Electrónico *
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="correo" 
                               name="correo" 
                               maxlength="100"
                               placeholder="Ej: user@example.com"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese un correo válido.
                        </div>
                    </div>

                    <!-- Saldo -->
                    <div class="col-md-6 mb-3">
                        <label for="saldo" class="form-label fw-semibold">
                            <i class="bi bi-currency-dollar me-1"></i>Saldo Inicial *
                        </label>
                        <input type="number" 
                               step="0.01" 
                               class="form-control" 
                               id="saldo" 
                               name="saldo" 
                               min="0"
                               placeholder="Ej: 1000.00"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese un saldo válido.
                        </div>
                    </div>
                </div>

                <!-- Dueño (Cliente) -->
                <div class="mb-3">
                    <label for="cliente" class="form-label fw-semibold">
                        <i class="bi bi-person-fill me-1"></i>Dueño (Cliente) *
                    </label>
                    <select name="cliente" id="cliente" class="form-select" required>
                        <option value="" selected disabled>Seleccionar cliente...</option>
                        <?php
                        require("../cliente/cliente_select.php");
                        if ($resultadoCliente && $resultadoCliente->num_rows > 0):
                            foreach ($resultadoCliente as $fila):
                        ?>
                            <option value="<?= $fila["identificacion"]; ?>">
                                <?= htmlspecialchars($fila["primer_nombre"] . ' ' . $fila["primer_apellido"]); ?> - C.C. <?= $fila["identificacion"]; ?>
                            </option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <div class="invalid-feedback">
                        Por favor seleccione un cliente.
                    </div>
                </div>

                <!-- CAMPOS ESPECÍFICOS PARA CUENTA -->
                <div id="camposCuenta" style="display: none;">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i><strong>Campos para Cuenta de Ahorros</strong>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="banco" class="form-label fw-semibold">
                                <i class="bi bi-bank me-1"></i>Banco *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="banco" 
                                   name="banco"
                                   maxlength="50"
                                   placeholder="Ej: Bancolombia">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label fw-semibold">
                                <i class="bi bi-shield-lock-fill me-1"></i>Clave (4 dígitos) *
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="clave" 
                                   name="clave"
                                   min="0"
                                   max="9999"
                                   placeholder="Ej: 1234">
                        </div>
                    </div>
                </div>

                <!-- CAMPOS ESPECÍFICOS PARA TARJETA -->
                <div id="camposTarjeta" style="display: none;">
                    <div class="alert alert-success">
                        <i class="bi bi-credit-card-2-front-fill me-2"></i><strong>Campos para Tarjeta</strong>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cvv" class="form-label fw-semibold">
                                <i class="bi bi-lock-fill me-1"></i>CVV (3 dígitos) *
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="cvv" 
                                   name="cvv"
                                   min="0"
                                   max="999"
                                   placeholder="Ej: 123">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_vencimiento" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Fecha de Vencimiento *
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="fecha_vencimiento" 
                                   name="fecha_vencimiento">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="titular" class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Titular *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="titular" 
                                   name="titular"
                                   maxlength="40"
                                   placeholder="Ej: Juan Pérez">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="activa" 
                                   name="activa"
                                   checked>
                            <label class="form-check-label fw-semibold" for="activa">
                                <i class="bi bi-check-circle me-1"></i>Tarjeta Activa
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Agregar Método de Pago
                    </button>
                </div>

            </form>
        </div>
    </div>

    <?php
    require("metodo_pago_select.php");
    if ($resultadoMetodoPago && $resultadoMetodoPago->num_rows > 0):
    ?>

    <!-- TABLA DE MÉTODOS DE PAGO -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>Lista de Métodos de Pago
                <span class="badge bg-light text-success"><?= $resultadoMetodoPago->num_rows ?></span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Número</th>
                            <th scope="col" class="text-center">Tipo</th>
                            <th scope="col" class="text-center">Correo</th>
                            <th scope="col" class="text-center">Saldo</th>
                            <th scope="col" class="text-center">Documento Dueño</th>
                            <th scope="col" class="text-center">Banco/CVV</th>
                            <th scope="col" class="text-center">Activa</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultadoMetodoPago as $fila): ?>
                        <tr>
                            <td class="text-center fw-semibold"><?= htmlspecialchars($fila["numero"]); ?></td>
                            <td class="text-center">
                                <?php if ($fila["tipo"] === 'cuenta'): ?>
                                    <span class="badge bg-info">
                                        <i class="bi bi-bank me-1"></i>Cuenta
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary">
                                        <i class="bi bi-credit-card me-1"></i>Tarjeta
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($fila["correo"]); ?>
                            </td>
                            <td class="text-center text-success fw-bold">
                                $<?= number_format($fila["saldo"], 2); ?>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($fila["dueno"]); ?></td>
                            <td class="text-center">
                                <?php if ($fila["tipo"] === 'cuenta'): ?>
                                    <?= htmlspecialchars($fila["banco"] ?? '-'); ?>
                                <?php else: ?>
                                    CVV: ***
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (is_null($fila["activa"])): ?>
                                    <span class="badge bg-secondary">N/A</span>
                                <?php elseif ($fila["activa"]): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Sí
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>No
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <form action="metodo_pago_delete.php" method="post" class="d-inline">
                                    <input type="hidden" name="numeroEliminar" value="<?= htmlspecialchars($fila["numero"]); ?>">
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Está seguro de eliminar este método de pago?')">
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
            No hay métodos de pago registrados. ¡Agrega el primero usando el formulario de arriba!
        </div>
    </div>

    <?php endif; ?>

</div>

<!-- Script para mostrar/ocultar campos según el tipo -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoMetodo = document.getElementById('tipo_metodo');
    const camposCuenta = document.getElementById('camposCuenta');
    const camposTarjeta = document.getElementById('camposTarjeta');
    
    const banco = document.getElementById('banco');
    const clave = document.getElementById('clave');
    const cvv = document.getElementById('cvv');
    const fechaVencimiento = document.getElementById('fecha_vencimiento');
    const titular = document.getElementById('titular');
    
    function actualizarCampos() {
        const tipo = tipoMetodo.value;
        
        if (tipo === 'cuenta') {
            // Mostrar campos de cuenta
            camposCuenta.style.display = 'block';
            camposTarjeta.style.display = 'none';
            
            // Hacer campos de cuenta obligatorios
            banco.required = true;
            clave.required = true;
            
            // Quitar obligatoriedad de tarjeta
            cvv.required = false;
            fechaVencimiento.required = false;
            titular.required = false;
            
            // Limpiar campos de tarjeta
            cvv.value = '';
            fechaVencimiento.value = '';
            titular.value = '';
            
        } else if (tipo === 'tarjeta') {
            // Mostrar campos de tarjeta
            camposCuenta.style.display = 'none';
            camposTarjeta.style.display = 'block';
            
            // Hacer campos de tarjeta obligatorios
            cvv.required = true;
            fechaVencimiento.required = true;
            titular.required = true;
            
            // Quitar obligatoriedad de cuenta
            banco.required = false;
            clave.required = false;
            
            // Limpiar campos de cuenta
            banco.value = '';
            clave.value = '';
            
        } else {
            // Ocultar ambos
            camposCuenta.style.display = 'none';
            camposTarjeta.style.display = 'none';
            
            // Quitar todas las obligatoriedades
            banco.required = false;
            clave.required = false;
            cvv.required = false;
            fechaVencimiento.required = false;
            titular.required = false;
        }
    }
    
    tipoMetodo.addEventListener('change', actualizarCampos);
    
    // Validación de formulario Bootstrap
    const form = document.getElementById('formMetodoPago');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
    
    // Reset form
    form.addEventListener('reset', function() {
        setTimeout(function() {
            camposCuenta.style.display = 'none';
            camposTarjeta.style.display = 'none';
            form.classList.remove('was-validated');
        }, 10);
    });
});
</script>

<?php
include "../includes/footer.php";
?>