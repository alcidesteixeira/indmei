<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
        <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
        <li><a class="nav-link" href="{{ route('register') }}">{{ __('Registar') }}</a></li>
    @else
        @if (Auth::user()->hasRole('1'))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Gerir Permissões <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('roles/create') }}">Criar Permissão</a>
                    <a class="dropdown-item" href="{{ url('roles/list') }}">Listar Permissões</a>
                    <a class="dropdown-item" href="{{ url('roles/attribute') }}">Listar Utilizadores</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['1', '7']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="pointer-events: none; background-color: #cccccc">
                    Gestão de Orçamentação <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('quotation/create') }}">Criar Orçamentação</a>
                    <a class="dropdown-item" href="{{ url('quotation/list') }}">Listar Orçamentos Enviados</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['1', '4']))
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Gestão de Encomendas <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('orders/create') }}">Criar Encomenda</a>
                        <a class="dropdown-item" href="{{ url('orders/list') }}">Listar Encomendas</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Gestão de Fornecedores <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('suppliers/create') }}">Criar Fornecedor</a>
                        <a class="dropdown-item" href="{{ url('suppliers/list') }}">Listar Fornecedores</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Gestão de Clientes <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('clients/create') }}">Criar Cliente</a>
                        <a class="dropdown-item" href="{{ url('clients/list') }}">Listar Clientes</a>
                    </div>
                </li>
        @endif
        @if (Auth::user()->hasAnyRole(['1', '5']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Gestão de Armazém <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('stock/receipt') }}">Dar Entrada de Stock</a>
                    <a class="dropdown-item" href="{{ url('stock/create') }}">Criar Nova Matéria-Prima</a>
                    <a class="dropdown-item" href="{{ url('stock/list') }}">Listar Stock</a>
                    <a class="dropdown-item" href="{{ url('stock/request') }}" style="pointer-events: none; background-color: #cccccc">Solicitar Matéria-Prima</a>
                    <a class="dropdown-item" href="{{ url('stock/request/history') }}" style="pointer-events: none; background-color: #cccccc">Histórico de Pedidos de Matérias-Primas</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['1', '3']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Gestão de Amostras <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('samples/create') }}">Criar Nova Amostra de Artigo</a>
                    <a class="dropdown-item" href="{{ url('samples/list') }}">Listar Amostras de Artigos</a>
                </div>
            </li>
        @endif
        @if (Auth::user()->hasAnyRole(['1', '6']))
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Encomendas em Produção <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('orders/list') }}">Listar Encomendas para Produção</a>
                </div>
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
