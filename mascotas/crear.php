<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

$duenos = $conn->query("SELECT ID_Dueno, CONCAT(Nombre,' ',Apellido) AS NombreCompleto FROM DUENO ORDER BY Apellido");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre']   ?? '');
    $especie  = trim($_POST['especie']  ?? '');
    $raza     = trim($_POST['raza']     ?? '');
    $fechaNac = $_POST['fecha_nac']     ?? '';
    $idDueno  = (int)($_POST['id_dueno'] ?? 0);

    if (!$nombre || !$idDueno) {
        $error = 'Nombre y dueño son obligatorios.';
    } else {
        $stmt = $conn->prepare("INSERT INTO MASCOTA (Nombre,Especie,Raza,FechaNacimiento,ID_Dueno) VALUES (?,?,?,?,?)");
        $fechaNac = $fechaNac ?: null;
        $stmt->bind_param('ssssi', $nombre, $especie, $raza, $fechaNac, $idDueno);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?msg=creado');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Nueva Mascota — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>🐶 Nueva Mascota</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Mascotas</a> / Crear</div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?><div class="alert alert-danger">❌ <?= $error ?></div><?php endif; ?>
    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Formulario de Registro</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre de la Mascota *</label>
              <input type="text" name="nombre" class="form-control" placeholder="Toby" required
                     value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Dueño *</label>
              <select name="id_dueno" class="form-control" required>
                <option value="">— Selecciona un dueño —</option>
                <?php while ($d = $duenos->fetch_assoc()): ?>
                <option value="<?= $d['ID_Dueno'] ?>"
                  <?= (($_POST['id_dueno'] ?? '') == $d['ID_Dueno']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($d['NombreCompleto']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Especie</label>
              <select name="especie" class="form-control">
                <option value="">— Selecciona —</option>
                <?php foreach (['Perro','Gato','Ave','Conejo','Reptil','Otro'] as $esp): ?>
                <option value="<?= $esp ?>"
                  <?= (($_POST['especie'] ?? '') == $esp) ? 'selected' : '' ?>>
                  <?= $esp ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Raza</label>
              <input type="text" name="raza" class="form-control" placeholder="Labrador"
                     value="<?= htmlspecialchars($_POST['raza'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Fecha de Nacimiento</label>
              <input type="date" name="fecha_nac" class="form-control"
                     value="<?= htmlspecialchars($_POST['fecha_nac'] ?? '') ?>">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Guardar Mascota</button>
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
