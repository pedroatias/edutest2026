<?php
/* ============================================
   config/db.php - Conexion a la base de datos
   IMPORTANTE: Cambiar DB_USER y DB_PASS
   con los datos de tu cPanel
   ============================================ */

define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario_db');   // <-- Cambiar
define('DB_PASS', 'tu_password_db');  // <-- Cambiar
define('DB_NAME', 'school_db');       // <-- Cambiar si usas otro nombre
define('SITE_NAME', 'Instituto Docente Gotita de Gente');
define('ANO_LECTIVO', 2026);

/** Retorna conexion PDO (singleton) */
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:30px;color:red;">'
                .'<h2>Error de conexion</h2><p>'.$e->getMessage().'</p>'
                .'<p>Revisa las credenciales en <b>config/db.php</b></p></div>');
        }
    }
    return $pdo;
}

function sanitize($v) { return htmlspecialchars(strip_tags(trim($v)),ENT_QUOTES,'UTF-8'); }
function redirect($url) { header("Location: $url"); exit; }
function isLoggedIn() { return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']); }
function requireLogin($r='index.php') { if(!isLoggedIn()) redirect($r); }
function isParentLoggedIn() { return isset($_SESSION['padre_id']) && !empty($_SESSION['padre_id']); }
function requireParentLogin() { if(!isParentLoggedIn()) redirect('../academico_padres.php'); }
function formatMoney($v) { return '$ '.number_format($v,0,',','.'); }
function getDesempeno($nota) {
    if($nota>=4.6) return ['label'=>'Superior','color'=>'success'];
    if($nota>=4.0) return ['label'=>'Alto',    'color'=>'info'];
    if($nota>=3.0) return ['label'=>'Basico',  'color'=>'warning'];
    return               ['label'=>'Bajo',     'color'=>'danger'];
}
function getInstitucion() { return getDB()->query("SELECT * FROM institucion LIMIT 1")->fetch(); }
