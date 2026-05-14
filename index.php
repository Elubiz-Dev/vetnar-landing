<?php
require_once 'config/conexion.php';
$root = '';
$conn = getConexion();

// Conteos para las tarjetas
$duenos      = $conn->query("SELECT COUNT(*) AS t FROM DUENO")->fetch_assoc()['t'];
$mascotas    = $conn->query("SELECT COUNT(*) AS t FROM MASCOTA")->fetch_assoc()['t'];
$veterinarios= $conn->query("SELECT COUNT(*) AS t FROM VETERINARIO")->fetch_assoc()['t'];
$citas       = $conn->query("SELECT COUNT(*) AS t FROM CITA")->fetch_assoc()['t'];
$medicamentos= $conn->query("SELECT COUNT(*) AS t FROM MEDICAMENTO")->fetch_assoc()['t'];

// Citas recientes con JOIN
$citasRecientes = $conn->query("
    SELECT C.Fecha, C.Motivo,
           M.Nombre AS Mascota,
           CONCAT(D.Nombre,' ',D.Apellido) AS Dueno,
           V.Nombre AS Veterinario
    FROM CITA C
    JOIN MASCOTA M     ON C.ID_Mascota = M.ID_Mascota
    JOIN DUENO D       ON M.ID_Dueno   = D.ID_Dueno
    JOIN VETERINARIO V ON C.ID_Vet     = V.ID_Vet
    ORDER BY C.Fecha DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<div class="main">
  <div class="topbar">
    <div>
      <h1>Dashboard</h1>
      <div class="breadcrumb">Resumen general del sistema</div>
    </div>
    <span style="font-size:.85rem;color:var(--muted);">📅 <?= date('d/m/Y') ?></span>
  </div>

  <div class="content">

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon green">👤</div>
        <div class="stat-info">
          <h4><?= $duenos ?></h4>
          <p>Dueños registrados</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon amber">🐶</div>
        <div class="stat-info">
          <h4><?= $mascotas ?></h4>
          <p>Mascotas</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon blue">🩺</div>
        <div class="stat-info">
          <h4><?= $veterinarios ?></h4>
          <p>Veterinarios</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon purple">📅</div>
        <div class="stat-info">
          <h4><?= $citas ?></h4>
          <p>Citas registradas</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon red">💊</div>
        <div class="stat-info">
          <h4><?= $medicamentos ?></h4>
          <p>Medicamentos</p>
        </div>
      </div>
    </div>

    <!-- Citas recientes -->
    <div class="card">
      <div class="card-header">
        <h3>📅 Citas Recientes</h3>
        <a href="citas/index.php" class="btn btn-outline btn-sm">Ver todas →</a>
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Mascota</th>
              <th>Dueño</th>
              <th>Veterinario</th>
              <th>Motivo</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($r = $citasRecientes->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($r['Fecha']) ?></td>
              <td><span class="badge badge-amber">🐾 <?= htmlspecialchars($r['Mascota']) ?></span></td>
              <td><?= htmlspecialchars($r['Dueno']) ?></td>
              <td><?= htmlspecialchars($r['Veterinario']) ?></td>
              <td><?= htmlspecialchars($r['Motivo']) ?></td>
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
