<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM VETERINARIO WHERE ID_Vet=$id");
    header('Location: index.php?msg=eliminado'); exit;
}

$msg = $_GET['msg'] ?? '';
$vets = $conn->query("
    SELECT V.*, COUNT(C.ID_Cita) AS total_citas
    FROM VETERINARIO V
    LEFT JOIN CITA C ON V.ID_Vet = C.ID_Vet
    GROUP BY V.ID_Vet ORDER BY V.Nombre
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Veterinarios — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>🩺 Veterinarios</h1>
      <div class="breadcrumb">Gestión de veterinarios</div>
    </div>
    <a href="crear.php" class="btn btn-primary">+ Nuevo Veterinario</a>
  </div>
  <div class="content">
    <?php if($msg==='creado'):?><div class="alert alert-success">✅ Veterinario registrado.</div>
    <?php elseif($msg==='editado'):?><div class="alert alert-success">✅ Veterinario actualizado.</div>
    <?php elseif($msg==='eliminado'):?><div class="alert alert-danger">🗑️ Veterinario eliminado.</div>
    <?php endif;?>

    <div class="card">
      <div class="card-header">
        <h3>Lista de Veterinarios</h3>
        <span style="font-size:.8rem;color:var(--muted);"><?= $vets->num_rows ?> registros</span>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>#</th><th>Nombre</th><th>Especialidad</th><th>Horario</th>
              <th>Teléfono</th><th>N° Licencia</th><th>Citas</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($v = $vets->fetch_assoc()): ?>
            <tr>
              <td><?= $v['ID_Vet'] ?></td>
              <td><strong><?= htmlspecialchars($v['Nombre']) ?></strong></td>
              <td><span class="badge badge-blue"><?= htmlspecialchars($v['Especialidad']) ?></span></td>
              <td><?= htmlspecialchars($v['Horario']) ?></td>
              <td><?= htmlspecialchars($v['Telefono']) ?></td>
              <td><?= htmlspecialchars($v['NumLicencia']) ?></td>
              <td><span class="badge badge-purple">📅 <?= $v['total_citas'] ?></span></td>
              <td>
                <div class="btn-group">
                  <a href="editar.php?id=<?= $v['ID_Vet'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                  <a href="index.php?eliminar=<?= $v['ID_Vet'] ?>" class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar este veterinario?')">🗑️ Eliminar</a>
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
