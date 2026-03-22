<?php
namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table         = 'favorites';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;

    protected $allowedFields = [
        'user_id',
        'snippet_id',
        'created_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'         => 'integer',
        'user_id'    => 'integer',
        'snippet_id' => 'integer',
    ];

    protected $useTimestamps  = false;
    protected $skipValidation = false;

    protected $validationRules = [
        'user_id'    => 'required|integer',
        'snippet_id' => 'required|integer',
    ];

    public function isFavorite(int $userId, int $snippetId): bool
    {
        return $this->where('user_id', $userId)
            ->where('snippet_id', $snippetId)
            ->countAllResults() > 0;
    }

    public function toggle(int $userId, int $snippetId): bool
    {
        $favorite = $this->where('user_id', $userId)
            ->where('snippet_id', $snippetId)
            ->first();

        if ($favorite) {
            $this->delete($favorite['id']);
            return false;
        }

        $this->insert([
            'user_id'    => $userId,
            'snippet_id' => $snippetId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return true;
    }
}
