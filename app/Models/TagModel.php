<?php
namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table         = 'tags';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;

    protected $allowedFields = [
        'name',
        'slug',
        'color',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[80]',
        'slug' => 'required|min_length[2]|max_length[80]',
    ];

    public function getAvailableTags(int $userId): array
    {
        return $this->groupStart()
            ->where('user_id', $userId)
            ->orWhere('user_id', null)
            ->groupEnd()
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}
