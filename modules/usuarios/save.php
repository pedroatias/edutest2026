<?php
require_once '../../includes/header.php';
if($_SESSION['user_rol'] !== 'admin') { header('Location: ../../dashboard.php'); exit; }
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre']);
$usuario = trim($_POST['usuario']);
$password = $_POST['password'];
$rol = $_POST['rol'];
$email = trim($_POST['email']);
$activo = isset($_POST['activo']) ? 1 : 0;
if($id > 0) {
  if(!empty($password)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE usuarios SET nombre=?, usuario=?, password=?, rol=?, email=?, activo=? WHERE id=?")
      ->execute([$nombre, $usuario, $hash, $rol, $email, $activo, $id]);
  } else {
    $pdo->prepare("UPDATE usuarios SET nombre=?, usuario=?, rol=?, email=?, activo=? WHERE id=?")
      ->execute([$nombre, $usuario, $rol, $email, $activo, $id]);
  }
  header('Location: index.php?flash=updated');
} else {
  if(empty($password)) { header('Location: index.php?error=password_required'); exit; }
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $pdo->prepare("INSERT INTO usuarios (nombre, usuario, password, rol, email, activo) VALUES (?,?,?,?,?,?)")
    ->execute([$nombre, $usuario, $hash, $rol, $email, $activo]);
  header('Location: index.php?flash=created');
}
exit;
