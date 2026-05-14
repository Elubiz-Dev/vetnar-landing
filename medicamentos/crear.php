<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre']  ?? '');
    $dosis   = trim($_POST['dosis']   ?? '');
    $precio  = (float)($_POST['precio'] ?? 0);
    $stock   = (int)($_POST['stock']  ?? 0);
    $desc    = trim($_POST['descripcion'] ?? '');

    if (!$nombre) {
        $error = 'El nombre es obligatorio.';
    } else {
        $stmt = $conn->prepare("INSERT INTO MEDICAMENTO (Nombre,Dosis,Precio,Stock,Descripcion) VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssdis', $nombre, $dosis, $precio, $stock, $desc);
        $stmt->execute(); $stmt->close();
        header('Location: index.php?msg=creado'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nuevo Medicamento — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>💊 Nuevo Medicamento</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Medicamentos</a> / Crear</div>
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
              <label>Nombre del Medicamento *</label>
              <input type="text" name="nombre" class="form-control"
                     placeholder="Amoxicilina 500mg" required
                     value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Dosis recomendada</label>
              <input type="text" name="dosis" class="form-control"
                     placeholder="1 cápsula cada 8h"
                     value="<?= htmlspecialchars($_POST['dosis'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Precio ($)</label>
              <input type="number" name="precio" class="form-control"
                     placeholder="25000" step="0.01" min="0"
                     value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Stock (unidades)</label>
              <input type="number" name="stock" class="form-control"
                     placeholder="50" min="0"
                     value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>">
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Descripción</label>
              <textarea name="descripcion" class="form-control"
                        placeholder="Antibiótico de amplio espectro..."><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar Medicamento</button>
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
