<?php

namespace Karacraft\Logman\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Karacraft\Logman\Models\EventLogger;

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
        $search = $request->search;
        $size = $request->size;
        $field = $request->sorters[0]["field"];     //  Nested Array
        $dir = $request->sorters[0]["dir"];         //  Nested Array
  
            $gatepass = EventLogger::where('action','LIKE','%' . $search . '%')
            ->orWhere('table','LIKE','%' . $search . '%')
            ->orWhere('rowid','LIKE','%' . $search . '%')
            ->orWhere('description','LIKE','%' . $search . '%')
            ->orWhere('ipaddress','LIKE','%' . $search . '%')
            ->orWhere('user_id','LIKE','%' . $search . '%')
            ->orWhere('user_name','LIKE','%' . $search . '%')
            ->orWhere('original','LIKE','%' . $search . '%')
            ->orWhere('changes','LIKE','%' . $search . '%')
            ->orderBy($field,$dir)
            ->paginate((int) $size);
     
        return $gatepass;
    }
}
