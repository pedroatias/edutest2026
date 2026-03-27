<?php
if(session_status() === PHP_SESSION_NONE) session_start();

// Cargar config usando ruta absoluta
$config_path = dirname(__FILE__) . '/config/db.php';
if(!defined('DB_HOST')) require_once $config_path;

// Si ya hay sesion activa, ir al dashboard
if(isset($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario  = trim($_POST['usuario']);
  $password = trim($_POST['password']);
  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? AND activo = 1");
  $stmt->execute([$usuario]);
  $user = $stmt->fetch();
  if($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id']     = $user['id'];
    $_SESSION['user_nombre'] = $user['nombre'];
    $_SESSION['user_rol']    = $user['rol'];
    header('Location: dashboard.php');
    exit;
  } else {
    $error = 'Usuario o contrasena incorrectos.';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= defined('SITE_NAME') ? SITE_NAME : 'EduTest 2026' ?> - Iniciar Sesion</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
<div class="login-container">
  <div class="login-card">
    <div class="login-logo">
      <i class="fas fa-graduation-cap"></i>
      <h2><?= defined('SITE_NAME') ? SITE_NAME : 'EduTest 2026' ?></h2>
      <p>Sistema de Gestion Escolar</p>
    </div>
    <?php if($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label"><i class="fas fa-user"></i> Usuario</label>
        <input type="text" name="usuario" class="form-control" placeholder="Ingrese su usuario" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><i class="fas fa-lock"></i> Contrasena</label>
        <input type="password" name="password" class="form-control" placeholder="Ingrese su contrasena" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-sign-in-alt"></i> Iniciar Sesion
      </button>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
