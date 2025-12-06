<?php
include "../includes/header.php";
?>

<h1 class="mt-3 fw-bold">Entidad análoga a METODO_PAGO (Método de pago)</h1>

<div class="formulario p-4 m-3 border rounded-3">

    <form action="metodo_pago_insert.php" method="post" class="form-group">

        <!-- Tipo de método de pago -->
        <div class="mb-3">
            <label for="tipo_metodo" class="form-label">Tipo de método de pago</label>
            <select name="tipo_metodo" id="tipo_metodo" class="form-select" required>
                <option value="" selected disabled hidden>Seleccionar...</option>
                <option value="CUENTA">Cuenta de ahorros</option>
                <option value="TARJETA">Tarjeta</option>
            </select>
        </div>

        <!-- PK -->
        <div class="mb-3">
            <label for="numero" class="form-label">Número</label>
            <input type="number" class="form-control" id="numero" name="numero" required>
        </div>

        <!-- Saldo -->
        <div class="mb-3">
            <label for="saldo" class="form-label">Saldo</label>
            <input type="number" step="0.01" class="form-control" id="saldo" name="saldo" required>
        </div>

        <!-- Campos para CUENTA -->
        <div class="mb-3">
            <label for="banco" class="form-label">Banco (solo CUENTA)</label>
            <input type="text" class="form-control" id="banco" name="banco">
        </div>

        <div class="mb-3">
            <label for="clave" class="form-label">Clave (solo CUENTA)</label>
            <input type="number" class="form-control" id="clave" name="clave">
        </div>

        <!-- Campos para TARJETA -->
        <div class="mb-3">
            <label for="cvv" class="form-label">CVV (solo TARJETA)</label>
            <input type="number" class="form-control" id="cvv" name="cvv">
        </div>

        <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento (solo TARJETA)</label>
            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
        </div>

        <div class="mb-3">
            <label for="titular" class="form-label">Titular (solo TARJETA)</label>
            <input type="text" class="form-control" id="titular" name="titular">
        </div>

        <div class="mb-3">
            <label for="activa" class="form-label">Método activo (solo TARJETA)</label>
            <input type="checkbox" id="activa" name="activa" checked>
        </div>

        <!-- Dueño: documento del cliente -->
        <div class="mb-3">
            <label for="cliente" class="form-label">Dueño (documento del cliente)</label>
            <select name="cliente" id="cliente" class="form-select" required>
                <option value="" selected disabled hidden>Seleccionar cliente...</option>

                <?php
                // Traer todos los clientes
                require("../cliente/cliente_select.php");
                
                if ($resultadoCliente && $resultadoCliente->num_rows > 0):
                    foreach ($resultadoCliente as $fila):
                ?>
                    <option value="<?= $fila["identificacion"]; ?>">
                        <?= $fila["primer_nombre"]; ?> <?= $fila["primer_apellido"]; ?> - C.C. <?= $fila["identificacion"]; ?>
                    </option>
                <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar</button>

    </form>
    
</div>

<?php
// Importar el SELECT de métodos de pago
require("metodo_pago_select.php");

// Verificar si llegan datos
if ($resultadoMetodoPago && $resultadoMetodoPago->num_rows > 0):
?>

<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Número</th>
                <th scope="col" class="text-center">Tipo</th>
                <th scope="col" class="text-center">Saldo</th>
                <th scope="col" class="text-center">Documento dueño</th>
                <th scope="col" class="text-center">Activa</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($resultadoMetodoPago as $fila): ?>

            <tr>
                <td class="text-center"><?= $fila["numero"]; ?></td>
                <td class="text-center"><?= $fila["tipo"]; ?></td>
                <td class="text-center">$<?= $fila["saldo"]; ?></td>
                <td class="text-center"><?= $fila["dueno"]; ?></td>
                <td class="text-center">
                    <?= is_null($fila["activa"]) ? "-" : ($fila["activa"] ? "Sí" : "No"); ?>
                </td>
                
                <td class="text-center">
                    <form action="metodo_pago_delete.php" method="post">
                        <input type="hidden" name="numeroEliminar" value="<?= $fila["numero"]; ?>">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>

            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
</div>

<?php
endif;

include "../includes/footer.php";
?>
