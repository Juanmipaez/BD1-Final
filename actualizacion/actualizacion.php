<?php
include "../includes/header.php";
?>

<h1 class="mt-3 fw-bold">Entidad análoga a ACTUALIZACION (Actualización)</h1>

<div class="formulario p-4 m-3 border rounded-3">

    <form action="actualizacion_insert.php" method="post" class="form-group">

        <!-- Cuenta de ahorros a la que se le hace la actualización -->
        <div class="mb-3">
            <label for="numero_cuenta_ahorros" class="form-label">Número cuenta de ahorros</label>
            <select name="numero_cuenta_ahorros" id="numero_cuenta_ahorros" class="form-select" required>
                <option value="" selected disabled hidden>Seleccionar...</option>

                <?php
                // Traer métodos de pago (usaremos solo los de tipo CUENTA)
                require("../metodo_pago/metodo_pago_select.php");

                if ($resultadoMetodoPago && $resultadoMetodoPago->num_rows > 0):
                    foreach ($resultadoMetodoPago as $filaMP):
                        if ($filaMP["tipo"] === "CUENTA"):
                ?>
                            <option value="<?= $filaMP["numero"]; ?>">
                                <?= $filaMP["numero"]; ?> (Saldo: <?= $filaMP["saldo"]; ?>)
                            </option>
                <?php
                        endif;
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_cambio" class="form-label">Fecha de cambio</label>
            <input type="date" class="form-control" id="fecha_cambio" name="fecha_cambio" required>
        </div>

        <div class="mb-3">
            <label for="siguiente_actualizacion" class="form-label">Siguiente actualización</label>
            <input type="date" class="form-control" id="siguiente_actualizacion" name="siguiente_actualizacion" required>
        </div>

        <div class="mb-3">
            <label for="detalles" class="form-label">Detalles</label>
            <input type="text" class="form-control" id="detalles" name="detalles" required>
        </div>

        <!-- Ejecutor: cliente que realiza la actualización -->
        <div class="mb-3">
            <label for="ejecutor" class="form-label">Ejecutor (cliente)</label>
            <select name="ejecutor" id="ejecutor" class="form-select" required>
                <option value="" selected disabled hidden>Seleccionar cliente...</option>

                <?php
                // Importar lista de clientes
                require("../cliente/cliente_select.php");
                
                if ($resultadoCliente && $resultadoCliente->num_rows > 0):
                    foreach ($resultadoCliente as $filaC):
                ?>
                        <option value="<?= $filaC["identificacion"]; ?>">
                            <?= $filaC["primer_nombre"]; ?> <?= $filaC["primer_apellido"]; ?> - C.C. <?= $filaC["identificacion"]; ?>
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
// Importar el código del otro archivo (SELECT de actualizacion)
require("actualizacion_select.php");

// Verificar si llegan datos
if ($resultadoActualizacion && $resultadoActualizacion->num_rows > 0):
?>

<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Número cuenta ahorros</th>
                <th scope="col" class="text-center">Fecha de cambio</th>
                <th scope="col" class="text-center">Siguiente actualización</th>
                <th scope="col" class="text-center">Detalles</th>
                <th scope="col" class="text-center">Ejecutor (doc)</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($resultadoActualizacion as $fila): ?>

            <tr>
                <td class="text-center"><?= $fila["numero_cuenta_ahorros"]; ?></td>
                <td class="text-center"><?= $fila["fecha_cambio"]; ?></td>
                <td class="text-center"><?= $fila["siguiente_actualizacion"]; ?></td>
                <td class="text-center"><?= $fila["detalles"]; ?></td>
                <td class="text-center"><?= $fila["ejecutor"]; ?></td>
                
                <!-- Botón de eliminar. Usamos la PK compuesta (numero_cuenta_ahorros, fecha_cambio) -->
                <td class="text-center">
                    <form action="actualizacion_delete.php" method="post">
                        <input type="hidden" name="numeroEliminar" value="<?= $fila["numero_cuenta_ahorros"]; ?>">
                        <input type="hidden" name="fecha_cambioEliminar" value="<?= $fila["fecha_cambio"]; ?>">
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
