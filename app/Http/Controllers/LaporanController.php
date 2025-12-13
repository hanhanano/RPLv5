<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $publications = Publication::with('stepsPlans')
            ->orderBy('publication_id', 'desc')
            ->get();

        return view('tampilan.laporan', compact('publications'));
    }
}
