<?php
// Detectar la página activa
$currentFile = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));

function isActive(string $section): string {
    global $currentDir, $currentFile;
    if ($section === 'dashboard' && $currentFile === 'index.php' && $currentDir === 'veterinaria') return 'active';
    if ($section === $currentDir) return 'active';
    return '';
}
?>
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">🐾</div>
    <h2>VetSystem</h2>
    <span>Clínica Veterinaria</span>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section">Principal</div>
    <a href="<?= $root ?>index.php" class="nav-item <?= isActive('dashboard') ?>">
      <span class="icon">📊</span> Dashboard
    </a>

    <div class="nav-section">Registros</div>
    <a href="<?= $root ?>duenos/index.php" class="nav-item <?= isActive('duenos') ?>">
      <span class="icon">👤</span> Dueños
    </a>
    <a href="<?= $root ?>mascotas/index.php" class="nav-item <?= isActive('mascotas') ?>">
      <span class="icon">🐶</span> Mascotas
    </a>
    <a href="<?= $root ?>veterinarios/index.php" class="nav-item <?= isActive('veterinarios') ?>">
      <span class="icon">🩺</span> Veterinarios
    </a>
    <a href="<?= $root ?>medicamentos/index.php" class="nav-item <?= isActive('medicamentos') ?>">
      <span class="icon">💊</span> Medicamentos
    </a>

    <div class="nav-section">Operaciones</div>
    <a href="<?= $root ?>citas/index.php" class="nav-item <?= isActive('citas') ?>">
      <span class="icon">📅</span> Citas
    </a>

    <div class="nav-section">Reportes</div>
    <a href="<?= $root ?>consultas/index.php" class="nav-item <?= isActive('consultas') ?>">
      <span class="icon">📋</span> Consultas SQL
    </a>
  </nav>
</aside>
