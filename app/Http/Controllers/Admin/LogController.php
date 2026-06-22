<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(): View
    {
        $logs = AuditLog::latest('created_at')->limit(100)->get();

        return view('admin.logs', compact('logs'));
    }
}
