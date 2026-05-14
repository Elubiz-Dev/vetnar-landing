<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM CITA WHERE ID_Cita=$id");
    header('Location: index.php?msg=eliminado'); exit;
}

$msg = $_GET['msg'] ?? '';

// JOIN con 4 tablas
$citas = $conn->query("
    SELECT C.ID_Cita, C.Fecha, C.Motivo,
           M.Nombre  AS Mascota,  M.Especie,
           CONCAT(D.Nombre,' ',D.Apellido) AS Dueno,
           V.Nombre  AS Veterinario,
           Med.Nombre AS Medicamento
    FROM CITA C
    JOIN MASCOTA     M   ON C.ID_Mascota = M.ID_Mascota
    JOIN DUENO       D   ON M.ID_Dueno   = D.ID_Dueno
    JOIN VETERINARIO V   ON C.ID_Vet     = V.ID_Vet
    LEFT JOIN MEDICAMENTO Med ON C.ID_Med = Med.ID_Med
    ORDER BY C.Fecha DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Citas — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>📅 Citas</h1>
      <div class="breadcrumb">Registro de citas veterinarias</div>
    </div>
    <a href="crear.php" class="btn btn-primary">+ Nueva Cita</a>
  </div>
  <div class="content">
    <?php if($msg==='creado'):?><div class="alert alert-success">✅ Cita registrada.</div>
    <?php elseif($msg==='editado'):?><div class="alert alert-success">✅ Cita actualizada.</div>
    <?php elseif($msg==='eliminado'):?><div class="alert alert-danger">🗑️ Cita eliminada.</div>
    <?php endif;?>

    <div class="card">
      <div class="card-header">
        <h3>Historial de Citas</h3>
        <span style="font-size:.8rem;color:var(--muted);"><?= $citas->num_rows ?> registros</span>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>#</th><th>Fecha</th><th>Mascota</th><th>Dueño</th>
              <th>Veterinario</th><th>Motivo</th><th>Medicamento</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($c = $citas->fetch_assoc()): ?>
            <tr>
              <td><?= $c['ID_Cita'] ?></td>
              <td><strong><?= $c['Fecha'] ?></strong></td>
              <td>
                <?php
                $emoji = match(strtolower($c['Especie'] ?? '')) {
                    'perro' => '🐶', 'gato' => '🐱', default => '🐾'
                };
                ?>
                <span class="badge badge-amber"><?= $emoji ?> <?= htmlspecialchars($c['Mascota']) ?></span>
              </td>
              <td><?= htmlspecialchars($c['Dueno']) ?></td>
              <td><?= htmlspecialchars($c['Veterinario']) ?></td>
              <td><?= htmlspecialchars($c['Motivo']) ?></td>
              <td>
                <?php if ($c['Medicamento']): ?>
                  <span class="badge badge-purple">💊 <?= htmlspecialchars($c['Medicamento']) ?></span>
                <?php else: ?>
                  <span class="badge badge-gray">— ninguno</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="btn-group">
                  <a href="editar.php?id=<?= $c['ID_Cita'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                  <a href="index.php?eliminar=<?= $c['ID_Cita'] ?>" class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar esta cita?')">🗑️ Eliminar</a>
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
