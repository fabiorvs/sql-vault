<?php
namespace App\Models;

use CodeIgniter\Model;

class DatabaseTypeModel extends Model
{
    protected $table         = 'database_types';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;

    protected $allowedFields = [
        'name',
        'slug',
        'color',
        'icon',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id' => 'integer',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[60]',
        'slug' => 'required|min_length[2]|max_length[60]|is_unique[database_types.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'slug' => [
            'is_unique' => 'Este slug já está cadastrado.',
        ],
    ];

    protected $skipValidation = false;
}
