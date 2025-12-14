@extends('layouts.principal')

@section('content')


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">


<!-- Modal Subir PDF -->
<div class="modal fade" id="modalSubirPDF" tabindex="-1" aria-labelledby="modalSubirPDFLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content body-defensa-civil p-3">
      
      
      <div class="modal-header">
        <h5 class="modal-title" id="modalSubirPDFLabel">
          <i class="fa-solid fa-file-pdf me-2 text-danger"></i> Subir PDF - Certificado y Resolución
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
     
      <div class="modal-body">
        <form id="formSubirPDF" enctype="multipart/form-data">
            @csrf
          <div class="row g-4">

            <!-- Seleccionar archivos -->
            <div class="col-md-6">
              <label class="form-label">Certificado PDF *</label>
              <input type="file" class="form-control" name="certificado_pdf" accept=".pdf">
            </div>
            <div class="col-md-6">
              <label class="form-label">Resolución PDF *</label>
              <input type="file" class="form-control" name="resolucion_pdf" accept=".pdf">
            </div>

           
            <div class="col-md-6">
              <label class="form-label">Fecha de Emisión *</label>
              <input type="date" class="form-control" name="fecha_emision">
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha de Vencimiento *</label>
              <input type="date" class="form-control" name="fecha_vencimiento">
            </div>

            
            <input type="hidden" name="idDetalle" id="idDetalleModal">

          </div>
        </form>
      </div>

     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
        <button type="submit" form="formSubirPDF" class="btn btn-primary">
          <i class="fa-solid fa-upload me-2"></i> Subir
        </button>
      </div>

    </div>
  </div>
</div>

<!-- PANEL PARA DOCUMENTOS -->
<div class="container mt-5 body-defensa-civil">
  <div class="card shadow-sm border-0">
    <div class="card-header defensa-civil-header text-white d-flex justify-content-between align-items-center">
      <div>
        <h5 class="mb-0"><i class="fa-solid fa-folder-open me-2"></i> Panel para documentos</h5>
        <small class="text-white">Crea documentos para generar certificados y resoluciones</small>
      </div>
      <div>
        <button class="btn btn-light btn-sm me-2" id="btnGenerarCertificado">
          <i class="fa-solid fa-file-pdf text-danger me-1"></i> Generar Certificación
        </button>
        <button class="btn btn-light btn-sm" id="btnGenerarResolucion">
          <i class="fa-solid fa-file-signature text-success me-1"></i> Generar Resolución
        </button>
      </div>
    </div>
  </div>
</div>


<!-- Modal para generar certificado -->
<div class="modal fade" id="modalCertificado" tabindex="-1" aria-labelledby="modalCertificadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-orange text-white">
        <h5 class="modal-title" id="modalCertificadoLabel">Generar Certificado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formCertificado" method="POST" action="{{ route('defensa_civil.generarCertificado') }}">
        @csrf
        <div class="modal-body row g-3">
          <div class="col-md-4">
            <label class="form-label">N° Certificado</label>
            <input type="text" name="nCertificado" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">N° Expediente</label>
            <input type="text" name="nExpediente" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Razón Social</label>
            <input type="text" name="razonSocial" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nombre Comercial</label>
            <input type="text" name="nombreComercial" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Solicitado por</label>
            <input type="text" name="solicitadoPor" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Riesgo</label>
            <input type="text" name="riesgo" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Ubicación Negocio</label>
            <input type="text" name="ubicacionNegocio" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Área</label>
            <input type="text" name="area" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Giro o Actividad</label>
            <input type="text" name="giroActividad" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Capacidad</label>
            <input type="text" name="nCapacidad" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">N° Resolución</label>
            <input type="text" name="nRes" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Fecha Expedición</label>
            <input type="text" name="fechaExpedicion" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Fecha Renovación</label>
            <input type="text" name="fechaRenovacion" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Fecha Caducidad</label>
            <input type="text" name="fechaCaducidad" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn bg-orange-boton">
            <i class="fa-solid fa-download me-1"></i> Generar PDF
          </button>
         
        </div>
      </form>
    </div>
  </div>
</div>



<script>
document.getElementById("btnGenerarCertificado").addEventListener("click", () => {
  const modal = new bootstrap.Modal(document.getElementById("modalCertificado"));
  modal.show();
});

document.getElementById("formCertificado").addEventListener("submit", async (e) => {
  e.preventDefault(); 

  const form = e.target;
  const formData = new FormData(form);
  const nCertificado = form.querySelector('[name="nCertificado"]').value.trim() || "SIN-NUMERO";

  
  let overlay = document.getElementById("overlayCarga");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.id = "overlayCarga";
    overlay.innerHTML = `
      <div class="contenido">
        <div class="spinner-border text-warning mb-3" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <h5>Generando certificado...</h5>
        <p class="text-light mb-0">Por favor espere unos segundos</p>
      </div>
    `;
   
    document.querySelector("#modalCertificado .modal-content").appendChild(overlay);
  }
  overlay.style.display = "flex";

  
  const submitButton = form.querySelector("button[type='submit']");
  const originalText = submitButton.innerHTML;
  submitButton.disabled = true;
  submitButton.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Generando...`;

  try {
    const response = await fetch(form.action, {
      method: "POST",
      body: formData,
      headers: {
        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
      }
    });

    if (!response.ok) throw new Error("Error al generar el PDF.");

    const blob = await response.blob();
    const nombreArchivo = `Certificado-${nCertificado}-MDP-GSM.pdf`;

    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = nombreArchivo;
    document.body.appendChild(a);
    a.click();
    a.remove();
    window.URL.revokeObjectURL(url);

    
    overlay.style.display = "none";
    submitButton.disabled = false;
    submitButton.innerHTML = originalText;

    const modal = bootstrap.Modal.getInstance(document.getElementById("modalCertificado"));
    modal.hide();
    form.reset();

  } catch (error) {
    overlay.style.display = "none";
    submitButton.disabled = false;
    submitButton.innerHTML = originalText;
    alert("Ocurrió un error: " + error.message);
  }
});
</script>



<div class="modal fade" id="modalResolucion" tabindex="-1" aria-labelledby="modalResolucionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-orange text-white">
        <h5 class="modal-title" id="modalResolucionLabel">Generar Resolución</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formResolucion" method="POST" action="{{ route('generar.resolucion') }}">
        @csrf
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">Fecha de Resolución</label>
            <input type="text" name="fechaResolucion" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">N° Resolución</label>
            <input type="text" name="nResolucion" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">N° Expediente</label>
            <input type="text" name="nExpediente" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Presentado por</label>
            <input type="text" name="presentadoPor" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Razón Social</label>
            <input type="text" name="razonSocial" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">N° Informe</label>
            <input type="text" name="nInforme" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tipo de Riesgo</label>
            <input type="text" name="tipoRiesgo" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Giro</label>
            <input type="text" name="giro" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Área</label>
            <input type="text" name="area" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Aforo</label>
            <input type="text" name="aforo" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Nombre Comercial</label>
            <input type="text" name="nombreComercial" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Sector</label>
            <input type="text" name="sector" class="form-control" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn bg-orange text-dark">
            <i class="fa-solid fa-download me-1"></i> Generar PDF
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
document.getElementById("btnGenerarResolucion").addEventListener("click", () => {
  const modal = new bootstrap.Modal(document.getElementById("modalResolucion"));
  modal.show();
});

document.getElementById("formResolucion").addEventListener("submit", async (e) => {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const nResolucion = form.querySelector('[name="nResolucion"]').value.trim() || "SIN-NUMERO";

  
  let overlay = document.getElementById("overlayCargaResolucion");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.id = "overlayCargaResolucion";
    overlay.style.position = "absolute";
    overlay.style.top = 0;
    overlay.style.left = 0;
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.background = "rgba(0, 0, 0, 0.6)";
    overlay.style.display = "flex";
    overlay.style.alignItems = "center";
    overlay.style.justifyContent = "center";
    overlay.style.zIndex = "1055";
    overlay.innerHTML = `
      <div style="text-align: center; color: white;">
        <div class="spinner-border text-light mb-3" style="width: 4rem; height: 4rem;" role="status"></div>
        <h5>Generando resolución...</h5>
        <p class="text-light mb-0">Por favor espere unos segundos</p>
      </div>
    `;
    document.querySelector("#modalResolucion .modal-content").appendChild(overlay);
  } else {
    overlay.style.display = "flex";
  }

  try {
    const response = await fetch(form.action, {
      method: "POST",
      body: formData,
      headers: {
        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
      }
    });

    if (!response.ok) throw new Error("Error al generar el PDF.");

    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `Resolucion-${nResolucion}-MDP-GSM.pdf`;

    document.body.appendChild(a);
    a.click();
    a.remove();
    window.URL.revokeObjectURL(url);

    // ✅ Restaurar modal
    overlay.style.display = "none";
    const modal = bootstrap.Modal.getInstance(document.getElementById("modalResolucion"));
    modal.hide();
    form.reset();

  } catch (error) {
    overlay.style.display = "none";
    alert("Ocurrió un error: " + error.message);
  }
});
</script>









<div class="body-defensa-civil">
    <div class="container mt-4">
        <div class="card defensa-civil-card">
            <div class="card-header defensa-civil-header">
                Defensa Civil - Certificaciones y Resoluciones
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover table-bordered" style="width:100%">
                        <thead class="defensa-civil-thead">
                            <tr>
                                <th class="text-center">SOBRE</th>
                                <th class="text-center">RUC</th>
                                <th class="text-center">Titular</th>
                                <th class="text-center">Nombre Comercial</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

<script>


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$(document).ready(function () {
    $('#datatable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('defensa_civil.listar') }}",
        columns: [
            { data: 'nsobre'},
            { data: 'ruc', name: 'ruc' },
            { data: 'titular', name: 'titular' },
            { data: 'nombre_comercial', name: 'nombre_comercial' },
            { 
                data: 'idDetalle',
                name: 'acciones',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
    return `
        <a href="#" class="btn btn-sm defensa-civil-btn-pdf me-1" title="Subir PDF">
            <i class="fas fa-file-pdf"></i>
        </a>
        <a href="#" class="btn btn-sm defensa-civil-btn-ver btn-outline-success" data-iddetalle="${row.idDetalle}" title="Ver PDF">
    <i class="fas fa-eye"></i>
</a>

    `;
}

            }
        ],
        pageLength: 10,
        lengthMenu: [ [10, 20, 50], [10, 20, 50] ],
        language: {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty:      "Mostrando 0 a 0 de 0 registros",
            infoFiltered:   "(filtrado de _MAX_ registros totales)",
            infoPostFix:    "",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron registros",
            emptyTable:     "No hay datos disponibles en la tabla",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Último"
            },
            aria: {
                sortAscending:  ": activar para ordenar ascendente",
                sortDescending: ": activar para ordenar descendente"
            }
        }
    });
});
</script>


<script>
$(document).on('click', '.defensa-civil-btn-ver', function (e) {
    e.preventDefault();

    let idDetalle = $(this).data('iddetalle');

    $.ajax({
        url: `/defensa-civil/ver-documentos/${idDetalle}`,
        method: "GET",
        success: function (doc) {
            if (doc.certificado) window.open(doc.certificado, "_blank");
            if (doc.resolucion) window.open(doc.resolucion, "_blank");
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.error ?? "No se encontraron documentos");
        }
    });
});




</script>


<script>
// -------------------------------------------------------
// ABRIR MODAL
// -------------------------------------------------------
$(document).on('click', '.defensa-civil-btn-pdf', function(e) {
    e.preventDefault();
    let data = $('#datatable').DataTable().row($(this).parents('tr')).data();
    $('#idDetalleModal').val(data.idDetalle);

    
    $('#formSubirPDF')[0].reset();
    $('#formSubirPDF input[type="file"]').val('');

    $('#modalSubirPDF').modal('show');
});

// -------------------------------------------------------
// ENVIAR FORMULARIO 
// -------------------------------------------------------
$('#formSubirPDF').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
        url: "{{ route('defensa_civil.subirPDF') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(response.success){

               
                $('#formSubirPDF')[0].reset();
                $('#formSubirPDF input[type="file"]').val('');

                $('#modalSubirPDF').modal('hide');
                $('#datatable').DataTable().ajax.reload();
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert("Error al subir PDFs: " + xhr.responseJSON.message);
        }
    });
});

// -------------------------------------------------------
// LIMPIAR INPUT  CUANDO SE CIERRA EL MODAL
// -------------------------------------------------------
$('#modalSubirPDF').on('hidden.bs.modal', function () {
    $('#formSubirPDF')[0].reset();
    $('#formSubirPDF input[type="file"]').val('');
});
</script>



@include('defensa_civil_expedientes')


@endsection
