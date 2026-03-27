<?php
require_once '../../includes/header.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$curso_id = intval($_POST['curso_id']);
$fecha = $_POST['fecha'];
$asistencias = $_POST['asistencia'] ?? [];
$pdo->prepare("DELETE FROM asistencia WHERE curso_id = ? AND fecha = ?")->execute([$curso_id, $fecha]);
$stmt = $pdo->prepare("INSERT INTO asistencia (estudiante_id, curso_id, fecha, estado, observacion) VALUES (?,?,?,?,?)");
foreach($asistencias as $est_id => $data) {
  $estado = $data['estado'] ?? 'presente';
  $obs = $data['observacion'] ?? '';
  $stmt->execute([intval($est_id), $curso_id, $fecha, $estado, $obs]);
}
header('Location: index.php?curso_id='.$curso_id.'&fecha='.$fecha.'&flash=saved');
exit;
