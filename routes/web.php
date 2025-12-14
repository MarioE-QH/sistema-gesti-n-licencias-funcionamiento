<?php
//MEQH-Contac-912595922
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\DefensaCivilController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MantenedorController;


Route::get('/ver-pdf/{filename}', [DefensaCivilController::class, 'verPDF'])->name('ver.pdf');
Route::get('/defensa-civil/ver-documentos/{ruc}', [DefensaCivilController::class, 'verDocumentos']);


Route::get('/', function () { return view('login');})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {


   // Route::get('/main', function () { return view('main');})->name('main');
        Route::get('/main', [MainController::class, 'index'])->name('main');
        Route::get('/ver-detalle/{idDetalle}', [MainController::class, 'verDetalle'])->name('ver.detalle');
        Route::get('/sin-documentos', [MainController::class, 'listarSinDocumentos'])->name('sinDocumentos');



    Route::middleware('role:ADMIN,ADMINISTRADOR')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/licencias', [AdminController::class, 'store'])->name('licencias.store');
        Route::put('/licencias/{id}', [AdminController::class, 'update'])->name('licencias.update');
        Route::delete('/licencias/{id}', [AdminController::class, 'destroy'])->name('licencias.destroy');
        Route::get('/licencias/exportar', [AdminController::class, 'exportar'])->name('licencias.exportar');
    });


    Route::middleware('role:ADMIN,DEFENSA_CIVIL')->group(function () {
          //Rout::get('/defensa_civil', function () {return view('defensa_civil');})->name('defensa_civil');
          Route::get('/defensa_civil', [DefensaCivilController::class, 'index'])->name('defensa_civil');
          Route::get('/defensa_civil/listar', [DefensaCivilController::class, 'listar'])->name('defensa_civil.listar');
          Route::post('/defensa_civil/subir-pdf', [DefensaCivilController::class, 'subirPDF'])->name('defensa_civil.subirPDF');
          Route::post('/defensa_civil/generar_certificado', [DefensaCivilController::class, 'generarCertificado'])->name('defensa_civil.generarCertificado');
          Route::post('/generar-resolucion', [DefensaCivilController::class, 'generarResolucion'])->name('generar.resolucion');
          Route::get('/defensa-civil/listar-expedientes', [DefensaCivilController::class, 'listarExpedientes']);
          Route::post('/defensa-civil/editar-expediente', [DefensaCivilController::class, 'editarExpediente']);
          Route::get('/expedientes/exportar', [DefensaCivilController::class, 'exportarExpedientes'])->name('expedientes.exportar');


    });

    Route::middleware('role:ADMIN')->group(function () {

        Route::get('/mantenedores', [MantenedorController::class, 'index'])->name('mantenedores.index');
        Route::get('/usuarios/data', [MantenedorController::class, 'data'])->name('usuarios.data');
        Route::post('/usuarios', [MantenedorController::class, 'store'])->name('usuarios.store');
        Route::post('/usuarios/{id}', [MantenedorController::class, 'update']);
        Route::delete('/usuarios/{id}', [MantenedorController::class, 'destroy']);

         
        Route::get('/sectores', [MantenedorController::class, 'indexSector'])->name('sectores.indexSector');
        Route::get('/sectores/data', [MantenedorController::class, 'listSector'])->name('sectores.data');
        Route::post('/sectores', [MantenedorController::class, 'storeSector'])->name('sectores.store');
        Route::post('/sectores/{id}', [MantenedorController::class, 'updateSector'])->name('sectores.update');
        Route::delete('/sectores/{id}', [MantenedorController::class, 'destroySector'])->name('sectores.destroy');


        Route::get('/direcciones', [MantenedorController::class, 'indexDirecciones'])->name('direcciones.indexDirecciones');
        Route::get('/direcciones/data', [MantenedorController::class, 'listDireccion'])->name('direcciones.data');
        Route::post('/direcciones', [MantenedorController::class, 'storeDireccion'])->name('direcciones.store');
        Route::post('/direcciones/{id}', [MantenedorController::class, 'updateDireccion']);
        Route::delete('/direcciones/{id}', [MantenedorController::class, 'destroyDireccion']);


        Route::get('/tiporiesgo', [MantenedorController::class, 'indexTipoRiesgo'])->name('tiporiesgo.indexTipoRiesgo');
        Route::get('/tiporiesgos/data', [MantenedorController::class, 'listTipoRiesgo'])->name('tiporiesgos.data');
        Route::post('/tiporiesgos', [MantenedorController::class, 'storeTipoRiesgo'])->name('tiporiesgos.store');
        Route::post('/tiporiesgos/{id}', [MantenedorController::class, 'updateTipoRiesgo']);
        Route::delete('/tiporiesgos/{id}', [MantenedorController::class, 'destroyTipoRiesgo']);
    });







});