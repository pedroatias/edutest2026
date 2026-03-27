<?php
require_once '../../includes/header.php';
if($_SESSION['user_rol'] !== 'admin') { header('Location: ../../dashboard.php'); exit; }
?>
<?php require_once '../../includes/sidebar.php'; ?>
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users-cog"></i> Usuarios</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="limpiarForm()">
      <i class="fas fa-plus"></i> Nuevo Usuario
    </button>
  </div>
  <?php
  if(isset($_GET['flash'])) {
    $msgs = ['created'=>'Usuario creado.','updated'=>'Usuario actualizado.'];
    $msg = $msgs[$_GET['flash']] ?? '';
    if($msg) echo '<div class="alert alert-success">'.$msg.'</div>';
  }
  $usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nombre")->fetchAll();
  ?>
  <div class="card">
    <div class="card-body">
      <table class="table table-hover">
        <thead class="table-dark"><tr><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach($usuarios as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['nombre']) ?></td>
          <td><?= htmlspecialchars($u['usuario']) ?></td>
          <td><span class="badge bg-<?= $u['rol']=='admin'?'danger':'primary' ?>"><?= ucfirst($u['rol']) ?></span></td>
          <td><span class="badge bg-<?= $u['activo']?'success':'secondary' ?>"><?= $u['activo']?'Activo':'Inactivo' ?></span></td>
          <td>
            <?php if($u['id'] != $_SESSION['user_id']): ?>
            <button class="btn btn-sm btn-warning" onclick="editarUsuario(<?= htmlspecialchars(json_encode($u)) ?>)"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(<?= $u['id'] ?>)"><i class="fas fa-trash"></i></button>
            <?php else: ?>
            <span class="text-muted">(actual)</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="modalUsuario" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title" id="modalTitulo">Nuevo Usuario</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST" action="save.php">
        <input type="hidden" name="id" id="usr_id">
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Nombre *</label><input type="text" name="nombre" id="usr_nombre" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Usuario *</label><input type="text" name="usuario" id="usr_usuario" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Contraseña <span id="pass_hint">(obligatoria)</span></label><input type="password" name="password" id="usr_password" class="form-control"></div>
          <div class="mb-3"><label class="form-label">Rol *</label>
            <select name="rol" id="usr_rol" class="form-select" required>
              <option value="admin">Admin</option>
              <option value="secretaria">Secretaría</option>
              <option value="profesor">Profesor</option>
            </select>
          </div>
          <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" id="usr_email" class="form-control"></div>
          <div class="mb-3"><div class="form-check"><input class="form-check-input" type="checkbox" name="activo" id="usr_activo" value="1" checked><label class="form-check-label" for="usr_activo">Activo</label></div></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar</button></div>
      </form>
    </div>
  </div>
</div>
<script>
function limpiarForm(){document.getElementById('usr_id').value='';document.querySelector('#modalUsuario form').reset();document.getElementById('modalTitulo').textContent='Nuevo Usuario';document.getElementById('pass_hint').textContent='(obligatoria)';}
function editarUsuario(u){
  document.getElementById('usr_id').value=u.id;
  document.getElementById('usr_nombre').value=u.nombre;
  document.getElementById('usr_usuario').value=u.usuario;
  document.getElementById('usr_password').value='';
  document.getElementById('usr_rol').value=u.rol;
  document.getElementById('usr_email').value=u.email||'';
  document.getElementById('usr_activo').checked=u.activo==1;
  document.getElementById('modalTitulo').textContent='Editar Usuario';
  document.getElementById('pass_hint').textContent='(dejar vacío para no cambiar)';
  new bootstrap.Modal(document.getElementById('modalUsuario')).show();
}
function eliminarUsuario(id){
  Swal.fire({title:'¿Eliminar usuario?',icon:'warning',showCancelButton:true,confirmButtonText:'Sí',cancelButtonText:'No'}).then(r=>{
    if(r.isConfirmed) ajaxRequest('delete.php',{id},res=>{ if(res.success){Swal.fire('Eliminado','','success').then(()=>location.reload());}else Swal.fire('Error',res.message,'error'); });
  });
}
</script>
<?php require_once '../../includes/footer.php'; ?>
