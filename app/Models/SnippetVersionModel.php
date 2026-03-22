<?php
namespace App\Models;

use CodeIgniter\Model;

class SnippetVersionModel extends Model
{
    protected $table         = 'snippet_versions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;

    protected $allowedFields = [
        'snippet_id',
        'version_number',
        'title',
        'description',
        'sql_content',
        'database_type_id',
        'type',
        'visibility',
        'change_note',
        'is_restore',
        'restored_from_version_id',
        'created_by',
        'created_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'                       => 'integer',
        'snippet_id'               => 'integer',
        'version_number'           => 'integer',
        'database_type_id'         => 'integer',
        'created_by'               => 'integer',
        'is_restore'               => 'integer',
        'restored_from_version_id' => '?integer',
    ];

    protected $useTimestamps  = false;
    protected $skipValidation = false;

    protected $validationRules = [
        'snippet_id'               => 'required|integer',
        'version_number'           => 'required|integer',
        'title'                    => 'required|min_length[3]|max_length[180]',
        'description'              => 'permit_empty',
        'sql_content'              => 'required|min_length[3]',
        'database_type_id'         => 'required|integer',
        'type'                     => 'required|in_list[query,trigger,procedure,function,view,script]',
        'visibility'               => 'required|in_list[private,shared]',
        'change_note'              => 'permit_empty|max_length[255]',
        'is_restore'               => 'permit_empty|in_list[0,1]',
        'restored_from_version_id' => 'permit_empty|integer',
        'created_by'               => 'required|integer',
    ];

    public function getNextVersionNumber(int $snippetId): int
    {
        $last = $this->where('snippet_id', $snippetId)
            ->orderBy('version_number', 'DESC')
            ->first();

        if (! $last) {
            return 1;
        }

        return ((int) $last['version_number']) + 1;
    }

    public function createVersionFromSnippet(array $snippet, int $createdBy, ?string $changeNote = null, int $isRestore = 0, ?int $restoredFromVersionId = null): bool
    {
        $versionNumber = $this->getNextVersionNumber((int) $snippet['id']);

        return (bool) $this->insert([
            'snippet_id'               => (int) $snippet['id'],
            'version_number'           => $versionNumber,
            'title'                    => $snippet['title'],
            'description'              => $snippet['description'] ?? null,
            'sql_content'              => $snippet['sql_content'],
            'database_type_id'         => (int) $snippet['database_type_id'],
            'type'                     => $snippet['type'],
            'visibility'               => $snippet['visibility'],
            'change_note'              => $changeNote,
            'is_restore'               => $isRestore,
            'restored_from_version_id' => $restoredFromVersionId,
            'created_by'               => $createdBy,
            'created_at'               => date('Y-m-d H:i:s'),
        ]);
    }

    public function getVersionsBySnippet(int $snippetId): array
    {
        return $this->select('
                snippet_versions.*,
                users.name as created_by_name,
                database_types.name as database_name
            ')
            ->join('users', 'users.id = snippet_versions.created_by')
            ->join('database_types', 'database_types.id = snippet_versions.database_type_id', 'left')
            ->where('snippet_versions.snippet_id', $snippetId)
            ->orderBy('snippet_versions.version_number', 'DESC')
            ->findAll();
    }

    public function getVersionDetail(int $versionId): ?array
    {
        return $this->select('
                snippet_versions.*,
                users.name as created_by_name,
                database_types.name as database_name
            ')
            ->join('users', 'users.id = snippet_versions.created_by')
            ->join('database_types', 'database_types.id = snippet_versions.database_type_id', 'left')
            ->where('snippet_versions.id', $versionId)
            ->first();
    }

    public function getLatestVersion(int $snippetId): ?array
    {
        return $this->where('snippet_id', $snippetId)
            ->orderBy('version_number', 'DESC')
            ->first();
    }

    public function hasRelevantChanges(array $currentSnippet, array $newData): bool
    {
        $fields = [
            'title',
            'description',
            'sql_content',
            'database_type_id',
            'type',
            'visibility',
        ];

        foreach ($fields as $field) {
            $currentValue = $currentSnippet[$field] ?? null;
            $newValue     = $newData[$field] ?? null;

            if ((string) $currentValue !== (string) $newValue) {
                return true;
            }
        }

        return false;
    }
}
