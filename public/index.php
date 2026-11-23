<?php
require_once __DIR__ . '/../src/Auth.php';

if (Auth::logado()) {
    header('Location: app.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PetControl - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width: 400px; margin-top: 12vh;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h3 class="text-center mb-1">🐾 PetControl</h3>
            <p class="text-center text-muted mb-4">Acesse o sistema</p>

            <div id="erro" class="alert alert-danger d-none"></div>

            <form id="form-login">
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="admin@petcontrol.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$('#form-login').on('submit', function (e) {
    e.preventDefault();
    $.post('api/login.php', $(this).serialize())
        .done(() => window.location = 'app.php')
        .fail(xhr => {
            const msg = (xhr.responseJSON && xhr.responseJSON.erro) || 'Falha no login.';
            $('#erro').text(msg).removeClass('d-none');
        });
});
</script>
</body>
</html>
