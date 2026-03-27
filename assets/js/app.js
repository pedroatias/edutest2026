/* ==============================================
   assets/js/app.js - Scripts globales
   ============================================== */

$(document).ready(function() {

  /* ---- Toggle del sidebar ---- */
  $('#sidebarToggle').on('click', function() {
    $('.sidebar').toggleClass('open collapsed');
    $('.main-content').toggleClass('expanded');
  });

  /* ---- Cerrar sidebar en mobile al hacer click fuera ---- */
  $(document).on('click', function(e) {
    if ($(window).width() < 768) {
      if (!$(e.target).closest('.sidebar, #sidebarToggle').length) {
        $('.sidebar').removeClass('open');
      }
    }
  });

  /* ---- Marcar menu item activo segun URL ---- */
  var currentUrl = window.location.pathname;
  $('.nav-menu a').each(function() {
    if ($(this).attr('href') && currentUrl.includes($(this).attr('href'))) {
      $(this).addClass('active');
    }
  });

  /* ---- Inicializar DataTables en todas las tablas .data-table ---- */
  if ($.fn.DataTable) {
    $('.data-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
      },
      responsive: true,
      pageLength: 15
    });
  }

  /* ---- Confirmacion de eliminacion ---- */
  $(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    var url = $(this).attr('href') || $(this).data('url');
    Swal.fire({
      title: 'Confirmar eliminacion',
      text: 'Esta accion no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Si, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(function(result) {
      if (result.isConfirmed) window.location.href = url;
    });
  });

  /* ---- Mostrar alertas flash con SweetAlert ---- */
  if (typeof flashMsg !== 'undefined' && flashMsg.text) {
    Swal.fire({
      icon: flashMsg.type || 'success',
      title: flashMsg.title || 'Exito',
      text: flashMsg.text,
      timer: 3000,
      showConfirmButton: false
    });
  }

  /* ---- Previsualizar imagen antes de subir ---- */
  $(document).on('change', '.input-foto', function() {
    var file = this.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('.preview-foto').attr('src', e.target.result).show();
      };
      reader.readAsDataURL(file);
    }
  });

});

/* ---- Funcion global para peticiones AJAX ---- */
function ajaxRequest(url, data, callback) {
  $.ajax({
    url: url,
    method: 'POST',
    data: data,
    dataType: 'json',
    success: function(resp) {
      if (typeof callback === 'function') callback(resp);
    },
    error: function() {
      Swal.fire('Error', 'Error de comunicacion con el servidor', 'error');
    }
  });
}
