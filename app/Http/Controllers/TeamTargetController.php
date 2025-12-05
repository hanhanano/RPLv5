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
        $request->validate([
            'publication_name'   => 'required|string|max:255|min:3',
            'publication_report' => 'required|string|max:255|min:3',
            'publication_pic'    => 'required|string|max:255|min:3',
            'publication_report_other' => 'nullable|string|max:255|min:3',
            'is_monthly' => 'nullable|boolean',
            'months' => 'nullable|array',
            'months.*' => 'integer|between:1,12',
        ]);

        $user = auth()->user();
    
        // Cek permission user
        if (in_array($user->role, ['ketua_tim', 'operator'])) {
            if ($request->publication_pic !== $user->team) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Anda tidak memiliki akses untuk membuat publikasi pada tim ini.');
            }
        }

        $publicationReport = $request->publication_report === 'other'
            ? $request->publication_report_other
            : $request->publication_report;

        \DB::beginTransaction();

        try {
            // LOGIKA GENERATE BULANAN
            if ($request->has('is_monthly') && $request->has('months') && is_array($request->months)) {
                
                // Panggil fungsi helper yang SUDAH DIPERBAIKI (ada di bawah)
                $this->generateMonthlyPublications(
                    $request->publication_name,
                    $publicationReport,
                    $request->publication_pic,
                    $request->months
                );
                
                $successMessage = count($request->months) . ' publikasi bulanan berhasil ditambahkan!';

            } else {
                // LOGIKA PUBLIKASI TUNGGAL (Manual)
                
                // 1. Simpan ke tabel PUBLICATIONS dan ambil ID-nya
                $publicationId = \DB::table('publications')->insertGetId([
                    'publication_name'   => $request->publication_name,
                    'publication_report' => $publicationReport,
                    'publication_pic'    => $request->publication_pic,
                    'fk_user_id'         => Auth::id(),
                    'is_monthly'         => 0,
                    'slug_publication'   => \Str::uuid(),
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                // 2. [PENTING] Simpan juga ke tabel TEAM_TARGETS agar muncul di tabel
                TeamTarget::create([
                    'team_name'      => $request->publication_pic,
                    'activity_name'  => $request->publication_name,
                    'report_name'    => $publicationReport,
                    'publication_id' => $publicationId, // Relasi ke publikasi
                    // Inisialisasi nilai 0
                    'q1_plan' => 0, 'q2_plan' => 0, 'q3_plan' => 0, 'q4_plan' => 0,
                    'q1_real' => 0, 'q2_real' => 0, 'q3_real' => 0, 'q4_real' => 0,
                    'output_plan' => 0, 'output_real' => 0
                ]);

                $successMessage = 'Publikasi berhasil ditambahkan!';
            }

            \DB::commit();
            
            // Redirect kembali ke halaman index (target)
            return redirect()->route('target.index') 
                ->with('success', $successMessage);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan publikasi: ' . $e->getMessage());
        }
    }

    // --- HELPER FUNCTION ---
    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 
            4 => 'April', 5 => 'Mei', 6 => 'Juni', 
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$monthNumber] ?? '';
    }

    private function generateMonthlyPublications($baseName, $report, $pic, array $months)
    {
        $currentYear = now()->year;

        foreach ($months as $monthNumber) {
            $monthNumber = (int)$monthNumber;

            // Hitung Tanggal Otomatis
            $targetDate = \Carbon\Carbon::create($currentYear, $monthNumber, 1);
            $startDate = $targetDate->copy()->startOfMonth()->format('Y-m-d');
            $endDate = $targetDate->copy()->endOfMonth()->format('Y-m-d');

            $year = $targetDate->year;
            $month = $targetDate->month;
            $monthName = $this->getMonthName($month);
            
            // Nama Kegiatan per Bulan
            $publicationName = $baseName . ' - ' . $monthName . ' ' . $year;

            // 1. Insert ke Tabel PUBLICATIONS
            $publicationId = \DB::table('publications')->insertGetId([
                'publication_name'   => $publicationName,
                'publication_report' => $report,
                'publication_pic'    => $pic,
                'fk_user_id'         => Auth::id(),
                'is_monthly'         => 1,
                'slug_publication'   => \Str::uuid(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // 2. [PENTING] Insert ke Tabel TEAM_TARGETS agar muncul di list
            TeamTarget::create([
                'team_name'      => $pic,
                'activity_name'  => $publicationName,
                'report_name'    => $report,
                'publication_id' => $publicationId, // Relasi
                // Inisialisasi nilai 0
                'q1_plan' => 0, 'q2_plan' => 0, 'q3_plan' => 0, 'q4_plan' => 0,
                'q1_real' => 0, 'q2_real' => 0, 'q3_real' => 0, 'q4_real' => 0,
                'output_plan' => 0, 'output_real' => 0
            ]);
            
            // 3. Buat Tahapan Otomatis
            $this->createDefaultStep($publicationId, $baseName, $monthName, $year, $startDate, $endDate);
        }
    }
    
    /**
     * Helper: Buat Tahapan Default dengan Tanggal yang sudah dihitung
     */
    private function createDefaultStep($publicationId, $baseName, $monthName, $year, $startDate, $endDate)
    {
        $planName = "Kegiatan " . $baseName . ' - ' . $monthName . ' ' . $year;
        
        \DB::table('steps_plans')->insert([
            'publication_id'    => $publicationId, // Perbaikan: Sebelumnya $pubId (Error undefined)
            'plan_type'         => 'pengumpulan data', 
            'plan_name'         => $planName,
            
            // Masukkan tanggal otomatis di sini
            'plan_start_date'   => $startDate, 
            'plan_end_date'     => $endDate,
            
            'plan_desc'         => "Tahapan kegiatan $baseName bulan $monthName $year",
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }

    // private function createDefaultStep($pubId, $baseName, $monthName, $year)
    // {
    //     DB::table('steps_plans')->insert([
    //         'publication_id'    => $pubId,
    //         'plan_type'         => 'monthly',
    //         'plan_name'         => "Kegiatan $baseName - $monthName $year",
    //         'plan_start_date'   => now()->format('Y-m-d'), // Tanggal dummy
    //         'created_at'        => now(),
    //         'updated_at'        => now(),
    //     ]);
    // }
    
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

    public function destroy($id) 
    {
        \DB::beginTransaction(); // Gunakan transaksi agar aman
        try {
            // Load target beserta relasi publikasinya
            $target = TeamTarget::with('publication')->findOrFail($id);
            
            if ($target->publication) {
                // 1. Hapus Tahapan (StepsPlans) terkait Publikasi ini terlebih dahulu
                // Ini penting agar tidak terkena Foreign Key Constraint Error
                $target->publication->stepsPlans()->delete();

                // 2. Hapus File (jika ada relasinya di model Publication)
                if (method_exists($target->publication, 'files')) {
                    $target->publication->files()->delete();
                }
                
                // 3. Setelah 'anak-anaknya' bersih, baru hapus Publikasi induknya
                $target->publication->delete(); 
            }
            
            // 4. Terakhir, hapus data TeamTarget itu sendiri
            $target->delete();

            \DB::commit(); // Commit perubahan ke database
            return redirect()->back()->with('success', 'Data Target & Publikasi berhasil dihapus');

        } catch (\Exception $e) {
            \DB::rollBack(); // Batalkan semua jika ada error
            \Log::error('Gagal menghapus target: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}