<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-graduate"></i> Estudiantes</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEstudiante" onclick="limpiarForm()">
      <i class="fas fa-plus"></i> Nuevo Estudiante
    </button>
  </div>
  <?php
  if(isset($_GET['flash'])) {
    $msg = $_GET['flash'] === 'created' ? 'Estudiante creado exitosamente.' : ($_GET['flash'] === 'updated' ? 'Estudiante actualizado.' : 'Estudiante eliminado.');
    echo '<div class="alert alert-success">'.$msg.'</div>';
  }
  $stmt = $pdo->query("SELECT e.*, c.nombre as curso_nombre FROM estudiantes e LEFT JOIN cursos c ON e.curso_id = c.id ORDER BY e.apellido, e.nombre");
  $estudiantes = $stmt->fetchAll();
  $cursos = $pdo->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetchAll();
  ?>
  <div class="card">
    <div class="card-body">
      <div class="mb-3"><input type="text" id="buscador" class="form-control" placeholder="Buscar estudiante..."></div>
      <table class="table table-hover" id="tablaEstudiantes">
        <thead class="table-dark">
          <tr><th>Nombre</th><th>Apellido</th><th>Documento</th><th>Email</th><th>Curso</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach($estudiantes as $e): ?>
        <tr>
          <td><?= htmlspecialchars($e['nombre']) ?></td>
          <td><?= htmlspecialchars($e['apellido']) ?></td>
          <td><?= htmlspecialchars($e['documento']) ?></td>
          <td><?= htmlspecialchars($e['email']) ?></td>
          <td><?= htmlspecialchars($e['curso_nombre'] ?? 'N/A') ?></td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="editarEstudiante(<?= htmlspecialchars(json_encode($e)) ?>)">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="eliminarEstudiante(<?= $e['id'] ?>)">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalEstudiante" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitulo">Nuevo Estudiante</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEstudiante" method="POST" action="save.php" enctype="multipart/form-data">
        <input type="hidden" name="id" id="est_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre *</label>
              <input type="text" name="nombre" id="est_nombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Apellido *</label>
              <input type="text" name="apellido" id="est_apellido" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Documento *</label>
              <input type="text" name="documento" id="est_documento" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="est_email" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Teléfono</label>
              <input type="text" name="telefono" id="est_telefono" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Fecha Nacimiento</label>
              <input type="date" name="fecha_nacimiento" id="est_fecha_nacimiento" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Curso</label>
              <select name="curso_id" id="est_curso_id" class="form-select">
                <option value="">Sin curso</option>
                <?php foreach($cursos as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Dirección</label>
              <input type="text" name="direccion" id="est_direccion" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Acudiente</label>
              <input type="text" name="acudiente" id="est_acudiente" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Tel. Acudiente</label>
              <input type="text" name="tel_acudiente" id="est_tel_acudiente" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Foto</label>
              <input type="file" name="foto" class="form-control input-foto" accept="image/*">
              <img class="preview-foto mt-2" style="max-width:100px;display:none;">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
function limpiarForm() {
  document.getElementById('est_id').value = '';
  document.getElementById('formEstudiante').reset();
  document.getElementById('modalTitulo').textContent = 'Nuevo Estudiante';
}
function editarEstudiante(e) {
  document.getElementById('est_id').value = e.id;
  document.getElementById('est_nombre').value = e.nombre;
  document.getElementById('est_apellido').value = e.apellido;
  document.getElementById('est_documento').value = e.documento;
  document.getElementById('est_email').value = e.email || '';
  document.getElementById('est_telefono').value = e.telefono || '';
  document.getElementById('est_fecha_nacimiento').value = e.fecha_nacimiento || '';
  document.getElementById('est_curso_id').value = e.curso_id || '';
  document.getElementById('est_direccion').value = e.direccion || '';
  document.getElementById('est_acudiente').value = e.acudiente || '';
  document.getElementById('est_tel_acudiente').value = e.tel_acudiente || '';
  document.getElementById('modalTitulo').textContent = 'Editar Estudiante';
  new bootstrap.Modal(document.getElementById('modalEstudiante')).show();
}
function eliminarEstudiante(id) {
  Swal.fire({title:'¿Eliminar estudiante?',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, eliminar',cancelButtonText:'Cancelar'}).then(r=>{
    if(r.isConfirmed) { ajaxRequest('delete.php',{id:id},function(res){if(res.success){Swal.fire('Eliminado','','success').then(()=>location.reload());}else{Swal.fire('Error',res.message,'error');}});}
  });
}
document.getElementById('buscador').addEventListener('keyup', function(){
  const q = this.value.toLowerCase();
  document.querySelectorAll('#tablaEstudiantes tbody tr').forEach(r=>{
    r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});
</script>
<?php require_once '../../includes/footer.php'; ?>
