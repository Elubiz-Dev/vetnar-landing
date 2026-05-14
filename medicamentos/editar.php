<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

$id  = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$med = $conn->query("SELECT * FROM MEDICAMENTO WHERE ID_Med=$id")->fetch_assoc();
if (!$med) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $dosis  = trim($_POST['dosis']  ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $stock  = (int)($_POST['stock']  ?? 0);
    $desc   = trim($_POST['descripcion'] ?? '');

    if (!$nombre) {
        $error = 'El nombre es obligatorio.';
    } else {
        $stmt = $conn->prepare("UPDATE MEDICAMENTO SET Nombre=?,Dosis=?,Precio=?,Stock=?,Descripcion=? WHERE ID_Med=?");
        $stmt->bind_param('ssdisi', $nombre, $dosis, $precio, $stock, $desc, $id);
        $stmt->execute(); $stmt->close();
        header('Location: index.php?msg=editado'); exit;
    }
    $med = compact('nombre','dosis','precio','stock') + ['Descripcion'=>$desc,'ID_Med'=>$id];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Medicamento — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>✏️ Editar Medicamento</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Medicamentos</a> / Editar #<?= $id ?></div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?><div class="alert alert-danger">❌ <?= $error ?></div><?php endif; ?>
    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Actualizar datos del medicamento</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group" style="grid-column:1/-1">
              <label>Nombre *</label>
              <input type="text" name="nombre" class="form-control" required
                     value="<?= htmlspecialchars($med['Nombre']) ?>">
            </div>
            <div class="form-group">
              <label>Dosis recomendada</label>
              <input type="text" name="dosis" class="form-control"
                     value="<?= htmlspecialchars($med['Dosis']) ?>">
            </div>
            <div class="form-group">
              <label>Precio ($)</label>
              <input type="number" name="precio" step="0.01" min="0" class="form-control"
                     value="<?= htmlspecialchars($med['Precio']) ?>">
            </div>
            <div class="form-group">
              <label>Stock (unidades)</label>
              <input type="number" name="stock" min="0" class="form-control"
                     value="<?= htmlspecialchars($med['Stock']) ?>">
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Descripción</label>
              <textarea name="descripcion" class="form-control"><?= htmlspecialchars($med['Descripcion']) ?></textarea>
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
