<?php
// Archivo para manejar las rutas de los recursos (CSS y JS)

function cargarRecursos() {
    // Estilos (CSS)
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">' . PHP_EOL;
    echo '<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">' . PHP_EOL;
   echo '  <link rel="stylesheet" href="../public/styles.css">'. PHP_EOL;
    // Scripts JS
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>' . PHP_EOL;
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>' . PHP_EOL;
    echo '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>' . PHP_EOL;
    echo '<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>' . PHP_EOL;
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>' . PHP_EOL;

}
?>
