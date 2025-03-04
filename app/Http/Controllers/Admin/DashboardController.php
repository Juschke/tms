<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rechnung;
use App\Models\Auftrag;
use App\Models\Kunde;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $rechnungen = Rechnung::with('auftrag', 'kunde')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard.index', compact('rechnungen'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
    
    }

}
