<?php

namespace Karacraft\Logman\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Karacraft\Logman\Models\EventLogger;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LogController extends Controller
{
    /**
     * Display a paginated list of event logs.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get filter parameters from query string
        $filters = Request::all();
        
        // Validate filters
        $allowedFilters = ['action', 'table', 'user_id'];
        foreach ($allowedFilters as $filter) {
            if (!empty($filters[$filter]) && !in_array($filters[$filter], $allowedFilters)) {
                // Silently ignore invalid filter or return 400 - for simplicity we'll just skip it
            }
        }
        
        // Build query with filters
        $query = EventLogger::when($filters, function ($builder) use ($filters) {
            if (!empty($filters['action'])) {
                $builder->where('action', 'like', '%' . $filters['action'] . '%');
            }
            
            if (!empty($filters['table'])) {
                $builder->where('table', 'like', '%' . $filters['table'] . '%');
            }
            
            if (!empty($filters['user_id'])) {
                $builder->where('user_id', $filters['user_id']);
            }
        });
        
        // Paginate results
        $pageSize = (isset($filters['size']) && is_numeric($filters['size'])) 
            ? max(10, min(100, (int)$filters['size'])) 
            : 20;
            
        $paginator = $query->orderByDesc('created_at')
            ->paginate($pageSize, ['*'], $filters['page'] ?? null);
        
        // Return JSON response for the frontend table component
        return response()->json([
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'next_page_url' => $paginator->nextPageUrl,
                'prev_page_url' => $paginator->previousPageUrl,
                'path' => Route::currentRouteName() ?: '/logman',
                'per_page' => $pageSize,
                'total' => $paginator->total(),
                'from' => $paginator->firstItem()->getKey(),
                'to' => $paginator->lastItem()->getKey(),
            ],
        ]);
    }
}