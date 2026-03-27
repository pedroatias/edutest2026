<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>
<div class="container-fluid py-4">
  <h2 class="mb-4"><i class="fas fa-calendar-check"></i> Asistencia</h2>
  <?php
  $cursos = $pdo->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetchAll();
  $filtro_curso = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;
  $filtro_fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
  $estudiantes_curso = [];
  if($filtro_curso) {
    $stmt = $pdo->prepare("SELECT e.id, e.nombre, e.apellido FROM estudiantes e WHERE e.curso_id = ? ORDER BY e.apellido, e.nombre");
    $stmt->execute([$filtro_curso]);
    $estudiantes_curso = $stmt->fetchAll();
  }
  $asistencias_guardadas = [];
  if($filtro_curso && $filtro_fecha) {
    $stmt = $pdo->prepare("SELECT estudiante_id, estado FROM asistencia WHERE curso_id = ? AND fecha = ?");
    $stmt->execute([$filtro_curso, $filtro_fecha]);
    foreach($stmt->fetchAll() as $a) { $asistencias_guardadas[$a['estudiante_id']] = $a['estado']; }
  }
  ?>
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row g-2">
        <div class="col-md-4">
          <label class="form-label">Curso</label>
          <select name="curso_id" class="form-select" required>
            <option value="">Seleccione curso</option>
            <?php foreach($cursos as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $filtro_curso==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Fecha</label>
          <input type="date" name="fecha" class="form-control" value="<?= $filtro_fecha ?>" required>
        </div>
        <div class="col-auto align-self-end"><button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cargar</button></div>
      </form>
    </div>
  </div>
  <?php if($filtro_curso && !empty($estudiantes_curso)): ?>
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Registro de Asistencia - <?= $filtro_fecha ?></h5>
      <div>
        <button class="btn btn-sm btn-success" onclick="marcarTodos('presente')">Todos Presentes</button>
        <button class="btn btn-sm btn-danger" onclick="marcarTodos('ausente')">Todos Ausentes</button>
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="save.php">
        <input type="hidden" name="curso_id" value="<?= $filtro_curso ?>">
        <input type="hidden" name="fecha" value="<?= $filtro_fecha ?>">
        <table class="table">
          <thead class="table-dark"><tr><th>#</th><th>Estudiante</th><th>Presente</th><th>Ausente</th><th>Justificado</th><th>Observación</th></tr></thead>
          <tbody>
          <?php foreach($estudiantes_curso as $i => $e):
            $estado = $asistencias_guardadas[$e['id']] ?? 'presente';
          ?>
          <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($e['nombre'].' '.$e['apellido']) ?></td>
            <td class="text-center"><input type="radio" name="asistencia[<?= $e['id'] ?>][estado]" value="presente" <?= $estado=='presente'?'checked':'' ?> class="form-check-input radio-estado"></td>
            <td class="text-center"><input type="radio" name="asistencia[<?= $e['id'] ?>][estado]" value="ausente" <?= $estado=='ausente'?'checked':'' ?> class="form-check-input radio-estado"></td>
            <td class="text-center"><input type="radio" name="asistencia[<?= $e['id'] ?>][estado]" value="justificado" <?= $estado=='justificado'?'checked':'' ?> class="form-check-input radio-estado"></td>
            <td><input type="text" name="asistencia[<?= $e['id'] ?>][observacion]" class="form-control form-control-sm" value="" placeholder="Opcional"></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Asistencia</button>
      </form>
    </div>
  </div>
  <?php elseif($filtro_curso && empty($estudiantes_curso)): ?>
  <div class="alert alert-info">Este curso no tiene estudiantes asignados.</div>
  <?php endif; ?>
</div>
<script>
function marcarTodos(estado){
  document.querySelectorAll('input[type=radio][value='+estado+']').forEach(r=>r.checked=true);
}
</script>
<?php require_once '../../includes/footer.php'; ?>
