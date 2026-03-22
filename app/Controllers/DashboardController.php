<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DatabaseTypeModel;
use App\Models\FavoriteModel;
use App\Models\SnippetModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $snippetModel      = new SnippetModel();
        $favoriteModel     = new FavoriteModel();
        $databaseTypeModel = new DatabaseTypeModel();

        $userId  = (int) session()->get('user_id');
        $isAdmin = (bool) session()->get('is_admin');

        $visibleBuilder = $snippetModel->getVisibleSnippets($userId, $isAdmin);
        $allVisible     = $visibleBuilder->findAll();

        $recentSnippets = array_values(array_filter($allVisible, function ($item) {
            return ($item['type'] ?? 'query') === 'query';
        }));

        $recentRoutines = array_values(array_filter($allVisible, function ($item) {
            return ($item['type'] ?? '') !== 'query';
        }));

        $databaseMap = [];
        foreach ($allVisible as $item) {
            $name = $item['database_name'] ?? 'Não definido';
            if (! isset($databaseMap[$name])) {
                $databaseMap[$name] = 0;
            }
            $databaseMap[$name]++;
        }

        $databaseStats = [];
        $total         = count($allVisible);

        foreach ($databaseMap as $name => $count) {
            $databaseStats[] = [
                'name'    => $name,
                'total'   => $count,
                'percent' => $total > 0 ? round(($count * 100) / $total) : 0,
            ];
        }

        $data = [
            'title'                   => 'Dashboard',
            'topbarButtonLink'        => site_url('consultas/nova'),
            'topbarButtonLabel'       => 'Nova Consulta',
            'topbarButtonIcon'        => 'bi-plus-lg',

            'sidebarTotalConsultas'   => count(array_filter($allVisible, fn($item) => ($item['type'] ?? 'query') === 'query')),
            'sidebarTotalFavoritas'   => $favoriteModel->where('user_id', $userId)->countAllResults(),
            'sidebarTotalCopias'      => 0,

            'total_snippets'          => count(array_filter($allVisible, fn($item) => ($item['type'] ?? 'query') === 'query')),
            'total_routines'          => count(array_filter($allVisible, fn($item) => ($item['type'] ?? '') !== 'query')),
            'total_favorites'         => $favoriteModel->where('user_id', $userId)->countAllResults(),
            'total_routine_favorites' => 0,
            'total_copies'            => 0,
            'total_databases'         => $databaseTypeModel->countAllResults(),

            'recent_snippets'         => array_slice($recentSnippets, 0, 5),
            'recent_routines'         => array_slice($recentRoutines, 0, 5),
            'database_stats'          => $databaseStats,
        ];

        return view('dashboard/index', $data);
    }
}
