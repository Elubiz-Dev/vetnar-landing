<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$vet = $conn->query("SELECT * FROM VETERINARIO WHERE ID_Vet=$id")->fetch_assoc();
if (!$vet) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = trim($_POST['nombre']       ?? '');
    $especialidad = trim($_POST['especialidad'] ?? '');
    $horario      = trim($_POST['horario']      ?? '');
    $telefono     = trim($_POST['telefono']     ?? '');
    $numLic       = trim($_POST['num_licencia'] ?? '');

    if (!$nombre) {
        $error = 'El nombre es obligatorio.';
    } else {
        $stmt = $conn->prepare("UPDATE VETERINARIO SET Nombre=?,Especialidad=?,Horario=?,Telefono=?,NumLicencia=? WHERE ID_Vet=?");
        $stmt->bind_param('sssssi', $nombre, $especialidad, $horario, $telefono, $numLic, $id);
        $stmt->execute(); $stmt->close();
        header('Location: index.php?msg=editado'); exit;
    }
    $vet = array_merge($vet, ['Nombre'=>$nombre,'Especialidad'=>$especialidad,'Horario'=>$horario,'Telefono'=>$telefono,'NumLicencia'=>$numLic]);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Veterinario — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>✏️ Editar Veterinario</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Veterinarios</a> / Editar #<?= $id ?></div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?><div class="alert alert-danger">❌ <?= $error ?></div><?php endif; ?>
    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Actualizar datos del veterinario</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group" style="grid-column:1/-1">
              <label>Nombre completo *</label>
              <input type="text" name="nombre" class="form-control" required
                     value="<?= htmlspecialchars($vet['Nombre']) ?>">
            </div>
            <div class="form-group">
              <label>Especialidad</label>
              <input type="text" name="especialidad" class="form-control"
                     value="<?= htmlspecialchars($vet['Especialidad']) ?>">
            </div>
            <div class="form-group">
              <label>Número de Licencia</label>
              <input type="text" name="num_licencia" class="form-control"
                     value="<?= htmlspecialchars($vet['NumLicencia']) ?>">
            </div>
            <div class="form-group">
              <label>Horario</label>
              <input type="text" name="horario" class="form-control"
                     value="<?= htmlspecialchars($vet['Horario']) ?>">
            </div>
            <div class="form-group">
              <label>Teléfono</label>
              <input type="text" name="telefono" class="form-control"
                     value="<?= htmlspecialchars($vet['Telefono']) ?>">
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
