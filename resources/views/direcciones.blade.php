@extends('layouts.principal')
@section('content')


<!-- Header  -->
<div class="d-flex align-items-center justify-content-between px-3 py-2 bg-white border rounded-top shadow-sm mt-4">
    <div>
        <h5 class="mb-0 text-dark fw-semibold" style="font-size:1rem;">Panel de Direcciones</h5>
        <small class="text-muted">Gestiona las direcciones del sistema</small>
    </div>
    <button type="button" class="btn btn-info btn-sm d-flex align-items-center" id="btnNuevaDireccion">
        <i class="fa-solid fa-plus fa-sm me-2"></i>
        Nueva Dirección
    </button>
</div>

<!-- Modal Dirección -->
<div class="modal fade" id="modalDireccion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form id="formDireccion" class="modal-content border-0 shadow-lg rounded-3">
            @csrf
            <input type="hidden" name="idDireccion">

            <div class="modal-header text-white" style="background: linear-gradient(135deg, #198187ff, #145f6cff);">
                <h6 class="modal-title fw-semibold" id="modalDireccionTitle">Registrar Dirección</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre de la vía</label>
                    <input type="text" name="nombre_via" class="form-control form-control-sm rounded-2 shadow-none" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sector</label>
                    <select name="idSector" class="form-select form-select-sm rounded-2 shadow-none" required>
                        @foreach(App\Models\Sector::all() as $sector)
                        <option value="{{ $sector->idSector }}">{{ $sector->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-end px-4">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-info px-4" id="btnGuardarDireccion">Guardar</button>
            </div>
        </form>
    </div>
</div>


<div class="card border-1 shadow-sm mt-2 rounded-3">
    <div class="card-body px-3 py-2">
        <div class="table-responsive">
            <table id="tablaDirecciones" class="table table-striped table-hover align-middle w-100" style="font-size:0.9rem;">
                <thead class="table-light">
                    <tr class="text-secondary">
                        <th>Nombre Vía</th>
                        <th>Sector</th>
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

    var tablaDirecciones = $('#tablaDirecciones').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        ajax: "{{ route('direcciones.data') }}",
        columns: [
            { data: 'nombre_via' },
            { data: 'sector' },
            {
                data: 'idDireccion',
                className: 'text-center',
                orderable: false,
                render: function(id) {
                    return `
                        <button class="btn btn-sm btn-outline-info me-1 btnEditarDireccion" data-id="${id}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminarDireccion" data-id="${id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        pageLength: 10
    });

    // ======== NUEVA DIRECCION ========
    $('#btnNuevaDireccion').click(function() {
        $('#formDireccion')[0].reset();
        $('#formDireccion input[name=idDireccion]').val('');
        $('#modalDireccionTitle').text('Registrar Dirección');
        $('#modalDireccion').modal('show');
    });

    // ======== EDITAR DIRECCION ========
    $(document).on('click', '.btnEditarDireccion', function() {
        let id = $(this).data('id');
        $.get("{{ route('direcciones.data') }}", function(res) {
            let dir = res.data.find(d => d.idDireccion == id);
            if(dir) {
                $('#formDireccion input[name=idDireccion]').val(dir.idDireccion);
                $('#formDireccion input[name=nombre_via]').val(dir.nombre_via);
                $('#formDireccion select[name=idSector]').val($(`select[name=idSector] option:contains('${dir.sector}')`).val());
                $('#modalDireccionTitle').text('Editar Dirección');
                $('#modalDireccion').modal('show');
            }
        });
    });

    // ======== GUARDAR / ACTUALIZAR DIRECCION ========
    $('#formDireccion').submit(function(e) {
        e.preventDefault();
        let id = $('#formDireccion input[name=idDireccion]').val();
        let url = id ? `/direcciones/${id}` : "{{ route('direcciones.store') }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('#modalDireccion').modal('hide');
                tablaDirecciones.ajax.reload();
                Swal.fire({ icon:'success', title: id ? 'Dirección actualizada' : 'Dirección creada', showConfirmButton:false, timer:1500 });
            },
            error: function() {
                Swal.fire({ icon:'error', title:'Error', text:'No se pudo guardar la dirección' });
            }
        });
    });

    // ======== ELIMINAR DIRECCION ========
    $(document).on('click', '.btnEliminarDireccion', function() {
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
                    url: `/direcciones/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        tablaDirecciones.ajax.reload();
                        Swal.fire({ icon:'success', title:'Dirección eliminada', showConfirmButton:false, timer:1500 });
                    },
                    error: function() {
                        Swal.fire({ icon:'error', title:'Error', text:'No se pudo eliminar la dirección' });
                    }
                });
            }
        });
    });

});
</script>




@endsection