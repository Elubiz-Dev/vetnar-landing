<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$dueno = $conn->query("SELECT * FROM DUENO WHERE ID_Dueno=$id")->fetch_assoc();
if (!$dueno) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']    ?? '');
    $apellido  = trim($_POST['apellido']  ?? '');
    $telefono  = trim($_POST['telefono']  ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $email     = trim($_POST['email']     ?? '');

    if (!$nombre || !$apellido) {
        $error = 'Nombre y apellido son obligatorios.';
    } else {
        $stmt = $conn->prepare("UPDATE DUENO SET Nombre=?,Apellido=?,Telefono=?,Direccion=?,Email=? WHERE ID_Dueno=?");
        $stmt->bind_param('sssssi', $nombre, $apellido, $telefono, $direccion, $email, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?msg=editado');
        exit;
    }
    // Rellenar con los datos enviados si hay error
    $dueno = array_merge($dueno, $_POST);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Editar Dueño — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>✏️ Editar Dueño</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Dueños</a> / Editar #<?= $id ?></div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?>
      <div class="alert alert-danger">❌ <?= $error ?></div>
    <?php endif; ?>

    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Actualizar datos del dueño</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre *</label>
              <input type="text" name="nombre" class="form-control" required
                     value="<?= htmlspecialchars($dueno['Nombre']) ?>">
            </div>
            <div class="form-group">
              <label>Apellido *</label>
              <input type="text" name="apellido" class="form-control" required
                     value="<?= htmlspecialchars($dueno['Apellido']) ?>">
            </div>
            <div class="form-group">
              <label>Teléfono</label>
              <input type="text" name="telefono" class="form-control"
                     value="<?= htmlspecialchars($dueno['Telefono']) ?>">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control"
                     value="<?= htmlspecialchars($dueno['Email']) ?>">
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Dirección</label>
              <input type="text" name="direccion" class="form-control"
                     value="<?= htmlspecialchars($dueno['Direccion']) ?>">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-warning">💾 Actualizar</button>
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
