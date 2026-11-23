<?php
require_once __DIR__ . '/../src/Auth.php';
Auth::exigirPagina();
$usuario = Auth::usuario();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PetControl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">🐾 PetControl</span>
        <span class="text-white">
            Olá, <?= htmlspecialchars($usuario['nome']) ?>
            <a href="logout.php" class="btn btn-sm btn-light ms-3">Sair</a>
        </span>
    </div>
</nav>

<div class="container">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#aba-clientes">Clientes</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#aba-pets">Pets</button></li>
    </ul>

    <div class="tab-content">
        <!-- CLIENTES -->
        <div class="tab-pane fade show active" id="aba-clientes">
            <div class="d-flex justify-content-between mb-3">
                <input type="search" id="busca-clientes" class="form-control w-50" placeholder="Buscar cliente...">
                <button class="btn btn-success" id="novo-cliente">+ Novo cliente</button>
            </div>
            <table class="table table-striped bg-white">
                <thead><tr><th>Nome</th><th>Telefone</th><th>E-mail</th><th>Cidade/UF</th><th style="width:150px">Ações</th></tr></thead>
                <tbody id="tbody-clientes"></tbody>
            </table>
        </div>

        <!-- PETS -->
        <div class="tab-pane fade" id="aba-pets">
            <div class="d-flex justify-content-between mb-3">
                <input type="search" id="busca-pets" class="form-control w-50" placeholder="Buscar pet...">
                <button class="btn btn-success" id="novo-pet">+ Novo pet</button>
            </div>
            <table class="table table-striped bg-white">
                <thead><tr><th>Nome</th><th>Espécie</th><th>Raça</th><th>Tutor</th><th style="width:150px">Ações</th></tr></thead>
                <tbody id="tbody-pets"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL CLIENTE -->
<div class="modal fade" id="modal-cliente" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form id="form-cliente">
            <div class="modal-header"><h5 class="modal-title">Cliente</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="alert alert-danger d-none erro-form"></div>
                <input type="hidden" name="id">
                <div class="row g-3">
                    <div class="col-md-12"><label class="form-label">Nome *</label><input name="nome" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Telefone</label><input name="telefone" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control"></div>
                    <div class="col-md-4">
                        <label class="form-label">CEP</label>
                        <input name="cep" id="cep" class="form-control" maxlength="9" placeholder="00000-000">
                        <div class="form-text" id="cep-status"></div>
                    </div>
                    <div class="col-md-8"><label class="form-label">Logradouro</label><input name="logradouro" class="form-control"></div>
                    <div class="col-md-5"><label class="form-label">Bairro</label><input name="bairro" class="form-control"></div>
                    <div class="col-md-5"><label class="form-label">Cidade</label><input name="cidade" class="form-control"></div>
                    <div class="col-md-2"><label class="form-label">UF</label><input name="uf" class="form-control" maxlength="2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div></div>
</div>

<!-- MODAL PET -->
<div class="modal fade" id="modal-pet" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form id="form-pet">
            <div class="modal-header"><h5 class="modal-title">Pet</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="alert alert-danger d-none erro-form"></div>
                <input type="hidden" name="id">
                <div class="mb-3"><label class="form-label">Tutor *</label><select name="cliente_id" id="select-tutor" class="form-select" required></select></div>
                <div class="mb-3"><label class="form-label">Nome *</label><input name="nome" class="form-control" required></div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Espécie *</label>
                        <select name="especie" class="form-select" required>
                            <option>Cachorro</option><option>Gato</option><option>Ave</option><option>Roedor</option><option>Outro</option>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label">Raça</label><input name="raca" class="form-control"></div>
                </div>
                <div class="mb-3"><label class="form-label">Nascimento</label><input type="date" name="nascimento" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/app.js"></script>
</body>
</html>
