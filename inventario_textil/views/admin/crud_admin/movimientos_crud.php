<?php
// movimientos.php
$movimientos = Movimiento::obtenerMovimientos();
?>

        <!-- Contenido de Movimientos -->
        <div class="tab-pane fade show active" id="pills-movimientos">
            <h3>Movimientos de Inventario</h3>
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID Movimiento</th>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $movimiento): ?>
                        <tr>
                            <td><?= $movimiento['id_movimiento'] ?></td>
                            <td><?= $movimiento['producto'] ?></td>
                            <td><?= $movimiento['usuario'] ?></td>
                            <td><?= ucfirst($movimiento['tipo_movimiento']) ?></td>
                            <td><?= $movimiento['cantidad'] ?></td>
                            <td><?= $movimiento['fecha_movimiento'] ?></td>
                            <td><?= $movimiento['descripcion'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>