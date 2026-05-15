<?php
$root = './';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Final de Sustentación — VetSystem</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #eee; }
        .page { background: white; width: 21cm; min-height: 29.7cm; padding: 2cm; margin: 1cm auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); position: relative; }
        .header { border-bottom: 2px solid #1a4a3a; padding-bottom: 1rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #1a4a3a; }
        h1 { color: #1a4a3a; font-size: 2.2rem; margin-top: 0; }
        h2 { color: #2d6b52; border-left: 5px solid #e8c84a; padding-left: 1rem; margin-top: 2rem; }
        .highlight-box { background: #f4f7f5; border: 1px solid #d1dbd4; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; }
        .tech-tag { display: inline-block; background: #1a4a3a; color: white; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.85rem; margin-right: 0.5rem; }
        .footer { position: absolute; bottom: 2cm; left: 2cm; right: 2cm; border-top: 1px solid #ddd; padding-top: 1rem; font-size: 0.8rem; color: #888; text-align: center; }
        
        @media print {
            body { background: white; }
            .page { margin: 0; box-shadow: none; width: 100%; }
            .no-print { display: none; }
        }
        
        .no-print-btn { background: #e8c84a; color: #1a4a3a; padding: 1rem 2rem; border: none; border-radius: 50px; font-weight: bold; cursor: pointer; position: fixed; top: 20px; right: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 1000; }
    </style>
</head>
<body>

<button class="no-print-btn no-print" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>

<div class="page">
    <div class="header">
        <div class="logo">🐾 VetNar System</div>
        <div style="text-align: right; font-size: 0.9rem; color: #666;">Mayo, 2026<br>Ibagué, Tolima</div>
    </div>

    <h1>Guía de Sustentación Académica</h1>
    <p>Este documento contiene la estructura lógica, técnica y estratégica para la presentación del proyecto final de Base de Datos.</p>

    <h2>1. Resumen del Proyecto</h2>
    <p><strong>VetNar</strong> es una plataforma integral de gestión veterinaria diseñada bajo el paradigma de arquitectura cliente-servidor. El sistema permite la administración completa del ciclo de vida de una consulta médica, desde la captación del cliente mediante una Landing Page hasta la gestión administrativa de registros médicos y stock de medicamentos.</p>

    <div class="highlight-box">
        <strong>Objetivo Principal:</strong> Centralizar la información clínica y operativa para mejorar la toma de decisiones basada en datos reales y reportes precisos.
    </div>

    <h2>2. Arquitectura de Software (Stack)</h2>
    <div>
        <span class="tech-tag">PHP 8.x</span>
        <span class="tech-tag">MySQL / MariaDB</span>
        <span class="tech-tag">XAMPP</span>
        <span class="tech-tag">Vanilla CSS</span>
        <span class="tech-tag">GitHub Pages</span>
    </div>

    <h2>3. Diseño de la Base de Datos</h2>
    <p>La base de datos <strong>veterinaria_db</strong> consta de 5 entidades principales relacionadas mediante llaves foráneas para garantizar la integridad referencial:</p>
    <ul>
        <li><strong>DUENO:</strong> Entidad maestra de clientes.</li>
        <li><strong>MASCOTA:</strong> Vinculada a Dueño (1:N).</li>
        <li><strong>VETERINARIO:</strong> Personal médico con especialidades.</li>
        <li><strong>MEDICAMENTO:</strong> Inventario de farmacia.</li>
        <li><strong>CITA:</strong> Entidad transaccional que une todas las anteriores.</li>
    </ul>

    <h2>4. Consultas y Reportes Estratégicos</h2>
    <p>El sistema implementa consultas de alta complejidad utilizando <code>INNER JOIN</code> y <code>LEFT JOIN</code>, permitiendo generar:</p>
    <ol>
        <li>Reportes de citas con trazabilidad completa (Dueño-Mascota-Vet).</li>
        <li>Análisis de productividad veterinaria (Ranking de citas).</li>
        <li>Control de medicamentos prescritos por consulta.</li>
    </ol>

    <h2>5. Guion de Sustentación</h2>
    <p>Durante la charla, enfócate en la <strong>normalización</strong> de las tablas y en cómo el sistema previene la pérdida de datos mediante relaciones sólidas. Resalta que la interfaz es <em>responsive</em> y está optimizada para la experiencia de usuario (UX).</p>

    <div class="footer">
        Documento generado automáticamente por Antigravity AI para el proyecto VetNar.
    </div>
</div>

</body>
</html>
