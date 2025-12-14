@extends('layouts.principal')
@section('content')

<!-- MODAL USUARIO -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form id="formUsuario" class="modal-content border-0 shadow-lg rounded-3">
            @csrf
            <input type="hidden" name="id">

            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                <h6 class="modal-title fw-semibold" id="modalTitle">Registrar Usuario</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="name" class="form-control form-control-sm rounded-2 shadow-none" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo</label>
                    <input type="email" name="email" class="form-control form-control-sm rounded-2 shadow-none" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Rol</label>
                    <select name="role" class="form-select form-select-sm rounded-2 shadow-none" required>
                        <option value="ADMIN">ADMIN</option>
                        <option value="FISCALIZADOR">FISCALIZADOR</option>
                        <option value="DEFENSA_CIVIL">DEFENSA CIVIL</option>
                        <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-sm rounded-2 shadow-none">
                    <small class="text-muted" id="passwordHelp">Dejar vacío si no desea cambiar la contraseña</small>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-end px-4">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-4" id="btnGuardar">Guardar</button>
            </div>
        </form>
    </div>
</div>


<div class="d-flex align-items-center justify-content-between px-3 py-2 bg-white border rounded-top shadow-sm mt-2">
    <div>
        <h5 class="mb-0 text-dark fw-semibold" style="font-size:1rem;">Panel de usuarios</h5>
        <small class="text-muted">Gestiona los usuarios del sistema</small>
    </div>
    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" id="btnNuevoUsuario">
        <i class="fa-solid fa-plus fa-sm me-2"></i>
        Nuevo Usuario
    </button>
</div>


<div class="card border-1 shadow-sm mt-4 rounded-3">
    <div class="card-header bg-white py-3 border-0">
        <h6 class="fw-semibold text-dark mb-0">Listado de Usuarios</h6>
    </div>

    <div class="card-body px-3 py-2">
        <div class="table-responsive">
            <table id="tablaUsuarios" class="table table-striped table-hover align-middle w-100" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr class="text-secondary">
                        <th style="width:70px;">ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
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

    var tabla = $('#tablaUsuarios').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        ajax: "{{ route('usuarios.data') }}",
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            {
                data: 'id',
                className: 'text-center',
                orderable: false,
                render: function (id) {
                    return `
                        <button class="btn btn-sm btn-outline-primary me-1 btnEditar" data-id="${id}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btnEliminar" data-id="${id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        pageLength: 10
    });

    // ======== NUEVO USUARIO ========
    $('#btnNuevoUsuario').click(function() {
        $('#formUsuario')[0].reset();
        $('#formUsuario input[name=id]').val('');
        $('#passwordHelp').text('Ingrese la contraseña del usuario.');
        $('#modalTitle').text('Registrar Usuario');
        $('#modalUsuario').modal('show');
    });

    // ======== EDITAR USUARIO ========
    $(document).on('click', '.btnEditar', function() {
        let id = $(this).data('id');

        $.get(`/usuarios/data`, function(res) {
            let usuario = res.data.find(u => u.id == id);
            if(usuario) {
                $('#formUsuario input[name=id]').val(usuario.id);
                $('#formUsuario input[name=name]').val(usuario.name);
                $('#formUsuario input[name=email]').val(usuario.email);
                $('#formUsuario select[name=role]').val(usuario.role);
                $('#passwordHelp').text('Dejar vacío si no desea cambiar la contraseña');
                $('#modalTitle').text('Editar Usuario');
                $('#modalUsuario').modal('show');
            }
        });
    });

    // ======== GUARDAR / ACTUALIZAR ========
    $('#formUsuario').submit(function(e) {
        e.preventDefault();
        let id = $('#formUsuario input[name=id]').val();
        let url = id ? `/usuarios/${id}` : "{{ route('usuarios.store') }}";
        let method = id ? 'POST' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function() {
                $('#modalUsuario').modal('hide');
                tabla.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: id ? 'Usuario actualizado' : 'Usuario creado',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error al guardar el usuario'
                });
            }
        });
    });

    // ======== ELIMINAR USUARIO ========
    $(document).on('click', '.btnEliminar', function() {
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
                    url: `/usuarios/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
    tabla.ajax.reload();
    Swal.fire({
        icon: 'success',
        title: 'Usuario eliminado',
        showConfirmButton: false,
        timer: 1500
    });
},

                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el usuario'
                        });
                    }
                });
            }
        });
    });

});
</script>
@endsection
