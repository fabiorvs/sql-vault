<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SQL Vault') ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/layout.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="app-shell">
        <?= $this->include('layout/sidebar') ?>

        <div class="app-main">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menuToggle" type="button">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title"><?= esc($title ?? 'SQL Vault') ?></h1>
                </div>

                <div class="topbar-right">
                    <?php if (isset($topbarButtonLink, $topbarButtonLabel)): ?>
                    <a href="<?= esc($topbarButtonLink) ?>" class="btn-primary-top">
                        <?php if (! empty($topbarButtonIcon)): ?>
                        <i class="bi <?= esc($topbarButtonIcon) ?>"></i>
                        <?php endif; ?>
                        <span><?= esc($topbarButtonLabel) ?></span>
                    </a>
                    <?php endif; ?>
                </div>
            </header>

            <main class="content-area">
                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-custom alert-success-custom">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-custom alert-error-custom">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <script>
    const body = document.body;
    const menuToggle = document.getElementById('menuToggle');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            body.classList.toggle('sidebar-open');
        });
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', function() {
            body.classList.remove('sidebar-open');
        });
    }
    </script>
</body>

</html>