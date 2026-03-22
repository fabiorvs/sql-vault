<?php
namespace App\Models;

use CodeIgniter\Model;

class SnippetModel extends Model
{
    protected $table          = 'snippets';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields  = true;

    protected $allowedFields = [
        'user_id',
        'database_type_id',
        'type',
        'title',
        'description',
        'sql_content',
        'visibility',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'               => 'integer',
        'user_id'          => 'integer',
        'database_type_id' => 'integer',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'user_id'          => 'required|integer',
        'database_type_id' => 'required|integer',
        'type'             => 'required|in_list[query,trigger,procedure,function,view,script]',
        'title'            => 'required|min_length[3]|max_length[180]',
        'description'      => 'permit_empty',
        'sql_content'      => 'required|min_length[3]',
        'visibility'       => 'required|in_list[private,shared]',
    ];

    protected $skipValidation = false;

    public function getVisibleSnippets(int $userId, bool $isAdmin = false)
    {
        $builder = $this->select('
                snippets.*,
                users.name AS user_name,
                database_types.name AS database_name,
                database_types.slug AS database_slug,
                database_types.color AS database_color,
                database_types.icon AS database_icon
            ')
            ->join('users', 'users.id = snippets.user_id')
            ->join('database_types', 'database_types.id = snippets.database_type_id');

        if (! $isAdmin) {
            $builder->groupStart()
                ->where('snippets.user_id', $userId)
                ->orWhere('snippets.visibility', 'shared')
                ->groupEnd();
        }

        return $builder->orderBy('snippets.id', 'DESC');
    }

    public function getByIdVisible(int $id, int $userId, bool $isAdmin = false)
    {
        $builder = $this->select('
                snippets.*,
                users.name AS user_name,
                database_types.name AS database_name,
                database_types.slug AS database_slug,
                database_types.color AS database_color,
                database_types.icon AS database_icon
            ')
            ->join('users', 'users.id = snippets.user_id')
            ->join('database_types', 'database_types.id = snippets.database_type_id')
            ->where('snippets.id', $id);

        if (! $isAdmin) {
            $builder->groupStart()
                ->where('snippets.user_id', $userId)
                ->orWhere('snippets.visibility', 'shared')
                ->groupEnd();
        }

        return $builder->first();
    }
}
