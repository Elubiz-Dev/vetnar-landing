<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query("DELETE FROM MASCOTA WHERE ID_Mascota=$id");
    header('Location: index.php?msg=eliminado');
    exit;
}

$msg = $_GET['msg'] ?? '';
$mascotas = $conn->query("
    SELECT M.*, CONCAT(D.Nombre,' ',D.Apellido) AS NombreDueno
    FROM MASCOTA M
    JOIN DUENO D ON M.ID_Dueno = D.ID_Dueno
    ORDER BY M.Nombre
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mascotas — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>🐶 Mascotas</h1>
      <div class="breadcrumb">Gestión de mascotas</div>
    </div>
    <a href="crear.php" class="btn btn-primary">+ Nueva Mascota</a>
  </div>
  <div class="content">
    <?php if ($msg === 'creado'):  ?><div class="alert alert-success">✅ Mascota registrada.</div>
    <?php elseif ($msg === 'editado'): ?><div class="alert alert-success">✅ Mascota actualizada.</div>
    <?php elseif ($msg === 'eliminado'): ?><div class="alert alert-danger">🗑️ Mascota eliminada.</div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header">
        <h3>Lista de Mascotas</h3>
        <span style="font-size:.8rem;color:var(--muted);"><?= $mascotas->num_rows ?> registros</span>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>#</th><th>Nombre</th><th>Especie</th><th>Raza</th>
              <th>Fecha Nac.</th><th>Dueño</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($m = $mascotas->fetch_assoc()): ?>
            <tr>
              <td><?= $m['ID_Mascota'] ?></td>
              <td><strong><?= htmlspecialchars($m['Nombre']) ?></strong></td>
              <td>
                <?php
                $emoji = match(strtolower($m['Especie'])) {
                    'perro' => '🐶', 'gato' => '🐱', 'ave' => '🦜',
                    'conejo' => '🐰', default => '🐾'
                };
                ?>
                <span class="badge badge-green"><?= $emoji ?> <?= htmlspecialchars($m['Especie']) ?></span>
              </td>
              <td><?= htmlspecialchars($m['Raza']) ?></td>
              <td><?= $m['FechaNacimiento'] ?></td>
              <td><?= htmlspecialchars($m['NombreDueno']) ?></td>
              <td>
                <div class="btn-group">
                  <a href="editar.php?id=<?= $m['ID_Mascota'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                  <a href="index.php?eliminar=<?= $m['ID_Mascota'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar esta mascota?')">🗑️ Eliminar</a>
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
