@extends('layouts.principal')
@section('content')


<!-- Modal Licencias sin documentos -->
<div class="modal fade" id="modalSinDocumentos" tabindex="-1"
  aria-labelledby="modalSinDocumentosLabel" aria-hidden="true">

  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalSinDocumentosLabel">
          <i class="fa-solid fa-folder-open me-2"></i>
          Locales sin documentos
        </h5>
        <button type="button" class="btn-close btn-close-white"
          data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      
      <div class="modal-body">

        
        <div class="row mb-3 align-items-center">
          <div class="col-md-3">
            <input type="text"
              id="buscarRuc"
              class="form-control form-control-sm"
              placeholder="Buscar por RUC">
          </div>
          <div class="col-md-2">
            <button class="btn btn-info btn-sm w-100" id="btnBuscarRuc">
              <i class="fa-solid fa-magnifying-glass me-1"></i>
              Buscar
            </button>
          </div>
        </div>



        <!-- TABLA -->
        <div id="tablaSinDocumentos" class="table-responsive">
          <p class="text-center text-muted py-4">
            Cargando información...
          </p>
        </div>

      </div>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function() {

    const modal = document.getElementById('modalSinDocumentos');
    const tablaDiv = document.getElementById('tablaSinDocumentos');
    const btnBuscar = document.getElementById('btnBuscarRuc');
    const inputRuc = document.getElementById('buscarRuc');

    function cargarTabla(page = 1) {
      const ruc = inputRuc.value;

      tablaDiv.innerHTML =
        '<p class="text-center text-muted py-4">Cargando información...</p>';

      fetch(`{{ route('sinDocumentos') }}?page=${page}&ruc=${ruc}`)
        .then(res => res.text())
        .then(html => tablaDiv.innerHTML = html)
        .catch(() => {
          tablaDiv.innerHTML =
            '<p class="text-center text-danger py-4">Error al cargar la información.</p>';
        });
    }

    
    modal.addEventListener('show.bs.modal', function() {
      inputRuc.value = '';
      cargarTabla();
    });

    
    btnBuscar.addEventListener('click', function() {
      cargarTabla();
    });

    
    inputRuc.addEventListener('keyup', function(e) {
      if (e.key === 'Enter') {
        cargarTabla();
      }
    });

    
    tablaDiv.addEventListener('click', function(e) {
      const link = e.target.closest('.pagination a');
      if (!link) return;

      e.preventDefault();

      const url = new URL(link.href);
      const page = url.searchParams.get('page');
      cargarTabla(page);
    });


  });
</script>





<div class="row g-4 justify-content-center">

  
  <div class="col-12 col-sm-6 col-md-4 col-lg-2">
    <div class="card shadow-sm p-3 d-flex flex-row align-items-center border-left-primary card-dashboard">
      <i class="fas fa-file-alt fa-2x text-primary me-3"></i>
      <div>
        <h5 class="mb-0">{{ $totalLicencias }}</h5>
        <small class="text-muted">Total Licencias</small>
      </div>
    </div>
  </div>



  
  <div class="col-12 col-sm-6 col-md-4 col-lg-2"">
            <div class=" card shadow-sm p-3 d-flex flex-row align-items-center border-left-success card-dashboard">
    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
    <div>
      <h5 class="mb-0">{{ $licenciasActivas }}</h5>
      <small class="text-muted">Activas</small>
    </div>
  </div>
</div>


<div class="col-12 col-sm-6 col-md-4 col-lg-2">
  <div class="card shadow-sm p-3 d-flex flex-row align-items-center border-left-danger card-dashboard">
    <i class="fas fa-times-circle fa-2x text-danger me-3"></i>
    <div>
      <h5 class="mb-0">{{ $licenciasInactivas }}</h5>
      <small class="text-muted">Inactivas</small>
    </div>
  </div>
</div>


<div class="col-12 col-sm-6 col-md-4 col-lg-2">
  <div class="card shadow-sm p-3 d-flex flex-row align-items-center border-left-warning card-dashboard">
    <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
    <div>
      <h5 class="mb-0">{{ $porVencer }}</h5>
      <small class="text-muted">Por Vencer</small>
    </div>
  </div>
</div>


<div class="col-12 col-sm-6 col-md-4 col-lg-2">
  <div class="card shadow-sm p-3 d-flex flex-row align-items-center border-left-purple card-dashboard">
    <i class="fas fa-ban fa-2x text-purple me-3"></i>
    <div>
      <h5 class="mb-0">{{ $vencidas }}</h5>
      <small class="text-muted">Vencidas</small>
    </div>
  </div>
</div>


<div class="col-12 col-sm-6 col-md-4 col-lg-2">
  <div class="card shadow-sm p-3 d-flex flex-row align-items-center border-left-info  card-dashboard">
    
    <a class="btn btn-sm btn-info text-white position-absolute"
      style="top: 0.5rem; right: 0.5rem; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 0.25rem;"
      data-bs-toggle="modal" data-bs-target="#modalSinDocumentos">
      Ver
    </a>
    <i class="fas fa-file-circle-exclamation fa-2x text-info me-3"></i>
    <div>
      <h5 class="mb-0">{{ $faltantesLicencias ?? 0 }}</h5>
      <small class="text-muted">Sin C & R</small>
    </div>
  </div>

</div>

</div>

<br>


<div class="container">
  <h3 class="mb-4 fw-bold">Panel de busqueda</h3>

  
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form class="row g-3" method="GET" action="{{ route('main') }}">
       
        <div class="col-md-4">
          <label for="search" class="form-label fw-semibold">Búsqueda</label>
          <input type="text" id="search" name="search" class="form-control"
            placeholder="Buscar por persona, local, DNI o RUC" value="{{ request('search') }}">
        </div>

        
        <div class="col-md-3">
          <label for="estado" class="form-label fw-semibold">Estado</label>
          <select id="estado" name="estado" class="form-select">
            <option value="">-- Todos --</option>
            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            <option value="vencida" {{ request('estado') == 'vencida' ? 'selected' : '' }}>Vencida</option>
            <option value="por_vencer" {{ request('estado') == 'por_vencer' ? 'selected' : '' }}>Por vencer</option>
          </select>
        </div>

        
        <div class="col-md-3">
          <label for="sector" class="form-label fw-semibold">Sector</label>
          <select id="sector" name="sector" class="form-select">
            <option value="">-- Seleccionar --</option>
            @foreach($sectores as $sector)
            <option value="{{ $sector->idSector }}" {{ request('sector') == $sector->idSector ? 'selected' : '' }}>
              {{ $sector->nombre }}
            </option>
            @endforeach
          </select>
        </div>

       
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fa-solid fa-magnifying-glass me-1"></i> Buscar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="row g-4">
  @foreach ($data as $item)
  <div class="col-md-4">
    <div class="bg-white rounded-3 shadow-sm border p-4 h-100 d-flex flex-column">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h5 class="fw-bold mb-1">{{ $item->nombre_comercial }}</h5>
          <p class="text-muted small mb-0">{{ $item->nombre_completos }}</p>
        </div>
        <span class="badge 
    @if ($item->estado == 0)
        bg-danger
    @elseif ($item->estado_vencimiento === 'vencida')
        bg-purple text-white
    @elseif ($item->estado_vencimiento === 'por_vencer')
        bg-warning text-white
    @else
        bg-success
    @endif">
          @if ($item->estado == 0)
          Inactivo
          @elseif ($item->estado_vencimiento === 'vencida')
          Vencida
          @elseif ($item->estado_vencimiento === 'por_vencer')
          Por vencer
          @else
          Activa
          @endif
        </span>


      </div>

      <div class="mb-3">
        <p class="text-muted small mb-1">
          <i class="fa-solid fa-map-pin me-2"></i>
          {{ $item->nombre_via }} {{ $item->nMunicipal }} - {{ $item->nombre_sector }}
        </p>
        <p class="text-muted small mb-1"><i class="fa-solid fa-id-card me-2"></i>DNI: {{ $item->dni }}</p>
        <p class="text-muted small mb-1"><i class="fa-solid fa-briefcase me-2"></i>RUC: <span class="ruc">{{ $item->ruc }}</span></p>

        <p class="text-muted small"><i class="fa-solid fa-calendar-days me-2"></i>Vence: {{ \Carbon\Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') }}</p>
      </div>

      <div class="mt-auto d-flex justify-content-between pt-3 border-top">
        <button class="btn btn-outline-primary btn-sm ver-detalle" data-id="{{ $item->idDetalle }}">
          Ver Detalle
        </button>


      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="mt-4 d-flex flex-column align-items-center">
  {{ $data->links('pagination::bootstrap-5') }}
</div>




<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

     
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fa-solid fa-tachograph-digital me-2 text-primary"></i> Detalles del Establecimiento
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      
      <div class="modal-body" id="detalleContenido">
        <div class="text-center text-muted py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Cargando información...</p>
        </div>
      </div>

      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-2"></i> Cerrar
        </button>
      </div>

    </div>
  </div>
</div>



<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
    const detalleContenido = document.getElementById('detalleContenido');

    document.querySelectorAll('.ver-detalle').forEach(boton => {
      boton.addEventListener('click', function() {

       
        const idDetalle = this.getAttribute('data-id');

        detalleContenido.innerHTML = `
        <div class="text-center text-muted py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Cargando información...</p>
        </div>
      `;

        
        fetch(`/ver-detalle/${idDetalle}`)
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              detalleContenido.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
              return;
            }

            const direccionCompleta = `${data.nombre_via ?? ''} ${data.nMunicipal ?? ''} - ${data.sector ?? ''}`.trim();
            const estado = data.estado_texto ?? 'Desconocido';
            let color = '';

            switch (estado.toLowerCase()) {
              case 'licencia activa':
                color = 'badge bg-success';
                break;
              case 'licencia inactiva':
                color = 'badge bg-danger';
                break;
              case 'por vencer':
                color = 'badge bg-warning text-dark';
                break;
              case 'licencia vencida':
                color = 'badge bg-secondary';
                break;
              default:
                color = 'badge bg-light text-dark border';
                break;
            }

            detalleContenido.innerHTML = `
            <div class="container-fluid">

              <!-- Encabezado -->
              <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                  <h5 class="fw-bold text-primary mb-1">${data.nombre_comercial ?? 'Sin nombre comercial'}</h5>
                  <span class="text-muted small">${data.giro_autorizado ?? 'Giro no registrado'}</span>
                </div>
                <div>
                  <span class="${color} px-3 py-2">${estado}</span>
                </div>
              </div>

              <div class="row g-4">

                <!-- Información del Local -->
                <div class="col-md-6">
                  <h6 class="mb-3"><i class="fa-solid fa-store me-2 text-success"></i> Información del Local</h6>
                  <div class="ps-2">
                    <p><strong>Propietario:</strong> ${data.propietario ?? '-'}</p>
                    <p><strong>DNI:</strong> ${data.dni ?? '-'}</p>
                    <p><strong>RUC:</strong> ${data.ruc ?? '-'}</p>
                    <p><strong>Dirección:</strong> ${direccionCompleta}</p>
                  </div>
                </div>

                <!-- Información de Licencia -->
                <div class="col-md-6">
                  <h6 class="mb-3"><i class="fa-solid fa-file-contract me-2 text-warning"></i> Información de Licencia</h6>
                  <div class="ps-2">
                    <p><strong>N° de Sobre:</strong> ${data.numero_sobre ?? '-'}</p>
                    <p><strong>F. Autorización:</strong> ${data.fecha_autorizacion ?? '-'}</p>
                    <p><strong>F. Certificado:</strong> ${data.fecha_certificado ?? '-'}</p>
                    <p><strong>F. Vencimiento:</strong> ${data.fecha_vencimiento ?? '-'}</p>
                  </div>
                </div>

              </div>

              <!-- Descripción -->
              <div class="mt-4">
                <h6 class="mb-3"><i class="fa-solid fa-note-sticky me-2 text-info"></i> Descripción</h6>
                <p class="text-muted ps-2">${data.descripcion ?? 'Sin descripción registrada.'}</p>
              </div>

              <!-- Documentos -->
              <div class="mt-5 border-top pt-4">
                <h6 class="mb-3"><i class="fa-solid fa-file-pdf me-2 text-danger"></i> Documentos Adjuntos</h6>
                <div class="row g-4">

                  <!-- Certificado -->
                  <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                      <div class="card-body">
                        ${data.certificado_pdf ? `
                          <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 me-3">
                              <i class="fa-solid fa-file-pdf fa-lg"></i>
                            </div>
                            <div>
                              <h6 class="fw-bold mb-1 text-primary">Certificado PDF</h6>
                              <small class="text-muted">Documento oficial del establecimiento</small>
                            </div>
                          </div>
                          <a href="/storage/${data.certificado_pdf}" target="_blank" 
                             class="btn btn-outline-primary btn-sm mt-3 w-100">
                            <i class="fa-solid fa-eye me-2"></i> Ver documento
                          </a>
                        ` : `
                          <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-file-circle-xmark fa-2x mb-2"></i>
                            <p class="mb-0">No se encontró el certificado</p>
                          </div>
                        `}
                      </div>
                    </div>
                  </div>

                  <!-- Resolución -->
                  <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                      <div class="card-body">
                        ${data.resolucion_pdf ? `
                          <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3">
                              <i class="fa-solid fa-file-pdf fa-lg"></i>
                            </div>
                            <div>
                              <h6 class="fw-bold mb-1 text-success">Resolución PDF</h6>
                              <small class="text-muted">Documento de aprobación o resolución</small>
                            </div>
                          </div>
                          <a href="/storage/${data.resolucion_pdf}" target="_blank" 
                             class="btn btn-outline-success btn-sm mt-3 w-100">
                            <i class="fa-solid fa-eye me-2"></i> Ver documento
                          </a>
                        ` : `
                          <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-file-circle-xmark fa-2x mb-2"></i>
                            <p class="mb-0">No se encontró la resolución</p>
                          </div>
                        `}
                      </div>
                    </div>
                  </div>

                </div>
              </div>

            </div>
          `;
          })
          .catch(error => {
            console.error(error);
            detalleContenido.innerHTML = `
            <div class="alert alert-danger text-center">
              <i class="fa-solid fa-triangle-exclamation me-2"></i>
              Ocurrió un error al cargar los detalles.
            </div>
          `;
          });

        modal.show();
      });
    });
  });
</script>




<!-- ================= Map + Search ================= -->
<hr class="my-4">

<div class="card shadow-sm mb-4">
  <div class="card-header bg-primary text-white">
    <i class="fa-solid fa-map-location-dot me-2"></i> Localizador - Buscar dirección
  </div>
  <div class="card-body">
    <!-- Campo de búsqueda -->
    <div class="mb-3">
      <input id="searchInput" class="form-control" type="text"
        placeholder="Busca una dirección (ej: Av. Enrique Valenzuela 287)" autocomplete="off">
    </div>

    <!-- Mapa -->
    <div id="map" style="height:420px; width:100%; border-radius:8px;"></div>

    <!-- Info de la selección -->
    <div id="locationInfo" class="mt-3 text-muted small"></div>
  </div>
</div>







<!-- ================= Script: initMap + Autocomplete ================= -->
<script>
  let map, marker, autocomplete, geocoder;

  function initMap() {
    // Coordenadas por defecto (puedes cambiar)
    const defaultLocation = {
      lat: -7.4000,
      lng: -79.5700
    };

    // Inicializa mapa
    map = new google.maps.Map(document.getElementById("map"), {
      center: defaultLocation,
      zoom: 14,
      streetViewControl: false,
      mapTypeControl: false,
    });

    // Marcador (vacío al inicio)
    marker = new google.maps.Marker({
      map,
      position: defaultLocation,
      draggable: false,
      visible: false
    });

    // Geocoder para fallback (opcional)
    geocoder = new google.maps.Geocoder();

    // Autocomplete (Places)
    const input = document.getElementById("searchInput");
    autocomplete = new google.maps.places.Autocomplete(input, {
      // types: ['address'], // opcional: restringir a direcciones
      // componentRestrictions: { country: 'pe' } // restringir país si quieres
    });

    // Pedimos campos mínimos (geometry para coordenadas, formatted_address)
    autocomplete.setFields(["formatted_address", "geometry", "name", "address_components"]);

    // Cuando el usuario selecciona una sugerencia
    autocomplete.addListener("place_changed", () => {
      const place = autocomplete.getPlace();

      if (place.geometry && place.geometry.location) {
        // Si Places devuelve geometry, usarla
        const loc = place.geometry.location;
        map.setCenter(loc);
        map.setZoom(17);
        marker.setPosition(loc);
        marker.setVisible(true);

        // Mostrar info
        const lat = loc.lat();
        const lng = loc.lng();
        const direccion = place.formatted_address || place.name || input.value;

        document.getElementById("locationInfo").innerHTML =
          `<strong>Dirección:</strong> ${direccion} &nbsp; • &nbsp; <strong>Lat:</strong> ${lat.toFixed(6)} &nbsp; <strong>Lng:</strong> ${lng.toFixed(6)}`;
      } else {
        // Si no devuelve geometry, intentar con Geocoder usando la cadena ingresada
        const address = input.value;
        if (address && address.trim() !== "") {
          geocodeAddress(address);
        } else {
          alert("No se pudo obtener la ubicación. Intenta una dirección diferente.");
        }
      }
    });

    // Si el usuario presiona Enter sin seleccionar, intentar geocodificar
    input.addEventListener("keydown", function(e) {
      if (e.key === "Enter") {
        e.preventDefault();
        const address = input.value;
        if (address && address.trim() !== "") {
          geocodeAddress(address);
        }
      }
    });
  }

  // Función fallback: Geocoding (si Autocomplete no dio geometry)
  function geocodeAddress(address) {
    geocoder.geocode({
      address: address
    }, (results, status) => {
      if (status === "OK" && results[0]) {
        const loc = results[0].geometry.location;
        map.setCenter(loc);
        map.setZoom(17);
        marker.setPosition(loc);
        marker.setVisible(true);

        const formatted = results[0].formatted_address;
        document.getElementById("locationInfo").innerHTML =
          `<strong>Dirección (geocodificada):</strong> ${formatted} &nbsp; • &nbsp; <strong>Lat:</strong> ${loc.lat().toFixed(6)} &nbsp; <strong>Lng:</strong> ${loc.lng().toFixed(6)}`;
      } else {
        document.getElementById("locationInfo").innerHTML =
          `<span class="text-danger">No se encontró la ubicación. Intenta otra dirección.</span>`;
      }
    });
  }
</script>


<!-- Cargar Google Maps JS con Places (reemplaza TU_API_KEY_AQUI) -->
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=&libraries=places&callback=initMap">
</script>



@endsection