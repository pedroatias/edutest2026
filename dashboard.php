<?php
// dashboard.php - ubicado en la raiz del proyecto
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>
<div class="container-fluid py-4">
  <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
  <?php
    $total_estudiantes = $pdo->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
    $total_profesores  = $pdo->query("SELECT COUNT(*) FROM profesores")->fetchColumn();
    $total_cursos      = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
    $total_usuarios    = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
  ?>
  <div class="row">
    <div class="col-md-3 mb-3">
      <div class="card stat-card stat-blue">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="card-title">Estudiantes</h6>
              <h2 class="mb-0"><?= $total_estudiantes ?></h2>
            </div>
            <i class="fas fa-user-graduate fa-2x"></i>
          </div>
          <a href="modules/estudiantes/index.php" class="btn btn-sm btn-light mt-2">Ver todos</a>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card stat-green">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="card-title">Profesores</h6>
              <h2 class="mb-0"><?= $total_profesores ?></h2>
            </div>
            <i class="fas fa-chalkboard-teacher fa-2x"></i>
          </div>
          <a href="modules/profesores/index.php" class="btn btn-sm btn-light mt-2">Ver todos</a>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card stat-orange">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="card-title">Cursos</h6>
              <h2 class="mb-0"><?= $total_cursos ?></h2>
            </div>
            <i class="fas fa-book fa-2x"></i>
          </div>
          <a href="modules/cursos/index.php" class="btn btn-sm btn-light mt-2">Ver todos</a>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card stat-card stat-purple">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="card-title">Usuarios</h6>
              <h2 class="mb-0"><?= $total_usuarios ?></h2>
            </div>
            <i class="fas fa-users fa-2x"></i>
          </div>
          <a href="modules/usuarios/index.php" class="btn btn-sm btn-light mt-2">Ver todos</a>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><h5><i class="fas fa-clock"></i> Accesos rapidos</h5></div>
        <div class="card-body">
          <div class="list-group">
            <a href="modules/estudiantes/create.php" class="list-group-item list-group-item-action"><i class="fas fa-plus"></i> Nuevo Estudiante</a>
            <a href="modules/profesores/create.php" class="list-group-item list-group-item-action"><i class="fas fa-plus"></i> Nuevo Profesor</a>
            <a href="modules/cursos/create.php" class="list-group-item list-group-item-action"><i class="fas fa-plus"></i> Nuevo Curso</a>
            <a href="modules/notas/index.php" class="list-group-item list-group-item-action"><i class="fas fa-star"></i> Gestionar Notas</a>
            <a href="modules/asistencia/index.php" class="list-group-item list-group-item-action"><i class="fas fa-calendar-check"></i> Registrar Asistencia</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><h5><i class="fas fa-info-circle"></i> Informacion del Sistema</h5></div>
        <div class="card-body">
          <table class="table">
            <tr><th>Sistema:</th><td><?= defined('SITE_NAME') ? SITE_NAME : 'EduTest 2026' ?></td></tr>
            <tr><th>Ano Lectivo:</th><td><?= defined('ANO_LECTIVO') ? ANO_LECTIVO : date('Y') ?></td></tr>
            <tr><th>Usuario:</th><td><?= htmlspecialchars($_SESSION['user_nombre']) ?></td></tr>
            <tr><th>Rol:</th><td><?= htmlspecialchars($_SESSION['user_rol']) ?></td></tr>
            <tr><th>Fecha:</th><td><?= date('d/m/Y H:i') ?></td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
