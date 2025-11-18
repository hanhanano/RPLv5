<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use App\Models\StepsPlan;

class StepsPlanController extends Controller
{
    public function index(Request $request, Publication $publication)
    {
        $search = $request->input('search');

        $query = StepsPlan::where('publication_id', $publication->publication_id)->with('stepsFinals');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('plan_name', 'LIKE', '%' . $search . '%')
                ->orWhere('plan_type', 'LIKE', '%' . $search . '%');
            });
        }

        $stepsplans = $query->get();

        $total_rencana   = $stepsplans->count();
        $total_realisasi = $stepsplans->whereNotNull('stepsFinals')->count();

        // $publication = Publication::findOrFail($publication->slug_publication);

        $rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $rekapFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $progressKumulatif = 0;
        $lintasTriwulan = 0;
        
         foreach ($publication->stepsPlans as $plan) {
                $q = getQuarter($plan->plan_start_date);
                if ($q) $rekapPlans[$q]++;

                if ($plan->stepsFinals) {
                    $fq = getQuarter($plan->stepsFinals->actual_started);
                    if ($fq) $rekapFinals[$fq]++;

                    if ($fq && $q && $fq != $q) {
                        $lintasTriwulan++;
                    }
                }
            }

            $totalPlans = array_sum($rekapPlans);
            $totalFinals = array_sum($rekapFinals);
            if ($totalPlans > 0) {
                $progressKumulatif = ($totalFinals / $totalPlans) * 100;
            } else {
                $progressKumulatif = 0;
            }
        $publication->progressKumulatif = $progressKumulatif;
        
        return view('tampilan.detail', compact('stepsplans', 'total_rencana', 'total_realisasi', 'publication', 'search', 'publication'));
    }
    
    // Simpan data untuk formulir Tambah Tahapan
    public function store(Request $request, Publication $publication)
    {
        $request->validate([
            'plan_type' => 'required|string',
            'plan_name' => 'required|string|max:256',
        ]);

        StepsPlan::create([
            'plan_type'      => $request->plan_type,
            'plan_name'      => $request->plan_name,
            'publication_id' => $publication->publication_id, 
        ]);

        return redirect()
            ->back()
            // ->route('steps.index', $publication)
            ->with('success', 'Tahapan berhasil ditambahkan.');
    }

    // Fungsi untuk update tahapan
    public function updateStage(Request $request, StepsPlan $plan){
        $request->validate([
            'plan_type' => 'required|string',
            'plan_name' => 'required|string|max:256',
        ]);

        $plan->update([
            'plan_type' => $request->plan_type,
            'plan_name' => $request->plan_name,
        ]);

        return redirect()->back()->with('success', 'Tahapan berhasil diperbarui.');
    }

    // Perbarui data untuk formulir Edit Rencana
    public function update(Request $request, StepsPlan $plan){
        // dd($request->hasFile('plan_doc'));
        $existingPlanDoc = $plan->plan_doc;

        $fileValidation = $existingPlanDoc ? 'nullable|file|mimes:pdf,jpg,png,jpeg,docx|max:2048' : 'required|file|mimes:pdf,jpg,png,jpeg,docx|max:2048';
        
        $validated = $request->validate([
            'plan_start_date' => 'required|date',
            'plan_end_date'   => 'required|date|after_or_equal:plan_start_date',
            'plan_desc'       => 'required|string',
            'plan_doc'        => $fileValidation,
        ]);
        
        // Upload dokumen jika ada
        // if ($request->hasFile('plan_doc')) {
        //     // dd($request->hasFile('plan_doc'));
        //     $path = $request->file('plan_doc')->store('documents', 'public');
        //     $validated['plan_doc'] = $path;
        //     // dd($validated);
        // }

        if ($request->hasFile('plan_doc')) {
            if ($existingPlanDoc) {
                \Storage::disk('public')->delete($existingPlanDoc);
            }
            $path = $request->file('plan_doc')->store('documents', 'public');
            $validated['plan_doc'] = $path;
        } else {
            $validated['plan_doc'] = $existingPlanDoc;
        }
        
        $plan->update($validated);
        return redirect()->back()->with('success', 'Rencana berhasil diperbarui.');
    }

    // Menghapus tahapan
    public function destroy(StepsPlan $plan){
        $plan->delete();

        return redirect()->back()->with('succes', 'Tahapan berhasil dihapus.');
    }
}