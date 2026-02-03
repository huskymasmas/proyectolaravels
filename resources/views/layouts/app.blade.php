<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Concretos y Agregados Oriente') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 1rem;
            transition: all 0.3s ease;
            overflow-y: auto;
            z-index: 1040;
        }
        #sidebar.collapsed { margin-left: -250px; }
        #sidebar .nav-link.active { background-color: #0d6efd; color: white; }
        #content { transition: all 0.3s ease; padding: 1rem; margin-left: 250px; }
        #content.expanded { margin-left: 0; }
        #toggleSidebar { position: fixed; top: 15px; left: 15px; z-index: 1050; }

        @media (max-width: 768px) {
            #sidebar { margin-left: -250px; }
            #sidebar.show { margin-left: 0; }
            #content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div id="app">

@php
    $isAdmin = Auth::check() && Auth::user()->hasRole('admin');
    $routeExists = fn($name) => Route::has($name);
@endphp

@if($isAdmin)

    <!-- Botón toggle -->
    <button class="btn btn-primary" id="toggleSidebar">☰</button>

    <!-- Sidebar -->
    <nav id="sidebar" class="bg-light border-end">
        <br><br>

        <!-- 🔍 BUSCADOR -->
        <input 
            type="text" 
            id="menuSearch" 
            class="form-control mb-3" 
            placeholder="Buscar en el menú..."
        >

        <ul class="nav flex-column" id="menuList">

     
            <!-- 2️⃣ Trabajos -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#trabajosMenu" role="button" aria-expanded="false" aria-controls="trabajosMenu">
                    Trabajos
                </a>
                <div class="collapse" id="trabajosMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('trabajo.index'))
                        <li class="nav-item">
                            <a href="{{ route('trabajo.index') }}" class="nav-link {{ request()->is('trabajo*') ? 'active' : '' }}">
                                Trabajos
                            </a>
                        </li>
                        @endif
                        @if($routeExists('avances.index'))
                        <li class="nav-item">
                            <a href="{{ route('avances.index') }}" class="nav-link {{ request()->is('avances*') ? 'active' : '' }}">
                                Trabajos Avances
                            </a>
                        </li>
                        @endif
                        @if($routeExists('estado_trabajo.index'))
                        <li class="nav-item">
                            <a href="{{ route('estado_trabajo.index') }}" class="nav-link {{ request()->is('estado_trabajo*') ? 'active' : '' }}">
                                Estado Trabajo
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- 3️⃣ Proyectos -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#proyectosMenu" role="button" aria-expanded="false" aria-controls="proyectosMenu">
                    Proyectos
                </a>
                <div class="collapse" id="proyectosMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('proyectos.index'))
                        <li class="nav-item">
                            <a href="{{ route('proyectos.index') }}" class="nav-link {{ request()->is('proyectos*') ? 'active' : '' }}">
                                Proyectos
                            </a>
                        </li>
                        @endif
                        @if($routeExists('aldea.index'))
                        <li class="nav-item">
                            <a href="{{ route('aldea.index') }}" class="nav-link {{ request()->is('aldea*') ? 'active' : '' }}">
                                Aldeas
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- 4️⃣ Concreto y Tramos -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#concretoMenu" role="button" aria-expanded="false" aria-controls="concretoMenu">
                    Concreto y Tramos
                </a>
                <div class="collapse" id="concretoMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('control_concreto_campo.index'))
                        <li class="nav-item">
                            <a href="{{ route('control_concreto_campo.index') }}" class="nav-link {{ request()->is('control_concreto_campo*') ? 'active' : '' }}">
                                Concreto en Campo
                            </a>
                        </li>
                        @endif
                        @if($routeExists('tramo_aplicacion.index'))
                        <li class="nav-item">
                            <a href="{{ route('tramo_aplicacion.index') }}" class="nav-link {{ request()->is('tramo_aplicacion*') ? 'active' : '' }}">
                                Tramos Aplicación
                            </a>
                        </li>
                        @endif
                        @if($routeExists('tramos.index'))
                        <li class="nav-item">
                            <a href="{{ route('tramos.index') }}" class="nav-link {{ request()->is('tramos*') ? 'active' : '' }}">
                                Tramos
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- 5️⃣ Vale Egreso / Ingreso -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#valesMenu" role="button" aria-expanded="false" aria-controls="valesMenu">
                    Vale Egreso / Ingreso
                </a>
                <div class="collapse" id="valesMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('vale_egreso.index'))
                        <li class="nav-item"><a href="{{ route('vale_egreso.index') }}" class="nav-link {{ request()->is('vale_egreso*') ? 'active' : '' }}">Vale Egreso Concreto</a></li>
                        @endif
                        @if($routeExists('vale_ingreso_material.index'))
                        <li class="nav-item"><a href="{{ route('vale_ingreso_material.index') }}" class="nav-link {{ request()->is('vale_ingreso_material*') ? 'active' : '' }}">Vale Ingreso Material</a></li>
                        @endif
                        @if($routeExists('vale_egreso_material.index'))
                        <li class="nav-item"><a href="{{ route('vale_egreso_material.index') }}" class="nav-link {{ request()->is('vale_egreso_material*') ? 'active' : '' }}">Vale Egreso Material</a></li>
                        @endif
                        @if($routeExists('valeegreso_maquinaria.index'))
                        <li class="nav-item"><a href="{{ route('valeegreso_maquinaria.index') }}" class="nav-link {{ request()->is('valeegreso_maquinaria*') ? 'active' : '' }}">Vale Egreso Maquinaria y Equipo</a></li>
                        @endif
                        @if($routeExists('valeingres_maquinaria.index'))
                        <li class="nav-item"><a href="{{ route('valeingres_maquinaria.index') }}" class="nav-link {{ request()->is('valeingres_maquinaria*') ? 'active' : '' }}">Vale Ingreso Maquinaria y Equipo</a></li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- 6️⃣ Facturas -->
            <li class="nav-item">
                <a href="{{ route('facturas.index') }}" class="nav-link {{ request()->is('facturas*') ? 'active' : '' }}">Facturas</a>
            </li>

            <!-- 7️⃣ Bodega -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#bodegaMenu" role="button" aria-expanded="false" aria-controls="bodegaMenu">
                    Bodega
                </a>
                <div class="collapse" id="bodegaMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('bodega.index'))
                        <li class="nav-item"><a href="{{ route('bodega.index') }}" class="nav-link {{ request()->is('bodega*') ? 'active' : '' }}">Bodega General</a></li>
                        @endif
                        @if($routeExists('bodega_proyecto.index'))
                        <li class="nav-item"><a href="{{ route('bodega_proyecto.index') }}" class="nav-link {{ request()->is('bodegaproyecto*') ? 'active' : '' }}">Bodega Proyecto</a></li>
                        @endif
                        @if($routeExists('estacionbodega.index'))
                        <li class="nav-item"><a href="{{ route('estacionbodega.index') }}" class="nav-link {{ request()->is('estacionbodega*') ? 'active' : '' }}">Estación Bodega</a></li>
                        @endif
                        @if($routeExists('bodegaparaproyectos.index'))
                        <li class="nav-item"><a href="{{ route('bodegaparaproyectos.index') }}" class="nav-link {{ request()->is('bodegaparaproyectos*') ? 'active' : '' }}">Bodega Proyectos</a></li>
                        @endif
                        @if($routeExists('maquinauso.index'))
                        <li class="nav-item"><a href="{{ route('maquinauso.index') }}" class="nav-link {{ request()->is('maquinauso*') ? 'active' : '' }}">Maquina Uso</a></li>
                        @endif
                        @if($routeExists('transferencia.index'))
                        <li class="nav-item"><a href="{{ route('transferencia.index') }}" class="nav-link {{ request()->is('transferencia*') ? 'active' : '' }}">Transferencias a Bodegas</a></li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- 8️⃣ Empleados / Nómina -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#empleadosMenu" role="button" aria-expanded="false" aria-controls="empleadosMenu">
                    Empleados / Nómina
                </a>
                <div class="collapse" id="empleadosMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('departamentos.index'))
                        <li class="nav-item"><a href="{{ route('departamentos.index') }}" class="nav-link {{ request()->is('departamentos*') ? 'active' : '' }}">Departamentos</a></li>
                        @endif
                        @if($routeExists('roles.index'))
                        <li class="nav-item"><a href="{{ route('roles.index') }}" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">Roles</a></li>
                        @endif
                        @if($routeExists('empleados.index'))
                        <li class="nav-item"><a href="{{ route('empleados.index') }}" class="nav-link {{ request()->is('empleados*') ? 'active' : '' }}">Empleados</a></li>
                        @endif
                        @if($routeExists('reportes.nomina.index'))
                        <li class="nav-item"><a href="{{ route('reportes.nomina.index') }}" class="nav-link {{ request()->is('reportes.nomina*') ? 'active' : '' }}">Nomina</a></li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- 9️⃣ FORMATO GENERAL PLANTA DE PRODUCCIÓN -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#formatoGeneralMenu" role="button" aria-expanded="false" aria-controls="formatoGeneralMenu">
                    Formato General Planta de Producción
                </a>
                <div class="collapse" id="formatoGeneralMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('Configuracion.index'))
                        <li class="nav-item"><a href="{{ route('Configuracion.index') }}" class="nav-link {{ request()->is('Configuracion*') ? 'active' : '' }}">Configuración</a></li>
                        @endif
                        @if($routeExists('dosificacion.index'))
                        <li class="nav-item"><a href="{{ route('dosificacion.index') }}" class="nav-link {{ request()->is('dosificacion*') ? 'active' : '' }}">Dosificación</a></li>
                        @endif
                        @if($routeExists('requerimientos.index'))
                        <li class="nav-item"><a href="{{ route('requerimientos.index') }}" class="nav-link {{ request()->is('requerimientos*') ? 'active' : '' }}">Requerimientos</a></li>
                        @endif
                        @if($routeExists('detalles.index'))
                        <li class="nav-item"><a href="{{ route('detalles.index') }}" class="nav-link {{ request()->is('detalles*') ? 'active' : '' }}">Detalles de Obra</a></li>
                        @endif
                        @if($routeExists('control_produccion.index'))
                        <li class="nav-item"><a href="{{ route('control_produccion.index') }}" class="nav-link {{ request()->is('control_produccion*') ? 'active' : '' }}">Control de Producción</a></li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- 🔟 FORMATO Control de Calidad -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#controlCalidadMenu" role="button" aria-expanded="false" aria-controls="controlCalidadMenu">
                    Formato Control de Calidad
                </a>
                <div class="collapse" id="controlCalidadMenu">
                    <ul class="nav flex-column ms-3">
                        @if($routeExists('control_concreto_campo.index'))
                        <li class="nav-item"><a href="{{ route('control_concreto_campo.index') }}" class="nav-link {{ request()->is('control_concreto_campo*') ? 'active' : '' }}">Control de Concreto de Campo</a></li>
                        @endif
                        @if($routeExists('formato_control_despacho_planta.index'))
                        <li class="nav-item"><a href="{{ route('formato_control_despacho_planta.index') }}" class="nav-link {{ request()->is('formato_control_despacho_planta*') ? 'active' : '' }}">Formato Control Despacho Planta</a></li>
                        @endif
                        @if($routeExists('tramo_aplicacion.index'))
                        <li class="nav-item"><a href="{{ route('tramo_aplicacion.index') }}" class="nav-link {{ request()->is('tramo_aplicacion*') ? 'active' : '' }}">Antisol</a></li>
                        @endif
                        @if($routeExists('tramos.index'))
                        <li class="nav-item"><a href="{{ route('tramos.index') }}" class="nav-link {{ request()->is('tramos*') ? 'active' : '' }}">Supervisor en Tramo</a></li>
                        @endif
                    </ul>
                </div>
            </li>

            <!-- Logout -->
            <li class="nav-item mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>
            </li>

        </ul>
    </nav>

    <!-- Contenido principal -->
    <div id="content">
        @yield('content')
    </div>

@else
    @include('header')
    <main class="py-4">
        @yield('content')
    </main>
@endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    toggleButton.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('show');
        } else {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
        }
    });

    // 🔍 BUSCADOR DEL MENÚ
    document.getElementById("menuSearch").addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll("#menuList .nav-item");

        items.forEach(li => {
            let text = li.innerText.toLowerCase();
            li.style.display = text.includes(filter) ? "" : "none";
        });
    });
</script>

@stack('scripts')
</body>
</html>
