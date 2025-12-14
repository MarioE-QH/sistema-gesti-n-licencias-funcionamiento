
<!-- Modal Exportar -->
<div class="modal fade" id="modalExportar" tabindex="-1" aria-labelledby="modalExportarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('expedientes.exportar') }}" method="GET">
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







<!-- PANEL PARA DOCUMENTOS -->
<div class="container mt-5 body-defensa-civil">
  <div class="card shadow-sm border-0">
    <div class="card-header defensa-civil-header text-white d-flex justify-content-between align-items-center">
      <div>
        <h5 class="mb-0"><i class="fa-solid fa-folder-open me-2"></i> Control de Expedientes</h5>
        <small class="text-white">Gestiona las certificaciones y resoluciones del sistema</small>
      </div>
      
  <div class="d-flex align-items-center gap-2">
<a href="#" class="btn btn-outline-light btn-sm d-flex align-items-center"
       data-bs-toggle="modal"
       data-bs-target="#modalExportar"> 
    <i class="fa-solid fa-file-export fa-sm me-2"></i>
    Exportar
</a>

  </div>
    </div>
  </div>
</div>


<!--MODAL PARA EDITAR CONTROL-EXPEDINTES-DEFENSA CIVIL -->

<div class="modal fade" id="modalEditarControl" tabindex="-1" aria-labelledby="modalEditarControlLabel" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">

      
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarControlLabel">
          <i class="fa-solid fa-helmet-safety me-2 text-warning"></i>Editar Datos de Defensa Civil
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      
      <div class="modal-body">
        <form id="formEditarControl">
          @csrf
          <input type="hidden" id="idControlExpediente" name="idControlExpediente">
          <input type="hidden" id="idDetalle" name="idDetalle">


          <div class="row g-4">
            <!-- Datos Generales -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-calendar-check me-2 text-primary"></i>Fechas y Aforo</h6>

              <div class="mb-3">
                <label class="form-label">Fecha Recepción D.C.</label>
                <input type="date" class="form-control" name="fecha_recep_dc" id="fecha_recep_dc">
              </div>

              <div class="mb-3">
                <label class="form-label">Aforo (personas)</label>
                <input type="number" class="form-control" name="aforo" id="aforo" min="1">
              </div>

              <div class="mb-3">
                <label class="form-label">Tipo de Informe ITSE</label>
                <select class="form-select" name="tipo_informe_itse" id="tipo_informe_itse">
                  <option value="">Seleccionar...</option>
                  <option value="Anexo 6">Anexo 6</option>
                  <option value="Anexo 7">Anexo 7</option>
                  <option value="No necesario">No necesario</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha Acta ITSE</label>
                <input type="date" class="form-control" name="fecha_acta_itse" id="fecha_acta_itse">
              </div>
            </div>

            <!-- Informe y Resolución -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-file-contract me-2 text-success"></i>Informes y Resoluciones</h6>

              <div class="mb-3">
                <label class="form-label">Resultado</label>
                <select class="form-select" name="resultado" id="resultado">
                  <option value="">Seleccionar...</option>
                  <option value="Si cumple">Si cumple</option>
                  <option value="No cumple">No cumple</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">N° Informe D.C.</label>
                <input type="text" class="form-control" name="n_informe_dc" id="n_informe_dc">
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha Informe D.C.</label>
                <input type="date" class="form-control" name="fecha_informe_dc" id="fecha_informe_dc">
              </div>

              <div class="mb-3">
                <label class="form-label">N° Resolución D.C.</label>
                <input type="text" class="form-control" name="n_resolucion_dc" id="n_resolucion_dc">
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha Resolución D.C.</label>
                <input type="date" class="form-control" name="fecha_resolucion_dc" id="fecha_resolucion_dc">
              </div>
            </div>

            <!-- Certificados -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-certificate me-2 text-danger"></i>Certificados</h6>

              <div class="mb-3">
                <label class="form-label">N° Certificado D.C.</label>
                <input type="text" class="form-control" name="n_certificado_dc" id="n_certificado_dc">
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha Certificado D.C.</label>
                <input type="date" class="form-control" name="fecha_cert_dc" id="fecha_cert_dc">
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha Renovación</label>
                <input type="date" class="form-control" name="fecha_renovacion" id="fecha_renovacion">
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha Caducidad</label>
                <input type="date" class="form-control" name="fecha_caducidad" id="fecha_caducidad">
              </div>
            </div>

            <!-- Notificación -->
            <div class="col-md-6">
              <h6 class="mb-3"><i class="fa-solid fa-bell me-2 text-warning"></i>Notificación</h6>

              <div class="mb-3">
                <label class="form-label">Notificado</label>
                <select class="form-select" name="notificado" id="notificado">
                  <option value="">Seleccionar...</option>
                  <option value="Si">Si</option>
                  <option value="No">No</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha Entrega Certificado</label>
                <input type="date" class="form-control" name="fecha_entrega_cert" id="fecha_entrega_cert">
              </div>

              <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado" id="estado">
                  <option value="">Seleccionar...</option>
                  <option value="Entregado">Entregado</option>
                  <option value="No entregado">No entregado</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Observación</label>
                <textarea class="form-control" name="observacion" id="observacion" rows="3"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formEditarControl" class="btn btn-primary">
          <i class="fa-solid fa-save me-2"></i>Guardar Cambios
        </button>
      </div>

    </div>
  </div>
</div>
<!-- CIERRE DEL MODAL CONTROL EXPEDIENTES DEFENSA CIVIL-->


<div class="body-defensa-civil">
    <div class="container mt-4">
        <div class="card defensa-civil-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataControl" class="table table-hover table-bordered" style="width:100%">
                        <thead class="defensa-civil-thead">
                            <tr>
                                <th>N°</th>
                                <th>N° EXPEDIENTE</th>
                                <th>FECHA EXPEDIENTE</th>
                                <th>FECHA RECEP. D.C.</th>
                                <th>REPRESENTANTE LEGAL</th>
                                <th>RUC</th>
                                <th>NOMBRE COMERCIAL</th>
                                <th>DIRECCION</th>
                                <th>SECTOR</th>
                                <th>GIRO</th>
                                <th>ÁREA (m²)</th>
                                <th>AFORO</th>
                                <th>RIESGO</th>
                                <th>ITSE</th>
                                <th>FECHA ITSE</th>
                                <th>RESULTADO</th>
                                <th>N° INFORME D.C.</th>
                                <th>FECHA INFORME D.C.</th>
                                <th>N° RESOLUCIÓN D.C</th>
                                <th>FECHA RESOLUCIÓN</th>
                                <th>N° CERTIFICADO D.C</th>
                                <th>FECHA CERT. D.C.</th>
                                <th>FECHA RENOVACIÓN</th>
                                <th>FECHA CADUCIDAD</th>
                                <th>NOTIFICADO</th>
                                <th>FECHA DE ENTREGA CEF</th>
                                <th>ESTADO</th>
                                <th>OBSERVACION</th>
                                <th>ACCIONES</th>
                                
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

<script>
$(document).ready(function () {
    const tabla = $('#dataControl').DataTable({
        processing: true,
        serverSide: false,
        ajax: "/defensa-civil/listar-expedientes",
        columns: [
            { data: 'numero' },
            { data: 'nExpediente' },
            { data: 'fecha_expediente' },
            { data: 'fecha_recep_dc' },
            { data: 'representante_legal' },
            { data: 'ruc' },
            { data: 'nombre_comercial' },
            { data: 'direccion' },
            { data: 'sector' },
            { data: 'giro' },
            { data: 'area' },
            { data: 'foro' },
            { data: 'riesgo' },
            { data: 'itse' },
            { data: 'fecha_itse' },
            { data: 'resultado' },
            { data: 'n_informe_dc' },
            { data: 'fecha_informe_dc' },
            { data: 'n_resolucion_dc' },
            { data: 'fecha_resolucion_dc' },
            { data: 'n_certificado_dc' },
            { data: 'fecha_cert_dc' },
            { data: 'fecha_renovacion' },
            { data: 'fecha_caducidad' },
            { data: 'notificado' },
            { data: 'fecha_entrega_cert' },
            { data: 'estado' },
            { data: 'observacion' },
            {
                data: null,
                render: function () {
                    return `
                        <button class="btn btn-warning btn-sm btn-editar">
                            <i class="fas fa-edit"></i> Editar
                        </button>`;
                }
            }
        ],
        pageLength: 12,
        lengthMenu: [ [12, 20, 50], [12, 20, 50] ],
        language: {
            search: "Buscar:",
            zeroRecords: "No se encontraron registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            paginate: { previous: "Anterior", next: "Siguiente" }
        }
    });

    
    $('#dataControl').on('click', '.btn-editar', function () {
        const data = tabla.row($(this).parents('tr')).data();
      
        if (!data) return;
        $('#idDetalle').val(data.idDetalle);
        $('#idControlExpediente').val(data.idControlExpediente);
        $('#fecha_recep_dc').val(data.fecha_recep_dc !== '—' ? moment(data.fecha_recep_dc, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#aforo').val(data.foro !== '—' ? data.foro : '');
        $('#tipo_informe_itse').val(data.itse !== '—' ? data.itse : '');
        $('#fecha_acta_itse').val(data.fecha_itse !== '—' ? moment(data.fecha_itse, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#resultado').val(data.resultado !== '—' ? data.resultado : '');
        $('#n_informe_dc').val(data.n_informe_dc !== '—' ? data.n_informe_dc : '');
        $('#fecha_informe_dc').val(data.fecha_informe_dc !== '—' ? moment(data.fecha_informe_dc, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#n_resolucion_dc').val(data.n_resolucion_dc !== '—' ? data.n_resolucion_dc : '');
        $('#fecha_resolucion_dc').val(data.fecha_resolucion_dc !== '—' ? moment(data.fecha_resolucion_dc, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#n_certificado_dc').val(data.n_certificado_dc !== '—' ? data.n_certificado_dc : '');
        $('#fecha_cert_dc').val(data.fecha_cert_dc !== '—' ? moment(data.fecha_cert_dc, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#fecha_renovacion').val(data.fecha_renovacion !== '—' ? moment(data.fecha_renovacion, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#fecha_caducidad').val(data.fecha_caducidad !== '—' ? moment(data.fecha_caducidad, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#notificado').val(data.notificado !== '—' ? data.notificado : '');
        $('#fecha_entrega_cert').val(data.fecha_entrega_cert !== '—' ? moment(data.fecha_entrega_cert, 'DD/MM/YYYY').format('YYYY-MM-DD') : '');
        $('#estado').val(data.estado !== '—' ? data.estado : '');
        $('#observacion').val(data.observacion !== '—' ? data.observacion : '');

        
       $('#modalEditarControl').modal('show');

    });

        
    $('#formEditarControl').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: '/defensa-civil/editar-expediente', 
            method: 'POST',
            data: formData,
            success: function (res) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: res.message || 'Los datos fueron actualizados correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#modalEditarControl').modal('hide');
                tabla.ajax.reload(null, false);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'No se pudo actualizar el expediente.',
                });
            }
        });
    });
});


</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/es.min.js"></script>
<script>
    moment.locale('es');
</script>
