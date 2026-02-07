<?php
// app/controllers/ProductoController.php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/productos/producto.php';

class ProductoController {
    private $producto;

    public function __construct($pdo) {
        $this->producto = new Producto($pdo);
    }

    public function index() {
        $productos = $this->producto->all();
        include __DIR__ . '/../views/productos/index.php';
    }

    public function show($id) {
        $producto = $this->producto->find($id);
        include __DIR__ . '/../views/productos/show.php';
    }
}
