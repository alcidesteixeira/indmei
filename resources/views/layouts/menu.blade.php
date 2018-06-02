<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
        <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
        <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
    @else
        @if (Auth::user()->hasRole('Admin'))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Roles <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('roles/create') }}">Criar Role</a>
                    <a class="dropdown-item" href="{{ url('roles/list') }}">Listar Roles</a>
                    <a class="dropdown-item" href="{{ url('roles/attribute') }}">Listar Utilizadores</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['Admin', 'Gestor de Encomenda']))
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Encomendas <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('roles/create') }}">Criar Encomenda</a>
                        <a class="dropdown-item" href="{{ url('roles/list') }}">Listar Encomendas</a>
                    </div>
                </li>
        @endif
        @if (Auth::user()->hasAnyRole(['Admin', 'Gestor de Armazém']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Armazém <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('roles/create') }}">Criar Nova Matéria-Prima</a>
                    <a class="dropdown-item" href="{{ url('roles/list') }}">Listar Matérias-Primas</a>
                    <a class="dropdown-item" href="{{ url('roles/list') }}">Solicitar Matéria-Prima</a>
                    <a class="dropdown-item" href="{{ url('roles/list') }}">Histórico de Matérias-Primas</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['Admin', 'Gestor de Artigo']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Artigo <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('roles/create') }}">Criar Novo Artigo</a>
                    <a class="dropdown-item" href="{{ url('roles/list') }}">Listar Artigos</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['Admin', 'Operário']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link" href="{{ url('roles/create') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                    Listar Encomendas </span>
                </a>
            </li>
        @endif

        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    @endguest
</ul>
