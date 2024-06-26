<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cadastro de Clientes</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body class="bg-dark text-white">
        <nav class="navbar bg-dark fixed-top border-bottom border-secondary py-3">
            <div class="w-100">
                <div class="row w-75 align-items-center mx-auto">
                    <div class="col ms- px-0">
                        <span id="spanNavbar" class="navbar-brand m-0 h1 text-white">
                            <a class="text-decoration-none text-white" href="index.php">CONTROLE DE CLIENTES</a>
                        </span>
                    </div>
                    <div class="col d-flex justify-content-end me- px-0">
                        <img id="imgLogo" src="{{ asset('Imagens/laravel_logo.png') }}">
                    </div>
                </div>
            </div>
        </nav>

        <nav class="navbar opacity-0 py-3">
            <div class="container-fluid">
                <span style="height:50px"></span>
            </div>
        </nav>

        <div id="divMain" class="w-75 mx-auto">
            <div class="border border-secondary rounded-2 mt-5 py-4 w-100">
                <div class="w-100 border-bottom border-secondary px-4 pb-4">
                    <button id="btnCadastrar" class="btn btn-success" onclick="mostrarModal('cadastro')"><i class="fa fa-add"></i> Cadastrar Cliente</button>
                </div>
                <div class="table-responsive mx-auto px-4">

                    <table class="table table-bordered table-dark table-striped table-hover text-center mt-3">
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Data de Nascimento</th>
                            <th>CEP</th>
                            <th>Endereço</th>
                            <th>Número</th>
                            <th>Bairro</th>
                            <th>Cidade</th>
                            <th>UF</th>
                            <th class="tableAcao">Ações</th>
                        </tr>

                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->id }}</td>
                                <td>{{ $cliente->nome }}</td>
                                <td>{{ $cliente->formatarData() }}</td>
                                <td>{{ $cliente->cep }}</td>
                                <td>{{ $cliente->endereco }}</td>
                                <td>{{ $cliente->numero == "" ? "(Sem Número)" : $cliente->numero }}</td>
                                <td>{{ $cliente->bairro }}</td>
                                <td>{{ $cliente->cidade }}</td>
                                <td>{{ $cliente->uf }}</td>
                                <td class="tableAcao">
                                    <button class="btnAcao btn btn-success p-0" onclick="mostrarModal('editar', {{ $cliente->id }})"><i class="iconAcao fa fa-pencil"></i></button>
                                    <button class="btnAcao btn btn-danger p-0" onclick="mostrarModalExcluir({{ $cliente->id }})"><i class="iconAcao fa fa-trash"></i></button>
                                </td>
                            </tr>

                            <div id="jsonCliente{{ $cliente->id }}" class="d-none">{{ json_encode($cliente) }}</div>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>


        <!-- Modal de Cadastro -->

        <div class="modal fade" id="modalForm" tabindex="-1">
            <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header border-secondary">
                <h5 class="modal-title">Título default</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCadastro" method="post" action="{{ route('cadastrar') }}">
                    <div class="modal-body">

                        {{ csrf_field() }}

                        <input id="inputCodigo" type="hidden" name="codigo">

                        <label class="form-label">Nome Completo</label>
                        <input id="ttbNome" class="form-control mb-3 bg-dark-subtle border-secondary" type="text" placeholder="João da Silva" name="nome" maxlength="120">

                        <label class="form-label">Data de Nascimento</label>
                        <input id="inputData" class="form-control mb-3 bg-dark-subtle border-secondary" type="date" name="dataNasc">

                        <label class="form-label">CEP</label>
                        <input id="ttbCep" class="form-control mb-3 bg-dark-subtle border-secondary" type="text" placeholder="12345-678" name="cep" maxlength="9">

                    </div>
                    <div class="modal-footer border-secondary">
                        <button id="btnCancelarModal" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btnConfirmarModal" type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>
            </div>
            </div>
        </div>

        <!-- Modal de Confirmação ao Excluir -->

        <div class="modal fade" id="modalExcluir" tabindex="-1">
            <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header border-secondary">
                <h5 class="modal-title">Atenção!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formDeletar" method="post" action="{{ route('deletar') }}">
                    <div class="modal-body">

                        {{ csrf_field() }}

                        <input id="inputCodigoExcluir" type="hidden" name="codigo">

                        <p>Tem certeza que deseja excluir este cadastro?</p>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-success">Sim</button>
                    </div>
                </form>
            </div>
            </div>
        </div>

        <!-- Alert -->

        <div class="alert alert-danger show fade d-none text-center fixed-bottom p-3 mb-0" role="alert">
            <i class="fa fa-trash me-1"></i> Você excluiu um cadastro
        </div>
        
        <script src="{{asset('script.js')}}"></script>
    </body>
</html>
