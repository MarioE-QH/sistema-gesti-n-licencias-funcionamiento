@extends('layouts.principal')
@section('content')


<div class="d-flex align-items-center justify-content-between px-3 py-2 bg-white border rounded-top shadow-sm mt-4">
    <div>
        <h5 class="mb-0 text-dark fw-semibold" style="font-size:1rem;">Panel de Tipos de Riesgo</h5>
        <small class="text-muted">Gestiona los tipos de riesgo del sistema</small>
    </div>
    <button type="button" class="btn btn-warning btn-sm d-flex align-items-center" id="btnNuevoTipo">
        <i class="fa-solid fa-plus fa-sm me-2"></i>
        Nuevo Tipo
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="modalTipo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form id="formTipo" class="modal-content border-0 shadow-lg rounded-3">
            @csrf
            <input type="hidden" name="idTipoRiesgo">

            <div class="modal-header text-white" style="background: linear-gradient(135deg, #ffc107, #d39e00);">
                <h6 class="modal-title fw-semibold" id="modalTipoTitle">Registrar Tipo de Riesgo</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-sm rounded-2 shadow-none" required>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-end px-4">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning px-4" id="btnGuardarTipo">Guardar</button>
            </div>
        </form>
    </div>
</div>


<div class="card border-1 shadow-sm mt-2 rounded-3">
    <div class="card-body px-3 py-2">
        <div class="table-responsive">
            <table id="tablaTipos" class="table table-striped table-hover align-middle w-100" style="font-size:0.9rem;">
                <thead class="table-light">
                    <tr class="text-secondary">
                        <th>Nombre</th>
                        <th class="text-center" style="width:120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




<script>
$(document).ready(function () {

    var tablaTipos = $('#tablaTipos').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        ajax: "{{ route('tiporiesgos.data') }}",
        columns: [
            { data: 'nombre' },
            {
                data: 'idTipoRiesgo',
                className: 'text-center',
                orderable: false,
                render: function(id) {
                    return `
                        <button class="btn btn-sm btn-outline-warning me-1 btnEditarTipo" data-id="${id}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarTipo" data-id="${id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        pageLength: 10
    });

    // ======== NUEVO TIPO ========
    $('#btnNuevoTipo').click(function() {
        $('#formTipo')[0].reset();
        $('#formTipo input[name=idTipoRiesgo]').val('');
        $('#modalTipoTitle').text('Registrar Tipo de Riesgo');
        $('#modalTipo').modal('show');
    });

    // ======== EDITAR  ========
    $(document).on('click', '.btnEditarTipo', function() {
        let id = $(this).data('id');
        $.get("{{ route('tiporiesgos.data') }}", function(res) {
            let tipo = res.data.find(t => t.idTipoRiesgo == id);
            if(tipo) {
                $('#formTipo input[name=idTipoRiesgo]').val(tipo.idTipoRiesgo);
                $('#formTipo input[name=nombre]').val(tipo.nombre);
                $('#modalTipoTitle').text('Editar Tipo de Riesgo');
                $('#modalTipo').modal('show');
            }
        });
    });

    // ======== GUARDAR / ACTUALIZAR  ========
    $('#formTipo').submit(function(e) {
        e.preventDefault();
        let id = $('#formTipo input[name=idTipoRiesgo]').val();
        let url = id ? `/tiporiesgos/${id}` : "{{ route('tiporiesgos.store') }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#modalTipo').modal('hide');
                tablaTipos.ajax.reload();
                Swal.fire({ icon:'success', title: id ? 'Tipo actualizado' : 'Tipo creado', showConfirmButton:false, timer:1500 });
            },
            error: function() {
                Swal.fire({ icon:'error', title:'Error', text:'No se pudo guardar el tipo de riesgo' });
            }
        });
    });

    // ======== ELIMINAR ========
    $(document).on('click', '.btnEliminarTipo', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: '¿Está seguro?',
            text: "No podrá revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `/tiporiesgos/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        tablaTipos.ajax.reload();
                        Swal.fire({ icon:'success', title:'Tipo eliminado', showConfirmButton:false, timer:1500 });
                    },
                    error: function() {
                        Swal.fire({ icon:'error', title:'Error', text:'No se pudo eliminar el tipo de riesgo' });
                    }
                });
            }
        });
    });

});
</script>



@endsection