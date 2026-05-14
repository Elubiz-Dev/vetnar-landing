<?php
// ============================================================
//  Configuración de conexión a la base de datos
//  Cambia los valores según tu instalación de XAMPP
// ============================================================

define('DB_HOST',   'localhost');
define('DB_USER',   'root');       // Usuario XAMPP por defecto
define('DB_PASS',   '');           // Contraseña XAMPP por defecto (vacía)
define('DB_NAME',   'veterinaria_db');
define('DB_CHARSET','utf8mb4');

function getConexion(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die('<div style="font-family:sans-serif;padding:2rem;color:#c0392b;">
              <h2>❌ Error de conexión</h2>
              <p>' . $conn->connect_error . '</p>
              <p>Revisa que XAMPP esté corriendo y la base de datos creada.</p>
             </div>');
    }
    $conn->set_charset(DB_CHARSET);
    return $conn;
}
