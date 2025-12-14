<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\DetalleLicencia;
use App\Models\Sector;
use App\Models\TipoRiesgo;
use App\Models\Direccion;
use App\Models\Persona;
use App\Models\Autorizacion;
use App\Models\Local;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;



class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = DetalleLicencia::with([
            'persona',
            'local.direccion.sector',
            'local.tipoRiesgo',
            'autorizacion'
        ]);

        // filtros opcionales
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->whereHas('persona', function($q2) use ($s) {
                    $q2->where('nombre_completos','like',"%{$s}%")
                       ->orWhere('dni','like',"%{$s}%")
                       ->orWhere('ruc','like',"%{$s}%");
                })->orWhereHas('local', function($q3) use ($s) {
                    $q3->where('nombre_comercial','like',"%{$s}%");
                });
            });
        }

        if ($request->filled('estado')) {
            $val = $request->estado === '1' ? 1 : 0;
            $query->where('estado', $val);
        }

        if ($request->filled('sector')) {
            $query->whereHas('local.direccion', function($q) use ($request) {
                $q->where('idSector', $request->sector);
            });
        }

        $licencias = $query->orderBy('idDetalle', 'desc')->paginate(50)->withQueryString();
        $sectores = Sector::orderBy('nombre')->get();
        $riesgos  = TipoRiesgo::orderBy('nombre')->get(); 
        $direcciones = Direccion::orderBy('nombre_via')->get();

        return view('administracion', compact('licencias', 'sectores', 'riesgos', 'direcciones'));

    }


public function store(Request $request)
{
   
    $validated = $request->validate([
        'nombre_completos' => 'required|string|max:255',

        'dni'              => 'nullable|string|digits:8',
        'ruc'              => 'required|string|digits_between:10,11',

        'nombre_comercial' => 'required|string|max:255',
        'nMunicipal'       => 'required|string|max:50',
        'giro_autorizado'  => 'required|string|max:255',
        'area'             => 'required|numeric',

        'direccion'        => 'required|integer',
        'tipo_riesgo'      => 'required|integer',

        'nAutorizacion'    => 'required|string|max:50',
        'fecha_ingreso'    => 'required|date',
        'fecha_emision'    => 'required|date',

        'nExpediente'      => 'required|string|max:100',
        'nResolucion'      => 'required|string|max:100',
        'estado'           => 'required|boolean',

        'nsobre'           => 'nullable|string|max:6',
        'descripcion'      => 'nullable|string|max:500',
    ]);

    
    $validated['estado'] = $validated['estado'] ? 1 : 0;

    
    if (!empty($validated['nsobre'])) {
        $validated['nsobre'] = ltrim($validated['nsobre'], '0');
        $validated['nsobre'] = $validated['nsobre'] === '' ? null : (int)$validated['nsobre'];
    }

    
    $dniIngresado = !empty($validated['dni']) ? ltrim($validated['dni'], '0') : null;

    
    $personaPorRuc = Persona::where('ruc', $validated['ruc'])->first();
    $personaPorDni = $dniIngresado ? Persona::where('dni', $dniIngresado)->first() : null;

    if ($personaPorRuc) {

       
        if (strcasecmp(trim($personaPorRuc->nombre_completos), trim($validated['nombre_completos'])) !== 0) {
            return back()->withInput()->with('error',
                '⚠️ El nombre no coincide con el registrado para este RUC: ' . $personaPorRuc->nombre_completos
            );
        }

        
        $dniGuardado = $personaPorRuc->dni ? ltrim($personaPorRuc->dni, '0') : null;

        if ($dniIngresado) {
            if ($dniGuardado && $dniGuardado !== $dniIngresado) {
                return back()->withInput()->with('error',
                    '⚠️ El DNI ingresado no coincide con el registrado para este RUC.'
                );
            }

            if (!$dniGuardado) {
                $personaPorRuc->dni = $dniIngresado;
                $personaPorRuc->save();
            }
        }

        $persona = $personaPorRuc;
    }
    else {

        if ($personaPorDni) {
            return back()->withInput()->with('error',
                '⚠️ Este ruc no coincide con el dni registrado'
            );
        }

        $persona = Persona::create([
            'nombre_completos' => $validated['nombre_completos'],
            'dni'              => $dniIngresado,
            'ruc'              => $validated['ruc'],
        ]);
    }

    
    $autorizacion = Autorizacion::create([
        'nAutorizacion'  => $validated['nAutorizacion'],
        'fecha_ingreso'  => $validated['fecha_ingreso'],
        'fecha_emision'  => $validated['fecha_emision'],
    ]);

    
    $local = Local::create([
        'nombre_comercial' => $validated['nombre_comercial'],
        'nMunicipal'       => $validated['nMunicipal'],
        'giro_autorizado'  => $validated['giro_autorizado'],
        'area'             => $validated['area'],
        'idDireccion'      => $validated['direccion'],
        'idTipoRiesgo'     => $validated['tipo_riesgo'],
    ]);

    
    $detalle = DetalleLicencia::create([
        'nExpediente'    => $validated['nExpediente'],
        'nResolucion'    => $validated['nResolucion'],
        'estado'         => $validated['estado'],
        'idPersona'      => $persona->idPersona,
        'idLocal'        => $local->idLocal,
        'idAutorizacion' => $autorizacion->idAutorizacion,
        'nsobre'         => $validated['nsobre'] ?? null,
        'descripcion'    => $validated['descripcion'] ?? null,
    ]);

    return back()->with('success', '✔ Licencia creada correctamente');
}





    
public function update(Request $request, $id) 
{
    
    $validated = $request->validate([
        'nombre_completos' => 'required|string|max:150',
        'dni'              => 'nullable|digits:8',   
        'ruc'              => 'required|numeric',
        'nombre_comercial' => 'required|string|max:150',
        'direccion'        => 'required|integer',
        'sector'           => 'required|integer',
        'nMunicipal'       => 'nullable|string|max:50',
        'tipo_riesgo'      => 'required|integer',
        'nAutorizacion'    => 'required|string|max:6',
        'fecha_ingreso'    => 'required|date',
        'fecha_emision'    => 'required|date',
        'nExpediente'      => 'required|string|max:20',
        'nResolucion'      => 'required|string|max:20',
        'estado'           => 'required|boolean',
        'giro_autorizado'  => 'required|string|max:150',
        'area'             => 'required|numeric|min:1',
        'nsobre'           => 'nullable|string|max:6',
        'descripcion'      => 'nullable|string|max:500',
    ]);

    
    if (!empty($validated['nsobre'])) {
        $validated['nsobre'] = ltrim($validated['nsobre'], '0');
        $validated['nsobre'] = $validated['nsobre'] === '' ? null : (int)$validated['nsobre'];
    }

    
    $detalle = DetalleLicencia::with(['persona','local.direccion','autorizacion'])->findOrFail($id);

    
    $dniLimpio = !empty($validated['dni']) ? ltrim($validated['dni'], '0') : null;

    
    $detalle->persona->update([
        'nombre_completos' => $validated['nombre_completos'],
        'dni'              => $dniLimpio,   
        'ruc'              => $validated['ruc'],
    ]);

    
    $detalle->local->update([
        'nombre_comercial' => $validated['nombre_comercial'],
        'nMunicipal'       => $validated['nMunicipal'],
        'giro_autorizado'  => $validated['giro_autorizado'],
        'area'             => $validated['area'],
        'idDireccion'      => $validated['direccion'],
        'idTipoRiesgo'     => $validated['tipo_riesgo'],
    ]);

   
    $detalle->local->direccion->update([
        'idSector' => $validated['sector'],
    ]);

   
    $detalle->autorizacion->update([
        'nAutorizacion' => $validated['nAutorizacion'],
        'fecha_ingreso' => $validated['fecha_ingreso'],
        'fecha_emision' => $validated['fecha_emision'],
    ]);

    
    $detalle->update([
        'nExpediente'  => $validated['nExpediente'],
        'nResolucion'  => $validated['nResolucion'],
        'estado'       => (int) $validated['estado'],
        'nsobre'       => $validated['nsobre'] ?? null,
        'descripcion'  => $validated['descripcion'] ?? null,
    ]);

    return redirect()->route('admin.index')->with('success', 'Licencia actualizada correctamente.');
}



public function destroy($id)
{
   
    $detalle = DetalleLicencia::with(['local', 'autorizacion'])->findOrFail($id);

    
    $local = $detalle->local;
    $autorizacion = $detalle->autorizacion;
    $persona = $detalle->persona;

    
    $detalle->delete();

    
    if ($local) {
        $local->delete();
    }

    if ($autorizacion) {
        $autorizacion->delete();
    }

    
    if ($persona && $persona->detalles()->count() === 0) {
        $persona->delete();
    }

    return redirect()->back()->with('success', 'Licencia eliminada correctamente');
}



public function exportar(Request $request)
{
    $query = DetalleLicencia::with([
        'persona',
        'local.direccion.sector',
        'local.tipoRiesgo',
        'autorizacion'
    ]);

    
    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereHas('autorizacion', function($q) use ($request) {
            $q->whereBetween('fecha_ingreso', [$request->fecha_inicio, $request->fecha_fin]);
        });
    }

    
    if ($request->filled('nsobre')) {
        $query->where('nsobre', $request->nsobre);
    }

   
    if ($request->filled('sobre_inicio') && $request->filled('sobre_fin')) {
        $query->whereBetween('nsobre', [$request->sobre_inicio, $request->sobre_fin]);
    }

    $licencias = $query->orderBy('idDetalle', 'desc')->get();

    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    
    $headers = [
        'N° SOBRE', 'RUC', 'DNI', 'TITULAR', 'DIRECCIÓN',
        'SECTOR', 'NOMBRE COMERCIAL', 'GIRO', 'ÁREA (m²)',
        'RIESGO', 'N° AUTORIZACIÓN', 'FECHA INGRESO',
        'FECHA EMISIÓN', 'N° EXPEDIENTE', 'N° RESOLUCIÓN',
        'ESTADO', 'Descripción'
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col.'1', $header);
        $col++;
    }

    
    $row = 2;
    foreach ($licencias as $l) {
        $sheet->setCellValue('A'.$row, $l->nsobre ?? '-');
        $sheet->setCellValue('B'.$row, $l->persona->ruc ?? '-');
        $sheet->setCellValue('C'.$row, $l->persona->dni ?? '-');
        $sheet->setCellValue('D'.$row, $l->persona->nombre_completos ?? '-');
        $sheet->setCellValue('E'.$row, ($l->local->direccion->nombre_via ?? '-') . ' ' . ($l->local->nMunicipal ?? ''));
        $sheet->setCellValue('F'.$row, $l->local->direccion->sector->nombre ?? '-');
        $sheet->setCellValue('G'.$row, $l->local->nombre_comercial ?? '-');
        $sheet->setCellValue('H'.$row, $l->local->giro_autorizado ?? '-');
        $sheet->setCellValue('I'.$row, $l->local->area ?? '-');
        $sheet->setCellValue('J'.$row, $l->local->tipoRiesgo->nombre ?? '-');
        $sheet->setCellValue('K'.$row, $l->autorizacion->nAutorizacion ?? '-');
        $sheet->setCellValue('L'.$row, optional($l->autorizacion)->fecha_ingreso);
        $sheet->setCellValue('M'.$row, optional($l->autorizacion)->fecha_emision);
        $sheet->setCellValue('N'.$row, $l->nExpediente);
        $sheet->setCellValue('O'.$row, $l->nResolucion);
        $sheet->setCellValue('P'.$row, $l->estado ? 'Activo' : 'Inactivo');
        $sheet->setCellValue('Q'.$row, $l->descripcion ?? '-');
        $row++;
    }

    
    $writer = new Xlsx($spreadsheet);
    $fileName = 'licencias.xlsx';

    return new StreamedResponse(function() use ($writer) {
        $writer->save('php://output');
    }, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => "attachment;filename=\"$fileName\"",
        'Cache-Control' => 'max-age=0'
    ]);
}




   
}
