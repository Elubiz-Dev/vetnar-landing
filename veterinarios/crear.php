<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre']      ?? '');
    $especialidad= trim($_POST['especialidad']?? '');
    $horario     = trim($_POST['horario']     ?? '');
    $telefono    = trim($_POST['telefono']    ?? '');
    $numLic      = trim($_POST['num_licencia']?? '');

    if (!$nombre) {
        $error = 'El nombre es obligatorio.';
    } else {
        $stmt = $conn->prepare("INSERT INTO VETERINARIO (Nombre,Especialidad,Horario,Telefono,NumLicencia) VALUES (?,?,?,?,?)");
        $stmt->bind_param('sssss', $nombre, $especialidad, $horario, $telefono, $numLic);
        if (!$stmt->execute()) {
            $error = 'Error: el número de licencia ya existe.';
        } else {
            header('Location: index.php?msg=creado'); exit;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nuevo Veterinario — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>🩺 Nuevo Veterinario</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Veterinarios</a> / Crear</div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?><div class="alert alert-danger">❌ <?= $error ?></div><?php endif; ?>
    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Formulario de Registro</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group" style="grid-column:1/-1">
              <label>Nombre completo *</label>
              <input type="text" name="nombre" class="form-control"
                     placeholder="Dra. Sofía Herrera" required
                     value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Especialidad</label>
              <input type="text" name="especialidad" class="form-control"
                     placeholder="Medicina General"
                     value="<?= htmlspecialchars($_POST['especialidad'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Número de Licencia</label>
              <input type="text" name="num_licencia" class="form-control"
                     placeholder="VET-001"
                     value="<?= htmlspecialchars($_POST['num_licencia'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Horario</label>
              <input type="text" name="horario" class="form-control"
                     placeholder="Lun-Vie 8am-5pm"
                     value="<?= htmlspecialchars($_POST['horario'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Teléfono</label>
              <input type="text" name="telefono" class="form-control"
                     placeholder="3112223344"
                     value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar Veterinario</button>
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
