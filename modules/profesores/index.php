<?php require_once '../../includes/header.php'; ?>
<?php require_once '../../includes/sidebar.php'; ?>
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chalkboard-teacher"></i> Profesores</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProfesor" onclick="limpiarForm()">
      <i class="fas fa-plus"></i> Nuevo Profesor
    </button>
  </div>
  <?php
  if(isset($_GET['flash'])) {
    $msg = ['created'=>'Profesor creado.','updated'=>'Profesor actualizado.','deleted'=>'Profesor eliminado.'][$_GET['flash']] ?? '';
    if($msg) echo '<div class="alert alert-success">'.$msg.'</div>';
  }
  $profesores = $pdo->query("SELECT * FROM profesores ORDER BY apellido, nombre")->fetchAll();
  ?>
  <div class="card">
    <div class="card-body">
      <div class="mb-3"><input type="text" id="buscador" class="form-control" placeholder="Buscar profesor..."></div>
      <table class="table table-hover" id="tablaProf">
        <thead class="table-dark"><tr><th>Nombre</th><th>Apellido</th><th>Documento</th><th>Email</th><th>Especialidad</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach($profesores as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['apellido']) ?></td>
          <td><?= htmlspecialchars($p['documento']) ?></td>
          <td><?= htmlspecialchars($p['email']) ?></td>
          <td><?= htmlspecialchars($p['especialidad']) ?></td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="editarProfesor(<?= htmlspecialchars(json_encode($p)) ?>)"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="eliminarProfesor(<?= $p['id'] ?>)"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="modalProfesor" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitulo">Nuevo Profesor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="save.php" enctype="multipart/form-data">
        <input type="hidden" name="id" id="prof_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Nombre *</label><input type="text" name="nombre" id="prof_nombre" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Apellido *</label><input type="text" name="apellido" id="prof_apellido" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Documento *</label><input type="text" name="documento" id="prof_documento" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" id="prof_email" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono" id="prof_telefono" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Especialidad</label><input type="text" name="especialidad" id="prof_especialidad" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Dirección</label><input type="text" name="direccion" id="prof_direccion" class="form-control"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Foto</label><input type="file" name="foto" class="form-control input-foto" accept="image/*"><img class="preview-foto mt-2" style="max-width:100px;display:none;"></div>
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
function limpiarForm(){document.getElementById('prof_id').value='';document.querySelector('#modalProfesor form').reset();document.getElementById('modalTitulo').textContent='Nuevo Profesor';}
function editarProfesor(p){
  document.getElementById('prof_id').value=p.id;
  ['nombre','apellido','documento','email','telefono','especialidad','direccion'].forEach(f=>{
    document.getElementById('prof_'+f).value=p[f]||'';
  });
  document.getElementById('modalTitulo').textContent='Editar Profesor';
  new bootstrap.Modal(document.getElementById('modalProfesor')).show();
}
function eliminarProfesor(id){
  Swal.fire({title:'¿Eliminar profesor?',icon:'warning',showCancelButton:true,confirmButtonText:'Sí',cancelButtonText:'No'}).then(r=>{
    if(r.isConfirmed) ajaxRequest('delete.php',{id},res=>{ if(res.success){Swal.fire('Eliminado','','success').then(()=>location.reload());}else Swal.fire('Error',res.message,'error'); });
  });
}
document.getElementById('buscador').addEventListener('keyup',function(){const q=this.value.toLowerCase();document.querySelectorAll('#tablaProf tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(q)?'':'none';});});
</script>
<?php require_once '../../includes/footer.php'; ?>
