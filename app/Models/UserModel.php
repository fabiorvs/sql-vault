<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table          = 'users';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields  = true;

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'is_admin',
        'status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'id'       => 'integer',
        'is_admin' => 'integer',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[120]',
        'email'    => 'required|valid_email|max_length[160]|is_unique[users.email,id,{id}]',
        'password' => 'permit_empty|min_length[6]|max_length[255]',
        'status'   => 'required|in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Este e-mail já está cadastrado.',
        ],
    ];

    protected $skipValidation = false;

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        if (! isset($data['data']['password']) || empty($data['data']['password'])) {
            return $data;
        }

        $info = password_get_info((string) $data['data']['password']);

        if ($info['algo'] === 0) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
