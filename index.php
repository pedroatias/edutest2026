<?php
session_start();
if(isset($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once 'config/db.php';
  $usuario = trim($_POST['usuario']);
  $password = trim($_POST['password']);
  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? AND activo = 1");
  $stmt->execute([$usuario]);
  $user = $stmt->fetch();
  if($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nombre'] = $user['nombre'];
    $_SESSION['user_rol'] = $user['rol'];
    header('Location: dashboard.php');
    exit;
  } else {
    $error = 'Usuario o contraseña incorrectos.';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduTest 2026 - Iniciar Sesión</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
<div class="login-container">
  <div class="login-card">
    <div class="login-logo">
      <i class="fas fa-graduation-cap"></i>
      <h2>EduTest 2026</h2>
      <p>Sistema de Gestión Escolar</p>
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
        <label class="form-label"><i class="fas fa-lock"></i> Contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
      </button>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
