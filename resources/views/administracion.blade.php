@extends('layouts.principal')
@section('content')

<!-- Modal Exportar -->
<div class="modal fade" id="modalExportar" tabindex="-1" aria-labelledby="modalExportarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('licencias.exportar') }}" method="GET">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalExportarLabel">Exportar Licencias</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
          </div>
          <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha Fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
          </div>
          <div class="mb-3">
            <label for="nsobre" class="form-label">N° Sobre</label>
            <input type="text" class="form-control" id="nsobre" name="nsobre" placeholder="Ej: 12345">
          </div>
          <div class="mb-3">
            <label for="sobre_inicio" class="form-label">Desde N° Sobre</label>
            <input type="number" name="sobre_inicio" id="sobre_inicio" class="form-control">
          </div>

          <div class="mb-3">
            <label for="sobre_fin" class="form-label">Hasta N° Sobre</label>
            <input type="number" name="sobre_fin" id="sobre_fin" class="form-control">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Exportar Excel</button>
        </div>
      </div>
    </form>
  </div>
</div>



<!-- Header compacto sobre la tabla -->
<div class="d-flex align-items-center justify-content-between px-3 py-2 bg-white border rounded-top shadow-sm">

  <div>
    <h5 class="mb-0 text-dark fw-semibold" style="font-size:1rem;">Panel de Administración</h5>
    <small class="text-muted">Gestiona las licencias de funcionamiento del sistema</small>
  </div>
  <div class="d-flex align-items-center gap-2">
    <a href="#" class="btn btn-outline-secondary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalExportar">
      <i class="fa-solid fa-file-export fa-sm me-2"></i>
      Exportar
    </a>
    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center"
      data-bs-toggle="modal" data-bs-target="#modalNuevo">
      <i class="fa-solid fa-plus fa-sm me-2"></i>
      Nuevo Local
    </button>

  </div>
</div>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="card border-0 shadow-sm mt-3">
  <div class="table-responsive">
    <table id="miTabla" class="table table-hover align-middle table-striped">
      <thead class="table-light text-secondary">
        <tr>
          <th>N° SOBRE</th>
          <th>RUC</th>
          <th>DNI</th>
          <th>TITULAR</th>
          <th>DIRECCIÓN</th>
          <th>SECTOR</th>
          <th>NOMBRE COMERCIAL</th>
          <th>GIRO</th>
          <th>ÁREA (m²)</th>
          <th>RIESGO</th>
          <th>N° AUTORIZACIÓN</th>
          <th>FECHA INGRESO</th>
          <th>FECHA EMISIÓN</th>
          <th>N° EXPEDIENTE</th>
          <th>N° RESOLUCIÓN</th>
          <th>ESTADO</th>
          <th>Descripción</th>
          <th class="text-center">ACCIONES</th>
        </tr>
      </thead>
      <tbody>
        @foreach($licencias as $l)
        <tr>
          <td>{{ $l->nsobre ?? '-' }}</td>

          <td class="text-truncate" style="max-width:120px;">{{ $l->persona->ruc ?? '-' }}</td>
          <td>{{ $l->persona->dni ?? '-----------' }}</td>
          <td class="text-truncate" style="max-width:150px;">{{ $l->persona->nombre_completos ?? '-' }}</td>
          <td class="text-truncate" style="max-width:180px;">
            {{ $l->local->direccion->nombre_via ?? '-' }} {{ $l->local->nMunicipal ?? '' }}
          </td>
          <td>{{ $l->local->direccion->sector->nombre ?? '-' }}</td>
          <td class="text-truncate" style="max-width:180px;">{{ $l->local->nombre_comercial ?? '-' }}</td>
          <td class="text-truncate" style="max-width:160px;">{{ $l->local->giro_autorizado ?? '-' }}</td>
          <td>{{ $l->local->area ?? '-' }}</td>
          <td>
            @php
            $riesgo = trim(strtolower($l->local->tipoRiesgo->nombre ?? ''));
            $color = match($riesgo) {
            'muy alto' => 'bg-dark',
            'alto' => 'bg-danger',
            'medio' => 'bg-warning',
            default => 'bg-success',
            };
            @endphp
            <span class="badge {{ $color }}">
              {{ $l->local->tipoRiesgo->nombre ?? '-' }}
            </span>
          </td>
          <td>{{ $l->autorizacion->nAutorizacion ?? '-' }}</td>
          <td>{{ optional($l->autorizacion)->fecha_ingreso ? date('d-m-Y', strtotime($l->autorizacion->fecha_ingreso)) : '-' }}</td>
          <td>{{ optional($l->autorizacion)->fecha_emision ? date('d-m-Y', strtotime($l->autorizacion->fecha_emision)) : '-' }}</td>
          <td>{{ $l->nExpediente }}</td>
          <td>{{ $l->nResolucion }}</td>
          <td>
            <span class="badge {{ $l->estado ? 'bg-primary' : 'bg-danger' }}">
              {{ $l->estado ? 'Activo' : 'Inactivo' }}
            </span>
          </td>
          <td>{{ $l->descripcion ?? '-' }}</td>
          <td class="text-end">
            <div class="d-flex justify-content-end gap-2">
              <button type="button"
                class="btn btn-sm btn-outline-success btnPreview"
                title="Preview"
                data-id="{{ $l->idDetalle }}"
                data-nombre="{{ $l->persona->nombre_completos }}"
                data-dni="{{ $l->persona->dni }}"
                data-ruc="{{ $l->persona->ruc }}"
                data-nombrecomercial="{{ $l->local->nombre_comercial }}"
                data-direccion="{{ $l->local->idDireccion }}"
                data-nmunicipal="{{ $l->local->nMunicipal }}"
                data-sector="{{ $l->local->direccion->idSector }}"
                data-nautorizacion="{{ $l->autorizacion->nAutorizacion }}"
                data-tiporiesgo="{{ $l->local->idTipoRiesgo }}"
                data-fechaingreso="{{ $l->autorizacion->fecha_ingreso }}"
                data-fechaemision="{{ $l->autorizacion->fecha_emision }}"
                data-nexpediente="{{ $l->nExpediente }}"
                data-nresolucion="{{ $l->nResolucion }}"
                data-estado="{{ $l->estado ? 1 : 0 }}"
                data-giro="{{ $l->local->giro_autorizado }}"
                data-area="{{ $l->local->area }}"
                data-nsobre="{{ $l->nsobre }}"
                data-descripcion="{{ $l->descripcion }}">
                <i class="fa-solid fa-eye"></i>
              </button>
              <button type="button"
                class="btn btn-sm btn-outline-warning btnEditar"
                title="Editar"
                data-id="{{ $l->idDetalle }}"
                data-nombre="{{ $l->persona->nombre_completos }}"
                data-dni="{{ $l->persona->dni }}"
                data-ruc="{{ $l->persona->ruc }}"
                data-nombrecomercial="{{ $l->local->nombre_comercial }}"
                data-direccion="{{ $l->local->idDireccion }}"
                data-nmunicipal="{{ $l->local->nMunicipal }}"
                data-sector="{{ $l->local->direccion->idSector }}"
                data-nautorizacion="{{ $l->autorizacion->nAutorizacion }}"
                data-tiporiesgo="{{ $l->local->idTipoRiesgo }}"
                data-fechaingreso="{{ $l->autorizacion->fecha_ingreso }}"
                data-fechaemision="{{ $l->autorizacion->fecha_emision }}"
                data-nexpediente="{{ $l->nExpediente }}"
                data-nresolucion="{{ $l->nResolucion }}"
                data-estado="{{ $l->estado ? 1 : 0 }}"
                data-giro="{{ $l->local->giro_autorizado }}"
                data-area="{{ $l->local->area }}"
                data-nsobre="{{ $l->nsobre }}"
                data-descripcion="{{ $l->descripcion }}">
                <i class="fa-solid fa-pen"></i>
              </button>

              <form action="{{ route('licencias.destroy', $l->idDetalle) }}" method="POST" class="form-eliminar">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" title="Eliminar">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>




            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="text-muted small">
        Mostrando {{ $licencias->firstItem() ?? 0 }} - {{ $licencias->lastItem() ?? 0 }} de {{ $licencias->total() }}
      </div>

      <div>
        {!! $licencias->links('pagination::bootstrap-5') !!}
      </div>
    </div>

  </div>
</div>





<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function() {
    $('#miTabla').DataTable({

      "ordering": false, 
      "paging": true, 
      "searching": true, 
      "info": true, 
      "lengthChange": true, 
      "pageLength": 50, 
      "language": {
        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
      }
    });
  });
</script>

<!-- Modal -->
<div class="modal fade" id="modalNuevo" tabindex="-1" aria-labelledby="modalNuevo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md custom-modal-width">
    <div class="modal-content">

      
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevo">
          <i class="fa-solid fa-file-circle-plus me-2"></i> Nueva Licencia
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      
      <div class="modal-body">
        <form id="formLicencia" action="{{ route('licencias.store') }}" method="POST">
          @csrf
          <div class="row g-4">

            <!-- Información del Titular -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-user me-2 text-primary"></i>Información del Titular</h6>
              <div class="mb-3">
                <label class="form-label">Nombre completo *</label>
                <input type="text" class="form-control" autocomplete="off" name="nombre_completos" required>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">DNI *</label>
                  <input type="text" class="form-control" autocomplete="off" name="dni">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">RUC *</label>
                  <input type="text" class="form-control" autocomplete="off" name="ruc" required>
                </div>
              </div>
            </div>

            <!-- Información del Establecimiento -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-store me-2 text-success"></i>Información del Establecimiento</h6>
              <div class="mb-3">
                <label class="form-label">Nombre Comercial *</label>
                <input type="text" class="form-control" autocomplete="off" name="nombre_comercial" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Dirección *</label>
                <select class="form-select" name="direccion" required>
                  <option value="">Seleccionar...</option>
                  @foreach($direcciones as $d)
                  <option value="{{ $d->idDireccion }}">{{ $d->nombre_via }}</option>
                  @endforeach
                </select>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">N° Municipal</label>
                  <input type="text" class="form-control" autocomplete="off" name="nMunicipal" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Sector *</label>
                  <select class="form-select" name="sector" required>
                    <option value="">Seleccionar...</option>
                    @foreach($sectores as $s)
                    <option value="{{ $s->idSector }}">{{ $s->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <!-- Información de Licencia -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-file-contract me-2 text-warning"></i>Información de Licencia</h6>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">N° Autorización</label>
                  <input type="text" class="form-control" name="nAutorizacion" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Tipo de Riesgo *</label>
                  <select class="form-select" name="tipo_riesgo" required>
                    <option value="">Seleccionar...</option>
                    @foreach($riesgos as $r)
                    <option value="{{ $r->idTipoRiesgo }}">{{ $r->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Fecha Ingreso</label>
                  <input type="date" class="form-control" name="fecha_ingreso" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Fecha Emisión</label>
                  <input type="date" class="form-control" name="fecha_emision" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">N° Expediente</label>
                  <input type="text" class="form-control" name="nExpediente" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">N° Resolución</label>
                  <input type="text" class="form-control" name="nResolucion" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Estado *</label>
                <select class="form-select" name="estado">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>
            </div>

            <!-- Información de Actividad -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-industry me-2 text-danger"></i>Información de Actividad</h6>
              <div class="mb-3">
                <label class="form-label">N° Sobre</label>
                <input type="text" class="form-control" autocomplete="off" name="nsobre" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Giro Autorizado *</label>
                <input type="text" class="form-control" autocomplete="off" name="giro_autorizado" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Área (m²)</label>
                <input type="number" class="form-control" autocomplete="off" name="area" min="1" step="0.01" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" autocomplete="off" rows="2"></textarea>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCancelarModal">Cancelar</button>
              <button type="submit" form="formLicencia" class="btn btn-primary" id="btnGuardarModal">
                <i class="fa-solid fa-save me-2"></i> Guardar
              </button>
            </div>

          </div>
      </div>
    </div>

   
    <script>
      document.addEventListener('DOMContentLoaded', function() {

        const modalEl = document.getElementById('modalNuevo');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const form = document.querySelector('#formLicencia');

        const btnGuardar = document.getElementById('btnGuardarModal');
        const btnCancelar = document.getElementById('btnCancelarModal');

        // -------------------------------
        // FUNCIONES PARA HABILITAR / DESHABILITAR CAMPOS
        // -------------------------------
        function disableFormFields() {
          form.querySelectorAll('input, select, textarea').forEach(campo => {
            campo.setAttribute('disabled', true);
          });
        }

        function enableFormFields() {
          form.querySelectorAll('input, select, textarea').forEach(campo => {
            campo.removeAttribute('disabled');
          });
        }

        // ------------------------------------
        // BOTÓN EDITAR
        // ------------------------------------
        document.querySelectorAll('.btnEditar').forEach(button => {
          button.addEventListener('click', function() {

            enableFormFields(); 

            const id = this.dataset.id;

            
            btnGuardar.style.display = '';
            btnCancelar.style.display = '';

            
            form.querySelector('input[name="nombre_completos"]').value = this.dataset.nombre || '';
            form.querySelector('input[name="dni"]').value = this.dataset.dni || '';
            form.querySelector('input[name="ruc"]').value = this.dataset.ruc || '';
            form.querySelector('input[name="nombre_comercial"]').value = this.dataset.nombrecomercial || '';
            form.querySelector('select[name="direccion"]').value = this.dataset.direccion || '';
            form.querySelector('input[name="nMunicipal"]').value = this.dataset.nmunicipal || '';
            form.querySelector('select[name="sector"]').value = this.dataset.sector || '';
            form.querySelector('input[name="nAutorizacion"]').value = this.dataset.nautorizacion || '';
            form.querySelector('select[name="tipo_riesgo"]').value = this.dataset.tiporiesgo || '';
            form.querySelector('input[name="fecha_ingreso"]').value = (this.dataset.fechaingreso || '').split(' ')[0];
            form.querySelector('input[name="fecha_emision"]').value = (this.dataset.fechaemision || '').split(' ')[0];
            form.querySelector('input[name="nExpediente"]').value = this.dataset.nexpediente || '';
            form.querySelector('input[name="nResolucion"]').value = this.dataset.nresolucion || '';
            form.querySelector('select[name="estado"]').value = this.dataset.estado;
            form.querySelector('input[name="giro_autorizado"]').value = this.dataset.giro || '';
            form.querySelector('input[name="area"]').value = this.dataset.area || '';
            form.querySelector('input[name="nsobre"]').value = this.dataset.nsobre || '';
            form.querySelector('textarea[name="descripcion"]').value = this.dataset.descripcion || '';

            
            form.action = `/licencias/${id}`;
            form.querySelectorAll('input[name="_method"]').forEach(e => e.remove());
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');

            modal.show();
          });
        });

        // ------------------------------------
        // BOTÓN NUEVO
        // ------------------------------------
        document.querySelector('#btnNuevo')?.addEventListener('click', function() {
          form.reset();
          enableFormFields();

          form.action = "{{ route('licencias.store') }}";
          form.querySelectorAll('input[name="_method"]').forEach(e => e.remove());

          btnGuardar.style.display = '';
          btnCancelar.style.display = '';

          modal.show();
        });

        // ------------------------------------
        // BOTÓN PREVIEW 
        // ------------------------------------
        document.querySelectorAll('.btnPreview').forEach(button => {
          button.addEventListener('click', function() {

            
            btnGuardar.style.display = 'none';
            btnCancelar.style.display = 'none';

           
            disableFormFields();

          
            form.querySelector('input[name="nombre_completos"]').value = this.dataset.nombre || '';
            form.querySelector('input[name="dni"]').value = this.dataset.dni || '';
            form.querySelector('input[name="ruc"]').value = this.dataset.ruc || '';
            form.querySelector('input[name="nombre_comercial"]').value = this.dataset.nombrecomercial || '';
            form.querySelector('select[name="direccion"]').value = this.dataset.direccion || '';
            form.querySelector('input[name="nMunicipal"]').value = this.dataset.nmunicipal || '';
            form.querySelector('select[name="sector"]').value = this.dataset.sector || '';
            form.querySelector('input[name="nAutorizacion"]').value = this.dataset.nautorizacion || '';
            form.querySelector('select[name="tipo_riesgo"]').value = this.dataset.tiporiesgo || '';
            form.querySelector('input[name="fecha_ingreso"]').value = (this.dataset.fechaingreso || '').split(' ')[0];
            form.querySelector('input[name="fecha_emision"]').value = (this.dataset.fechaemision || '').split(' ')[0];
            form.querySelector('input[name="nExpediente"]').value = this.dataset.nexpediente || '';
            form.querySelector('input[name="nResolucion"]').value = this.dataset.nresolucion || '';
            form.querySelector('select[name="estado"]').value = this.dataset.estado;
            form.querySelector('input[name="giro_autorizado"]').value = this.dataset.giro || '';
            form.querySelector('input[name="area"]').value = this.dataset.area || '';
            form.querySelector('input[name="nsobre"]').value = this.dataset.nsobre || '';
            form.querySelector('textarea[name="descripcion"]').value = this.dataset.descripcion || '';

            modal.show();
          });
        });

        // ------------------------------------
        // LIMPIAR AL CERRAR
        // ------------------------------------
        modalEl.addEventListener('hidden.bs.modal', function() {

          enableFormFields(); 

          form.reset();
          form.querySelectorAll('input[name="_method"]').forEach(e => e.remove());

          document.body.classList.remove('modal-open');
          document.body.style.removeProperty('padding-right');
          document.body.style.overflow = '';
        });

      });
    </script>


@if(session('success') || session('error'))
    <div id="flash-data"
         data-success="{{ session('success') }}"
         data-error="{{ session('error') }}">
    </div>
@endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const flash = document.getElementById('flash-data');
    if (!flash) return; 

    const errorMessage = flash.dataset.error;
    const successMessage = flash.dataset.success;

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: errorMessage,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Entendido',
            didClose: () => {
                const modalEl = document.getElementById('modalNuevo');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            }
        });
    }

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: successMessage,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
    }

});
</script>


 
    @endsection