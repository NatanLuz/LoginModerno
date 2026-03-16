<?php
// Validando senha no servidor para o formulário de cadastro (sem banco de dados)

function validarSenha(string $senha): array
{
    $erros = [];

    if (strlen($senha) < 8) {
        $erros[] = 'A senha deve ter pelo menos 8 caracteres.';
    }
    if (!preg_match('/[A-Z]/', $senha)) {
        $erros[] = 'A senha deve conter pelo menos uma letra maiúscula.';
    }
    if (!preg_match('/[a-z]/', $senha)) {
        $erros[] = 'A senha deve conter pelo menos uma letra minúscula.';
    }
    if (!preg_match('/\d/', $senha)) {
        $erros[] = 'A senha deve conter pelo menos um número.';
    }
    if (!preg_match('/[^A-Za-z0-9]/', $senha)) {
        $erros[] = 'A senha deve conter pelo menos um símbolo (ex: ! @ # $ %).';
    }

    return $erros;
}

$mensagensErro = [];
$mensagemSucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $senha    = $_POST['password'] ?? '';
    $confirmarSenha = $_POST['password_confirm'] ?? '';

    if ($senha !== $confirmarSenha) {
        $mensagensErro[] = 'As senhas não coincidem.';
    }

    $mensagensErro = array_merge($mensagensErro, validarSenha($senha));

    if (empty($usuario) || empty($email)) {
        $mensagensErro[] = 'Usuário e e-mail são obrigatórios.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagensErro[] = 'E-mail inválido.';
    }

    if (empty($mensagensErro)) {
        // Aqui apenas simulamos o cadastro, sem banco de dados
        $mensagemSucesso = 'Cadastro realizado com sucesso! (simulação, sem banco de dados)';
    }
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Resultado do cadastro</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #223243;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .box {
        background: #1b2836;
        padding: 20px 30px;
        border-radius: 10px;
        max-width: 480px;
    }

    h1 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 20px;
    }

    ul {
        padding-left: 20px;
    }

    li {
        margin-bottom: 5px;
    }

    a {
        color: #00dfc4;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="box">
        <h1>Resultado do cadastro</h1>
        <?php if (!empty($mensagensErro)): ?>
        <p>O cadastro não pôde ser concluído pelos seguintes motivos:</p>
        <ul>
            <?php foreach ($mensagensErro as $erro): ?>
            <li><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p><?php echo htmlspecialchars($mensagemSucesso, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <p><a href="index.html">Voltar a tela de login</a></p>
    </div>
</body>

</html>