<?php
namespace App\Database\Seeds;

use App\Models\TagModel;
use CodeIgniter\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run()
    {
        $model = new TagModel();

        $tags = [
            ['name' => 'Relatório', 'slug' => 'relatorio', 'color' => '#3b82f6'],
            ['name' => 'Performance', 'slug' => 'performance', 'color' => '#10b981'],
            ['name' => 'Debug', 'slug' => 'debug', 'color' => '#ef4444'],
            ['name' => 'JOIN', 'slug' => 'join', 'color' => '#6366f1'],
            ['name' => 'Agregação', 'slug' => 'agregacao', 'color' => '#f59e0b'],
            ['name' => 'Financeiro', 'slug' => 'financeiro', 'color' => '#14b8a6'],
            ['name' => 'RH', 'slug' => 'rh', 'color' => '#ec4899'],
            ['name' => 'Logs', 'slug' => 'logs', 'color' => '#a855f7'],
            ['name' => 'Correção', 'slug' => 'correcao', 'color' => '#f97316'],
            ['name' => 'Migração', 'slug' => 'migracao', 'color' => '#22c55e'],
        ];

        foreach ($tags as $tag) {
            $exists = $model
                ->where('slug', $tag['slug'])
                ->where('user_id', null)
                ->first();

            if (! $exists) {
                $model->insert([
                    'name'       => $tag['name'],
                    'slug'       => $tag['slug'],
                    'color'      => $tag['color'],
                    'user_id'    => null,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
