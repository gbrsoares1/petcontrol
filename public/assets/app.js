// Escapa texto para evitar injeção de HTML na tabela
function esc(t) {
    return $('<div>').text(t ?? '').html();
}

function mostrarErro(form, xhr) {
    const msg = (xhr.responseJSON && xhr.responseJSON.erro) || 'Erro ao salvar.';
    $(form).find('.erro-form').text(msg).removeClass('d-none');
}

function preencherForm(form, dados) {
    $(form)[0].reset();
    $(form).find('.erro-form').addClass('d-none');
    Object.keys(dados).forEach(k => $(form).find('[name="' + k + '"]').val(dados[k]));
}

// ======================= CLIENTES =======================
const modalCliente = new bootstrap.Modal('#modal-cliente');

function carregarClientes() {
    $.getJSON('api/clientes.php', { acao: 'listar', busca: $('#busca-clientes').val() }, r => {
        const linhas = r.dados.map(c => `
            <tr>
                <td>${esc(c.nome)}</td><td>${esc(c.telefone)}</td><td>${esc(c.email)}</td>
                <td>${esc(c.cidade)}${c.uf ? '/' + esc(c.uf) : ''}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="editarCliente(${c.id})">Editar</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="excluirCliente(${c.id})">Excluir</button>
                </td>
            </tr>`).join('');
        $('#tbody-clientes').html(linhas || '<tr><td colspan="5" class="text-center text-muted">Nenhum cliente cadastrado.</td></tr>');
    });
}

function editarCliente(id) {
    $.getJSON('api/clientes.php', { acao: 'buscar', id }, r => {
        preencherForm('#form-cliente', r.dados);
        $('#cep-status').text('');
        modalCliente.show();
    });
}

function excluirCliente(id) {
    if (!confirm('Excluir este cliente? Os pets dele também serão excluídos.')) return;
    $.post('api/clientes.php', { acao: 'excluir', id }, () => { carregarClientes(); carregarPets(); });
}

$('#novo-cliente').on('click', () => {
    preencherForm('#form-cliente', {});
    $('#cep-status').text('');
    modalCliente.show();
});

$('#form-cliente').on('submit', function (e) {
    e.preventDefault();
    $.post('api/clientes.php', $(this).serialize() + '&acao=salvar')
        .done(() => { modalCliente.hide(); carregarClientes(); })
        .fail(xhr => mostrarErro(this, xhr));
});

$('#busca-clientes').on('input', carregarClientes);

// ---- Integração externa: API ViaCEP (preenche o endereço pelo CEP) ----
$('#cep').on('input', function () {
    let v = $(this).val().replace(/\D/g, '').slice(0, 8);
    $(this).val(v.length > 5 ? v.slice(0, 5) + '-' + v.slice(5) : v);

    if (v.length === 8) {
        $('#cep-status').text('Buscando endereço...');
        $.getJSON('https://viacep.com.br/ws/' + v + '/json/')
            .done(d => {
                if (d.erro) { $('#cep-status').text('CEP não encontrado.'); return; }
                const f = $('#form-cliente');
                f.find('[name="logradouro"]').val(d.logradouro);
                f.find('[name="bairro"]').val(d.bairro);
                f.find('[name="cidade"]').val(d.localidade);
                f.find('[name="uf"]').val(d.uf);
                $('#cep-status').text('Endereço preenchido ✔');
            })
            .fail(() => $('#cep-status').text('Não foi possível consultar o CEP.'));
    }
});

// ========================= PETS =========================
const modalPet = new bootstrap.Modal('#modal-pet');

function carregarPets() {
    $.getJSON('api/pets.php', { acao: 'listar', busca: $('#busca-pets').val() }, r => {
        const linhas = r.dados.map(p => `
            <tr>
                <td>${esc(p.nome)}</td><td>${esc(p.especie)}</td><td>${esc(p.raca)}</td><td>${esc(p.tutor)}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="editarPet(${p.id})">Editar</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="excluirPet(${p.id})">Excluir</button>
                </td>
            </tr>`).join('');
        $('#tbody-pets').html(linhas || '<tr><td colspan="5" class="text-center text-muted">Nenhum pet cadastrado.</td></tr>');
    });
}

// Preenche o <select> de tutores com os clientes cadastrados
function carregarTutores(callback) {
    $.getJSON('api/clientes.php', { acao: 'listar' }, r => {
        const opcoes = r.dados.map(c => `<option value="${c.id}">${esc(c.nome)}</option>`).join('');
        $('#select-tutor').html(opcoes);
        if (callback) callback(r.dados.length);
    });
}

function editarPet(id) {
    carregarTutores(() => {
        $.getJSON('api/pets.php', { acao: 'buscar', id }, r => {
            preencherForm('#form-pet', r.dados);
            modalPet.show();
        });
    });
}

function excluirPet(id) {
    if (!confirm('Excluir este pet?')) return;
    $.post('api/pets.php', { acao: 'excluir', id }, carregarPets);
}

$('#novo-pet').on('click', () => {
    carregarTutores(qtd => {
        if (qtd === 0) { alert('Cadastre um cliente antes de cadastrar um pet.'); return; }
        preencherForm('#form-pet', {});
        modalPet.show();
    });
});

$('#form-pet').on('submit', function (e) {
    e.preventDefault();
    $.post('api/pets.php', $(this).serialize() + '&acao=salvar')
        .done(() => { modalPet.hide(); carregarPets(); })
        .fail(xhr => mostrarErro(this, xhr));
});

$('#busca-pets').on('input', carregarPets);

// Início
carregarClientes();
carregarPets();
