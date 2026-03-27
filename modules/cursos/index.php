<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book"></i> Cursos</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCurso" onclick="limpiarForm()">
      <i class="fas fa-plus"></i> Nuevo Curso
    </button>
  </div>
  <?php
  if(isset($_GET['flash'])) {
    $msg = ['created'=>'Curso creado.','updated'=>'Curso actualizado.','deleted'=>'Curso eliminado.'][$_GET['flash']] ?? '';
    if($msg) echo '<div class="alert alert-success">'.$msg.'</div>';
  }
  $stmt = $pdo->query("SELECT c.*, p.nombre as prof_nombre, p.apellido as prof_apellido, (SELECT COUNT(*) FROM estudiantes e WHERE e.curso_id=c.id) as total_est FROM cursos c LEFT JOIN profesores p ON c.profesor_id = p.id ORDER BY c.nombre");
  $cursos = $stmt->fetchAll();
  $profesores = $pdo->query("SELECT id, nombre, apellido FROM profesores ORDER BY apellido")->fetchAll();
  ?>
  <div class="card">
    <div class="card-body">
      <table class="table table-hover">
        <thead class="table-dark"><tr><th>Nombre</th><th>Grado</th><th>Sección</th><th>Profesor</th><th>Estudiantes</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach($cursos as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['nombre']) ?></td>
          <td><?= htmlspecialchars($c['grado']) ?></td>
          <td><?= htmlspecialchars($c['seccion']) ?></td>
          <td><?= $c['prof_nombre'] ? htmlspecialchars($c['prof_nombre'].' '.$c['prof_apellido']) : 'Sin asignar' ?></td>
          <td><span class="badge bg-primary"><?= $c['total_est'] ?></span></td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="editarCurso(<?= htmlspecialchars(json_encode($c)) ?>)"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="eliminarCurso(<?= $c['id'] ?>)"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="modalCurso" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title" id="modalTitulo">Nuevo Curso</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" action="save.php">
        <input type="hidden" name="id" id="cur_id">
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Nombre *</label><input type="text" name="nombre" id="cur_nombre" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Grado</label><input type="text" name="grado" id="cur_grado" class="form-control"></div>
          <div class="mb-3"><label class="form-label">Sección</label><input type="text" name="seccion" id="cur_seccion" class="form-control"></div>
          <div class="mb-3"><label class="form-label">Descripción</label><textarea name="descripcion" id="cur_descripcion" class="form-control" rows="3"></textarea></div>
          <div class="mb-3"><label class="form-label">Profesor</label>
            <select name="profesor_id" id="cur_profesor_id" class="form-select">
              <option value="">Sin asignar</option>
              <?php foreach($profesores as $p): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre'].' '.$p['apellido']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar</button></div>
      </form>
    </div>
  </div>
</div>
<script>
function limpiarForm(){document.getElementById('cur_id').value='';document.querySelector('#modalCurso form').reset();document.getElementById('modalTitulo').textContent='Nuevo Curso';}
function editarCurso(c){
  document.getElementById('cur_id').value=c.id;
  ['nombre','grado','seccion','descripcion'].forEach(f=>{document.getElementById('cur_'+f).value=c[f]||'';});
  document.getElementById('cur_profesor_id').value=c.profesor_id||'';
  document.getElementById('modalTitulo').textContent='Editar Curso';
  new bootstrap.Modal(document.getElementById('modalCurso')).show();
}
function eliminarCurso(id){
  Swal.fire({title:'¿Eliminar curso?',text:'Los estudiantes del curso quedarán sin curso asignado.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí',cancelButtonText:'No'}).then(r=>{
    if(r.isConfirmed) ajaxRequest('delete.php',{id},res=>{ if(res.success){Swal.fire('Eliminado','','success').then(()=>location.reload());}else Swal.fire('Error',res.message,'error'); });
  });
}
</script>
<?php require_once '../../includes/footer.php'; ?>
