<?php echo $this->extend('layout/default') ?>

<?php echo $this->section('content') ?>

<div class="page-toolbar">
    <form method="get" class="filters-row">
        <div class="filter-group">
            <select name="database_type_id" class="filter-control" onchange="this.form.submit()">
                <option value="0">Todos os bancos</option>
                <?php foreach ($databaseTypes as $db): ?>
                <option value="<?php echo esc($db['id']) ?>"
                    <?php echo (int) $filters['database_type_id'] === (int) $db['id'] ? 'selected' : '' ?>>
                    <?php echo esc($db['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <select name="tag_id" class="filter-control" onchange="this.form.submit()">
                <option value="0">Tags</option>
                <?php foreach ($tags as $tag): ?>
                <option value="<?php echo esc($tag['id']) ?>"
                    <?php echo (int) $filters['tag_id'] === (int) $tag['id'] ? 'selected' : '' ?>>
                    <?php echo esc($tag['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-search">
            <input type="text" name="q" value="<?php echo esc($filters['q']) ?>" class="filter-control"
                placeholder="Buscar consultas...">
            <button type="submit" class="btn-filter-action">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
</div>

<div class="results-count">
    <?php echo esc($totalItems) ?> <?php echo strtolower(esc($entityLabelPlural)) ?> encontradas
</div>

<?php if (! empty($items)): ?>
<div class="snippet-grid">
    <?php foreach ($items as $item): ?>
    <div class="snippet-card">
        <div class="snippet-card-header">
            <div>
                <div class="snippet-title"><?php echo esc($item['title']) ?></div>
                <?php if (! empty($item['description'])): ?>
                <div class="snippet-description"><?php echo esc($item['description']) ?></div>
                <?php endif; ?>
            </div>

            <span class="db-badge"
                style="border-color: <?php echo esc($item['database_color'] ?? '#333') ?>; color: <?php echo esc($item['database_color'] ?? '#fff') ?>;">
                <?php echo esc($item['database_name'] ?? '-') ?>
            </span>
        </div>

        <pre class="snippet-code-preview"><?php echo esc(mb_strimwidth($item['sql_content'], 0, 140, '...')) ?></pre>

        <?php if (! empty($item['tags'])): ?>
        <div class="snippet-tags">
            <?php foreach ($item['tags'] as $tag): ?>
            <span class="tag-badge"
                style="background: <?php echo esc($tag['color'] ?? '#333') ?>20; border-color: <?php echo esc($tag['color'] ?? '#333') ?>; color: <?php echo esc($tag['color'] ?? '#fff') ?>;">
                <?php echo esc($tag['name']) ?>
            </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="snippet-card-footer">
            <div class="snippet-meta">
                <span><i class="bi bi-clock"></i> <?php echo date('d/m/Y', strtotime($item['created_at'])) ?></span>
                <span><i class="bi bi-copy"></i> 0</span>
            </div>

            <div class="snippet-actions">
                <button type="button" class="icon-btn" title="Favoritar">
                    <i class="bi <?php echo !  empty($item['is_favorite']) ? 'bi-star-fill text-warning' : 'bi-star' ?>"></i>
                </button>

                <button type="button" class="icon-btn" title="Copiar">
                    <i class="bi bi-copy"></i>
                </button>

                <button type="button" class="icon-btn" title="Mais opções">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="card-panel">
    <div class="empty-state">
        Nenhuma consulta encontrada.
    </div>
</div>
<?php endif; ?>

<?php echo $this->endSection() ?>