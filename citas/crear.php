<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();
$error = '';

$mascotas    = $conn->query("SELECT M.ID_Mascota, CONCAT(M.Nombre,' (',D.Nombre,' ',D.Apellido,')') AS NombreInfo FROM MASCOTA M JOIN DUENO D ON M.ID_Dueno=D.ID_Dueno ORDER BY M.Nombre");
$veterinarios= $conn->query("SELECT ID_Vet, Nombre, Especialidad FROM VETERINARIO ORDER BY Nombre");
$medicamentos= $conn->query("SELECT ID_Med, Nombre FROM MEDICAMENTO ORDER BY Nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha     = $_POST['fecha']      ?? '';
    $motivo    = trim($_POST['motivo']?? '');
    $idMascota = (int)($_POST['id_mascota'] ?? 0);
    $idVet     = (int)($_POST['id_vet']     ?? 0);
    $idMed     = (int)($_POST['id_med']     ?? 0) ?: null;

    if (!$fecha || !$idMascota || !$idVet) {
        $error = 'Fecha, mascota y veterinario son obligatorios.';
    } else {
        $stmt = $conn->prepare("INSERT INTO CITA (Fecha,Motivo,ID_Mascota,ID_Vet,ID_Med) VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssiii', $fecha, $motivo, $idMascota, $idVet, $idMed);
        $stmt->execute(); $stmt->close();
        header('Location: index.php?msg=creado'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Cita — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>📅 Nueva Cita</h1>
      <div class="breadcrumb"><a href="index.php" style="color:var(--accent)">Citas</a> / Crear</div>
    </div>
  </div>
  <div class="content">
    <?php if ($error): ?><div class="alert alert-danger">❌ <?= $error ?></div><?php endif; ?>
    <div class="card" style="max-width:800px">
      <div class="card-header"><h3>Formulario de Cita</h3></div>
      <div class="card-body">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label>Fecha *</label>
              <input type="date" name="fecha" class="form-control" required
                     value="<?= htmlspecialchars($_POST['fecha'] ?? date('Y-m-d')) ?>">
            </div>
            <div class="form-group">
              <label>Mascota *</label>
              <select name="id_mascota" class="form-control" required>
                <option value="">— Selecciona una mascota —</option>
                <?php while ($m = $mascotas->fetch_assoc()): ?>
                <option value="<?= $m['ID_Mascota'] ?>"
                  <?= (($_POST['id_mascota'] ?? '') == $m['ID_Mascota']) ? 'selected' : '' ?>>
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
                  <?= (($_POST['id_vet'] ?? '') == $v['ID_Vet']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($v['Nombre']) ?> — <?= htmlspecialchars($v['Especialidad']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Medicamento prescrito <small style="color:var(--muted)">(opcional)</small></label>
              <select name="id_med" class="form-control">
                <option value="">— Sin medicamento —</option>
                <?php while ($med = $medicamentos->fetch_assoc()): ?>
                <option value="<?= $med['ID_Med'] ?>"
                  <?= (($_POST['id_med'] ?? '') == $med['ID_Med']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($med['Nombre']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group" style="grid-column:1/-1">
              <label>Motivo de la cita</label>
              <textarea name="motivo" class="form-control"
                        placeholder="Describe el motivo de la visita..."><?= htmlspecialchars($_POST['motivo'] ?? '') ?></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Registrar Cita</button>
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
