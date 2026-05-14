<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

$id   = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$cita = $conn->query("SELECT * FROM CITA WHERE ID_Cita=$id")->fetch_assoc();
if (!$cita) { header('Location: index.php'); exit; }

$mascotas     = $conn->query("SELECT M.ID_Mascota, CONCAT(M.Nombre,' (',D.Nombre,' ',D.Apellido,')') AS NombreInfo FROM MASCOTA M JOIN DUENO D ON M.ID_Dueno=D.ID_Dueno ORDER BY M.Nombre");
$veterinarios = $conn->query("SELECT ID_Vet, Nombre, Especialidad FROM VETERINARIO ORDER BY Nombre");
$medicamentos = $conn->query("SELECT ID_Med, Nombre FROM MEDICAMENTO ORDER BY Nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha     = $_POST['fecha']         ?? '';
    $motivo    = trim($_POST['motivo']   ?? '');
    $idMascota = (int)($_POST['id_mascota'] ?? 0);
    $idVet     = (int)($_POST['id_vet']     ?? 0);
    $idMed     = (int)($_POST['id_med']     ?? 0) ?: null;

    if (!$fecha || !$idMascota || !$idVet) {
        $error = 'Fecha, mascota y veterinario son obligatorios.';
    } else {
        $stmt = $conn->prepare("UPDATE CITA SET Fecha=?,Motivo=?,ID_Mascota=?,ID_Vet=?,ID_Med=? WHERE ID_Cita=?");
        $stmt->bind_param('ssiiii', $fecha, $motivo, $idMascota, $idVet, $idMed, $id);
        $stmt->execute(); $stmt->close();
        header('Location: index.php?msg=editado'); exit;
    }
    $cita['Fecha'] = $fecha; $cita['Motivo'] = $motivo;
    $cita['ID_Mascota'] = $idMascota; $cita['ID_Vet'] = $idVet; $cita['ID_Med'] = $idMed;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cita — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>✏️ Editar Cita</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Citas</a> / Editar #<?= $id ?></div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?><div class="alert alert-danger">❌ <?= $error ?></div><?php endif; ?>
    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Modificar datos de la cita</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label>Fecha *</label>
              <input type="date" name="fecha" class="form-control" required
                     value="<?= htmlspecialchars($cita['Fecha']) ?>">
            </div>
            <div class="form-group">
              <label>Mascota *</label>
              <select name="id_mascota" class="form-control" required>
                <option value="">— Selecciona una mascota —</option>
                <?php while ($m = $mascotas->fetch_assoc()): ?>
                <option value="<?= $m['ID_Mascota'] ?>"
                  <?= ($cita['ID_Mascota'] == $m['ID_Mascota']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($m['NombreInfo']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Veterinario *</label>
              <select name="id_vet" class="form-control" required>
                <option value="">— Selecciona un veterinario —</option>
                <?php while ($v = $veterinarios->fetch_assoc()): ?>
                <option value="<?= $v['ID_Vet'] ?>"
                  <?= ($cita['ID_Vet'] == $v['ID_Vet']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($v['Nombre']) ?> — <?= htmlspecialchars($v['Especialidad']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Medicamento prescrito</label>
              <select name="id_med" class="form-control">
                <option value="">— Sin medicamento —</option>
                <?php while ($med = $medicamentos->fetch_assoc()): ?>
                <option value="<?= $med['ID_Med'] ?>"
                  <?= ($cita['ID_Med'] == $med['ID_Med']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($med['Nombre']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Motivo de la cita</label>
              <textarea name="motivo" class="form-control"><?= htmlspecialchars($cita['Motivo']) ?></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-warning">💾 Actualizar Cita</button>
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
