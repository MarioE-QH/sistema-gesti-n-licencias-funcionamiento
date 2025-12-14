<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetalleLicencia;
use App\Models\Documento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
public function index(Request $request)
{
    
    $totalLicencias = DetalleLicencia::count();
    $licenciasActivas = DetalleLicencia::where('estado', 1)->count();
    $licenciasInactivas = DetalleLicencia::where('estado', 0)->count();

    $hoy = Carbon::today();
    $en7dias = Carbon::today()->addDays(7);

    
    $idsUltimosDocumentos = DB::table('documento')
        ->select(DB::raw('MAX(idDocumento) as id'))
        ->groupBy('idDetalle')
        ->pluck('id')
        ->toArray();

    
    if (empty($idsUltimosDocumentos)) {
        $porVencer = 0;
        $vencidas = 0;
    } else {
        $documentosRecientes = Documento::whereIn('idDocumento', $idsUltimosDocumentos);

        $porVencer = (clone $documentosRecientes)
            ->whereBetween('fecha_vencimiento', [$hoy, $en7dias])
            ->count();

        $vencidas = (clone $documentosRecientes)
            ->where('fecha_vencimiento', '<', $hoy)
            ->count();
    }

   
    $search = $request->input('search');
    $estado = $request->input('estado');
    $sector = $request->input('sector');

    
    $query = DB::table('documento as d')
        ->join('detalle_licencias as dl', 'd.idDetalle', '=', 'dl.idDetalle')
        ->join('persona as p', 'dl.idPersona', '=', 'p.idPersona')
        ->join('local as l', 'dl.idLocal', '=', 'l.idLocal')
        ->join('direccion as dir', 'l.idDireccion', '=', 'dir.idDireccion')
        ->join('sector as s', 'dir.idSector', '=', 's.idSector')
        ->whereIn('d.idDocumento', $idsUltimosDocumentos)
        ->select(
            'dl.idDetalle',
            'p.nombre_completos',
            'p.dni',
            'p.ruc',
            'l.nombre_comercial',
            'l.nMunicipal',
            'dir.nombre_via',
            's.nombre as nombre_sector',
            'dl.estado',
            'd.fecha_vencimiento',
            DB::raw("
                CASE 
                    WHEN d.fecha_vencimiento < CURDATE() THEN 'vencida'
                    WHEN d.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 'por_vencer'
                    ELSE 'activa'
                END AS estado_vencimiento
            ")
        );

    
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('p.nombre_completos', 'like', "%{$search}%")
              ->orWhere('l.nombre_comercial', 'like', "%{$search}%")
              ->orWhere('p.dni', 'like', "%{$search}%")
              ->orWhere('p.ruc', 'like', "%{$search}%");
        });
    }

    if ($estado) {
        switch ($estado) {
            case 'activo':
                $query->where('dl.estado', 1);
                break;
            case 'inactivo':
                $query->where('dl.estado', 0);
                break;
            case 'vencida':
                $query->where('d.fecha_vencimiento', '<', $hoy);
                break;
            case 'por_vencer':
                $query->whereBetween('d.fecha_vencimiento', [$hoy, $en7dias]);
                break;
        }
    }

    if ($sector) {
        $query->where('s.idSector', $sector);
    }

    $query->orderByDesc('d.fecha_vencimiento');

    
    $data = $query->paginate(9)->withQueryString();

    
    $sectores = DB::table('sector')->get();

    
    $faltantesLicencias = DB::table('detalle_licencias as dl')
        ->leftJoin('documento as d', 'dl.idDetalle', '=', 'd.idDetalle')
        ->whereNull('d.idDocumento')
        ->count();

   
    return view('main', compact(
        'totalLicencias',
        'licenciasActivas',
        'licenciasInactivas',
        'porVencer',
        'vencidas',
        'data',
        'sectores',
        'faltantesLicencias'
    ));
}


public function listarSinDocumentos(Request $request)
{
    $query = DB::table('detalle_licencias as dl')
        ->join('persona as p', 'dl.idPersona', '=', 'p.idPersona')
        ->join('local as l', 'dl.idLocal', '=', 'l.idLocal')
        ->join('direccion as dir', 'l.idDireccion', '=', 'dir.idDireccion')
        ->join('sector as s', 'dir.idSector', '=', 's.idSector')
        ->leftJoin('documento as d', 'dl.idDetalle', '=', 'd.idDetalle')
        ->whereNull('d.idDetalle')
        ->select(
            'p.nombre_completos',
            'p.dni',
            'p.ruc',
            'l.nombre_comercial',
            'dl.descripcion',
            'l.nMunicipal',
            'dir.nombre_via',
            's.nombre as nombre_sector',
            'dl.estado'
        )
        ->orderBy('l.nombre_comercial', 'asc');

    
    if ($request->filled('ruc')) {
        $query->where('p.ruc', 'like', '%' . $request->ruc . '%');
    }

    $sinDocumentos = $query->paginate(10);

    
    $html = '
    <table class="table table-sm table-hover align-middle text-nowrap">
      <thead class="table-info small text-uppercase">
        <tr>
          <th>Nombre Comercial</th>
          <th>Propietario</th>
          <th>DNI</th>
          <th>RUC</th>
          <th>Dirección</th>
          <th>Sector</th>
          <th>Observación</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>';

    if ($sinDocumentos->isEmpty()) {
        $html .= '
          <tr>
            <td colspan="8" class="text-center text-muted py-3">
              No hay resultados.
            </td>
          </tr>';
    } else {
        foreach ($sinDocumentos as $item) {
            $estado = $item->estado
              ? '<span class="badge bg-info bg-opacity-50 text-dark">Sin documento</span>'
              : '<span class="badge bg-danger bg-opacity-50 text-dark">Inactivo</span>';

            $html .= "
              <tr>
                <td>{$item->nombre_comercial}</td>
                <td>{$item->nombre_completos}</td>
                <td>{$item->dni}</td>
                <td>{$item->ruc}</td>
                <td>{$item->nombre_via} {$item->nMunicipal}</td>
                <td>{$item->nombre_sector}</td>
                <td>{$item->descripcion}</td>
                <td>{$estado}</td>
              </tr>";
        }
    }

    $html .= '</tbody></table>';

    
    $html .= '<div class="d-flex justify-content-center mt-3">'
      . $sinDocumentos->withQueryString()->links('pagination::bootstrap-5')
      . '</div>';


    return response($html);
}


public function verDetalle($idDetalle)
{
    try {
        
        $detalleLocal = DB::table('detalle_licencias as dl')
            ->join('persona as p', 'dl.idPersona', '=', 'p.idPersona')
            ->join('local as l', 'dl.idLocal', '=', 'l.idLocal')
            ->join('direccion as di', 'l.idDireccion', '=', 'di.idDireccion')
            ->join('sector as s', 'di.idSector', '=', 's.idSector')
            ->leftJoin('autorizacion as a', 'dl.idAutorizacion', '=', 'a.idAutorizacion')
            ->where('dl.idDetalle', $idDetalle)
            ->select(
                'dl.idDetalle as idDetalle',
                'l.nombre_comercial',
                'l.giro_autorizado',
                'l.area',
                'p.nombre_completos as propietario',
                'p.dni',
                'p.ruc',
                'di.nombre_via',
                'l.nMunicipal',
                's.nombre as sector',
                'dl.nsobre as numero_sobre',
                'a.fecha_emision as fecha_autorizacion',
                'dl.descripcion',
                'dl.estado'
            )
            ->first();

        if (!$detalleLocal) {
            return response()->json(['error' => 'No se encontró el detalle del local.'], 404);
        }

        
        $docLocal = DB::table('documento')
            ->where('idDetalle', $idDetalle)
            ->orderByDesc('idDocumento')
            ->select(
                'idDocumento',
                'idDetalle',
                'fecha_emision',
                'fecha_vencimiento',
                'certificado_pdf',
                'resolucion_pdf'
            )
            ->first();

        
        $docRuc = null;
        if (!$docLocal) {
            $docRuc = DB::table('documento as d')
                ->join('detalle_licencias as dl', 'd.idDetalle', '=', 'dl.idDetalle')
                ->join('persona as p', 'dl.idPersona', '=', 'p.idPersona')
                ->where('p.ruc', $detalleLocal->ruc)
                ->orderByDesc('d.idDocumento')
                ->select(
                    'd.idDocumento',
                    'd.idDetalle',
                    'd.fecha_emision',
                    'd.fecha_vencimiento',
                    'd.certificado_pdf',
                    'd.resolucion_pdf'
                )
                ->first();
        }

        
        $document = $docLocal ?? $docRuc;

        
        if (!$document) {
            $document = (object)[
                'idDocumento' => null,
                'idDetalle' => $detalleLocal->idDetalle,
                'fecha_emision' => null,
                'fecha_vencimiento' => null,
                'certificado_pdf' => null,
                'resolucion_pdf' => null,
                'document_source' => 'none'
            ];
        } else {
            
            $document->document_source = $docLocal ? 'local' : 'ruc';
        }

        
        $estado_texto = 'Desconocido';
        if ($document->fecha_vencimiento) {
            
            $fv = $document->fecha_vencimiento;
            $today = new \DateTime('today');
            try {
                $fechaVenc = new \DateTime($fv);
                $diffDays = (int)$today->diff($fechaVenc)->format('%r%a'); 
                if ($fechaVenc < $today) {
                    $estado_texto = 'Licencia Vencida';
                } elseif ($diffDays <= 7) {
                    $estado_texto = 'Por Vencer';
                } else {
                    $estado_texto = ($detalleLocal->estado == 1) ? 'Licencia Activa' : 'Licencia Inactiva';
                }
            } catch (\Exception $ex) {
                $estado_texto = ($detalleLocal->estado == 1) ? 'Licencia Activa' : 'Licencia Inactiva';
            }
        } else {
            $estado_texto = ($detalleLocal->estado == 1) ? 'Licencia Activa' : 'Licencia Inactiva';
        }

        
        $respuesta = (object) array_merge(
            (array) $detalleLocal,
            [
                'fecha_certificado' => $document->fecha_emision ?? null,
                'fecha_vencimiento' => $document->fecha_vencimiento ?? null,
                'certificado_pdf' => $document->certificado_pdf ?? null,
                'resolucion_pdf' => $document->resolucion_pdf ?? null,
                'document_source' => $document->document_source ?? 'none',
                'estado_texto' => $estado_texto
            ]
        );

        return response()->json($respuesta);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}
