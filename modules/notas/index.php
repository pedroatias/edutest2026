<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-list"></i> Notas</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNota" onclick="limpiarForm()">
      <i class="fas fa-plus"></i> Nueva Nota
    </button>
  </div>
  <?php
  $cursos = $pdo->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetchAll();
  $estudiantes = $pdo->query("SELECT id, nombre, apellido, curso_id FROM estudiantes ORDER BY apellido, nombre")->fetchAll();
  $filtro_curso = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;
  $filtro_periodo = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
  $sql = "SELECT n.*, e.nombre as est_nombre, e.apellido as est_apellido, c.nombre as curso_nombre FROM notas n JOIN estudiantes e ON n.estudiante_id = e.id JOIN cursos c ON n.curso_id = c.id WHERE 1=1";
  $params = [];
  if($filtro_curso) { $sql .= ' AND n.curso_id = ?'; $params[] = $filtro_curso; }
  if($filtro_periodo) { $sql .= ' AND n.periodo = ?'; $params[] = $filtro_periodo; }
  $sql .= ' ORDER BY e.apellido, e.nombre, n.materia';
  $stmt = $pdo->prepare($sql); $stmt->execute($params);
  $notas = $stmt->fetchAll();
  ?>
  <div class="card mb-3">
    <div class="card-body">
      <form method="GET" class="row g-2">
        <div class="col-md-4">
          <select name="curso_id" class="form-select">
            <option value="">Todos los cursos</option>
            <?php foreach($cursos as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $filtro_curso==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <input type="text" name="periodo" class="form-control" placeholder="Periodo (ej: 2024-1)" value="<?= htmlspecialchars($filtro_periodo) ?>">
        </div>
        <div class="col-auto"><button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filtrar</button></div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <table class="table table-hover">
        <thead class="table-dark"><tr><th>Estudiante</th><th>Curso</th><th>Materia</th><th>Nota</th><th>Periodo</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach($notas as $n): ?>
        <tr>
          <td><?= htmlspecialchars($n['est_nombre'].' '.$n['est_apellido']) ?></td>
          <td><?= htmlspecialchars($n['curso_nombre']) ?></td>
          <td><?= htmlspecialchars($n['materia']) ?></td>
          <td><span class="badge bg-<?= $n['nota'] >= 3 ? 'success' : 'danger' ?>"><?= number_format($n['nota'],1) ?></span></td>
          <td><?= htmlspecialchars($n['periodo']) ?></td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="editarNota(<?= htmlspecialchars(json_encode($n)) ?>)"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="eliminarNota(<?= $n['id'] ?>)"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($notas)): ?><tr><td colspan="6" class="text-center text-muted">No hay notas registradas.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="modalNota" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title" id="modalTitulo">Nueva Nota</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" action="save.php">
        <input type="hidden" name="id" id="nota_id">
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Curso *</label>
            <select name="curso_id" id="nota_curso_id" class="form-select" required onchange="filtrarEstudiantes()">
              <option value="">Seleccione curso</option>
              <?php foreach($cursos as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3"><label class="form-label">Estudiante *</label>
            <select name="estudiante_id" id="nota_estudiante_id" class="form-select" required>
              <option value="">Seleccione estudiante</option>
              <?php foreach($estudiantes as $e): ?>
              <option value="<?= $e['id'] ?>" data-curso="<?= $e['curso_id'] ?>"><?= htmlspecialchars($e['nombre'].' '.$e['apellido']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3"><label class="form-label">Materia *</label><input type="text" name="materia" id="nota_materia" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Nota (0-5) *</label><input type="number" name="nota" id="nota_valor" class="form-control" step="0.1" min="0" max="5" required></div>
          <div class="mb-3"><label class="form-label">Periodo *</label><input type="text" name="periodo" id="nota_periodo" class="form-control" placeholder="ej: 2024-1" required></div>
          <div class="mb-3"><label class="form-label">Observaciones</label><textarea name="observaciones" id="nota_observaciones" class="form-control" rows="2"></textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar</button></div>
      </form>
    </div>
  </div>
</div>
<script>
var todosEstudiantes = <?= json_encode($estudiantes) ?>;
function limpiarForm(){document.getElementById('nota_id').value='';document.querySelector('#modalNota form').reset();document.getElementById('modalTitulo').textContent='Nueva Nota';filtrarEstudiantes();}
function filtrarEstudiantes(){
  const cursoId = document.getElementById('nota_curso_id').value;
  const sel = document.getElementById('nota_estudiante_id');
  const curVal = sel.value;
  sel.innerHTML = '<option value="">Seleccione estudiante</option>';
  todosEstudiantes.forEach(e=>{
    if(!cursoId || e.curso_id == cursoId){
      const opt = document.createElement('option');
      opt.value = e.id; opt.textContent = e.nombre+' '+e.apellido;
      if(e.id == curVal) opt.selected = true;
      sel.appendChild(opt);
    }
  });
}
function editarNota(n){
  document.getElementById('nota_id').value=n.id;
  document.getElementById('nota_curso_id').value=n.curso_id;
  filtrarEstudiantes();
  document.getElementById('nota_estudiante_id').value=n.estudiante_id;
  document.getElementById('nota_materia').value=n.materia;
  document.getElementById('nota_valor').value=n.nota;
  document.getElementById('nota_periodo').value=n.periodo;
  document.getElementById('nota_observaciones').value=n.observaciones||'';
  document.getElementById('modalTitulo').textContent='Editar Nota';
  new bootstrap.Modal(document.getElementById('modalNota')).show();
}
function eliminarNota(id){
  Swal.fire({title:'¿Eliminar nota?',icon:'warning',showCancelButton:true,confirmButtonText:'Sí',cancelButtonText:'No'}).then(r=>{
    if(r.isConfirmed) ajaxRequest('delete.php',{id},res=>{ if(res.success){Swal.fire('Eliminado','','success').then(()=>location.reload());}else Swal.fire('Error',res.message,'error'); });
  });
}
</script>
<?php require_once '../../includes/footer.php'; ?>
