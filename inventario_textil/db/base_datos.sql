-- Crear la base de datos
CREATE DATABASE inventario_textil;
USE inventario_textil;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') NOT NULL
);

-- Tabla de categorías
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Tabla de productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    id_categoria INT,
    color VARCHAR(50),
    talla VARCHAR(20),
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- Tabla de movimientos de inventario
CREATE TABLE movimientos_inventario (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla de pedidos
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'enviado', 'entregado', 'cancelado') NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla de detalles del pedido
CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

-- Insertar datos en usuarios
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Admin1', 'admin1@example.com', 'admin123', 'admin'),
('Usuario1', 'usuario1@example.com', 'user123', 'usuario');

-- Insertar datos en categorías
INSERT INTO categorias (nombre, descripcion) VALUES
('Camisas', 'Ropa superior de diferentes estilos'),
('Pantalones', 'Pantalones de varias tallas y colores');

-- Insertar datos en productos
INSERT INTO productos (nombre, descripcion, id_categoria, color, talla, precio, stock) VALUES
('Camisa Azul', 'Camisa de algodón azul', 1, 'Azul', 'M', 25.99, 50),
('Pantalón Negro', 'Pantalón de mezclilla negro', 2, 'Negro', 'L', 39.99, 30);

-- Insertar datos en movimientos de inventario
INSERT INTO movimientos_inventario (id_producto, id_usuario, tipo_movimiento, cantidad, descripcion) VALUES
(1, 1, 'entrada', 50, 'Ingreso inicial de stock'),
(2, 1, 'entrada', 30, 'Ingreso inicial de stock');

-- Insertar datos en pedidos
INSERT INTO pedidos (id_usuario, estado) VALUES
(2, 'pendiente');

-- Insertar datos en detalle_pedido
INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES
(1, 1, 2, 25.99),
(1, 2, 1, 39.99);
