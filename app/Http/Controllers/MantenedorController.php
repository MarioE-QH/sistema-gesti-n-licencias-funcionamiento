<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sector;
use App\Models\Direccion;
use App\Models\TipoRiesgo;
use Illuminate\Support\Facades\Hash;

class MantenedorController extends Controller
{

    public function index()
    {
        return view('mantenedores');
    }
    public function data()
    {
        $usuarios = User::select('id', 'name', 'email', 'role')->get();

        return response()->json([
            'data' => $usuarios
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|string',
            'password' => 'required|min:4'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }


    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $usuario->id,
            'role'     => 'required|string',
            'password' => 'nullable|min:4'
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return back()->with('success', 'Usuario actualizado correctamente.');
    }


    public function destroy($id)
    {
        $usuario = User::find($id);

        if ($usuario) {
            $usuario->delete();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        }

        return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
    }





    public function indexSector()
    {
        return view('sectores');
    }



    public function listSector()
    {
        $sectores = Sector::select('idSector', 'nombre')->get();

        return response()->json([
            'data' => $sectores
        ]);
    }

    
    public function storeSector(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:sector,nombre'
        ]);

        $sector = Sector::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'message' => 'Sector creado correctamente',
            'sector' => $sector
        ]);
    }

   
    public function updateSector(Request $request, $id)
    {
        $sector = Sector::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255|unique:sector,nombre,' . $sector->idSector . ',idSector'
        ]);

        $sector->nombre = $request->nombre;
        $sector->save();

        return response()->json([
            'message' => 'Sector actualizado correctamente',
            'sector' => $sector
        ]);
    }

   
    public function destroySector($id)
    {
        $sector = Sector::findOrFail($id);
        $sector->delete();

        return response()->json([
            'message' => 'Sector eliminado correctamente'
        ]);
    }


    public function indexDirecciones()
    {
        return view('direcciones');
    }


    
    public function listDireccion()
    {
        $direcciones = Direccion::with('sector:idSector,nombre')
            ->select('idDireccion', 'nombre_via', 'idSector')
            ->get();

        $data = $direcciones->map(function ($d) {
            return [
                'idDireccion' => $d->idDireccion,
                'nombre_via' => $d->nombre_via,
                'sector' => $d->sector->nombre ?? '',
            ];
        });

        return response()->json(['data' => $data]);
    }

   
    public function storeDireccion(Request $request)
    {
        $request->validate([
            'nombre_via' => 'required|string|max:255',
            'idSector' => 'required|exists:sector,idSector',
        ]);

        $direccion = Direccion::create([
            'nombre_via' => $request->nombre_via,
            'idSector' => $request->idSector
        ]);

        return response()->json([
            'message' => 'Dirección creada correctamente',
            'direccion' => $direccion
        ]);
    }

   
    public function updateDireccion(Request $request, $id)
    {
        $direccion = Direccion::findOrFail($id);

        $request->validate([
            'nombre_via' => 'required|string|max:255',
            'idSector' => 'required|exists:sector,idSector',
        ]);

        $direccion->nombre_via = $request->nombre_via;
        $direccion->idSector = $request->idSector;
        $direccion->save();

        return response()->json([
            'message' => 'Dirección actualizada correctamente',
            'direccion' => $direccion
        ]);
    }

    
    public function destroyDireccion($id)
    {
        $direccion = Direccion::findOrFail($id);
        $direccion->delete();

        return response()->json([
            'message' => 'Dirección eliminada correctamente'
        ]);
    }


    public function indexTipoRiesgo()
    {
        return view('tiporiesgo');
    }

    
    public function listTipoRiesgo()
    {
        $tipos = TipoRiesgo::select('idTipoRiesgo', 'nombre')->get();

        return response()->json(['data' => $tipos]);
    }

    
    public function storeTipoRiesgo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tiporiesgo,nombre'
        ]);

        $tipo = TipoRiesgo::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'message' => 'Tipo de riesgo creado correctamente',
            'tipo' => $tipo
        ]);
    }

    
    public function updateTipoRiesgo(Request $request, $id)
    {
        $tipo = TipoRiesgo::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255|unique:tiporiesgo,nombre,' . $tipo->idTipoRiesgo . ',idTipoRiesgo'
        ]);

        $tipo->nombre = $request->nombre;
        $tipo->save();

        return response()->json([
            'message' => 'Tipo de riesgo actualizado correctamente',
            'tipo' => $tipo
        ]);
    }

    
    public function destroyTipoRiesgo($id)
    {
        $tipo = TipoRiesgo::findOrFail($id);
        $tipo->delete();

        return response()->json([
            'message' => 'Tipo de riesgo eliminado correctamente'
        ]);
    }
}
