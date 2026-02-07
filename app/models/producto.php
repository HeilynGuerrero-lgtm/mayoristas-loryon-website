<?php
class Producto {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function all() {
        $stmt = $this->pdo->query("SELECT 
            ID_PRODUCTO as id_producto,
            NOMBRE as nombre,
            CATEGORIA as categoria,
            PRECIO_COMPRA as precio_compra,
            PRECIO_VENTA as precio_venta,
            STOCK as stock,
            DESCRIPCION as descripcion,
            IMAGEN as imagen,
            PROV_COD as prov_cod
        FROM producto");
        return $stmt->fetchAll();
    }
   public function obtenerCategorias() {
    $stmt = $this->pdo->query("SELECT DISTINCT CATEGORIA FROM producto ORDER BY CATEGORIA ASC");
    return $stmt->fetchAll();
}

public function obtenerPorCategoria($categoria) {
    $sql = "SELECT 
            ID_PRODUCTO as id_producto,
            NOMBRE as nombre,
            CATEGORIA as categoria,
            PRECIO_COMPRA as precio_compra,
            PRECIO_VENTA as precio_venta,
            STOCK as stock,
            DESCRIPCION as descripcion,
            IMAGEN as imagen,
            PROV_COD as prov_cod
        FROM producto
        WHERE CATEGORIA = :cat";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':cat' => $categoria]);
    return $stmt->fetchAll();
}



    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM producto WHERE id_producto = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }

public function create($data) {
    $sql = "INSERT INTO producto (id_producto,nombre,categoria,precio_compra,precio_venta,stock,descripcion,imagen,prov_cod) 
            VALUES (:id,:nombre,:categoria,:pc,:pv,:stock,:desc,:img,:prov)";
    $this->pdo->prepare($sql)->execute([
        ':id'=>$data['id_producto'],
        ':nombre'=>$data['nombre'],
        ':categoria'=>$data['categoria'],
        ':pc'=>$data['precio_compra'],
        ':pv'=>$data['precio_venta'],
        ':stock'=>$data['stock'],
        ':desc'=>$data['descripcion'],
        ':img'=>$data['imagen'],
        ':prov'=>$data['prov_cod']
    ]);
}

public function buscar($texto)
{
    $stmt = $this->pdo->prepare("
        SELECT 
            ID_PRODUCTO as id_producto,
            NOMBRE as nombre,
            CATEGORIA as categoria,
            PRECIO_COMPRA as precio_compra,
            PRECIO_VENTA as precio_venta,
            STOCK as stock,
            DESCRIPCION as descripcion,
            IMAGEN as imagen
        FROM producto
        WHERE NOMBRE LIKE ?
    ");

    $stmt->execute(["%$texto%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function update($id, $data) {
    $sql = "UPDATE producto 
            SET 
                NOMBRE = :nombre,
                CATEGORIA = :categoria,
                PRECIO_COMPRA = :pc,
                PRECIO_VENTA = :pv,
                STOCK = :stock,
                DESCRIPCION = :desc,
                IMAGEN = :img,
                PROV_COD = :prov
            WHERE ID_PRODUCTO = :id";
    $this->pdo->prepare($sql)->execute([
        ':id' => $id,
        ':nombre' => $data['nombre'],
        ':categoria' => $data['categoria'],
        ':pc' => $data['precio_compra'],
        ':pv' => $data['precio_venta'],
        ':stock' => $data['stock'],
        ':desc' => $data['descripcion'],
        ':img' => $data['imagen'],
        ':prov' => $data['prov_cod']
    ]);
}


    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM producto WHERE ID_PRODUCTO = ?");
        return $stmt->execute([$id]);
    }

}
