<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3 fw-bold">Entidad análoga a EMPRESA (Método de pago)</h1>

<!-- FORMULARIO. Cambiar los campos de acuerdo a su trabajo -->
<div class="formulario p-4 m-3 border rounded-3">

    <form action="empresa_insert.php" method="post" class="form-group">

        <div class="mb-3">
            <label for="tipo_metodo" class="form-label">Tipo de Método de Pago</label>
            <select name="tipo_metodo" id="tipo_metodo" class="form-select" required>
                <option value="" selected disabled hidden>Seleccionar...</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="cuenta_banco">Cuenta Bancaria</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_metodo" class="form-label">Número</label>
            <input type="number" class="form-control" id="id_metodo" name="id_metodo" required>
        </div>

        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="number" class="form-control" id="cvv" name="cvv" required>
        </div>

        <div class="mb-3">
            <label for="fecha_venc" class="form-label">Fecha vencimiento</label>
            <input type="date" class="form-control" id="fecha_venc" name="fecha_venc" required>
        </div>

        <div class="mb-3">
            <label for="estado_metodo" class="form-label">Metodo de pago activo</label>
            <input type="checkbox" id="estado_metodo" name="estado_metodo" checked>
        </div>
        
        <!-- Consultar la lista de clientes y desplegarlos -->
        <div class="mb-3">
            <label for="cliente" class="form-label">Cliente</label>
            <select name="cliente" id="cliente" class="form-select">
                
                <!-- Option por defecto -->
                <option value="" selected disabled hidden></option>

                <?php
                // Importar el código del otro archivo
                require("../cliente/cliente_select.php");
                
                // Verificar si llegan datos
                if($resultadoCliente):
                    
                    // Iterar sobre los registros que llegaron
                    foreach ($resultadoCliente as $fila):
                ?>

                <!-- Opción que se genera -->
                <option value="<?= $fila["cedula"]; ?>"><?= $fila["nombre"]; ?> - C.C. <?= $fila["cedula"]; ?></option>

                <?php
                        // Cerrar los estructuras de control
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar</button>

    </form>
    
</div>

<?php
// Importar el código del otro archivo
require("empresa_select.php");

// Verificar si llegan datos
if($resultadoEmpresa and $resultadoEmpresa->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">NIT</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Presupuesto</th>
                <th scope="col" class="text-center">Cliente</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoEmpresa as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= $fila["nit"]; ?></td>
                <td class="text-center"><?= $fila["nombre"]; ?></td>
                <td class="text-center">$<?= $fila["presupuesto"]; ?></td>
                <td class="text-center">C.C. <?= $fila["cliente"]; ?></td>
                
                <!-- Botón de eliminar. Debe de incluir la CP de la entidad para identificarla -->
                <td class="text-center">
                    <form action="empresa_delete.php" method="post">
                        <input hidden type="text" name="nitEliminar" value="<?= $fila["nit"]; ?>">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>

            </tr>

            <?php
            // Cerrar los estructuras de control
            endforeach;
            ?>

        </tbody>

    </table>
</div>

<?php
endif;

include "../includes/footer.php";
?>