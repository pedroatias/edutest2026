<?php
/* ================================================
   config/db.php - Conexion a la base de datos
   IMPORTANTE: Cambiar los valores de abajo
   con los datos de tu cPanel en Namecheap
   ================================================ */

define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario_db');   // <-- Cambiar
define('DB_PASS', 'tu_password_db');  // <-- Cambiar
define('DB_NAME', 'school_db');       // <-- Cambiar
define('SITE_NAME', 'EduTest 2026');
define('ANO_LECTIVO', 2026);

try {
  $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
  $pdo = new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]);
} catch (PDOException $e) {
  http_response_code(500);
  die('<div style="font-family:sans-serif;padding:30px;color:red;">'
    .'<h2>Error de conexion a la base de datos</h2>'
    .'<p>'.$e->getMessage().'</p>'
    .'<p>Revisa las credenciales en <b>config/db.php</b></p></div>');
}
