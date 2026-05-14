<?php
require_once '../config/conexion.php';
$root = '../';
$conn = getConexion();

$consultaActiva = $_GET['q'] ?? '1';

// ── CONSULTAS DISPONIBLES ─────────────────────────────────
$consultas = [
  '1' => [
    'titulo'      => 'Citas completas con Dueño, Mascota y Veterinario',
    'descripcion' => 'JOIN entre CITA, MASCOTA, DUENO y VETERINARIO — 4 tablas',
    'sql'         => "
      SELECT
        C.ID_Cita,
        C.Fecha,
        C.Motivo,
        M.Nombre  AS Mascota,
        M.Especie,
        CONCAT(D.Nombre,' ',D.Apellido) AS Dueno,
        D.Telefono,
        V.Nombre  AS Veterinario,
        V.Especialidad
      FROM CITA C
      JOIN MASCOTA     M ON C.ID_Mascota = M.ID_Mascota
      JOIN DUENO       D ON M.ID_Dueno   = D.ID_Dueno
      JOIN VETERINARIO V ON C.ID_Vet     = V.ID_Vet
      ORDER BY C.Fecha DESC
    ",
  ],
  '2' => [
    'titulo'      => 'Citas con medicamentos prescritos',
    'descripcion' => 'JOIN entre CITA, MASCOTA y MEDICAMENTO — muestra qué medicamento se prescribió',
    'sql'         => "
      SELECT
        C.ID_Cita,
        C.Fecha,
        M.Nombre    AS Mascota,
        M.Especie,
        Med.Nombre  AS Medicamento,
        Med.Dosis,
        Med.Precio  AS PrecioMed
      FROM CITA C
      JOIN MASCOTA      M   ON C.ID_Mascota = M.ID_Mascota
      LEFT JOIN MEDICAMENTO Med ON C.ID_Med = Med.ID_Med
      ORDER BY C.Fecha DESC
    ",
  ],
  '3' => [
    'titulo'      => 'Mascotas con datos de su dueño',
    'descripcion' => 'JOIN entre MASCOTA y DUENO — listado completo de mascotas y propietarios',
    'sql'         => "
      SELECT
        M.ID_Mascota,
        M.Nombre    AS Mascota,
        M.Especie,
        M.Raza,
        M.FechaNacimiento,
        CONCAT(D.Nombre,' ',D.Apellido) AS Dueno,
        D.Telefono,
        D.Email
      FROM MASCOTA M
      JOIN DUENO D ON M.ID_Dueno = D.ID_Dueno
      ORDER BY D.Apellido, M.Nombre
    ",
  ],
  '4' => [
    'titulo'      => 'Dueños con total de mascotas y citas',
    'descripcion' => 'JOIN + GROUP BY — conteo de mascotas y citas por dueño',
    'sql'         => "
      SELECT
        CONCAT(D.Nombre,' ',D.Apellido) AS Dueno,
        D.Telefono,
        COUNT(DISTINCT M.ID_Mascota)  AS TotalMascotas,
        COUNT(DISTINCT C.ID_Cita)     AS TotalCitas
      FROM DUENO D
      LEFT JOIN MASCOTA M ON D.ID_Dueno   = M.ID_Dueno
      LEFT JOIN CITA    C ON M.ID_Mascota = C.ID_Mascota
      GROUP BY D.ID_Dueno
      ORDER BY TotalCitas DESC
    ",
  ],
  '5' => [
    'titulo'      => 'Veterinarios con ranking de citas',
    'descripcion' => 'JOIN + GROUP BY — veterinarios ordenados por número de citas atendidas',
    'sql'         => "
      SELECT
        V.Nombre        AS Veterinario,
        V.Especialidad,
        V.NumLicencia,
        COUNT(C.ID_Cita) AS TotalCitas,
        MAX(C.Fecha)     AS UltimaCita
      FROM VETERINARIO V
      LEFT JOIN CITA C ON V.ID_Vet = C.ID_Vet
      GROUP BY V.ID_Vet
      ORDER BY TotalCitas DESC
    ",
  ],
];

$actual  = $consultas[$consultaActiva] ?? $consultas['1'];
$result  = $conn->query($actual['sql']);
$columns = $result ? $result->fetch_fields() : [];
$rows    = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Consultas SQL — VetSystem</title>
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
  <style>
    .query-tabs {
      display: flex;
      flex-direction: column;
      gap: .4rem;
      margin-bottom: 1.5rem;
    }
    .query-tab {
      display: flex;
      align-items: flex-start;
      gap: .75rem;
      padding: .85rem 1.1rem;
      border-radius: 10px;
      border: 1.5px solid var(--border);
      background: var(--white);
      text-decoration: none;
      transition: all .18s;
      cursor: pointer;
    }
    .query-tab:hover { border-color: var(--accent); }
    .query-tab.active { border-color: var(--primary); background: #f0f5f2; }
    .tab-num {
      width: 28px; height: 28px;
      border-radius: 8px;
      background: var(--primary);
      color: #fff;
      font-size: .8rem;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .query-tab.active .tab-num { background: var(--accent); color: var(--primary); }
    .tab-info h4 { font-size: .85rem; font-weight: 700; color: var(--primary); }
    .tab-info p  { font-size: .75rem; color: var(--muted); margin-top: .15rem; }
    .sql-block {
      background: #0f2419;
      color: #7ee8a2;
      border-radius: 10px;
      padding: 1.2rem 1.5rem;
      font-family: 'Courier New', monospace;
      font-size: .82rem;
      line-height: 1.7;
      margin-bottom: 1.5rem;
      overflow-x: auto;
      white-space: pre;
    }
    .result-count {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      font-size: .8rem;
      color: var(--muted);
      padding: .3rem .7rem;
      background: var(--bg);
      border-radius: 20px;
      border: 1px solid var(--border);
    }
    .layout-two { display: grid; grid-template-columns: 280px 1fr; gap: 1.5rem; align-items: start; }
    @media(max-width:900px){ .layout-two { grid-template-columns: 1fr; } }
  </style>
</head>
<body>
<?php include $root . 'includes/sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <div>
      <h1>📋 Consultas SQL</h1>
      <div class="breadcrumb">Consultas con 2 o más tablas — JOINs y agregaciones</div>
    </div>
  </div>
  <div class="content">

    <div class="layout-two">

      <!-- Lista de consultas -->
      <div>
        <div class="card">
          <div class="card-header"><h3>Consultas disponibles</h3></div>
          <div class="card-body">
            <div class="query-tabs">
              <?php foreach ($consultas as $key => $q): ?>
              <a href="?q=<?= $key ?>" class="query-tab <?= $consultaActiva == $key ? 'active' : '' ?>">
                <div class="tab-num"><?= $key ?></div>
                <div class="tab-info">
                  <h4><?= htmlspecialchars($q['titulo']) ?></h4>
                  <p><?= htmlspecialchars($q['descripcion']) ?></p>
                </div>
              </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Resultado -->
      <div>
        <div class="card" style="margin-bottom:1.2rem">
          <div class="card-header">
            <h3><?= htmlspecialchars($actual['titulo']) ?></h3>
            <span class="result-count">📊 <?= count($rows) ?> resultados</span>
          </div>
          <div class="card-body" style="padding-bottom:.5rem">
            <p style="font-size:.83rem;color:var(--muted);margin-bottom:.8rem">
              <?= htmlspecialchars($actual['descripcion']) ?>
            </p>
            <div class="sql-block"><?= htmlspecialchars(trim($actual['sql'])) ?></div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><h3>Resultado</h3></div>
          <div class="table-responsive">
            <?php if (empty($rows)): ?>
              <div style="padding:2rem;text-align:center;color:var(--muted)">Sin resultados</div>
            <?php else: ?>
            <table>
              <thead>
                <tr>
                  <?php foreach ($columns as $col): ?>
                  <th><?= htmlspecialchars($col->name) ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $row): ?>
                <tr>
                  <?php foreach ($row as $val): ?>
                  <td><?= htmlspecialchars((string)($val ?? '—')) ?></td>
                  <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>
<?php $conn->close(); ?>
