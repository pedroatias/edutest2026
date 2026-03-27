<?php
require_once '../../includes/header.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$id = intval($_POST['id'] ?? 0);
$f = ['nombre','apellido','documento','email','telefono','especialidad','direccion'];
$vals = array_map(fn($k) => trim($_POST[$k] ?? ''), array_combine($f,$f));
$foto = null;
if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
  $fname = 'foto_' . time() . '.' . $ext;
  $dest = __DIR__ . '/../../uploads/profesores/' . $fname;
  if(!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
  if(move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) $foto = $fname;
}
if($id > 0) {
  $sql = "UPDATE profesores SET nombre=?, apellido=?, documento=?, email=?, telefono=?, especialidad=?, direccion=?";
  $params = array_values($vals);
  if($foto) { $sql .= ', foto=?'; $params[] = $foto; }
  $sql .= ' WHERE id=?'; $params[] = $id;
  $pdo->prepare($sql)->execute($params);
  header('Location: index.php?flash=updated');
} else {
  $pdo->prepare("INSERT INTO profesores (nombre,apellido,documento,email,telefono,especialidad,direccion,foto) VALUES (?,?,?,?,?,?,?,?)")
    ->execute([...array_values($vals), $foto]);
  header('Location: index.php?flash=created');
}
exit;
