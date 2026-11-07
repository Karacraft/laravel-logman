<?php

namespace Karacraft\Logman\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Karacraft\Logman\Models\EventLogger;
use Illuminate\Validation\Rule;

class LogmanController extends Controller
{

    public function __construct(){
        $this->middleware(['web','auth']);
    }

    public function index()
    {
        return view('logman::logman.index');
    }

    public function getMasterData(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'sort' => ['nullable', 'array'],
            'sort.0.field' => [
                'nullable',
                'string',
                Rule::in([
                    'id', 'action', 'table', 'rowid', 'description',
                    'original', 'changes', 'user_id', 'user_name',
                    'ipaddress', 'created_at', 'updated_at',
                ]),
            ],
            'sort.0.dir' => ['nullable', 'in:asc,desc'],
        ]);

        $search = $validated['search'] ?? '';
        $field = $validated['sort'][0]['field'] ?? 'id';
        $direction = $validated['sort'][0]['dir'] ?? 'desc';

        $logs = EventLogger::query();
        if ($search !== '') {
            $logs->where(function ($query) use ($search) {
                foreach ([
                    'action', 'table', 'rowid', 'description', 'ipaddress',
                    'user_id', 'user_name', 'original', 'changes',
                ] as $column) {
                    $query->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            });
        }

        return $logs->orderBy($field, $direction)
            ->paginate($validated['size'] ?? 10);
    }
}
