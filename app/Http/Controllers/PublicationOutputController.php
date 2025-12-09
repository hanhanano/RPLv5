<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationOutputController extends Controller
{
    // Menampilkan halaman daftar output untuk publikasi tertentu
    public function index($slug)
    {
        $publication = Publication::where('slug_publication', $slug)
            ->with(['publicationPlans' => function($q) {
                $q->orderBy('plan_date', 'asc'); // Urutkan berdasarkan tanggal rencana
            }])
            ->firstOrFail();

        return view('tampilan.outputs.index', compact('publication'));
    }

    // Menyimpan Tanggal Rilis & File
    public function update(Request $request, $id)
    {
        $request->validate([
            'plan_name'   => 'required|string|max:255',
            'plan_date'   => 'required|date',
            // VALIDASI TAMBAHAN: Tanggal Rilis harus >= Tanggal Rencana
            'actual_date' => 'nullable|date|after_or_equal:plan_date', 
            'file_output' => 'nullable|mimes:pdf,xls,xlsx,doc,docx|max:10240',
        ], [
            // Custom Error Message (Opsional)
            'actual_date.after_or_equal' => 'Tanggal realisasi tidak boleh lebih awal dari tanggal rencana.',
        ]);

        $plan = PublicationPlan::findOrFail($id);

        // Update Data Rencana
        $plan->plan_name = $request->plan_name;
        $plan->plan_date = $request->plan_date;
        
        // Update Realisasi
        if ($request->filled('actual_date')) {
            $plan->actual_date = $request->actual_date;
        }

        // Proses Upload File
        if ($request->hasFile('file_output')) {
            if ($plan->file_path && Storage::disk('public')->exists($plan->file_path)) {
                Storage::disk('public')->delete($plan->file_path);
            }
            $file = $request->file('file_output');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('outputs', $filename, 'public');
            $plan->file_path = $path;
        }

        $plan->save();

        return redirect()->back()->with('success', 'Data output berhasil diperbarui!');
    }

    // Menambah Output
    public function store(Request $request, $slug)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'plan_date' => 'required|date',
        ]);

        $publication = Publication::where('slug_publication', $slug)->firstOrFail();

        $pubId = $publication->publication_id; 
        
        PublicationPlan::create([
            'publication_id' => $pubId,
            'plan_name' => $request->plan_name,
            'plan_date' => $request->plan_date,
        ]);

        return redirect()->back()->with('success', 'Rencana output berhasil ditambahkan!');
    }

    // Menghapus Output
    public function destroy($id)
    {
        $plan = PublicationPlan::findOrFail($id);

        if ($plan->file_path && Storage::disk('public')->exists($plan->file_path)) {
            Storage::disk('public')->delete($plan->file_path);
        }

        $plan->delete();

        return redirect()->back()->with('success', 'Output berhasil dihapus!');
    }
}