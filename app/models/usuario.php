<?php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM usuario ORDER BY ID_USUARIO DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE ID_USUARIO = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO usuario (NOMBRE_COMPLETO, TELEFONO, EMAIL, CLAVE, ID_ROL)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['nombre'],
            $data['telefono'],
            $data['email'],
            $data['clave'],
            1 // siempre admin
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE usuario
            SET NOMBRE_COMPLETO = ?, TELEFONO = ?, EMAIL = ?
            WHERE ID_USUARIO = ?
        ");
        return $stmt->execute([
            $data['nombre'],
            $data['telefono'],
            $data['email'],
            $id
        ]);
    }

    public function updatePassword($id, $clave) {
        $stmt = $this->pdo->prepare("UPDATE usuario SET CLAVE = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$clave, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE ID_USUARIO = ?");
        return $stmt->execute([$id]);
    }
}
