<?php echo $this->extend('layout/default') ?>

<?php echo $this->section('content') ?>

<div class="form-page-wrap">
    <div class="form-header-inline">
        <div>
            <h2 class="form-title-main">Nova <?php echo esc($entityLabelSingle) ?></h2>
            <p class="form-subtitle-main">Cadastre uma <?php echo strtolower(esc($entityLabelSingle)) ?> para reutilizar
                depois.</p>
        </div>
        <a href="<?php echo site_url('consultas') ?>" class="btn-outline-top">
            <i class="bi bi-arrow-left"></i>
            <span>Voltar</span>
        </a>
    </div>

    <form action="<?php echo esc($formAction) ?>" method="post" class="snippet-form-card">
        <?php echo csrf_field() ?>

        <div class="form-grid">
            <div class="form-col-main">
                <div class="form-section">
                    <label class="form-label">Título</label>
                    <input type="text" name="title" class="form-control-dark"
                        value="<?php echo esc($item['title'] ?? '') ?>" placeholder="Ex: Funcionários por departamento"
                        required>
                </div>

                <div class="form-section">
                    <label class="form-label">Descrição</label>
                    <textarea name="description" class="form-control-dark form-textarea-small"
                        placeholder="Descreva brevemente o objetivo da consulta"><?php echo esc($item['description'] ?? '') ?></textarea>
                </div>

                <div class="form-section">
                    <label class="form-label">Código SQL</label>
                    <div id="editor" class="monaco-editor-box"></div>
                    <textarea name="sql_content" id="sql_content" hidden></textarea>
                </div>
            </div>

            <div class="form-col-side">
                <div class="form-section">
                    <label class="form-label">Banco de dados</label>
                    <select name="database_type_id" class="form-control-dark" required>
                        <option value="">Selecione</option>
                        <?php foreach ($databaseTypes as $db): ?>
                        <option value="<?php echo esc($db['id']) ?>"
                            <?php echo (int) ($item['database_type_id'] ?? 0) === (int) $db['id'] ? 'selected' : '' ?>>
                            <?php echo esc($db['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-section">
                    <label class="form-label">Visibilidade</label>
                    <select name="visibility" class="form-control-dark" required>
                        <option value="private"
                            <?php echo($item['visibility'] ?? 'private') === 'private' ? 'selected' : '' ?>>Privada
                        </option>
                        <option value="shared" <?php echo($item['visibility'] ?? '') === 'shared' ? 'selected' : '' ?>>
                            Compartilhada</option>
                    </select>
                </div>

                <div class="form-section">
                    <label class="form-label">Tags</label>
                    <div class="tag-input-container">
                        <div class="tag-selected" id="tagSelected"></div>

                        <input type="text" id="tagSearch" class="form-control-dark"
                            placeholder="Buscar ou adicionar tag...">

                        <div class="tag-dropdown" id="tagDropdown"></div>
                    </div>

                    <!-- hidden inputs -->
                    <div id="tagHiddenInputs"></div>
                    <small class="form-help">Segure Ctrl ou Cmd para selecionar mais de uma.</small>
                </div>

                <div class="form-actions-vertical">
                    <button type="submit" class="btn-save-main">
                        <i class="bi bi-check2-circle"></i>
                        <span>Salvar <?php echo esc($entityLabelSingle) ?></span>
                    </button>

                    <a href="<?php echo site_url('consultas') ?>" class="btn-cancel-main">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php echo $this->endSection() ?>


<?php echo $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
<script>
require.config({
    paths: {
        vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs'
    }
});

require(['vs/editor/editor.main'], function() {
    const textarea = document.getElementById('sql_content');
    const editorEl = document.getElementById('editor');
    const form = document.querySelector('.snippet-form-card');

    if (!textarea || !editorEl || !form) {
        console.log('Monaco: elementos não encontrados');
        return;
    }

    const editor = monaco.editor.create(editorEl, {
        value: textarea.value || '',
        language: 'sql',
        theme: 'vs-dark',
        automaticLayout: true,
        fontSize: 14,
        minimap: {
            enabled: false
        },
        wordWrap: 'on',
        scrollBeyondLastLine: false,
    });

    form.addEventListener('submit', function() {
        textarea.value = editor.getValue();
        console.log('SQL enviado:', textarea.value);
    });
});
</script>

<script>
const tags = <?php echo json_encode($tags) ?>;
let selectedTags = <?php echo json_encode($selectedTags ?? []) ?>;

const tagSelectedEl = document.getElementById('tagSelected');
const tagDropdown = document.getElementById('tagDropdown');
const tagSearch = document.getElementById('tagSearch');
const hiddenInputs = document.getElementById('tagHiddenInputs');

function renderSelectedTags() {
    tagSelectedEl.innerHTML = '';
    hiddenInputs.innerHTML = '';

    selectedTags.forEach(tagId => {
        const tag = tags.find(t => t.id == tagId);
        if (!tag) return;

        const chip = document.createElement('div');
        chip.className = 'tag-chip';
        chip.style.borderColor = tag.color || '#555';
        chip.style.color = tag.color || '#fff';
        chip.style.background = (tag.color || '#555') + '20';

        chip.innerHTML = `
            ${tag.name}
            <button onclick="removeTag(${tag.id})">✕</button>
        `;

        tagSelectedEl.appendChild(chip);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = tag.id;
        hiddenInputs.appendChild(input);
    });
}

function renderDropdown(filter = '') {
    tagDropdown.innerHTML = '';

    const filtered = tags.filter(tag =>
        tag.name.toLowerCase().includes(filter.toLowerCase()) &&
        !selectedTags.includes(tag.id)
    );

    if (!filtered.length) {
        tagDropdown.style.display = 'none';
        return;
    }

    filtered.forEach(tag => {
        const option = document.createElement('div');
        option.className = 'tag-option';
        option.innerText = tag.name;

        option.onclick = () => {
            selectedTags.push(tag.id);
            tagSearch.value = '';
            renderSelectedTags();
            renderDropdown();
        };

        tagDropdown.appendChild(option);
    });

    tagDropdown.style.display = 'block';
}

function removeTag(id) {
    selectedTags = selectedTags.filter(t => t != id);
    renderSelectedTags();
    renderDropdown(tagSearch.value);
}

tagSearch.addEventListener('input', function() {
    renderDropdown(this.value);
});

document.addEventListener('click', function(e) {
    if (!tagSearch.contains(e.target) && !tagDropdown.contains(e.target)) {
        tagDropdown.style.display = 'none';
    }
});

tagSearch.addEventListener('focus', function() {
    renderDropdown(this.value);
});

// inicializa
renderSelectedTags();
</script>
<?php echo $this->endSection() ?>