<?php
require_once '../../includes/header.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$documento = trim($_POST['documento']);
$email = trim($_POST['email']);
$telefono = trim($_POST['telefono']);
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?: null;
$curso_id = !empty($_POST['curso_id']) ? intval($_POST['curso_id']) : null;
$direccion = trim($_POST['direccion']);
$acudiente = trim($_POST['acudiente']);
$tel_acudiente = trim($_POST['tel_acudiente']);
$foto = null;
if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
  $fname = 'foto_' . time() . '.' . $ext;
  $dest = __DIR__ . '/../../uploads/estudiantes/' . $fname;
  if(!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
  if(move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
    $foto = $fname;
  }
}
if($id > 0) {
  $sql = "UPDATE estudiantes SET nombre=?, apellido=?, documento=?, email=?, telefono=?, fecha_nacimiento=?, curso_id=?, direccion=?, acudiente=?, tel_acudiente=?";
  $params = [$nombre, $apellido, $documento, $email, $telefono, $fecha_nacimiento, $curso_id, $direccion, $acudiente, $tel_acudiente];
  if($foto) { $sql .= ', foto=?'; $params[] = $foto; }
  $sql .= ' WHERE id=?'; $params[] = $id;
  $pdo->prepare($sql)->execute($params);
  header('Location: index.php?flash=updated');
} else {
  $stmt = $pdo->prepare("INSERT INTO estudiantes (nombre, apellido, documento, email, telefono, fecha_nacimiento, curso_id, direccion, acudiente, tel_acudiente, foto) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
  $stmt->execute([$nombre, $apellido, $documento, $email, $telefono, $fecha_nacimiento, $curso_id, $direccion, $acudiente, $tel_acudiente, $foto]);
  header('Location: index.php?flash=created');
}
exit;
