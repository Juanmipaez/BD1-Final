<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3 fw-bold">Entidad análoga a CLIENTE (CLIENTE)</h1>

<!-- FORMULARIO. Cambiar los campos de acuerdo a su trabajo -->
<div class="formulario p-4 m-3 border rounded-3">

    <form action="cliente_insert.php" method="post" class="form-group">

        <div class="mb-3">
            <label for="identificacion" class="form-label">Identificación</label>
            <input type="number" class="form-control" id="identificacion" name="identificacion" required>
        </div>

        <div class="mb-3">
            <label for="primer_nombre" class="form-label">Primer nombre</label>
            <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" required>
        </div>  

        <div class="mb-3">
            <label for="segundo_nombre" class="form-label">Segundo nombre</label>
            <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre">
        </div>  

        <div class="mb-3">
            <label for="primer_apellido" class="form-label">Primer Apellido</label>
            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
        </div>

        <div class="mb-3">
            <label for="segundo_apellido" class="form-label"> Segundo Apellido</label>
            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
        </div>  

        <div class="mb-3">
            <label for="celular" class="form-label">Teléfono</label>
            <input type="number" class="form-control" id="celular" name="celular" required>
        </div>

        <div class="mb-3">
            <label for="pago_efectivo" class="form-label">Habilitado pago en efectivo</label>
            <input type="checkbox" id="pago_efectivo" name="pago_efectivo" checked>
            <!-- <input type="checkbox" class="form-control" id="pago_efectivo" name="pago_efectivo" checked> -->
        </div>

        <button type="submit" class="btn btn-primary">Agregar</button>

    </form>
    
</div>

<?php
// Importar el código del otro archivo
require("cliente_select.php");

// Verificar si llegan datos
if($resultadoCliente and $resultadoCliente->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Identificación</th>
                <th scope="col" class="text-center">primer_nombre</th>
                <th scope="col" class="text-center">segundo_nombre</th>
                <th scope="col" class="text-center">celular</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoCliente as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= $fila["identificacion"]; ?></td>
                <td class="text-center"><?= $fila["primer_nombre"]; ?></td>
                <td class="text-center"><?= $fila["celular"]; ?></td>
                
                <!-- Botón de eliminar. Debe de incluir la CP de la entidad para identificarla -->
                <td class="text-center">
                    <form action="cliente_delete.php" method="post">
                        <input hidden type="text" name="cedulaEliminar" value="<?= $fila["identificacion"]; ?>">
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