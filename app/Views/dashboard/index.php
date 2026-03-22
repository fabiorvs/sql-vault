<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<div class="grid-cards">
    <div class="card-panel stat-card">
        <div class="stat-top">
            <span>Consultas</span>
            <i class="bi bi-file-earmark-code"></i>
        </div>
        <div>
            <div class="stat-number"><?= esc($total_snippets ?? 0) ?></div>
            <div class="stat-label">Consultas salvas</div>
        </div>
    </div>

    <div class="card-panel stat-card">
        <div class="stat-top">
            <span>Rotinas</span>
            <i class="bi bi-braces-asterisk"></i>
        </div>
        <div>
            <div class="stat-number"><?= esc($total_routines ?? 0) ?></div>
            <div class="stat-label">Rotinas salvas</div>
        </div>
    </div>

    <div class="card-panel stat-card">
        <div class="stat-top">
            <span>C. Favoritas</span>
            <i class="bi bi-star"></i>
        </div>
        <div>
            <div class="stat-number"><?= esc($total_favorites ?? 0) ?></div>
            <div class="stat-label">Consultas marcadas</div>
        </div>
    </div>

    <div class="card-panel stat-card">
        <div class="stat-top">
            <span>R. Favoritas</span>
            <i class="bi bi-star"></i>
        </div>
        <div>
            <div class="stat-number"><?= esc($total_routine_favorites ?? 0) ?></div>
            <div class="stat-label">Rotinas marcadas</div>
        </div>
    </div>

    <div class="card-panel stat-card">
        <div class="stat-top">
            <span>Cópias</span>
            <i class="bi bi-copy"></i>
        </div>
        <div>
            <div class="stat-number"><?= esc($total_copies ?? 0) ?></div>
            <div class="stat-label">SQL copiado</div>
        </div>
    </div>

    <div class="card-panel stat-card">
        <div class="stat-top">
            <span>Bancos</span>
            <i class="bi bi-database"></i>
        </div>
        <div>
            <div class="stat-number"><?= esc($total_databases ?? 0) ?></div>
            <div class="stat-label">Tipos diferentes</div>
        </div>
    </div>
</div>

<div class="grid-sections">
    <div class="card-panel">
        <div class="section-header">
            <div>
                <div class="section-title">Por Banco de Dados</div>
                <div class="section-subtitle">Qtde. de consultas por tipo</div>
            </div>
        </div>

        <?php if (! empty($database_stats)): ?>
        <?php foreach ($database_stats as $db): ?>
        <div style="margin-bottom: 18px;">
            <div style="display:flex;justify-content:space-between;gap:12px;">
                <strong><?= esc($db['name']) ?></strong>
                <span class="stat-label"><?= esc($db['total']) ?> (<?= esc($db['percent']) ?>%)</span>
            </div>
            <div class="progress-line">
                <span style="width: <?= esc($db['percent']) ?>%;"></span>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="empty-state">
            Nenhum dado encontrado para exibir por banco.
        </div>
        <?php endif; ?>
    </div>

    <div class="card-panel">
        <div class="section-header">
            <div>
                <div class="section-title">Consultas Recentes</div>
                <div class="section-subtitle">Últimas consultas atualizadas</div>
            </div>
            <a href="<?= site_url('consultas') ?>" class="link-inline">
                Ver todas <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <?php if (! empty($recent_snippets)): ?>
        <div class="list-simple">
            <?php foreach ($recent_snippets as $item): ?>
            <div class="list-item">
                <div class="list-item-title"><?= esc($item['title']) ?></div>
                <div class="list-item-meta">
                    <?= esc($item['database_name'] ?? '-') ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            Nenhuma consulta ainda.
        </div>
        <?php endif; ?>
    </div>

    <div class="card-panel">
        <div class="section-header">
            <div>
                <div class="section-title">Rotinas Recentes</div>
                <div class="section-subtitle">Últimas rotinas atualizadas</div>
            </div>
            <a href="<?= site_url('rotinas') ?>" class="link-inline">
                Ver todas <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <?php if (! empty($recent_routines)): ?>
        <div class="list-simple">
            <?php foreach ($recent_routines as $item): ?>
            <div class="list-item">
                <div class="list-item-title"><?= esc($item['title']) ?></div>
                <div class="list-item-meta">
                    <?= esc($item['database_name'] ?? '-') ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            Nenhuma rotina ainda
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>