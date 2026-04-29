<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Médica - {{ $patient->name }}</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 14px; 
            color: #1f2937; 
            margin: 0;
            padding: 20px;
        }
        /* Membrete (Header) */
        .header { 
            width: 100%; 
            border-bottom: 3px solid #4F46E5; /* Color Índigo */
            padding-bottom: 15px; 
            margin-bottom: 20px; 
        }
        .header td { vertical-align: top; }
        .clinic-name { font-size: 24px; font-weight: bold; color: #111827; margin: 0; }
        .doctor-name { font-size: 18px; font-weight: bold; color: #4F46E5; margin: 0; }
        
        /* Barra de Datos del Paciente */
        .patient-info { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
            background-color: #f9fafb;
        }
        .patient-info td { 
            padding: 8px 12px; 
            border: 1px solid #e5e7eb; 
            font-size: 12px; 
        }
        .label { font-weight: bold; color: #6b7280; text-transform: uppercase; font-size: 10px;}
        
        /* Cuerpo de la Receta */
        .rx-symbol { 
            font-size: 32px; 
            font-weight: bold; 
            color: #4F46E5; 
            font-family: serif;
            margin-bottom: 15px;
        }
        .plan-content { 
            min-height: 400px; 
            font-size: 15px; 
            line-height: 1.8; 
            white-space: pre-wrap; /* Respeta los saltos de línea del doctor */
        }
        
        /* Pie de página (Firma) */
        .footer { 
            position: absolute; 
            bottom: 30px; 
            width: 100%; 
            text-align: center; 
        }
        .signature-line { 
            width: 250px; 
            margin: 0 auto; 
            border-top: 1px solid #111827; 
            padding-top: 5px; 
            margin-bottom: 15px;
        }
        .footer-text { font-size: 11px; color: #6b7280; }
    </style>
</head>
<body>

    <!-- Encabezado / Membrete -->
    <table class="header">
        <tr>
            <td style="width: 50%; text-align: left;">
                <h1 class="clinic-name">{{ $clinic->name ?? 'Clínica Médica' }}</h1>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #6b7280;">
                    Atención Médica Integral<br>
                    Fecha: {{ \Carbon\Carbon::parse($consultation->created_at)->translatedFormat('d \d\e F, Y') }}
                </p>
            </td>
            <td style="width: 50%; text-align: right;">
                <h2 class="doctor-name">Dr(a). {{ $doctor->name }}</h2>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #6b7280;">
                    Médico Cirujano<br>
                    Cédula Profesional: En Trámite
                </p>
            </td>
        </tr>
    </table>

    <!-- Datos del Paciente -->
    <table class="patient-info">
        <tr>
            <td style="width: 50%;">
                <div class="label">Paciente</div>
                <div>{{ $patient->name }}</div>
            </td>
            <td style="width: 25%;">
                <div class="label">Edad</div>
                <div>{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age . ' años' : 'N/E' }}</div>
            </td>
            <td style="width: 25%;">
                <div class="label">Alergias</div>
                <div style="color: #dc2626; font-weight: bold;">{{ $patient->allergies ?: 'Ninguna' }}</div>
            </td>
        </tr>
        <!-- Segunda fila de signos vitales -->
        <tr>
            <td>
                <div class="label">Presión Arterial</div>
                <div>{{ $consultation->vitals['bp'] ?? '- -' }}</div>
            </td>
            <td>
                <div class="label">Peso</div>
                <div>{{ $consultation->vitals['weight'] ?? '- -' }} kg</div>
            </td>
            <td>
                <div class="label">Temperatura</div>
                <div>{{ $consultation->vitals['temp'] ?? '- -' }} °C</div>
            </td>
        </tr>
    </table>

    <!-- Área de Prescripción (Rx) -->
    <div class="rx-symbol">Rx.</div>
    
    <div class="plan-content">
{{ $consultation->plan }}
    </div>

    <!-- Firma y Pie de Página -->
    <div class="footer">
        <div class="signature-line">
            Firma del Médico
        </div>
        <div class="footer-text">
            Este documento es una receta médica oficial expedida a través del Expediente Clínico Electrónico.<br>
            Cualquier alteración a este documento invalida su uso.
        </div>
    </div>

</body>
</html>