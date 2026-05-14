<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM DUENO WHERE ID_Dueno=$id");
    header('Location: index.php?msg=eliminado');
    exit;
}

$msg = $_GET['msg'] ?? '';
$duenos = $conn->query("
    SELECT D.*, COUNT(M.ID_Mascota) AS total_mascotas
    FROM DUENO D
    LEFT JOIN MASCOTA M ON D.ID_Dueno = M.ID_Dueno
    GROUP BY D.ID_Dueno
    ORDER BY D.Apellido, D.Nombre
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dueños — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>👤 Dueños</h1>
      <div class="breadcrumb">Gestión de dueños de mascotas</div>
    </div>
    <a href="crear.php" class="btn btn-primary">+ Nuevo Dueño</a>
  </div>
  <div class="content">

    <?php if ($msg === 'creado'):  ?>
      <div class="alert alert-success">✅ Dueño registrado correctamente.</div>
    <?php elseif ($msg === 'editado'): ?>
      <div class="alert alert-success">✅ Dueño actualizado correctamente.</div>
    <?php elseif ($msg === 'eliminado'): ?>
      <div class="alert alert-danger">🗑️ Dueño eliminado.</div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header">
        <h3>Lista de Dueños</h3>
        <span style="font-size:.8rem;color:var(--muted);">
          <?= $duenos->num_rows ?> registros
        </span>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Dirección</th>
              <th>Mascotas</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($d = $duenos->fetch_assoc()): ?>
            <tr>
              <td><?= $d['ID_Dueno'] ?></td>
              <td><strong><?= htmlspecialchars($d['Nombre'].' '.$d['Apellido']) ?></strong></td>
              <td><?= htmlspecialchars($d['Telefono']) ?></td>
              <td><?= htmlspecialchars($d['Email']) ?></td>
              <td><?= htmlspecialchars($d['Direccion']) ?></td>
              <td>
                <span class="badge badge-amber">🐾 <?= $d['total_mascotas'] ?></span>
              </td>
              <td>
                <div class="btn-group">
                  <a href="editar.php?id=<?= $d['ID_Dueno'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                  <a href="index.php?eliminar=<?= $d['ID_Dueno'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar este dueño? También se eliminarán sus mascotas.')">
                    🗑️ Eliminar
                  </a>
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
