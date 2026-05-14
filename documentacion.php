<?php
$root = './';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación Técnica — VetSystem</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #1a4a3a 0%, #2d6b52 100%);
            --accent-grad: linear-gradient(135deg, #e8c84a 0%, #d4b02d 100%);
            --glass: rgba(255, 255, 255, 0.9);
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f7f5; }
        
        .doc-container { max-width: 1000px; margin: 4rem auto; padding: 0 2rem; }
        
        .doc-header { 
            text-align: center; 
            margin-bottom: 5rem; 
            background: var(--primary-grad); 
            color: white; 
            padding: 4rem 2rem; 
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(26, 74, 58, 0.15);
        }
        .doc-header h1 { font-size: 3rem; font-weight: 800; margin-bottom: 1rem; }
        .doc-header p { opacity: 0.8; font-size: 1.1rem; }

        .section-card {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 3rem;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .section-title { 
            font-size: 1.8rem; 
            font-weight: 800; 
            color: #1a4a3a; 
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .section-title span { color: #e8c84a; }

        /* Estilos del Diagrama de Flujo */
        .flowchart {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            padding: 2rem 0;
        }
        .flow-node {
            background: white;
            border: 2px solid #1a4a3a;
            padding: 1.2rem 2rem;
            border-radius: 16px;
            font-weight: 600;
            color: #1a4a3a;
            position: relative;
            min-width: 200px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .flow-node.start { background: #1a4a3a; color: white; border-radius: 50px; }
        .flow-node.process { border-color: #2d6b52; }
        .flow-node.decision { background: #fdf9e7; border-color: #e8c84a; clip-path: polygon(10% 0%, 90% 0%, 100% 50%, 90% 100%, 10% 100%, 0% 50%); padding: 1.5rem 3rem; }
        
        .flow-arrow {
            width: 2px;
            height: 40px;
            background: #1a4a3a;
            position: relative;
        }
        .flow-arrow::after {
            content: '▼';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            color: #1a4a3a;
            font-size: 0.8rem;
        }

        .sql-preview {
            background: #0f2419;
            color: #7ee8a2;
            padding: 1.5rem;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            overflow-x: auto;
        }

        @media print {
            .sidebar { display: none; }
            .doc-container { margin: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main">
    <div class="doc-container">
        
        <header class="doc-header">
            <h1>Documentación del Proyecto</h1>
            <p>VetSystem — Guía Técnica y Sustentación Académica</p>
        </header>

        <!-- Sección de Requisitos -->
        <section class="section-card">
            <h2 class="section-title"><span>01.</span> Resumen de Requisitos</h2>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="padding:1rem; background:#f0f7f3; border-radius:12px;">
                    <strong>Tablas:</strong> 5 Entidades principales perfectamente normalizadas.
                </div>
                <div style="padding:1rem; background:#fdf9e7; border-radius:12px;">
                    <strong>Relaciones:</strong> 4 Llaves foráneas con integridad referencial.
                </div>
                <div style="padding:1rem; background:#f0f7f3; border-radius:12px;">
                    <strong>Registros:</strong> +50 filas de datos reales simulados.
                </div>
                <div style="padding:1rem; background:#fdf9e7; border-radius:12px;">
                    <strong>Consultas:</strong> 5 Consultas avanzadas con Joins y Agregaciones.
                </div>
            </div>
        </section>

        <!-- Diagrama 1 -->
        <section class="section-card">
            <h2 class="section-title"><span>02.</span> Diagrama de Flujo: Consulta de Citas</h2>
            <p>Este proceso describe cómo el sistema une 4 tablas para generar el reporte de citas con datos del dueño y veterinario.</p>
            
            <div class="flowchart">
                <div class="flow-node start">INICIO CONSULTA</div>
                <div class="flow-arrow"></div>
                <div class="flow-node">JOIN: CITA + MASCOTA</div>
                <div class="flow-arrow"></div>
                <div class="flow-node">JOIN: MASCOTA + DUEÑO</div>
                <div class="flow-arrow"></div>
                <div class="flow-node">JOIN: CITA + VETERINARIO</div>
                <div class="flow-arrow"></div>
                <div class="flow-node process">ORDENAR POR FECHA (DESC)</div>
                <div class="flow-arrow"></div>
                <div class="flow-node start">MOSTRAR REPORTE</div>
            </div>

            <div class="sql-preview">
SELECT C.Fecha, M.Nombre, CONCAT(D.Nombre, ' ', D.Apellido) AS Dueno, V.Nombre AS Veterinario 
FROM CITA C 
JOIN MASCOTA M ON C.ID_Mascota = M.ID_Mascota 
JOIN DUENO D ON M.ID_Dueno = D.ID_Dueno 
JOIN VETERINARIO V ON C.ID_Vet = V.ID_Vet;
            </div>
        </section>

        <!-- Diagrama 2 -->
        <section class="section-card">
            <h2 class="section-title"><span>03.</span> Diagrama de Flujo: Ranking de Vets</h2>
            <p>Lógica aplicada para obtener estadísticas de productividad por cada profesional médico.</p>
            
            <div class="flowchart">
                <div class="flow-node start">INICIO ESTADÍSTICA</div>
                <div class="flow-arrow"></div>
                <div class="flow-node">JOIN: VETERINARIO + CITA</div>
                <div class="flow-arrow"></div>
                <div class="flow-node decision">¿TIENE CITAS?</div>
                <div class="flow-arrow"></div>
                <div class="flow-node process">AGRUPAR POR ID_VET</div>
                <div class="flow-arrow"></div>
                <div class="flow-node">CALCULAR COUNT(ID_CITA)</div>
                <div class="flow-arrow"></div>
                <div class="flow-node start">GENERAR RANKING</div>
            </div>
        </section>

        <!-- Footer -->
        <footer style="text-align:center; padding: 2rem; color: #888; font-size: 0.9rem;">
            © 2026 VetNar — Proyecto de Gestión de Base de Datos.
        </footer>

    </div>
</div>

</body>
</html>
