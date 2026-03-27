<?php
require_once '../../includes/header.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre']);
$grado = trim($_POST['grado']);
$seccion = trim($_POST['seccion']);
$descripcion = trim($_POST['descripcion']);
$profesor_id = !empty($_POST['profesor_id']) ? intval($_POST['profesor_id']) : null;
if($id > 0) {
  $pdo->prepare("UPDATE cursos SET nombre=?, grado=?, seccion=?, descripcion=?, profesor_id=? WHERE id=?")
    ->execute([$nombre, $grado, $seccion, $descripcion, $profesor_id, $id]);
  header('Location: index.php?flash=updated');
} else {
  $pdo->prepare("INSERT INTO cursos (nombre, grado, seccion, descripcion, profesor_id) VALUES (?,?,?,?,?)")
    ->execute([$nombre, $grado, $seccion, $descripcion, $profesor_id]);
  header('Location: index.php?flash=created');
}
exit;
