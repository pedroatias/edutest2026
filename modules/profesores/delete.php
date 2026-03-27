<?php
require_once '../../config/db.php';
session_start();
if(!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['success'=>false,'message'=>'No autorizado']); exit; }
header('Content-Type: application/json');
$id = intval($_POST['id'] ?? 0);
if($id <= 0) { echo json_encode(['success'=>false,'message'=>'ID inválido']); exit; }
try {
  $pdo->prepare("DELETE FROM profesores WHERE id = ?")->execute([$id]);
  echo json_encode(['success'=>true]);
} catch(Exception $e) {
  echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
