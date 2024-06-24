<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cadastro de Clientes</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Styles -->
        <link rel="stylesheet" href="<?php echo e(asset('style.css')); ?>">
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
                        <img id="imgLogo" src="<?php echo e(asset('Imagens/laravel_logo.png')); ?>">
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

                        <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($cliente->id); ?></td>
                                <td><?php echo e($cliente->nome); ?></td>
                                <td><?php echo e($cliente->formatarData()); ?></td>
                                <td><?php echo e($cliente->cep); ?></td>
                                <td><?php echo e($cliente->endereco); ?></td>
                                <td><?php echo e($cliente->numero == "" ? "(Sem Número)" : $cliente->numero); ?></td>
                                <td><?php echo e($cliente->bairro); ?></td>
                                <td><?php echo e($cliente->cidade); ?></td>
                                <td><?php echo e($cliente->uf); ?></td>
                                <td class="tableAcao">
                                    <button class="btnAcao btn btn-success p-0" onclick="mostrarModal('editar', <?php echo e($cliente->id); ?>)"><i class="iconAcao fa fa-pencil"></i></button>
                                    <form method="post" action="<?php echo e(route('deletar', $cliente->id)); ?>" class="d-inline">
                                        <?php echo e(csrf_field()); ?>

                                        <button class="btnAcao btn btn-danger p-0"><i class="iconAcao fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <div id="jsonCliente<?php echo e($cliente->id); ?>" class="d-none"><?php echo e(json_encode($cliente)); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <form id="formCadastro" method="post" action="<?php echo e(route('cadastrar')); ?>">
                <div class="modal-body">

                    <?php echo e(csrf_field()); ?>


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
        
        <script src="<?php echo e(asset('script.js')); ?>"></script>
    </body>
</html>
<?php /**PATH C:\Users\Zanshin\Documents\vsc_projetos\cadastro_de_clientes_laravel\resources\views/welcome.blade.php ENDPATH**/ ?>