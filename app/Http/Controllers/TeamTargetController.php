<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamTarget;
use App\Models\Publication;
use App\Models\StepsPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon; 

class TeamTargetController extends Controller
{
    public function index(Request $request)
    {
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

        DB::beginTransaction();

        try {
            // LOGIKA GENERATE BULANAN
            if ($request->has('is_monthly') && $request->has('months') && is_array($request->months)) {
                
                $this->generateMonthlyPublications(
                    $request->publication_name,
                    $publicationReport,
                    $request->publication_pic,
                    $request->months,
                    $request 
                );
                
                $successMessage = count($request->months) . ' publikasi bulanan berhasil ditambahkan!';

            } else {
                // LOGIKA PUBLIKASI TUNGGAL (Manual)
                
                // 1. Simpan ke tabel PUBLICATIONS
                $publicationId = DB::table('publications')->insertGetId([
                    'publication_name'   => $request->publication_name,
                    'publication_report' => $publicationReport,
                    'publication_pic'    => $request->publication_pic,
                    'fk_user_id'         => Auth::id(),
                    'is_monthly'         => 0,
                    'slug_publication'   => Str::uuid(),
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                // 2. Simpan ke tabel TEAM_TARGETS
                $targetTahapan = $request->input('q1_plan', 0);
                $targetOutput  = $request->input('output_plan', 0);
                $realOutput    = $request->input('output_real', 0);

                TeamTarget::create([
                    'team_name'      => $request->publication_pic,
                    'activity_name'  => $request->publication_name,
                    'report_name'    => $publicationReport,
                    'publication_id' => $publicationId,
                    
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

                    'output_real_q1' => $request->input('output_real_q1', 0),
                    'output_real_q2' => $request->input('output_real_q2', 0),
                    'output_real_q3' => $request->input('output_real_q3', 0),
                    'output_real_q4' => $request->input('output_real_q4', 0),
                ]);

                // [GENERATE DETAIL OTOMATIS]
                // 1. Tahapan
                $this->syncSimpleSteps($publicationId, $targetTahapan);
                // 2. Output (LOGIKA BARU)
                $this->syncSimpleOutputs($publicationId, $targetOutput, $realOutput);

                $successMessage = 'Publikasi berhasil ditambahkan beserta detail Tahapan & Output!';
            }

            DB::commit();
            return redirect()->route('target.index')->with('success', $successMessage);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id) 
    {
        $reportName = ($request->publication_report === 'other') 
            ? $request->publication_report_other 
            : $request->publication_report;

        if (empty($reportName)) {
            $targetLama = TeamTarget::find($id);
            $reportName = $targetLama->report_name ?? '-'; 
        }

        $target = TeamTarget::findOrFail($id);

        $target->update([
            'team_name'     => $request->publication_pic,
            'activity_name' => $request->publication_name,
            'report_name'   => $reportName,
            
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

            'output_real_q1' => $request->input('output_real_q1', 0),
            'output_real_q2' => $request->input('output_real_q2', 0),
            'output_real_q3' => $request->input('output_real_q3', 0),
            'output_real_q4' => $request->input('output_real_q4', 0),
        ]);
        
        if($target->publication) {
            $target->publication->update([
                'publication_name'   => $request->publication_name,
                'publication_report' => $reportName,
                'publication_pic'    => $request->publication_pic,
            ]);

            // [SINKRONISASI TAHAPAN SAAT UPDATE]
            $targetTahapan = $request->input('q1_plan', 0);
            $this->syncSimpleSteps($target->publication_id, $targetTahapan);

            // [SINKRONISASI OUTPUT SAAT UPDATE]
            $targetOutput = $request->input('output_plan', 0);
            $realOutput   = $request->input('output_real', 0);
            $this->syncSimpleOutputs($target->publication_id, $targetOutput, $realOutput);
        }
        
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    // --- HELPER FUNCTIONS ---

    /**
     * Membuat Tahapan otomatis untuk PUBLIKASI TUNGGAL / MANUAL
     */
    private function syncSimpleSteps($publicationId, $targetCount)
    {
        $targetCount = (int)$targetCount;
        if ($targetCount <= 0) return;

        $year = now()->year;
        $startDate = "$year-01-01";
        $endDate = "$year-01-31";

        $existingCount = StepsPlan::where('publication_id', $publicationId)->count();

        if ($targetCount > $existingCount) {
            $needed = $targetCount - $existingCount;
            for ($i = 1; $i <= $needed; $i++) {
                $counter = $existingCount + $i;
                StepsPlan::create([
                    'publication_id'    => $publicationId,
                    'plan_type'         => 'pengumpulan data', 
                    'plan_name'         => "Tahapan $counter", 
                    'plan_start_date'   => $startDate,
                    'plan_end_date'     => $endDate,
                    'plan_desc'         => "Tahapan otomatis ke-$counter",
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }

    /**
     * [BARU] Membuat Detail Output otomatis (di tabel publication_plans)
     */
    private function syncSimpleOutputs($publicationId, $targetCount, $realCount)
    {
        $targetCount = (int)$targetCount;
        $realCount   = (int)$realCount;
        if ($targetCount <= 0) return;

        // Gunakan tabel 'publication_plans' untuk detail output
        
        $year = now()->year;
        // Kita set default di Q1 (Januari akhir) atau sesuaikan logika tanggalnya
        $planDate = "$year-01-31"; 

        // 1. Cek jumlah output yang sudah ada
        $existing = DB::table('publication_plans')->where('publication_id', $publicationId)->get();
        $existingCount = $existing->count();

        // 2. Tambah Detail Output jika kurang (berdasarkan Target)
        if ($targetCount > $existingCount) {
            $needed = $targetCount - $existingCount;
            for ($i = 1; $i <= $needed; $i++) {
                $counter = $existingCount + $i;
                DB::table('publication_plans')->insert([
                    'publication_id' => $publicationId,
                    'plan_name'      => "Output $counter",
                    'plan_date'      => $planDate,
                    'actual_date'    => null, // Awalnya belum terealisasi
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        // 3. Update Realisasi (Isi actual_date) sejumlah 'output_real'
        // Ambil lagi data terbaru
        $allOutputs = DB::table('publication_plans')
                        ->where('publication_id', $publicationId)
                        ->orderBy('id')
                        ->get();
        
        $currentRealized = $allOutputs->whereNotNull('actual_date')->count();

        // Jika realisasi di form bertambah, update item yang belum selesai
        // if ($realCount > $currentRealized) {
        //     $toUpdate = $realCount - $currentRealized;
        //     foreach ($allOutputs as $out) {
        //         if ($toUpdate <= 0) break;
        //         if (is_null($out->actual_date)) {
        //             DB::table('publication_plans')
        //                 ->where('id', $out->id)
        //                 ->update([
        //                     'actual_date' => $planDate, // Anggap selesai di tanggal plan
        //                     'updated_at' => now()
        //                 ]);
        //             $toUpdate--;
        //         }
        //     }
        // }
    }

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

    private function generateMonthlyPublications($baseName, $report, $pic, array $months, $request)
    {
        $currentYear = now()->year;

        // Ambil nilai target
        $targetTahapan = (int)$request->input('q1_plan', 0);
        $targetOutput  = (int)$request->input('output_plan', 0);
        $realOutput    = (int)$request->input('output_real', 0);

        foreach ($months as $monthNumber) {
            $monthNumber = (int)$monthNumber;

            $targetDate = Carbon::create($currentYear, $monthNumber, 1);
            $startDate = $targetDate->copy()->startOfMonth()->format('Y-m-d');
            $endDate = $targetDate->copy()->endOfMonth()->format('Y-m-d');

            $year = $targetDate->year;
            $month = $targetDate->month;
            $monthName = $this->getMonthName($month);
            
            $publicationName = $baseName . ' - ' . $monthName . ' ' . $year;

            // 1. Buat Publikasi
            $publicationId = DB::table('publications')->insertGetId([
                'publication_name'   => $publicationName,
                'publication_report' => $report,
                'publication_pic'    => $pic,
                'fk_user_id'         => Auth::id(),
                'is_monthly'         => 1,
                'slug_publication'   => Str::uuid(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // 2. Buat TeamTarget
            TeamTarget::create([
                'team_name'      => $pic,
                'activity_name'  => $publicationName,
                'report_name'    => $report,
                'publication_id' => $publicationId,
                
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
                
                'output_real_q1' => $request->input('output_real_q1', 0), 
                'output_real_q2' => $request->input('output_real_q2', 0), 
                'output_real_q3' => $request->input('output_real_q3', 0), 
                'output_real_q4' => $request->input('output_real_q4', 0),
            ]);
            
            // 3. Buat Detail Tahapan
            $this->createMonthlySteps($publicationId, $targetTahapan, $startDate, $endDate, $baseName, $monthName, $year);

            // 4. [BARU] Buat Detail Output
            $this->createMonthlyOutputs($publicationId, $targetOutput, $realOutput, $endDate, $baseName, $monthName, $year);
        }
    }

    private function createMonthlySteps($publicationId, $count, $startDate, $endDate, $baseName, $monthName, $year)
    {
        if ($count <= 0) return;

        for ($i = 1; $i <= $count; $i++) {
            $planName = "Tahapan $i - " . $baseName . " (" . $monthName . ")";
            
            DB::table('steps_plans')->insert([
                'publication_id'    => $publicationId, 
                'plan_type'         => 'pengumpulan data', 
                'plan_name'         => $planName,
                'plan_start_date'   => $startDate, 
                'plan_end_date'     => $endDate,
                'plan_desc'         => "Tahapan ke-$i untuk kegiatan $baseName bulan $monthName $year",
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }

    /**
     * [BARU] Helper khusus untuk membuat detail output bulanan
     */
    private function createMonthlyOutputs($publicationId, $targetCount, $realCount, $endDate, $baseName, $monthName, $year)
    {
        if ($targetCount <= 0) return;

        // Buat Plan Output sejumlah target
        for ($i = 1; $i <= $targetCount; $i++) {
            // Cek apakah item ini sudah terealisasi (berdasarkan jumlah realCount)
            $isRealized = ($i <= $realCount);
            
            DB::table('publication_plans')->insert([
                'publication_id' => $publicationId,
                'plan_name'      => "Output $i - " . $baseName . " (" . $monthName . ")",
                'plan_date'      => $endDate, // Target selesai di akhir bulan
                // 'actual_date'    => $isRealized ? $endDate : null, // Jika terealisasi, set tanggal sama
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    public function destroy($id) 
    {
        DB::beginTransaction();
        try {
            $target = TeamTarget::with('publication')->findOrFail($id);
            
            if ($target->publication) {
                // Hapus Tahapan
                $target->publication->stepsPlans()->delete();
                
                // [BARU] Hapus Output (publication_plans)
                if (\Schema::hasTable('publication_plans')) {
                    DB::table('publication_plans')->where('publication_id', $target->publication_id)->delete();
                }

                if (method_exists($target->publication, 'files')) {
                    $target->publication->files()->delete();
                }
                
                $target->publication->delete(); 
            }
            
            $target->delete();

            DB::commit(); 
            return redirect()->back()->with('success', 'Data Target & Publikasi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack(); 
            \Log::error('Gagal menghapus target: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}