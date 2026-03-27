<?php
session_start();
require_once __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit;
}
$base_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/edutest2026/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduTest 2026</title>
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/all.min.css">
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/sweetalert2.min.css">
</head>
<body>
<div class="wrapper">
