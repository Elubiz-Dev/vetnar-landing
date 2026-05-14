<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']    ?? '');
    $apellido  = trim($_POST['apellido']  ?? '');
    $telefono  = trim($_POST['telefono']  ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $email     = trim($_POST['email']     ?? '');

    if (!$nombre || !$apellido) {
        $error = 'Nombre y apellido son obligatorios.';
    } else {
        $stmt = $conn->prepare("INSERT INTO DUENO (Nombre,Apellido,Telefono,Direccion,Email) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss', $nombre, $apellido, $telefono, $direccion, $email);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?msg=creado');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Nuevo Dueño — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>👤 Nuevo Dueño</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Dueños</a> / Crear</div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?>
      <div class="alert alert-danger">❌ <?= $error ?></div>
    <?php endif; ?>

    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Formulario de Registro</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre *</label>
              <input type="text" name="nombre" class="form-control"
                     placeholder="Carlos" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Apellido *</label>
              <input type="text" name="apellido" class="form-control"
                     placeholder="Ramírez" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Teléfono</label>
              <input type="text" name="telefono" class="form-control"
                     placeholder="3101234567" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control"
                     placeholder="correo@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Dirección</label>
              <input type="text" name="direccion" class="form-control"
                     placeholder="Calle 15 #23-45, Ibagué" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar Dueño</button>
            <a href="index.php" class="btn btn-outline">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php $conn->close(); ?>
