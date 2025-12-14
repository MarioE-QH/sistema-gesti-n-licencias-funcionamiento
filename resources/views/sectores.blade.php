@extends('layouts.principal')
@section('content')



<!-- MODAL SECTOR-->
<div class="modal fade" id="modalSector" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form id="formSector" class="modal-content border-0 shadow-lg rounded-3">
            @csrf
            <input type="hidden" name="idSector">

            <div class="modal-header text-white" style="background: linear-gradient(135deg, #198754, #146c43);">
                <h6 class="modal-title fw-semibold" id="modalTitleSector">Registrar Sector</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del sector</label>
                    <input type="text" name="nombre" class="form-control form-control-sm rounded-2 shadow-none" required>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-end px-4">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success px-4" id="btnGuardarSector">Guardar</button>
            </div>
        </form>
    </div>
</div>


<div class="d-flex align-items-center justify-content-between px-3 py-2 bg-white border rounded-top shadow-sm mt-5">
    <div>
        <h5 class="mb-0 text-dark fw-semibold" style="font-size:1rem;">Panel de sectores</h5>
        <small class="text-muted">Gestiona los sectores del sistema</small>
    </div>
    <button type="button" class="btn btn-success btn-sm d-flex align-items-center" id="btnNuevoSector">
        <i class="fa-solid fa-plus fa-sm me-2"></i>
        Nuevo Sector
    </button>
</div>


<div class="card border-1 shadow-sm mt-2 rounded-3">
    <div class="card-header bg-white py-3 border-0">
        <h6 class="fw-semibold text-dark mb-0">Listado de Sectores</h6>
    </div>

    <div class="card-body px-3 py-2">
        <div class="table-responsive">
            <table id="tablaSectores" class="table table-striped table-hover align-middle w-100" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr class="text-secondary">
                        <th style="width:70px;">ID</th>
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

    
    var tablaSectores = $('#tablaSectores').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        ajax: "{{ route('sectores.data') }}",
        columns: [
            { data: 'idSector' },
            { data: 'nombre' },
            {
                data: 'idSector',
                className: 'text-center',
                orderable: false,
                render: function(id) {
                    return `
                        <button class="btn btn-sm btn-outline-success me-1 btnEditarSector" data-id="${id}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarSector" data-id="${id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        pageLength: 10
    });

    // ======== NUEVO SECTOR ========
    $('#btnNuevoSector').click(function() {
        $('#formSector')[0].reset();
        $('#formSector input[name=idSector]').val('');
        $('#modalTitleSector').text('Registrar Sector');
        $('#modalSector').modal('show');
    });

    // ======== EDITAR SECTOR ========
    $(document).on('click', '.btnEditarSector', function() {
        let id = $(this).data('id');
        $.get("{{ route('sectores.data') }}", function(res) {
            let sector = res.data.find(s => s.idSector == id);
            if(sector) {
                $('#formSector input[name=idSector]').val(sector.idSector);
                $('#formSector input[name=nombre]').val(sector.nombre);
                $('#modalTitleSector').text('Editar Sector');
                $('#modalSector').modal('show');
            }
        });
    });

    // ======== GUARDAR / ACTUALIZAR SECTOR ========
    $('#formSector').submit(function(e) {
        e.preventDefault();
        let id = $('#formSector input[name=idSector]').val();
        let url = id ? `/sectores/${id}` : "{{ route('sectores.store') }}";
        let method = 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function() {
                $('#modalSector').modal('hide');
                tablaSectores.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: id ? 'Sector actualizado' : 'Sector creado',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error al guardar el sector'
                });
            }
        });
    });

    // ======== ELIMINAR SECTOR ========
    $(document).on('click', '.btnEliminarSector', function() {
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
            if (result.isConfirmed) {
                $.ajax({
                    url: `/sectores/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        tablaSectores.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sector eliminado',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el sector'
                        });
                    }
                });
            }
        });
    });

});
</script>


@endsection