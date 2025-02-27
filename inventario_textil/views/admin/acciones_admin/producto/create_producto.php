<!-- Modal Agregar Producto -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarLabel"><i class="fas fa-box"></i> Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <form id="formAgregar">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">Categoría</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <option value="1">Camisas</option>
                                <option value="2">Pantalones</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Color</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-palette"></i></span>
                                <input type="text" class="form-control" id="color" name="color" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="talla" class="form-label">Talla</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                <input type="text" class="form-control" id="talla" name="talla" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label">Precio (€)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-euro-sign"></i></span>
                                <input type="number" class="form-control" id="precio" name="precio" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                <input type="number" class="form-control" id="stock" name="stock" required>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
