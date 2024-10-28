<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit;

class AuditController extends Controller
{

    public function index()
    {
        $audit = Audit::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.audit.index', compact('audit'));
    }

}
