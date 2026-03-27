<?php
if(session_status() === PHP_SESSION_NONE) session_start();

// Calcular la ruta a config/db.php desde cualquier ubicacion
// __DIR__ aqui = ruta absoluta de la carpeta 'includes'
$config_path = dirname(__DIR__) . '/config/db.php';
if(!defined('DB_HOST')) require_once $config_path;

// Redirigir al login si no hay sesion activa
if(!isset($_SESSION['user_id'])) {
  // Calcular la URL relativa al index.php desde la ubicacion actual
  $script_path = str_replace('\\','/',$_SERVER['SCRIPT_FILENAME']);
  $doc_root    = str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']);
  $rel_path    = str_replace($doc_root, '', dirname($script_path));
  $parts       = array_filter(explode('/', $rel_path));
  $ups         = count($parts);
  $prefix      = str_repeat('../', $ups);
  header('Location: '.$prefix.'index.php');
  exit;
}

// base_url: detecta automaticamente la URL raiz del proyecto
$script  = str_replace('\\','/',$_SERVER['SCRIPT_FILENAME']);
$docroot = rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/');
$rel     = str_replace($docroot, '', dirname($script));
$rel_parts = array_values(array_filter(explode('/', $rel)));
// Buscar la carpeta del proyecto (donde esta index.php)
$project_root = $docroot;
foreach($rel_parts as $part) {
  $project_root .= '/'.$part;
  if(file_exists($project_root.'/index.php') && file_exists($project_root.'/config/db.php')) break;
}
$rel_url  = str_replace($docroot, '', $project_root);
$base_url = (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off'?'https':'http').'://'.$_SERVER['HTTP_HOST'].rtrim($rel_url,'/').'/'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= defined('SITE_NAME') ? SITE_NAME : 'EduTest 2026' ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
</head>
<body>
<div class="wrapper">
