<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
        <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
        <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
    @else
        @if (Auth::user()->hasRole('1'))
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
        @if (Auth::user()->hasAnyRole(['1', '4']))
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
        @if (Auth::user()->hasAnyRole(['1', '5']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Gestão de Armazém <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('stock/create') }}">Inserir Nova Matéria-Prima</a>
                    <a class="dropdown-item" href="{{ url('stock/list') }}">Listar Matérias-Primas</a>
                    <a class="dropdown-item" href="{{ url('stock/request') }}">Solicitar Matéria-Prima</a>
                    <a class="dropdown-item" href="{{ url('stock/request/history') }}">Histórico de Pedidos de Matérias-Primas</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['1', '3']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Gestão de Amostra de Artigos <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('samples/create') }}">Criar Nova Amostra de Artigo</a>
                    <a class="dropdown-item" href="{{ url('samples/list') }}">Listar Amostras de Artigos</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['1', '6']))
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
