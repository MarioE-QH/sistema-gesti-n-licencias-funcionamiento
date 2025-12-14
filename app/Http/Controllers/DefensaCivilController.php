<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleLicencia;
use App\Models\ControlExpediente;
use App\Models\Documento;

use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DefensaCivilController extends Controller
{

     public function exportarExpedientes(Request $request)
{
    $query = DetalleLicencia::with([
        'persona',
        'local.direccion.sector',
        'local.tipoRiesgo',
        'autorizacion',
        'controlExpediente'
    ]);

    
    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereHas('autorizacion', function ($q) use ($request) {
            $q->whereBetween('fecha_ingreso', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        });
    }

   
    if ($request->filled('nsobre')) {
        $query->where('nsobre', $request->nsobre);
    }

    
    if ($request->filled('sobre_inicio') && $request->filled('sobre_fin')) {
        $query->whereBetween('nsobre', [
            $request->sobre_inicio,
            $request->sobre_fin
        ]);
    }

    $expedientes = $query->orderBy('idDetalle', 'desc')->get();

   
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    
    $headers = [
        'N°',
        'N° EXPEDIENTE',
        'FECHA EXPEDIENTE',
        'FECHA RECEPCIÓN DC',
        'REPRESENTANTE LEGAL',
        'RUC',
        'NOMBRE COMERCIAL',
        'DIRECCIÓN',
        'SECTOR',
        'GIRO',
        'ÁREA',
        'AFORO',
        'RIESGO',
        'TIPO ITSE',
        'FECHA ITSE',
        'RESULTADO',
        'N° INFORME DC',
        'FECHA INFORME DC',
        'N° RESOLUCIÓN DC',
        'FECHA RESOLUCIÓN DC',
        'N° CERTIFICADO DC',
        'FECHA CERTIFICADO DC',
        'FECHA RENOVACIÓN',
        'FECHA CADUCIDAD',
        'NOTIFICADO',
        'FECHA ENTREGA CERT.',
        'ESTADO',
        'OBSERVACIÓN'
    ];

    
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    
    $fmtFecha = fn($f) => $f ? Carbon::parse($f)->format('d/m/Y') : '—';
    $v = fn($x) => $x ?? '—';

   
    $row = 2;
    foreach ($expedientes as $i => $e) {
        $sheet->setCellValue('A'.$row, $i + 1);
        $sheet->setCellValue('B'.$row, $v($e->nExpediente));
        $sheet->setCellValue('C'.$row, $fmtFecha(optional($e->autorizacion)->fecha_ingreso));
        $sheet->setCellValue('D'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_recep_dc));
        $sheet->setCellValue('E'.$row, $v(optional($e->persona)->nombre_completos));
        $sheet->setCellValue('F'.$row, $v(optional($e->persona)->ruc));
        $sheet->setCellValue('G'.$row, $v(optional($e->local)->nombre_comercial));
        $sheet->setCellValue(
            'H'.$row,
            $v(optional($e->local->direccion)->nombre_via . ' ' . ($e->local->nMunicipal ?? ''))
        );
        $sheet->setCellValue('I'.$row, $v(optional($e->local->direccion->sector)->nombre));
        $sheet->setCellValue('J'.$row, $v(optional($e->local)->giro_autorizado));
        $sheet->setCellValue('K'.$row, $v(optional($e->local)->area));
        $sheet->setCellValue('L'.$row, $v(optional($e->controlExpediente)->aforo));
        $sheet->setCellValue('M'.$row, $v(optional($e->local->tipoRiesgo)->nombre));
        $sheet->setCellValue('N'.$row, $v(optional($e->controlExpediente)->tipo_informe_itse));
        $sheet->setCellValue('O'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_acta_itse));
        $sheet->setCellValue('P'.$row, $v(optional($e->controlExpediente)->resultado));
        $sheet->setCellValue('Q'.$row, $v(optional($e->controlExpediente)->num_informe_defensa_civil));
        $sheet->setCellValue('R'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_informe_defensa_civil));
        $sheet->setCellValue('S'.$row, $v(optional($e->controlExpediente)->num_resolucion_dc));
        $sheet->setCellValue('T'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_resolucion_dc));
        $sheet->setCellValue('U'.$row, $v(optional($e->controlExpediente)->num_certificado_dc));
        $sheet->setCellValue('V'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_cert_dc));
        $sheet->setCellValue('W'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_renovacion));
        $sheet->setCellValue('X'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_caducidad));
        $sheet->setCellValue('Y'.$row, $v(optional($e->controlExpediente)->notificado));
        $sheet->setCellValue('Z'.$row, $fmtFecha(optional($e->controlExpediente)->fecha_entrega_cert));
        $sheet->setCellValue('AA'.$row, $v(optional($e->controlExpediente)->estado));
        $sheet->setCellValue('AB'.$row, $v(optional($e->controlExpediente)->observacion));

        $row++;
    }

  
    $writer = new Xlsx($spreadsheet);
    $fileName = 'expedientes_defensa_civil.xlsx';

    return new StreamedResponse(function () use ($writer) {
        $writer->save('php://output');
    }, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => "attachment;filename=\"$fileName\"",
        'Cache-Control' => 'max-age=0'
    ]);
}

    public function index()
    {
        return view('defensa_civil');
    }

    public function listar()
    {
        $datos = DetalleLicencia::with(['persona', 'local'])
    ->get()
    ->map(function($item) {
        return [
            'nsobre' => $item->nsobre ?? '',
            'ruc' => $item->persona->ruc ?? '',
            'titular' => $item->persona->nombre_completos ?? '',
            'nombre_comercial' => $item->local->nombre_comercial ?? '',
            'idDetalle' => $item->idDetalle
        ];
    });

return response()->json(['data' => $datos]);

    }




public function listarExpedientes()
{
    $expedientes = DetalleLicencia::with([
        'persona',
        'local.direccion.sector',
        'local.tipoRiesgo',
        'autorizacion',
        'controlExpediente'
    ])
    ->get()
    ->map(function ($item, $index) {
        
        $formatearFecha = function ($fecha) {
            return $fecha ? Carbon::parse($fecha)->format('d/m/Y') : '—';
        };

        
        $vacio = function ($valor) {
            return $valor ?? '—';
        };

        return [
            'idDetalle' => $item->idDetalle,
            'idControlExpediente' => optional($item->controlExpediente)->idControlExpediente,
            'numero' => $index + 1,
            'nExpediente' => $vacio($item->nExpediente),
            'fecha_expediente' => $formatearFecha(optional($item->autorizacion)->fecha_ingreso),
            'fecha_recep_dc' => $formatearFecha(optional($item->controlExpediente)->fecha_recep_dc),
            'representante_legal' => $vacio(optional($item->persona)->nombre_completos),
            'ruc' => $vacio(optional($item->persona)->ruc),
            'nombre_comercial' => $vacio(optional($item->local)->nombre_comercial),
            'direccion' => $vacio(optional($item->local->direccion)->nombre_via . ' ' . ($item->local->nMunicipal ?? '')),
            'sector' => $vacio(optional($item->local->direccion->sector)->nombre),
            'giro' => $vacio(optional($item->local)->giro_autorizado),
            'area' => $vacio(optional($item->local)->area),
            'foro' => $vacio(optional($item->controlExpediente)->aforo),
            'riesgo' => $vacio(optional($item->local->tipoRiesgo)->nombre),
            'itse' => $vacio(optional($item->controlExpediente)->tipo_informe_itse),
            'fecha_itse' => $formatearFecha(optional($item->controlExpediente)->fecha_acta_itse),
            'resultado' => $vacio(optional($item->controlExpediente)->resultado),
            'n_informe_dc' => $vacio(optional($item->controlExpediente)->num_informe_defensa_civil),
            'fecha_informe_dc' => $formatearFecha(optional($item->controlExpediente)->fecha_informe_defensa_civil),
            'n_resolucion_dc' => $vacio(optional($item->controlExpediente)->num_resolucion_dc),
            'fecha_resolucion_dc' => $formatearFecha(optional($item->controlExpediente)->fecha_resolucion_dc),
            'n_certificado_dc' => $vacio(optional($item->controlExpediente)->num_certificado_dc),
            'fecha_cert_dc' => $formatearFecha(optional($item->controlExpediente)->fecha_cert_dc),
            'fecha_renovacion' => $formatearFecha(optional($item->controlExpediente)->fecha_renovacion),
            'fecha_caducidad' => $formatearFecha(optional($item->controlExpediente)->fecha_caducidad),
            'notificado' => $vacio(optional($item->controlExpediente)->notificado),
            'fecha_entrega_cert' => $formatearFecha(optional($item->controlExpediente)->fecha_entrega_cert),
            'estado' => $vacio(optional($item->controlExpediente)->estado),
            'observacion' => $vacio(optional($item->controlExpediente)->observacion),
        ];
    });

    return response()->json(['data' => $expedientes]);
}



public function editarExpediente(Request $request)
{
    try {
        
        $control = ControlExpediente::find($request->idControlExpediente);

        
        if (!$control) {
            $control = new ControlExpediente();
            $control->save();

            
            if ($request->has('idDetalle')) {
                $detalle = DetalleLicencia::find($request->idDetalle);
                if ($detalle) {
                    $detalle->idControlExpediente = $control->idControlExpediente;
                    $detalle->save();
                }
            }
        }

        
        $control->update([
            'fecha_recep_dc' => $request->fecha_recep_dc,
            'aforo' => $request->aforo,
            'tipo_informe_itse' => $request->tipo_informe_itse,
            'fecha_acta_itse' => $request->fecha_acta_itse,
            'resultado' => $request->resultado,
            'num_informe_defensa_civil' => $request->n_informe_dc,
            'fecha_informe_defensa_civil' => $request->fecha_informe_dc,
            'num_resolucion_dc' => $request->n_resolucion_dc,
            'fecha_resolucion_dc' => $request->fecha_resolucion_dc,
            'num_certificado_dc' => $request->n_certificado_dc,
            'fecha_cert_dc' => $request->fecha_cert_dc,
            'fecha_renovacion' => $request->fecha_renovacion,
            'fecha_caducidad' => $request->fecha_caducidad,
            'notificado' => $request->notificado,
            'fecha_entrega_cert' => $request->fecha_entrega_cert,
            'estado' => $request->estado,
            'observacion' => $request->observacion,
        ]);

        return response()->json([
            'message' => 'Datos guardados correctamente.',
            'idControlExpediente' => $control->idControlExpediente
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al actualizar: ' . $e->getMessage()
        ], 500);
    }
}





// --------------------------------------------------------------------------------------------------------
public function subirPDF(Request $request)
{
    $request->validate([
        'idDetalle' => 'required|exists:detalle_licencias,idDetalle',
        'certificado_pdf' => 'required|mimes:pdf|max:2048',
        'resolucion_pdf' => 'required|mimes:pdf|max:2048',
        'fecha_emision' => 'required|date',
        'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
    ]);

    
    $detalle = DetalleLicencia::with(['persona', 'local'])->findOrFail($request->idDetalle);
    $persona = $detalle->persona;
    $local = $detalle->local;

    if (!$persona || !$local) {
        return response()->json([
            'success' => false,
            'message' => 'No se encontró la persona o el local asociado a este detalle.'
        ], 404);
    }

    $nombre = strtoupper(str_replace(' ', '_', $persona->nombre_completos));
    $ruc = $persona->ruc ?? 'SINRUC';
    $nombreLocal = strtoupper(str_replace(' ', '_', $local->nombre_comercial));

   
    $documentosPrevios = Documento::where('idDetalle', $detalle->idDetalle)->count();

    
    $version = $documentosPrevios > 0 ? "_MODIFICADO_V{$documentosPrevios}" : "";

    $certificadoNombre = "certificado_{$nombre}_{$ruc}_{$nombreLocal}{$version}.pdf";
    $resolucionNombre  = "resolucion_{$nombre}_{$ruc}_{$nombreLocal}{$version}.pdf";

    $certificadoPath = $request->file('certificado_pdf')->storeAs('documentos', $certificadoNombre, 'public');
    $resolucionPath = $request->file('resolucion_pdf')->storeAs('documentos', $resolucionNombre, 'public');

    Documento::create([
        'idDetalle' => $detalle->idDetalle,
        'certificado_pdf' => $certificadoPath,
        'resolucion_pdf' => $resolucionPath,
        'fecha_emision' => $request->fecha_emision,
        'fecha_vencimiento' => $request->fecha_vencimiento,
        'fecha_subida' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'PDFs subidos correctamente'
    ]);
}




public function verPDF($filename)
{
    $path = storage_path('app/public/documentos/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
}

public function verDocumentos($idDetalle)
{
    $detalle = DetalleLicencia::with('local', 'documentos')->findOrFail($idDetalle);

    $ultimoDocumento = $detalle->documentos()->latest('fecha_subida')->first();

    if (!$ultimoDocumento) {
        return response()->json(['error' => 'No se encontraron documentos'], 404);
    }

    return response()->json([
        'local'       => $detalle->local->nombre_comercial ?? 'SIN LOCAL',
        'certificado' => route('ver.pdf', basename($ultimoDocumento->certificado_pdf)),
        'resolucion'  => route('ver.pdf', basename($ultimoDocumento->resolucion_pdf)),
    ]);
}







public function generarCertificado(Request $request)
{
    try {
        
        $templatePath = public_path('plantillas/PLANTILLA_CERTIFICADO_MDP_2025.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'No se encontró la plantilla del certificado.');
        }

        
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor->setValue('nCertificado', $request->nCertificado);
        $templateProcessor->setValue('nExpediente', $request->nExpediente);
        $templateProcessor->setValue('razonSocial', $request->razonSocial);
        $templateProcessor->setValue('nombreComercial', $request->nombreComercial);
        $templateProcessor->setValue('ubicacion', $request->ubicacion);
        $templateProcessor->setValue('solicitadoPor', $request->solicitadoPor);
        $templateProcessor->setValue('riesgo', $request->riesgo);
        $templateProcessor->setValue('ubicacionNegocio', $request->ubicacionNegocio);
        $templateProcessor->setValue('area', $request->area);
        $templateProcessor->setValue('giroActividad', $request->giroActividad);
        $templateProcessor->setValue('nCapacidad', $request->nCapacidad);
        $templateProcessor->setValue('nRes', $request->nRes);
        $templateProcessor->setValue('fechaExpedicion', $request->fechaExpedicion);
        $templateProcessor->setValue('fechaRenovacion', $request->fechaRenovacion);
        $templateProcessor->setValue('fechaCaducidad', $request->fechaCaducidad);

        
        $ruc = preg_replace('/[^0-9]/', '', $request->nExpediente ?? time()); 
        $storageDir = storage_path('app/public/certificados');
        if (!file_exists($storageDir)) mkdir($storageDir, 0777, true);

        $docxPath = "{$storageDir}/Certificado_{$ruc}.docx";
        $pdfPath  = "{$storageDir}/Certificado_{$ruc}.pdf";

        $templateProcessor->saveAs($docxPath);

        
        $sofficePath = '"C:\Program Files\LibreOffice\program\soffice.exe"';
        $userProfileDir = storage_path('app/libreoffice-profile');

        if (!file_exists($userProfileDir)) {
            mkdir($userProfileDir, 0777, true);
        }

        
        $command = "{$sofficePath} --headless --norestore --nolockcheck --nofirststartwizard "
            . "-env:UserInstallation=file:///" . str_replace('\\', '/', $userProfileDir) . " "
            . "--convert-to pdf --outdir \"{$storageDir}\" \"{$docxPath}\"";

        exec($command . " 2>&1", $output, $returnCode);

        if (!file_exists($pdfPath)) {
            throw new \Exception("Error al generar PDF: " . implode("\n", $output));
        }

        
        if (file_exists($docxPath)) {
            unlink($docxPath);
        }

             return response()->download($pdfPath, "Certificado-{$request->nCertificado}-MDP-GSM.pdf", [
            'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);


    } catch (\Exception $e) {
        return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
    }
}


public function generarResolucion(Request $request)
{
    try {
        $templatePath = public_path('plantillas/PLANTILLA_RESOLUCION_MDP_2025.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'No se encontró la plantilla de resolución.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        
        $templateProcessor->setValue('fechaResolucion', $request->fechaResolucion);
        $templateProcessor->setValue('nResolucion', $request->nResolucion);
        $templateProcessor->setValue('nExpediente', $request->nExpediente);
        $templateProcessor->setValue('presentadoPor', $request->presentadoPor);
        $templateProcessor->setValue('razonSocial', $request->razonSocial);
        $templateProcessor->setValue('nInforme', $request->nInforme);
        $templateProcessor->setValue('tipoRiesgo', $request->tipoRiesgo);
        $templateProcessor->setValue('giro', $request->giro);
        $templateProcessor->setValue('ubicacion', $request->ubicacion);
        $templateProcessor->setValue('area', $request->area);
        $templateProcessor->setValue('aforo', $request->aforo);
        $templateProcessor->setValue('nombreComercial', $request->nombreComercial);
        $templateProcessor->setValue('sector', $request->sector);

        
        $storageDir = storage_path('app/public/resoluciones');
        if (!file_exists($storageDir)) mkdir($storageDir, 0777, true);

        $fileName = "Resolucion_{$request->nResolucion}";
        $docxPath = "{$storageDir}/{$fileName}.docx";
        $pdfPath  = "{$storageDir}/{$fileName}.pdf";

        $templateProcessor->saveAs($docxPath);

        
        $sofficePath = '"C:\Program Files\LibreOffice\program\soffice.exe"';
        $userProfileDir = storage_path('app/libreoffice-profile');
        if (!file_exists($userProfileDir)) mkdir($userProfileDir, 0777, true);

        $command = "{$sofficePath} --headless --norestore --nolockcheck --nofirststartwizard "
            . "-env:UserInstallation=file:///" . str_replace('\\', '/', $userProfileDir) . " "
            . "--convert-to pdf --outdir \"{$storageDir}\" \"{$docxPath}\"";

        exec($command . " 2>&1", $output, $returnCode);

        if (!file_exists($pdfPath)) {
            throw new \Exception("Error al generar PDF: " . implode("\n", $output));
        }

        unlink($docxPath); 
        return response()->download($pdfPath, "Resolucion-{$request->nResolucion}-MDP-GSM.pdf", [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);

    } catch (\Exception $e) {
        return back()->with('error', 'Error al generar la Resolución: ' . $e->getMessage());
    }
}

   


}
