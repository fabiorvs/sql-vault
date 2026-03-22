<?php
namespace App\Models;

use CodeIgniter\Model;

class SnippetTagModel extends Model
{
    protected $table         = 'snippet_tags';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;

    protected $allowedFields = [
        'snippet_id',
        'tag_id',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'         => 'integer',
        'snippet_id' => 'integer',
        'tag_id'     => 'integer',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'snippet_id' => 'required|integer',
        'tag_id'     => 'required|integer',
    ];

    protected $skipValidation = false;

    /*
    |--------------------------------------------------------------------------
    | Métodos úteis
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna todas as tags de um snippet
     */
    public function getTagsBySnippet(int $snippetId): array
    {
        return $this->select('
                tags.id,
                tags.name,
                tags.slug,
                tags.color
            ')
            ->join('tags', 'tags.id = snippet_tags.tag_id')
            ->where('snippet_tags.snippet_id', $snippetId)
            ->orderBy('tags.name', 'ASC')
            ->findAll();
    }

    /**
     * Retorna apenas IDs das tags (útil para form edit)
     */
    public function getTagIdsBySnippet(int $snippetId): array
    {
        $rows = $this->select('tag_id')
            ->where('snippet_id', $snippetId)
            ->findAll();

        return array_map(fn($row) => (int) $row['tag_id'], $rows);
    }

    /**
     * Define as tags de um snippet (replace total)
     */
    public function syncTags(int $snippetId, array $tagIds): void
    {
        // Remove todas atuais
        $this->where('snippet_id', $snippetId)->delete();

        if (empty($tagIds)) {
            return;
        }

        $data = [];

        foreach ($tagIds as $tagId) {
            $data[] = [
                'snippet_id' => $snippetId,
                'tag_id'     => (int) $tagId,
            ];
        }

        $this->insertBatch($data);
    }

    /**
     * Adiciona uma tag ao snippet (sem duplicar)
     */
    public function attach(int $snippetId, int $tagId): bool
    {
        $exists = $this->where('snippet_id', $snippetId)
            ->where('tag_id', $tagId)
            ->first();

        if ($exists) {
            return false;
        }

        return (bool) $this->insert([
            'snippet_id' => $snippetId,
            'tag_id'     => $tagId,
        ]);
    }

    /**
     * Remove uma tag do snippet
     */
    public function detach(int $snippetId, int $tagId): bool
    {
        return (bool) $this->where('snippet_id', $snippetId)
            ->where('tag_id', $tagId)
            ->delete();
    }

    /**
     * Retorna snippets por tag
     */
    public function getSnippetsByTag(int $tagId): array
    {
        return $this->select('snippets.*')
            ->join('snippets', 'snippets.id = snippet_tags.snippet_id')
            ->where('snippet_tags.tag_id', $tagId)
            ->orderBy('snippets.id', 'DESC')
            ->findAll();
    }
}
