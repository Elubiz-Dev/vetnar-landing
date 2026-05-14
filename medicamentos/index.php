<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM MEDICAMENTO WHERE ID_Med=$id");
    header('Location: index.php?msg=eliminado'); exit;
}

$msg  = $_GET['msg'] ?? '';
$meds = $conn->query("SELECT * FROM MEDICAMENTO ORDER BY Nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Medicamentos — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div><h1>💊 Medicamentos</h1><div class="breadcrumb">Inventario de medicamentos</div></div>
    <a href="crear.php" class="btn btn-primary">+ Nuevo Medicamento</a>
  </div>
  <div class="content">
    <?php if($msg==='creado'):?><div class="alert alert-success">✅ Medicamento registrado.</div>
    <?php elseif($msg==='editado'):?><div class="alert alert-success">✅ Medicamento actualizado.</div>
    <?php elseif($msg==='eliminado'):?><div class="alert alert-danger">🗑️ Medicamento eliminado.</div>
    <?php endif;?>

    <div class="card">
      <div class="card-header">
        <h3>Inventario de Medicamentos</h3>
        <span style="font-size:.8rem;color:var(--muted);"><?= $meds->num_rows ?> registros</span>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>#</th><th>Nombre</th><th>Dosis</th><th>Precio</th>
              <th>Stock</th><th>Descripción</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($m = $meds->fetch_assoc()): ?>
            <tr>
              <td><?= $m['ID_Med'] ?></td>
              <td><strong><?= htmlspecialchars($m['Nombre']) ?></strong></td>
              <td><?= htmlspecialchars($m['Dosis']) ?></td>
              <td>$<?= number_format($m['Precio'], 0, ',', '.') ?></td>
              <td>
                <?php
                $color = $m['Stock'] <= 5 ? 'badge-red' : ($m['Stock'] <= 15 ? 'badge-amber' : 'badge-green');
                // badge-red no existe, usemos danger style inline
                $style = $m['Stock'] <= 5 ? 'background:#fde4e4;color:#7a1515' :
                         ($m['Stock'] <= 15 ? 'background:#fdeecb;color:#7a5a00' : 'background:#d4f4e7;color:#0a5c3a');
                ?>
                <span class="badge" style="<?= $style ?>"><?= $m['Stock'] ?> uds.</span>
              </td>
              <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                <?= htmlspecialchars($m['Descripcion']) ?>
              </td>
              <td>
                <div class="btn-group">
                  <a href="editar.php?id=<?= $m['ID_Med'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                  <a href="index.php?eliminar=<?= $m['ID_Med'] ?>" class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar este medicamento?')">🗑️ Eliminar</a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php $conn->close(); ?>
