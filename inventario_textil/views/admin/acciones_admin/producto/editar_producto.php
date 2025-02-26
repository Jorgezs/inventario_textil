
<!-- Modal de Edición del Producto -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditar" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_producto" name="id_producto">

                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_id_categoria" class="form-label">Categoría</label>
                        <input type="number" class="form-control" id="edit_id_categoria" name="id_categoria" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="edit_color" name="color" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_talla" class="form-label">Talla</label>
                            <input type="text" class="form-control" id="edit_talla" name="talla" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="edit_precio" name="precio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-arrow-left"></i> Volver</button>
                </div>
            </form>
        </div>
    </div>
</div>
