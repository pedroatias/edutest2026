<?php
require_once '../../includes/header.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$id = intval($_POST['id'] ?? 0);
$estudiante_id = intval($_POST['estudiante_id']);
$curso_id = intval($_POST['curso_id']);
$materia = trim($_POST['materia']);
$nota = floatval($_POST['nota']);
$periodo = trim($_POST['periodo']);
$observaciones = trim($_POST['observaciones']);
if($id > 0) {
  $pdo->prepare("UPDATE notas SET estudiante_id=?, curso_id=?, materia=?, nota=?, periodo=?, observaciones=? WHERE id=?")
    ->execute([$estudiante_id, $curso_id, $materia, $nota, $periodo, $observaciones, $id]);
} else {
  $pdo->prepare("INSERT INTO notas (estudiante_id, curso_id, materia, nota, periodo, observaciones) VALUES (?,?,?,?,?,?)")
    ->execute([$estudiante_id, $curso_id, $materia, $nota, $periodo, $observaciones]);
}
header('Location: index.php?flash=saved');
exit;
