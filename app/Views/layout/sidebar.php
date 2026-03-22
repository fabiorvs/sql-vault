<?php
$currentUrl = current_url(true);
$path = trim($currentUrl->getPath(), '/');

function isActiveMenu(array $routes): bool
{
    $currentPath = trim(current_url(true)->getPath(), '/');

    foreach ($routes as $route) {
        if ($currentPath === trim($route, '/')) {
            return true;
        }

        if (str_starts_with($currentPath, trim($route, '/') . '/')) {
            return true;
        }
    }

    return false;
}
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-database"></i>
            </div>
            <div class="brand-text">SQL Vault</div>
        </div>
    </div>

    <div class="sidebar-search">
        <form action="<?= site_url('consultas') ?>" method="get">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Buscar...">
            </div>
        </form>
    </div>

    <div class="sidebar-action">
        <a href="<?= site_url('consultas/nova') ?>" class="btn-sidebar-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nova Consulta</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-title">Menu</div>

        <a href="<?= site_url('dashboard') ?>" class="nav-item <?= isActiveMenu(['dashboard']) ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= site_url('consultas') ?>" class="nav-item <?= isActiveMenu(['consultas']) ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-code"></i>
            <span>Consultas</span>
        </a>

        <a href="<?= site_url('rotinas') ?>" class="nav-item <?= isActiveMenu(['rotinas']) ? 'active' : '' ?>">
            <i class="bi bi-braces-asterisk"></i>
            <span>Rotinas</span>
        </a>

        <a href="<?= site_url('favoritas') ?>" class="nav-item <?= isActiveMenu(['favoritas']) ? 'active' : '' ?>">
            <i class="bi bi-star"></i>
            <span>Favoritas</span>
        </a>

        <div class="nav-group-title">Gerenciar</div>

        <a href="<?= site_url('tags') ?>" class="nav-item <?= isActiveMenu(['tags']) ? 'active' : '' ?>">
            <i class="bi bi-tags"></i>
            <span>Tags</span>
        </a>

        <a href="<?= site_url('lixeira') ?>" class="nav-item <?= isActiveMenu(['lixeira']) ? 'active' : '' ?>">
            <i class="bi bi-trash"></i>
            <span>Lixeira</span>
        </a>

        <a href="<?= site_url('configuracoes') ?>"
            class="nav-item <?= isActiveMenu(['configuracoes']) ? 'active' : '' ?>">
            <i class="bi bi-gear"></i>
            <span>Configurações</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-summary">
            <div class="summary-label">Total de consultas</div>
            <div class="summary-value"><?= esc($sidebarTotalConsultas ?? 0) ?></div>

            <div class="summary-meta">
                <span><i class="bi bi-star"></i> <?= esc($sidebarTotalFavoritas ?? 0) ?></span>
                <span><i class="bi bi-copy"></i> <?= esc($sidebarTotalCopias ?? 0) ?> cópias</span>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">
                <?= esc(mb_strtoupper(mb_substr(session()->get('user_name') ?? 'U', 0, 1))) ?>
            </div>

            <div class="user-info">
                <div class="user-name"><?= esc(session()->get('user_name') ?? 'Usuário') ?></div>
                <div class="user-email"><?= esc(session()->get('user_email') ?? '') ?></div>
            </div>

            <a href="<?= site_url('logout') ?>" class="logout-btn" title="Sair">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</aside>