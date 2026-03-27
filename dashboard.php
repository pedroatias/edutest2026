<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="container-fluid py-4">
  <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
  <?php
  $total_estudiantes = $pdo->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
  $total_profesores = $pdo->query("SELECT COUNT(*) FROM profesores")->fetchColumn();
  $total_cursos = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
  $total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
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
      <div class="card stat-card stat-red">
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
        <div class="card-header"><h5><i class="fas fa-user-graduate"></i> Últimos Estudiantes</h5></div>
        <div class="card-body">
          <table class="table table-sm">
            <thead><tr><th>Nombre</th><th>Documento</th><th>Curso</th></tr></thead>
            <tbody>
            <?php
            $stmt = $pdo->query("SELECT e.nombre, e.apellido, e.documento, c.nombre as curso FROM estudiantes e LEFT JOIN cursos c ON e.curso_id = c.id ORDER BY e.id DESC LIMIT 5");
            while($row = $stmt->fetch()): ?>
            <tr><td><?= htmlspecialchars($row['nombre'].' '.$row['apellido']) ?></td><td><?= htmlspecialchars($row['documento']) ?></td><td><?= htmlspecialchars($row['curso'] ?? 'N/A') ?></td></tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><h5><i class="fas fa-calendar-check"></i> Asistencia Reciente</h5></div>
        <div class="card-body">
          <table class="table table-sm">
            <thead><tr><th>Fecha</th><th>Estudiante</th><th>Estado</th></tr></thead>
            <tbody>
            <?php
            $stmt = $pdo->query("SELECT a.fecha, a.estado, e.nombre, e.apellido FROM asistencia a JOIN estudiantes e ON a.estudiante_id = e.id ORDER BY a.fecha DESC LIMIT 5");
            while($row = $stmt->fetch()): ?>
            <tr><td><?= $row['fecha'] ?></td><td><?= htmlspecialchars($row['nombre'].' '.$row['apellido']) ?></td><td><span class="badge bg-<?= $row['estado']=='presente' ? 'success' : ($row['estado']=='ausente' ? 'danger' : 'warning') ?>"><?= ucfirst($row['estado']) ?></span></td></tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
