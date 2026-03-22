<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SQL Vault</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        min-height: 100vh;
        background: #0b0b0c;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: #111214;
        border: 1px solid #23252b;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .35);
    }

    .form-control {
        background: #181a1f;
        border: 1px solid #2b2f36;
        color: #fff;
    }

    .form-control:focus {
        background: #181a1f;
        color: #fff;
        border-color: #4b5563;
        box-shadow: none;
    }

    .brand {
        font-size: 28px;
        font-weight: 700;
    }

    .muted {
        color: #9ca3af;
    }

    .btn-light {
        font-weight: 600;
    }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="mb-4">
            <div class="brand">SQL Vault</div>
            <div class="muted">Entre com seu usuário para acessar o repositório.</div>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?php echo esc(session()->getFlashdata('error')) ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?php echo esc(session()->getFlashdata('success')) ?>
        </div>
        <?php endif; ?>

        <form action="<?php echo site_url('/login') ?>" method="post">
            <?php echo csrf_field() ?>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo old('email') ?>"
                    required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-light w-100">Entrar</button>
        </form>
    </div>
</body>

</html>