<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamTarget;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamTargetController extends Controller
{
    public function index(Request $request)
    {
        // ... (Kode INDEX sama persis seperti sebelumnya, tidak perlu diubah) ...
        // Hanya pastikan query mengambil data targets
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'ketua_tim'])) {
            abort(403, 'Akses Ditolak');
        }

        $query = TeamTarget::with('publication'); 

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('activity_name', 'like', "%{$search}%")
                  ->orWhere('team_name', 'like', "%{$search}%")
                  ->orWhereHas('publication', function($subQ) use ($search) {
                      $subQ->where('publication_report', 'like', "%{$search}%");
                  });
            });
        }

        if ($user->role === 'ketua_tim') {
            $query->where('team_name', $user->team);
        }

        $targets = $query->get();
        
        // Kita tidak lagi butuh $publicationsList untuk dropdown, 
        // tapi jika ingin tetap dikirim tidak masalah.
        return view('tampilan.team_targets', compact('targets'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'team_name'     => 'required|string',
            'activity_name' => 'required|string', // Nama dasar kegiatan
            // Validasi nama laporan (bisa dari select atau manual)
            'report_name_select' => 'nullable|string',
            'report_name_manual' => 'nullable|string',
            
            // Validasi Bulanan
            'is_monthly'    => 'nullable|boolean',
            'months'        => 'nullable|array',
            'months.*'      => 'integer|between:1,12',

            // Angka Target
            'q1_plan' => 'nullable|numeric', 'output_plan' => 'nullable|numeric',
            // ... (validasi angka lainnya opsional, default 0)
        ]);

        // 2. Tentukan Nama Laporan (Report Name)
        $reportName = ($request->report_name_select === 'other') 
            ? $request->report_name_manual 
            : $request->report_name_select;

        if (empty($reportName)) {
            return redirect()->back()->with('error', 'Nama Sasaran/Laporan wajib diisi!');
        }

        DB::beginTransaction(); // Mulai Transaksi Database

        try {
            $user = Auth::user();
            
            if ($request->has('is_monthly') && $request->has('months') && is_array($request->months)) {
                
                $year = now()->year;
                $countCreated = 0;

                foreach ($request->months as $monthNum) {
                    $monthName = $this->getMonthName($monthNum);
                    
                    // A. Buat Nama Kegiatan Unik: "Sakernas - Januari 2025"
                    $fullActivityName = $request->activity_name . ' - ' . $monthName . ' ' . $year;

                    // B. Otomatis Buat Publikasi di Dashboard
                    $pub = Publication::create([
                        'publication_name'   => $fullActivityName,
                        'publication_report' => $reportName,
                        'publication_pic'    => $request->team_name, // PIC sesuai Tim yg dipilih
                        'fk_user_id'         => $user->id,
                        'is_monthly'         => 1,
                        'slug_publication'   => Str::uuid(),
                    ]);

                    // C. Buat Default Step di Publikasi (Opsional, agar user senang sudah ada isinya)
                    $this->createDefaultStep($pub->publication_id, $request->activity_name, $monthName, $year);

                    // D. Buat Target Kinerja yang Terhubung ke Publikasi Tadi
                    TeamTarget::create([
                        'team_name'     => $request->team_name,
                        'report_name'   => $reportName, // Backup nama
                        'activity_name' => $fullActivityName,
                        'publication_id'=> $pub->publication_id, // <--- KUNCI PENGHUBUNG
                        
                        // Copy data angka target (sama untuk semua bulan)
                        'q1_plan' => $request->q1_plan ?? 0, 'q2_plan' => $request->q2_plan ?? 0,
                        'q3_plan' => $request->q3_plan ?? 0, 'q4_plan' => $request->q4_plan ?? 0,
                        'q1_real' => $request->q1_real ?? 0, 'q2_real' => $request->q2_real ?? 0,
                        'q3_real' => $request->q3_real ?? 0, 'q4_real' => $request->q4_real ?? 0,
                        'output_plan' => $request->output_plan ?? 0,
                        'output_real' => $request->output_real ?? 0,
                    ]);
                    
                    $countCreated++;
                }

                $msg = "Berhasil membuat $countCreated Target & Publikasi Bulanan!";
            
            } else {
                
                // A. Buat Publikasi
                $pub = Publication::create([
                    'publication_name'   => $request->activity_name,
                    'publication_report' => $reportName,
                    'publication_pic'    => $request->team_name,
                    'fk_user_id'         => $user->id,
                    'is_monthly'         => 0,
                    'slug_publication'   => Str::uuid(),
                ]);

                // B. Buat Target
                TeamTarget::create([
                    'team_name'     => $request->team_name,
                    'report_name'   => $reportName,
                    'activity_name' => $request->activity_name,
                    'publication_id'=> $pub->publication_id, // Terhubung!
                    
                    // Angka Target
                    'q1_plan' => $request->input('q1_plan', 0), 'q2_plan' => $request->input('q2_plan', 0),
                    'q3_plan' => $request->input('q3_plan', 0), 'q4_plan' => $request->input('q4_plan', 0),
                    'q1_real' => $request->input('q1_real', 0), 'q2_real' => $request->input('q2_real', 0),
                    'q3_real' => $request->input('q3_real', 0), 'q4_real' => $request->input('q4_real', 0),
                    'output_plan' => $request->input('output_plan', 0),
                    'output_real' => $request->input('output_real', 0),
                ]);

                $msg = "Data Target & Publikasi berhasil ditambahkan!";
            }

            DB::commit();
            return redirect()->back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // --- HELPER FUNCTION (Copy dari PublicationController agar mandiri) ---
    private function getMonthName($monthNumber)
    {
        $months = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 
                   7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
        return $months[$monthNumber] ?? '';
    }

    private function createDefaultStep($pubId, $baseName, $monthName, $year)
    {
        DB::table('steps_plans')->insert([
            'publication_id'    => $pubId,
            'plan_type'         => 'monthly',
            'plan_name'         => "Kegiatan $baseName - $monthName $year",
            'plan_start_date'   => now()->format('Y-m-d'), // Tanggal dummy
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
    
    // ... Function Update & Destroy tetap ada ...
    public function update(Request $request, $id) 
    {
        // 1. Tentukan Nama Laporan (Sama seperti logika di STORE)
        // Ambil dari select, jika 'other' ambil dari input manual
        $reportName = ($request->report_name_select === 'other') 
            ? $request->report_name_manual 
            : $request->report_name_select;

        // Validasi sederhana agar tidak Error SQL Column cannot be null
        if (empty($reportName)) {
            // Jika kosong, kita coba ambil data lama agar aman, atau set default
            $targetLama = TeamTarget::find($id);
            $reportName = $targetLama->report_name ?? '-'; 
        }

        $target = TeamTarget::findOrFail($id);

        // 2. Update Data Target
        // Kita tidak bisa pakai $request->except() mentah-mentah karena nama field beda
        $target->update([
            'team_name'     => $request->team_name,
            'activity_name' => $request->activity_name,
            'report_name'   => $reportName, // <--- Ini yang bikin error 500 sebelumnya jika tidak diset
            
            // Update Angka (Gunakan input(), jika null dianggap data lama atau 0)
            'q1_plan' => $request->input('q1_plan', 0), 
            'q2_plan' => $request->input('q2_plan', 0),
            'q3_plan' => $request->input('q3_plan', 0), 
            'q4_plan' => $request->input('q4_plan', 0),
            
            'q1_real' => $request->input('q1_real', 0), 
            'q2_real' => $request->input('q2_real', 0),
            'q3_real' => $request->input('q3_real', 0), 
            'q4_real' => $request->input('q4_real', 0),
            
            'output_plan' => $request->input('output_plan', 0),
            'output_real' => $request->input('output_real', 0),
        ]); 
        
        // 3. Update Publikasi Terkait (Jika Ada)
        if($target->publication) {
            $target->publication->update([
                'publication_name'   => $request->activity_name,
                'publication_report' => $reportName, // Update nama laporan di publikasi juga
                'publication_pic'    => $request->team_name,
            ]);
        }
        
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id) {
        $target = TeamTarget::findOrFail($id);
        
        if ($target->publication) {
            $target->publication->delete(); // Hapus publikasi terkait
        }
        
        $target->delete();
        return redirect()->back()->with('success', 'Data Target & Publikasi berhasil dihapus');
    }
}