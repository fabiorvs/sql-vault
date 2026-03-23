<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DatabaseTypeModel;
use App\Models\FavoriteModel;
use App\Models\SnippetModel;
use App\Models\SnippetTagModel;
use App\Models\SnippetVersionModel;
use App\Models\TagModel;

class QueriesController extends BaseController
{
    protected SnippetModel $snippetModel;
    protected DatabaseTypeModel $databaseTypeModel;
    protected FavoriteModel $favoriteModel;
    protected TagModel $tagModel;
    protected SnippetTagModel $snippetTagModel;
    protected SnippetVersionModel $snippetVersionModel;

    public function __construct()
    {
        $this->snippetModel        = new SnippetModel();
        $this->databaseTypeModel   = new DatabaseTypeModel();
        $this->favoriteModel       = new FavoriteModel();
        $this->tagModel            = new TagModel();
        $this->snippetTagModel     = new SnippetTagModel();
        $this->snippetVersionModel = new SnippetVersionModel();
    }

    public function index()
    {
        $userId  = (int) session()->get('user_id');
        $isAdmin = (bool) session()->get('is_admin');

        $q              = trim((string) $this->request->getGet('q'));
        $databaseTypeId = (int) ($this->request->getGet('database_type_id') ?? 0);
        $tagId          = (int) ($this->request->getGet('tag_id') ?? 0);

        $builder = $this->snippetModel
            ->getVisibleSnippets($userId, $isAdmin)
            ->where('snippets.type', 'query');

        if ($q !== '') {
            $builder->groupStart()
                ->like('snippets.title', $q)
                ->orLike('snippets.description', $q)
                ->orLike('snippets.sql_content', $q)
                ->groupEnd();
        }

        if ($databaseTypeId > 0) {
            $builder->where('snippets.database_type_id', $databaseTypeId);
        }

        if ($tagId > 0) {
            $builder->join('snippet_tags', 'snippet_tags.snippet_id = snippets.id', 'inner')
                ->where('snippet_tags.tag_id', $tagId);
        }

        $items = $builder
            ->groupBy('snippets.id')
            ->orderBy('snippets.updated_at', 'DESC')
            ->findAll();

        $favoriteIds = array_column(
            $this->favoriteModel->where('user_id', $userId)->findAll(),
            'snippet_id'
        );

        foreach ($items as &$item) {
            $item['tags']        = $this->snippetTagModel->getTagsBySnippet((int) $item['id']);
            $item['is_favorite'] = in_array((int) $item['id'], $favoriteIds, true);
        }

        $allVisibleQueries = $this->snippetModel
            ->getVisibleSnippets($userId, $isAdmin)
            ->where('snippets.type', 'query')
            ->findAll();

        $data = [
            'title'                 => 'Consultas',
            'topbarButtonLink'      => site_url('consultas/nova'),
            'topbarButtonLabel'     => 'Nova Consulta',
            'topbarButtonIcon'      => 'bi-plus-lg',

            'sidebarTotalConsultas' => count($allVisibleQueries),
            'sidebarTotalFavoritas' => $this->favoriteModel->where('user_id', $userId)->countAllResults(),
            'sidebarTotalCopias'    => 0,

            'items'                 => $items,
            'totalItems'            => count($items),
            'databaseTypes'         => $this->databaseTypeModel->orderBy('name', 'ASC')->findAll(),
            'tags'                  => $this->tagModel->getAvailableTags($userId),
            'filters'               => [
                'q'                => $q,
                'database_type_id' => $databaseTypeId,
                'tag_id'           => $tagId,
            ],
            'entityType'            => 'query',
            'entityLabelPlural'     => 'Consultas',
            'entityLabelSingle'     => 'Consulta',
            'entityCreateRoute'     => site_url('consultas/nova'),
        ];

        return view('queries/index', $data);
    }

    public function new ()
    {
        $userId  = (int) session()->get('user_id');
        $isAdmin = (bool) session()->get('is_admin');

        $allVisibleQueries = $this->snippetModel
            ->getVisibleSnippets($userId, $isAdmin)
            ->where('snippets.type', 'query')
            ->findAll();

        $data = [
            'title'                 => 'Nova Consulta',
            'topbarButtonLink'      => site_url('consultas'),
            'topbarButtonLabel'     => 'Voltar',
            'topbarButtonIcon'      => 'bi-arrow-left',

            'sidebarTotalConsultas' => count($allVisibleQueries),
            'sidebarTotalFavoritas' => $this->favoriteModel->where('user_id', $userId)->countAllResults(),
            'sidebarTotalCopias'    => 0,

            'databaseTypes'         => $this->databaseTypeModel->orderBy('name', 'ASC')->findAll(),
            'tags'                  => $this->tagModel->getAvailableTags($userId),
            'entityType'            => 'query',
            'entityLabelPlural'     => 'Consultas',
            'entityLabelSingle'     => 'Consulta',
            'formAction'            => site_url('consultas/create'),
            'item'                  => [
                'title'            => old('title'),
                'description'      => old('description'),
                'database_type_id' => old('database_type_id'),
                'visibility'       => old('visibility') ?: 'private',
                'sql_content'      => old('sql_content'),
            ],
            'selectedTags'          => old('tags') ?? [],
        ];

        return view('queries/form', $data);
    }

    public function create()
    {
        $userId = (int) session()->get('user_id');

        $rules = [
            'title'            => 'required|min_length[3]|max_length[180]',
            'description'      => 'permit_empty',
            'database_type_id' => 'required|integer',
            'visibility'       => 'required|in_list[private,shared]',
            'sql_content'      => 'required|min_length[3]',
            'tags.*'           => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Verifique os campos do formulário.');
        }

        $data = [
            'user_id'          => $userId,
            'database_type_id' => (int) $this->request->getPost('database_type_id'),
            'type'             => 'query',
            'title'            => trim((string) $this->request->getPost('title')),
            'description'      => trim((string) $this->request->getPost('description')),
            'sql_content'      => trim((string) $this->request->getPost('sql_content')),
            'visibility'       => (string) $this->request->getPost('visibility'),
        ];

        $snippetId = $this->snippetModel->insert($data, true);

        if (! $snippetId) {
            return redirect()->back()->withInput()->with('error', 'Não foi possível salvar a consulta.');
        }

        $snippet = $this->snippetModel->find($snippetId);

        $this->snippetVersionModel->createVersionFromSnippet(
            $snippet,
            $userId,
            'Versão inicial'
        );

        $tags = $this->request->getPost('tags') ?? [];
        $this->snippetTagModel->syncTags((int) $snippetId, (array) $tags);

        return redirect()->to('/consultas')->with('success', 'Consulta criada com sucesso.');
    }
}
