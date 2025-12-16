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
    private const SPECIAL_INDICATORS = [
        "Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai Standar",
        "Indeks Pelayanan Publik - Penilaian Mandiri",
        "Nilai SAKIP oleh Inspektorat",
        "Indeks Implementasi BerAKHLAK",
    ];

    public static function isSpecialIndicator($reportName): bool
    {
        return in_array($reportName, self::SPECIAL_INDICATORS);
    }

    public static function getSpecialIndicators(): array
    {
        return self::SPECIAL_INDICATORS;
    }

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

        $year = session('selected_year', now()->year);
        $query->whereYear('created_at', $year); 

        $targets = $query->orderBy('id', 'desc')->get();

        foreach ($targets as $target) {
            $target->is_special = self::isSpecialIndicator($target->report_name);
        }

        $specialIndicators = self::SPECIAL_INDICATORS;
        
        return view('tampilan.team_targets', compact('targets', 'specialIndicators'));
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
            // Tambahkan validasi untuk poin (boleh desimal)
            'output_real_q1' => 'nullable|numeric|min:0',
            'output_real_q2' => 'nullable|numeric|min:0',
            'output_real_q3' => 'nullable|numeric|min:0',
            'output_real_q4' => 'nullable|numeric|min:0',
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

        $isSpecial = self::isSpecialIndicator($publicationReport);

        DB::beginTransaction();

        try {
            if ($request->has('is_monthly') && $request->has('months') && is_array($request->months)) {
                
                $this->generateMonthlyPublications(
                    $request->publication_name,
                    $publicationReport,
                    $request->publication_pic,
                    $request->months,
                    $request,
                    $isSpecial // Pass flag
                );
                
                $successMessage = count($request->months) . ' publikasi bulanan berhasil ditambahkan!';

            } else {
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

                $targetTahapan = $request->input('q1_plan', 0);
                $targetOutput  = $request->input('output_plan', 0);
                $realOutput    = $request->input('output_real', 0);

                TeamTarget::create([
                    'team_name'      => $request->publication_pic,
                    'activity_name'  => $request->publication_name,
                    'report_name'    => $publicationReport,
                    'publication_id' => $publicationId,
                    
                    'q1_plan' => $request->input('q1_plan', 0), 
                    'q2_plan' => $request->input('q1_plan', 0),
                    'q3_plan' => $request->input('q1_plan', 0), 
                    'q4_plan' => $request->input('q1_plan', 0),
                    
                    'q1_real' => $request->input('q1_real', 0), 
                    'q2_real' => $request->input('q2_real', 0),
                    'q3_real' => $request->input('q3_real', 0), 
                    'q4_real' => $request->input('q4_real', 0),
                    
                    'output_plan' => $request->input('output_plan', 0),
                    'output_real' => $request->input('output_real', 0),

                    // TARGET poin per TW (di-lock)
                    'output_real_q1' => $request->input('output_real_q1', 0),
                    'output_real_q2' => $request->input('output_real_q2', 0),
                    'output_real_q3' => $request->input('output_real_q3', 0),
                    'output_real_q4' => $request->input('output_real_q4', 0),

                    'actual_output_q1' => 0,
                    'actual_output_q2' => 0,
                    'actual_output_q3' => 0,
                    'actual_output_q4' => 0,
                    
                    'is_special_indicator' => $isSpecial,
                ]);

                if (!$isSpecial) {
                    $this->syncSimpleSteps($publicationId, $targetTahapan);
                    $this->syncSimpleOutputs($publicationId, $targetOutput, $realOutput);
                }

                $successMessage = 'Publikasi berhasil ditambahkan!';
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
        $request->validate([
            'publication_name' => 'required|string|max:255|min:3',
            'publication_pic'  => 'required|string|max:255|min:3',
            // Tambahkan validasi untuk poin (boleh desimal)
            'output_real_q1' => 'nullable|numeric|min:0',
            'output_real_q2' => 'nullable|numeric|min:0',
            'output_real_q3' => 'nullable|numeric|min:0',
            'output_real_q4' => 'nullable|numeric|min:0',
        ]);

        $reportName = ($request->publication_report === 'other') 
            ? $request->publication_report_other 
            : $request->publication_report;

        if (empty($reportName)) {
            $targetLama = TeamTarget::find($id);
            $reportName = $targetLama->report_name ?? '-'; 
        }

        $target = TeamTarget::findOrFail($id);

        $isSpecial = self::isSpecialIndicator($reportName);

        $target->update([
            'team_name'     => $request->publication_pic,
            'activity_name' => $request->publication_name,
            'report_name'   => $reportName,
            
            'q1_plan' => $request->input('q1_plan', 0), 
            'q2_plan' => $request->input('q1_plan', 0),
            'q3_plan' => $request->input('q1_plan', 0), 
            'q4_plan' => $request->input('q1_plan', 0),
            
            'q1_real' => $request->input('q1_real', 0), 
            'q2_real' => $request->input('q2_real', 0),
            'q3_real' => $request->input('q3_real', 0), 
            'q4_real' => $request->input('q4_real', 0),
            
            'output_plan' => $request->input('output_plan', 0),
            'output_real' => $request->input('output_real', 0),

            // TARGET poin per TW
            'output_real_q1' => $request->input('output_real_q1', 0),
            'output_real_q2' => $request->input('output_real_q2', 0),
            'output_real_q3' => $request->input('output_real_q3', 0),
            'output_real_q4' => $request->input('output_real_q4', 0),
            
            'is_special_indicator' => $isSpecial,
        ]);
        
        if($target->publication) {
            $target->publication->update([
                'publication_name'   => $request->publication_name,
                'publication_report' => $reportName,
                'publication_pic'    => $request->publication_pic,
            ]);

            if (!$isSpecial) {
                $targetTahapan = $request->input('q1_plan', 0);
                $this->syncSimpleSteps($target->publication_id, $targetTahapan);

                $targetOutput = $request->input('output_plan', 0);
                $realOutput   = $request->input('output_real', 0);
                $this->syncSimpleOutputs($target->publication_id, $targetOutput, $realOutput);
            }
        }
        
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function updateRealisasiPoin(Request $request, $id)
    {
        $request->validate([
            'actual_output_q1' => 'nullable|numeric|min:0',
            'actual_output_q2' => 'nullable|numeric|min:0',
            'actual_output_q3' => 'nullable|numeric|min:0',
            'actual_output_q4' => 'nullable|numeric|min:0',
        ]);

        $target = TeamTarget::findOrFail($id);

        if (!self::isSpecialIndicator($target->report_name)) {
            return redirect()->back()->with('error', 'Hanya indikator spesial yang bisa diupdate realisasi poinnya.');
        }

        $target->update([
            'actual_output_q1' => $request->input('actual_output_q1', 0),
            'actual_output_q2' => $request->input('actual_output_q2', 0),
            'actual_output_q3' => $request->input('actual_output_q3', 0),
            'actual_output_q4' => $request->input('actual_output_q4', 0),
        ]);

        return redirect()->back()->with('success', 'Realisasi poin berhasil diupdate!');
    }

    // --- HELPER FUNCTIONS ---
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

    private function syncSimpleOutputs($publicationId, $targetCount, $realCount)
    {
        $targetCount = (int)$targetCount;
        $realCount   = (int)$realCount;
        if ($targetCount <= 0) return;

        $year = now()->year;
        $planDate = "$year-01-31"; 

        $existing = DB::table('publication_plans')->where('publication_id', $publicationId)->get();
        $existingCount = $existing->count();

        if ($targetCount > $existingCount) {
            $needed = $targetCount - $existingCount;
            for ($i = 1; $i <= $needed; $i++) {
                $counter = $existingCount + $i;
                DB::table('publication_plans')->insert([
                    'publication_id' => $publicationId,
                    'plan_name'      => "Output $counter",
                    'plan_date'      => $planDate,
                    'actual_date'    => null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
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

    private function generateMonthlyPublications($baseName, $report, $pic, array $months, $request, $isSpecial = false)
    {
        $currentYear = now()->year;

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

            TeamTarget::create([
                'team_name'      => $pic,
                'activity_name'  => $publicationName,
                'report_name'    => $report,
                'publication_id' => $publicationId,
                
                'q1_plan' => $request->input('q1_plan', 0), 
                'q2_plan' => $request->input('q1_plan', 0), 
                'q3_plan' => $request->input('q1_plan', 0), 
                'q4_plan' => $request->input('q1_plan', 0),
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

                'actual_output_q1' => 0,
                'actual_output_q2' => 0,
                'actual_output_q3' => 0,
                'actual_output_q4' => 0,
                
                'is_special_indicator' => $isSpecial,
            ]);
            
            if (!$isSpecial) {
                $this->createMonthlySteps($publicationId, $targetTahapan, $startDate, $endDate, $baseName, $monthName, $year);
                $this->createMonthlyOutputs($publicationId, $targetOutput, $realOutput, $endDate, $baseName, $monthName, $year);
            }
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

    private function createMonthlyOutputs($publicationId, $targetCount, $realCount, $endDate, $baseName, $monthName, $year)
    {
        if ($targetCount <= 0) return;

        for ($i = 1; $i <= $targetCount; $i++) {
            DB::table('publication_plans')->insert([
                'publication_id' => $publicationId,
                'plan_name'      => "Output $i - " . $baseName . " (" . $monthName . ")",
                'plan_date'      => $endDate,
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
                $target->publication->stepsPlans()->delete();
                
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
