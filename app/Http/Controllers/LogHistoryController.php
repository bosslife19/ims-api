<?php

namespace App\Http\Controllers;

use App\Models\LogHistory;
use Illuminate\Http\Request;

class LogHistoryController extends Controller
{
    public function getLogs(){
        $logs = LogHistory::latest()->get();

        return response($logs, 200);
    }
}
