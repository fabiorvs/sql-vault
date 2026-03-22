<?php
namespace App\Database\Seeds;

use App\Models\DatabaseTypeModel;
use CodeIgniter\Database\Seeder;

class DatabaseTypeSeeder extends Seeder
{
    public function run()
    {
        $model = new DatabaseTypeModel();

        $items = [
            [
                'name'  => 'MySQL',
                'slug'  => 'mysql',
                'color' => '#f59e0b',
                'icon'  => 'database',
            ],
            [
                'name'  => 'PostgreSQL',
                'slug'  => 'postgresql',
                'color' => '#3b82f6',
                'icon'  => 'database',
            ],
            [
                'name'  => 'Oracle',
                'slug'  => 'oracle',
                'color' => '#ef4444',
                'icon'  => 'database',
            ],
            [
                'name'  => 'SQL Server',
                'slug'  => 'sql-server',
                'color' => '#10b981',
                'icon'  => 'database',
            ],
        ];

        foreach ($items as $item) {
            $exists = $model->where('slug', $item['slug'])->first();

            if (! $exists) {
                $model->insert($item);
            }
        }
    }
}
