
$(document).ready(function () {
    $('#productosTable').DataTable({
        "ajax": {
            "url": "../controllers/productoController.php?action=fetch",
            "type": "GET"
        },
        "columns": [
            { "title": "ID" },
            { "title": "Nombre" },
            { "title": "Descripción" },
            { "title": "Precio" },
            { "title": "Stock" },
            { "title": "Acciones", "orderable": false }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    // Cargar datos en el modal de edición
    $(document).on("click", ".btnEditar", function () {
        let id_producto = $(this).data("id");
        $.ajax({
            url: "../controllers/productoController.php",
            type: "GET",
            data: {
                action: "get", id_producto: id_producto
            },
            dataType: "json",
            success: function (data) {
                $("#id_producto").val(data.id_producto);
                $("#edit_nombre").val(data.nombre);
                $("#edit_descripcion").val(data.descripcion);
                $("#edit_id_categoria").val(data.id_categoria);
                $("#edit_color").val(data.color);
                $("#edit_talla").val(data.talla);
                $("#edit_precio").val(data.precio);
                $("#edit_stock").val(data.stock);
                $("#modalEditar").modal("show");
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la información del producto.',
                });
            }
        });
    });

    // Guardar cambios en edición
    $("#formEditar").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "../controllers/productoController.php?action=edit",
            type: "POST",
            data: $(this).serialize(),
            success: function () {
                $("#modalEditar").modal("hide");
                $("#productosTable").DataTable().ajax.reload();
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el producto.',
                });
            }
        });
    });

    // Agregar producto con AJAX
    $("#formAgregar").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "../controllers/productoController.php?action=create",
            type: "POST",
            data: $(this).serialize(),
            success: function () {
                $("#modalAgregar").modal("hide");
                $("#productosTable").DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto agregado!',
                    text: 'El producto se ha agregado correctamente.',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo agregar el producto. Inténtalo de nuevo.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // Eliminar producto con AJAX
    $(document).on("click", ".btnEliminar", function () {
        let id_producto = $(this).data("id");
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../controllers/productoController.php?action=delete&id_producto=" + id_producto;
            }
        });
    });
});
