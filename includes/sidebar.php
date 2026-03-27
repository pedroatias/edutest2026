<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<nav id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <h3><i class="fas fa-graduation-cap"></i> EduTest</h3>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['user_nombre']) ?></p>
  </div>
  <ul class="sidebar-nav">
    <li class="<?= ($current_page=='dashboard.php') ? 'active' : '' ?>">
      <a href="<?= $base_url ?>dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    </li>
    <li class="<?= ($current_dir=='estudiantes') ? 'active' : '' ?>">
      <a href="<?= $base_url ?>modules/estudiantes/index.php"><i class="fas fa-user-graduate"></i> Estudiantes</a>
    </li>
    <li class="<?= ($current_dir=='profesores') ? 'active' : '' ?>">
      <a href="<?= $base_url ?>modules/profesores/index.php"><i class="fas fa-chalkboard-teacher"></i> Profesores</a>
    </li>
    <li class="<?= ($current_dir=='cursos') ? 'active' : '' ?>">
      <a href="<?= $base_url ?>modules/cursos/index.php"><i class="fas fa-book"></i> Cursos</a>
    </li>
    <li class="<?= ($current_dir=='notas') ? 'active' : '' ?>">
      <a href="<?= $base_url ?>modules/notas/index.php"><i class="fas fa-clipboard-list"></i> Notas</a>
    </li>
    <li class="<?= ($current_dir=='asistencia') ? 'active' : '' ?>">
      <a href="<?= $base_url ?>modules/asistencia/index.php"><i class="fas fa-calendar-check"></i> Asistencia</a>
    </li>
    <?php if($_SESSION['user_rol'] == 'admin'): ?>
    <li class="<?= ($current_dir=='usuarios') ? 'active' : '' ?>">
      <a href="<?= $base_url ?>modules/usuarios/index.php"><i class="fas fa-users-cog"></i> Usuarios</a>
    </li>
    <?php endif; ?>
    <li>
      <a href="<?= $base_url ?>logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </li>
  </ul>
</nav>
<div id="content">
  <nav class="navbar navbar-expand-lg navbar-top">
    <button type="button" id="sidebarCollapse" class="btn btn-sidebar">
      <i class="fas fa-bars"></i>
    </button>
    <span class="ms-auto me-3"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user_nombre']) ?> (<?= ucfirst($_SESSION['user_rol']) ?>)</span>
  </nav>
