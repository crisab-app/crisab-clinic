<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Médica - {{ $patient->name }}</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; /* Letra optimizada para Media Carta */
            color: #111827; 
            margin: 0;
            padding: 10px 15px; 
        }
        
        /* Encabezado: Logo y Datos del Doctor */
        .header { 
            width: 100%; 
            border-bottom: 2px solid #4F46E5; 
            padding-bottom: 10px; 
            margin-bottom: 15px; 
        }
        .header td { vertical-align: top; }
        
        .logo-container img {
            max-width: 120px; 
            max-height: 60px;
        }

        .doctor-info { text-align: right; line-height: 1.3; }
        .doctor-name { font-size: 16px; font-weight: bold; color: #4F46E5; margin: 0 0 3px 0; }
        .doctor-creds { font-size: 10px; color: #4b5563; margin: 0; }
        
        /* Barra de Datos del Paciente */
        .patient-info { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
            background-color: #f9fafb;
        }
        .patient-info td { 
            padding: 6px 8px; 
            border: 1px solid #e5e7eb; 
        }
        .label { font-weight: bold; color: #6b7280; text-transform: uppercase; font-size: 8px;}
        .value { font-size: 11px; font-weight: bold;}
        
        /* Cuerpo de la Receta */
        .rx-symbol { 
            font-size: 24px; 
            font-weight: bold; 
            color: #4F46E5; 
            font-family: serif;
            margin-bottom: 10px;
        }
        .plan-content { 
            min-height: 200px; /* Espacio para escribir la receta */
            font-size: 13px; 
            line-height: 1.6; 
            white-space: pre-wrap; 
        }
        
        /* Pie de página (Firma y Dirección) */
        .footer { 
            position: absolute; 
            bottom: 20px; 
            width: 100%; 
            text-align: center; 
        }
        .signature-line { 
            width: 200px; 
            margin: 0 auto; 
            border-top: 1px solid #111827; 
            padding-top: 5px; 
            margin-bottom: 10px;
            font-weight: bold;
        }
        .clinic-address {
            font-size: 9px;
            color: #4b5563;
            line-height: 1.4;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Encabezado / Membrete Oficial -->
    <table class="header">
        <tr>
            <td style="width: 40%;" class="logo-container">
                <!-- Buscamos si la clínica tiene su propio logo dinámico -->
                @if($clinic->logo_path && file_exists(storage_path('app/public/' . $clinic->logo_path)))
                    <img src="{{ storage_path('app/public/' . $clinic->logo_path) }}" alt="Logo Clínica">
                @else
                    <!-- Si no han subido nada, mostramos el nombre en texto como plan de respaldo -->
                    <h2 style="margin:0; color:#111827; font-size: 18px;">{{ $clinic->name ?? 'Mi Clínica' }}</h2>
                @endif
            </td>
            <td style="width: 60%;" class="doctor-info">
                <h2 class="doctor-name">Dr(a). {{ $doctor->name }}</h2>
                <div class="doctor-creds">
                    Médico Cirujano y Partero<br>
                    <strong>Universidad Nacional Autónoma de México</strong><br>
                    Céd. Profesional: <strong>12345678</strong> | Céd. Especialidad: <strong>87654321</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- Fecha de emisión -->
    <div style="text-align: right; font-size: 10px; margin-bottom: 10px; color: #4b5563;">
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($consultation->created_at)->translatedFormat('d \d\e F, Y - H:i') }}
    </div>

    <!-- Datos de Identificación del Paciente (NOM-004) -->
    <table class="patient-info">
        <tr>
            <td style="width: 50%;">
                <div class="label">Paciente</div>
                <div class="value">{{ $patient->name }}</div>
            </td>
            <td style="width: 20%;">
                <div class="label">Edad</div>
                <div class="value">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age . ' años' : 'N/E' }}</div>
            </td>
            <td style="width: 30%;">
                <div class="label">Alergias</div>
                <div class="value" style="color: #dc2626;">{{ $patient->allergies ?: 'Desconoce / Ninguna' }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Presión Arterial</div>
                <div class="value">{{ $consultation->vitals['bp'] ?? '- -' }}</div>
            </td>
            <td>
                <div class="label">Peso</div>
                <div class="value">{{ $consultation->vitals['weight'] ?? '- -' }} kg</div>
            </td>
            <td>
                <div class="label">Temperatura</div>
                <div class="value">{{ $consultation->vitals['temp'] ?? '- -' }} °C</div>
            </td>
        </tr>
    </table>

    <!-- Área de Prescripción (Rx) -->
    <div class="rx-symbol">Rx.</div>
    
    <div class="plan-content">
{{ $consultation->plan }}
    </div>

    <!-- Firma y Pie de Página Legal -->
    <div class="footer">
        <div class="signature-line">
            Firma del Médico
        </div>
        <div class="clinic-address">
            <strong>{{ $clinic->name ?? 'Clínica Médica' }}</strong><br>
            {{ $clinic->address ?? 'Dirección no registrada' }} | Teléfono: {{ $clinic->phone ?? 'N/E' }}<br>
            <em>Documento expedido a través de Expediente Clínico Electrónico.</em>
        </div>
    </div>

</body>
</html>