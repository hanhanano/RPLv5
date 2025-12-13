<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Publication;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun dari Session, default ke tahun sekarang
        $selectedYear = session('selected_year', now()->year);

        if ($request->ajax() && $request->has('triwulan')) {
            return $this->getStatistikPerTriwulan($request->input('triwulan'), $selectedYear);
        }

        $query = Publication::with([
            'user',
            'stepsPlans.stepsFinals.struggles',
            'files',
            'teamTarget',
            'publicationPlans'
        ]);

        // Filter query utama berdasarkan tahun
        $query->whereYear('created_at', $selectedYear);

        $user = auth()->user();

        if ($user && in_array($user->role, ['ketua_tim', 'operator'])) {
            $query->where('publication_pic', $user->team);
        }

        // Paksa urut ID terbesar (terbaru) di atas
        $publications = $query->orderBy('publication_id', 'desc')->get();
        
        $rekapPublikasiTahunan = $this->getStatistikPublikasiTahunan($user, $selectedYear);
        
        foreach ($publications as $publication) {
            
            // Inisialisasi Array 0
            $rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $rekapFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $lintasTriwulan = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $tepatWaktu = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
            $terlambat = [1 => 0, 2 => 0, 3 => 0, 4 => 0];  
            $outputPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $outputFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            
            $listPlans = [1 => [], 2 => [], 3 => [], 4 => []];
            $listFinals = [1 => [], 2 => [], 3 => [], 4 => []];
            $listLintas = [1 => [], 2 => [], 3 => [], 4 => []];

            foreach ($publication->stepsPlans as $plan) {
                $q = $this->getQuarter($plan->plan_start_date);
                if ($q) {
                    $rekapPlans[$q]++; 
                    $listPlans[$q][] = $plan->plan_name;
                }
                
                if ($plan->stepsFinals) {
                    $fq = $this->getQuarter($plan->stepsFinals->actual_started);
                    if ($fq) {
                        $rekapFinals[$fq]++; 
                        $listFinals[$fq][] = $plan->plan_name;

                        if ($q && $fq <= $q) {
                            $tepatWaktu[$fq]++;
                        } else {
                            $terlambat[$fq]++;
                            $lintasTriwulan[$fq]++;
                            
                            $listLintas[$fq][] = [
                                'plan_name' => $plan->plan_name,
                                'from_quarter' => $q,
                                'to_quarter' => $fq,
                                'delay' => getDelayQuarters($q, $fq)
                            ];
                        }
                    }
                }        
            }

            foreach ($publication->publicationPlans as $pPlan) {
                // Hitung Rencana Output
                if ($pPlan->plan_date) {
                    $q = $this->getQuarter($pPlan->plan_date);
                    if ($q) $outputPlans[$q]++;
                }
                // Hitung Realisasi Output
                if ($pPlan->actual_date) {
                    $q = $this->getQuarter($pPlan->actual_date);
                    if ($q) $outputFinals[$q]++;
                }
            }

            $totalPlans = array_sum($rekapPlans);
            $totalFinals = array_sum($rekapFinals);
            
            $publication->progressKumulatif = ($totalPlans > 0) 
                ? ($totalFinals / $totalPlans) * 100 
                : 0;

            $progressTriwulan = [];
            foreach ([1, 2, 3, 4] as $q) {
                if ($rekapPlans[$q] > 0) {
                    $progressTriwulan[$q] = ($rekapFinals[$q] / $rekapPlans[$q]) * 100;
                } else {
                    $progressTriwulan[$q] = 0;
                }
            }

            $publication->rekapPlans = $rekapPlans;
            $publication->rekapFinals = $rekapFinals;
            $publication->lintasTriwulan = $lintasTriwulan;
            $publication->tepatWaktu = $tepatWaktu;
            $publication->terlambat = $terlambat;
            $publication->progressTriwulan = $progressTriwulan;
            $publication->listPlans = $listPlans;
            $publication->listFinals = $listFinals;
            $publication->listLintas = $listLintas;
            $publication->outputPlans = $outputPlans;
            $publication->outputFinals = $outputFinals;
        }

        // --- SIAPKAN DATA JSON KHUSUS UNTUK GRAFIK ---
        $chartPublicationsData = $publications->map(function($p) {
            $cleanPic = str_replace('Tim ', '', $p->publication_pic);

            return [
                'publication_pic' => $p->publication_pic,
                'rekapPlans' => $p->rekapPlans,
                'rekapFinals' => $p->rekapFinals,
                'tepatWaktu' => $p->tepatWaktu,
                'terlambat' => $p->terlambat,
                'outputPlans' => $p->outputPlans,
                'outputFinals' => $p->outputFinals,
            ];
        })->values();

        // Hitung Data Grafik
        $chartPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $chartFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $chartTepatWaktu = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
        $chartTerlambat = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
        $chartOutputPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $chartOutputFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $chartPerTim = [];

        foreach ($publications as $publication) {
            foreach ([1, 2, 3, 4] as $q) {
                $chartPlans[$q] += $publication->rekapPlans[$q] ?? 0;
                $chartFinals[$q] += $publication->rekapFinals[$q] ?? 0;
                $chartTepatWaktu[$q] += $publication->tepatWaktu[$q] ?? 0;
                $chartTerlambat[$q] += $publication->terlambat[$q] ?? 0;

                // Jumlahkan data Output dari setiap publikasi ke Global
                $chartOutputPlans[$q] += $publication->outputPlans[$q] ?? 0;
                $chartOutputFinals[$q] += $publication->outputFinals[$q] ?? 0;
            }
            
            $pic = str_replace('Tim ', '', $publication->publication_pic);
            
            if (!isset($chartPerTim[$pic])) {
                $chartPerTim[$pic] = [
                    'plans' => 0, 'finals' => 0, 'tepat_waktu' => 0, 'terlambat' => 0
                ];
            }
            
            $chartPerTim[$pic]['plans'] += array_sum($publication->rekapPlans);
            $chartPerTim[$pic]['finals'] += array_sum($publication->rekapFinals);
            $chartPerTim[$pic]['tepat_waktu'] += array_sum($publication->tepatWaktu);
            $chartPerTim[$pic]['terlambat'] += array_sum($publication->terlambat);
        }
        
        $dataGrafikBatang = [
            'labels' => ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4'],
            'rencana' => array_values($chartPlans),
            'realisasi' => array_values($chartFinals),
            'tepat_waktu' => array_values($chartTepatWaktu),
            'terlambat' => array_values($chartTerlambat)
        ];
        
        $dataGrafikPublikasi = [
            'labels' => ['Selesai', 'Berlangsung', 'Belum'],
            'data' => [
                $rekapPublikasiTahunan['sudahSelesai'] ?? 0,
                $rekapPublikasiTahunan['sedangBerlangsung'] ?? 0,
                $rekapPublikasiTahunan['belumBerlangsung'] ?? 0,
            ]
        ];

        $dataRingSummary = [
            'publikasiSelesai' => $rekapPublikasiTahunan['sudahSelesai'] ?? 0,
            'totalPublikasi' => $rekapPublikasiTahunan['total'] ?? 0,
            'tahapanSelesai' => array_sum($chartFinals), 
            'totalTahapan' => array_sum($chartPlans),
            'outputSelesai' => array_sum($chartOutputFinals),
            'totalOutput' => array_sum($chartOutputPlans),
        ];

        $dataGrafikRing = [
            'labels' => ['Publikasi Selesai', 'Tahapan Selesai', 'Output Selesai'],
            'data' => [
                $dataRingSummary['publikasiSelesai'],  
                $dataRingSummary['tahapanSelesai'], 
                $dataRingSummary['outputSelesai'], 
            ]
        ];

        $dataGrafikPerTim = [
            'labels' => array_keys($chartPerTim),
            'plans' => array_column($chartPerTim, 'plans'),
            'finals' => array_column($chartPerTim, 'finals'),
            'tepat_waktu' => array_column($chartPerTim, 'tepat_waktu'),
            'terlambat' => array_column($chartPerTim, 'terlambat')
        ];
        
        $dataTahapanSummary = [];
        $rencanaArray = $dataGrafikBatang['rencana'];
        $realisasiArray = $dataGrafikBatang['realisasi'];

        foreach ([0, 1, 2, 3] as $i) {
            $q = $i + 1;
            $r = $rencanaArray[$i];
            $f = $realisasiArray[$i];
            $percent = ($r > 0) ? round(($f / $r) * 100) : 0;
            
            if ($percent == 100) { $color = 'text-green-600'; } 
            elseif ($percent >= 67) { $color = 'text-yellow-600'; } 
            elseif ($percent >= 50) { $color = 'text-orange-600'; } 
            else { $color = 'text-red-600'; }

            $dataTahapanSummary[] = [
                'q' => 'Triwulan ' . $q,
                'ratio' => $f . '/' . $r,
                'percent_text' => $percent . '% selesai',
                'color' => $color,
            ];
        }

        return view('tampilan.homeketua', compact(
            'publications', 
            'chartPublicationsData',
            'rekapPublikasiTahunan', 
            'dataGrafikPublikasi',
            'dataGrafikBatang', 
            'dataGrafikRing', 
            'dataTahapanSummary', 
            'dataRingSummary', 
            'dataGrafikPerTim',
            'selectedYear' 
        ));
    }

    private function getStatistikPerTriwulan($triwulan, $year)
    {
        $user = auth()->user();
        $selectedTriwulan = (int)$triwulan;

        // Pastikan 'publicationPlans' dimuat
        $query = Publication::with(['user', 'stepsPlans.stepsFinals', 'publicationPlans']);
        
        $query->whereYear('created_at', $year);

        if ($user && in_array($user->role, ['ketua_tim', 'operator'])) {
            $query->where('publication_pic', $user->team);
        }

        $publications = $query->get();

        $totalPublikasi = $publications->count();
        $sudahSelesaiPublikasi = 0;
        $sedangBerlangsungPublikasi = 0;
        
        // Variabel Tahapan
        $totalTahapanKumulatif = 0;
        $selesaiTahapanKumulatif = 0;
        $sedangTahapanKumulatif = 0;
        $tertundaTahapanKumulatif = 0;
        $belumBerlangsungTahapanKumulatif = 0;

        // Variabel Output
        $totalOutputKumulatif = 0;
        $selesaiOutputKumulatif = 0;
        $belumBerlangsungOutputKumulatif = 0; 

        foreach ($publications as $publication) {
            
            // Variabel Scope untuk Publikasi Ini
            $plansInScope = 0;
            $completedPlansInScope = 0;
            
            $outputsInScope = 0;
            $completedOutputsInScope = 0;

            // A. LOGIKA TAHAPAN
            if ($publication->stepsPlans) {
                foreach ($publication->stepsPlans as $plan) {
                    if (empty($plan->plan_start_date)) {
                        $belumBerlangsungTahapanKumulatif++;
                        continue;
                    }
                    $q = $this->getQuarter($plan->plan_start_date); 
                    
                    if ($q && $q <= $selectedTriwulan) {
                        $totalTahapanKumulatif++;
                        $plansInScope++;

                        if ($plan->stepsFinals) {
                            $fq = $this->getQuarter($plan->stepsFinals->actual_started);
                            if ($fq && $fq <= $selectedTriwulan) {
                                $selesaiTahapanKumulatif++;
                                $completedPlansInScope++;
                                if ($fq > $q) {
                                    $tertundaTahapanKumulatif++;
                                }
                            } else {
                                $sedangTahapanKumulatif++;
                            }
                        } else {
                            $sedangTahapanKumulatif++;
                        }
                    }
                }
            }

            // B. LOGIKA OUTPUT
            if ($publication->publicationPlans) {
                foreach ($publication->publicationPlans as $outPlan) {
                    $qOut = $this->getQuarter($outPlan->plan_date);

                    if ($qOut && $qOut <= $selectedTriwulan) {
                        $totalOutputKumulatif++;
                        $outputsInScope++;

                        if (!empty($outPlan->actual_date)) {
                            $qActualOut = $this->getQuarter($outPlan->actual_date);
                            
                            if ($qActualOut && $qActualOut <= $selectedTriwulan) {
                                $selesaiOutputKumulatif++;
                                $completedOutputsInScope++;
                            } else {
                                $belumBerlangsungOutputKumulatif++;
                            }
                        } else {
                            $belumBerlangsungOutputKumulatif++;
                        }
                    }
                }
            }

            // Publikasi aktif jika ada rencana tahapan ATAU rencana output
            $anyActivityInScope = ($plansInScope > 0 || $outputsInScope > 0);

            if ($anyActivityInScope) {
                // Cek status Tahapan (Jika target 0, dianggap selesai)
                $isStagesDone = ($plansInScope > 0) ? ($completedPlansInScope === $plansInScope) : true;
                
                // Cek status Output (Jika target 0, dianggap selesai)
                $isOutputsDone = ($outputsInScope > 0) ? ($completedOutputsInScope === $outputsInScope) : true;

                // Syarat Selesai: KEDUANYA harus selesai
                if ($isStagesDone && $isOutputsDone) {
                    $sudahSelesaiPublikasi++;
                } else {
                    $sedangBerlangsungPublikasi++;
                }
            }
        }

        $belumBerlangsungPublikasi = $totalPublikasi - $sudahSelesaiPublikasi - $sedangBerlangsungPublikasi;
        
        $persentaseRealisasiTahapan = ($totalTahapanKumulatif > 0) 
            ? round(($selesaiTahapanKumulatif / $totalTahapanKumulatif) * 100, 2) 
            : 0;

        $persentaseRealisasiOutput = ($totalOutputKumulatif > 0)
            ? round(($selesaiOutputKumulatif / $totalOutputKumulatif) * 100, 2)
            : 0;

        return response()->json([
            'publikasi' => [
                'total' => $totalPublikasi,
                'belumBerlangsung' => $belumBerlangsungPublikasi,
                'sedangBerlangsung' => $sedangBerlangsungPublikasi,
                'sudahSelesai' => $sudahSelesaiPublikasi,
            ],
            'tahapan' => [
                'total' => $totalTahapanKumulatif,
                'belumBerlangsung' => $belumBerlangsungTahapanKumulatif, 
                'sedangBerlangsung' => $sedangTahapanKumulatif,
                'sudahSelesai' => $selesaiTahapanKumulatif,
                'tertunda' => $tertundaTahapanKumulatif, 
                'persentaseRealisasi' => $persentaseRealisasiTahapan,
            ],
            'output' => [
                'total' => $totalOutputKumulatif,
                'belumBerlangsung' => $belumBerlangsungOutputKumulatif,
                'sudahSelesai' => $selesaiOutputKumulatif,
                'persentaseRealisasi' => $persentaseRealisasiOutput
            ]
        ]);
    }

    private function getQuarter($date)
    {
        if (empty($date)) return null;
        try {
            return ceil(\Carbon\Carbon::parse($date)->month / 3);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Logika Statistik Tahunan
    private function getStatistikPublikasiTahunan($user = null, $year = null)
    {  
        $query = Publication::with(['user','stepsPlans.stepsFinals', 'publicationPlans']);
        
        if($year) {
            $query->whereYear('created_at', $year);
        }

        if ($user && in_array($user->role, ['ketua_tim', 'operator'])) {
            $query->where('publication_pic', $user->team);
        }

        $publications = $query->get();

        $totalPublikasi = $publications->count();
        $belumBerlangsungPublikasi = 0;
        $sedangBerlangsungPublikasi = 0;
        $sudahSelesaiPublikasi = 0;

        foreach ($publications as $publication) {
            
            $totalTahapan = count($publication->stepsPlans);
            $jumlahSelesaiTahapan = 0;
            $jumlahBelumAdaTanggal = 0;

            foreach ($publication->stepsPlans as $plan) {
                if (empty($plan->plan_start_date) && empty($plan->plan_end_date)) {
                    $jumlahBelumAdaTanggal++;
                    continue;
                }
                if ($plan->stepsFinals) {
                    $jumlahSelesaiTahapan++;
                }
            }

            $totalOutputTarget = 0;
            $totalOutputRealisasi = 0;
            foreach ($publication->publicationPlans as $outPlan) {
                if ($outPlan->plan_date) $totalOutputTarget++;
                if ($outPlan->actual_date) $totalOutputRealisasi++;
            }

            if ($totalTahapan === 0 || $jumlahBelumAdaTanggal === $totalTahapan) {
                $belumBerlangsungPublikasi++;
            } 
            
            elseif (
                ($jumlahSelesaiTahapan === $totalTahapan) && 
                ($totalOutputRealisasi >= $totalOutputTarget)
            ) {
                $sudahSelesaiPublikasi++;
            } else {
                $sedangBerlangsungPublikasi++;
            }
        }

        return [
            'total' => $totalPublikasi,
            'belumBerlangsung' => $belumBerlangsungPublikasi,
            'sedangBerlangsung' => $sedangBerlangsungPublikasi,
            'sudahSelesai' => $sudahSelesaiPublikasi,
        ];
    }

    public function search(Request $request)
    {
        // Ambil Tahun dari Session
        $selectedYear = session('selected_year', now()->year);
        $query = $request->input('query');

        $publications = Publication::whereYear('created_at', $selectedYear) // Filter Tahun
        ->when($query, function ($q) use ($query) {
            $q->where(function($sub) use ($query){
                $sub->where('publication_report', 'like', "%{$query}%")
                    ->orWhere('publication_name', 'like', "%{$query}%")
                    ->orWhere('publication_pic', 'like', "%{$query}%");
            });
        })
        ->with(['user', 'stepsPlans.stepsFinals.struggles', 'files', 'teamTarget', 'publicationPlans'])
        ->orderBy('publication_id', 'desc')
        ->get();

        foreach ($publications as $publication) {$rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $rekapFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $lintasTriwulan = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
            
            $listPlans = [1 => [], 2 => [], 3 => [], 4 => []];
            $listFinals = [1 => [], 2 => [], 3 => [], 4 => []];
            $listLintas = [1 => [], 2 => [], 3 => [], 4 => []];

            foreach ($publication->stepsPlans as $plan) {
                $q = getQuarter($plan->plan_start_date);
                if ($q) {
                    $rekapPlans[$q]++;
                    $listPlans[$q][] = $plan->plan_name; 
                }
                if ($plan->stepsFinals) {
                    $fq = getQuarter($plan->stepsFinals->actual_started);
                    if ($fq) {
                        $rekapFinals[$fq]++;
                        $listFinals[$fq][] = $plan->plan_name;
                    }
                    if ($fq && $q && $fq != $q) {
                        $lintasTriwulan[$fq]++; 
                        $listLintas[$fq][] = [
                            'plan_name' => $plan->plan_name,
                            'from_quarter' => "Triwulan $q",
                            'to_quarter' => "Triwulan $fq"
                        ];
                    }
                }
            }
            $totalPlans = array_sum($rekapPlans);
            $totalFinals = array_sum($rekapFinals);
            $progressKumulatif = $totalPlans > 0 ? ($totalFinals / $totalPlans) * 100 : 0;

            $progressTriwulan = [];
            foreach ([1, 2, 3, 4] as $q) {
                $progressTriwulan[$q] = $rekapPlans[$q] > 0 ? ($rekapFinals[$q] / $rekapPlans[$q]) * 100 : 0;
            }

            $publication->rekapPlans = $rekapPlans;
            $publication->rekapFinals = $rekapFinals;
            $publication->progressKumulatif = $progressKumulatif;
            $publication->progressTriwulan = $progressTriwulan;
            $publication->listPlans = $listPlans;
            $publication->listFinals = $listFinals;
            $publication->listLintas = $listLintas;
        }

        return response()->json($publications->map(function($pub) {
            return [
                'slug_publication' => $pub->slug_publication,
                'publication_report' => $pub->publication_report,
                'publication_name' => $pub->publication_name,
                'publication_pic' => $pub->publication_pic,
                
                'rekapPlans' => $pub->rekapPlans,
                'rekapFinals' => $pub->rekapFinals,
                'filesCount' => $pub->files->count(),

                'target_q1_plan' => $pub->teamTarget->q1_plan ?? 0,
                'target_q2_plan' => $pub->teamTarget->q2_plan ?? 0,
                'target_q3_plan' => $pub->teamTarget->q3_plan ?? 0,
                'target_q4_plan' => $pub->teamTarget->q4_plan ?? 0,
                'target_q1_real' => $pub->teamTarget->q1_real ?? 0,
                'target_q2_real' => $pub->teamTarget->q2_real ?? 0,
                'target_q3_real' => $pub->teamTarget->q3_real ?? 0,
                'target_q4_real' => $pub->teamTarget->q4_real ?? 0,
                'target_output_plan' => $pub->teamTarget->output_plan ?? 0,
                'target_output_real' => $pub->teamTarget->output_real ?? 0,

                'target_output_real_q1' => $pub->teamTarget->output_real_q1 ?? 0,
                'target_output_real_q2' => $pub->teamTarget->output_real_q2 ?? 0,
                'target_output_real_q3' => $pub->teamTarget->output_real_q3 ?? 0,
                'target_output_real_q4' => $pub->teamTarget->output_real_q4 ?? 0,

                'lintasTriwulan' => $pub->lintasTriwulan,
                'progressKumulatif' => $pub->progressKumulatif,
                'progressTriwulan' => $pub->progressTriwulan,
                'listPlans' => $pub->listPlans,     
                'listFinals' => $pub->listFinals,   
                'listLintas' => $pub->listLintas,   
                'filesList' => $pub->files->pluck('file_name')->toArray(),
            ];
        }));
    }
    // Menampilkan detail publikasi
    public function show($id)
    {
        $publication = Publication::with([
            'user',
            'stepsPlans.stepsFinals.struggles',
            'files',
            'publicationPlans'
        ])->findOrFail($id);
        return view('publications.show', compact('publication'));
    }

    // Menampilkan form create
    public function create()
    {
        $users = User::all();
        return view('publications.create', compact('users'));
    }
    
    // Function Store
    public function store(Request $request) {
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
                return redirect()->back()->withInput()->with('error', 'Akses ditolak.');
            }
        }
        $publicationReport = $request->publication_report === 'other' ? $request->publication_report_other : $request->publication_report;
        \DB::beginTransaction();
        try {
            if ($request->has('is_monthly') && $request->has('months') && is_array($request->months)) {
                $this->generateMonthlyPublications($request->publication_name, $publicationReport, $request->publication_pic, $request->months);
                $successMessage = count($request->months) . ' publikasi bulanan berhasil ditambahkan!';
            } else {
                \DB::table('publications')->insert([
                    'publication_name'   => $request->publication_name,
                    'publication_report' => $publicationReport,
                    'publication_pic'    => $request->publication_pic,
                    'fk_user_id'         => Auth::id(),
                    'is_monthly'         => 0,
                    'slug_publication'   => \Str::uuid(),
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
                $successMessage = 'Publikasi berhasil ditambahkan!';
            }
            \DB::commit();
            return redirect()->route('daftarpublikasi')->with('success', $successMessage);
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    // Function Update
    public function update(Request $request, Publication $publication) {
         $request->validate([
            'publication_name'   => 'required|string|max:255|min:3',
            'publication_report' => 'required|string|max:255|min:3',
            'publication_pic'    => 'required|string|max:255|min:3',
            'publication_report_other' => 'nullable|string|max:255|min:3'
        ]);
        $publicationReport = $request->publication_report === 'other' ? $request->publication_report_other : $request->publication_report;
        $publication->update([
            'publication_name'   => $request->publication_name,
            'publication_report' => $publicationReport,
            'publication_pic'    => $request->publication_pic,
        ]);
        return redirect()->route('daftarpublikasi')->with('success', 'Publikasi diperbarui.');
    }

    // Function Destroy
    public function destroy(Publication $publication) {
        try {
            $publication->stepsPlans()->delete();
            $publication->delete();
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Terhapus'], 200);
            }
            return redirect()->route('publications.index')->with('success', 'Terhapus');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal hapus');
        }
    }
    
    // Function generateMonthlyPublications
    private function generateMonthlyPublications($baseName, $report, $pic, array $months) {
        $currentYear = now()->year;
        foreach ($months as $monthNumber) {
            $targetDate = \Carbon\Carbon::create($currentYear, (int)$monthNumber, 1);
            $publicationName = $baseName . ' - ' . $this->getMonthName($targetDate->month) . ' ' . $targetDate->year;
            
            $id = \DB::table('publications')->insertGetId([
                'publication_name' => $publicationName,
                'publication_report' => $report,
                'publication_pic' => $pic,
                'fk_user_id' => Auth::id(),
                'is_monthly' => 1,
                'slug_publication' => \Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->createDefaultStep($id, $baseName, $this->getMonthName($targetDate->month), $targetDate->year, $targetDate->copy()->startOfMonth(), $targetDate->copy()->endOfMonth());
        }
    }
    
    private function createDefaultStep($id, $base, $mName, $y, $start, $end) {
        \DB::table('steps_plans')->insert([
            'publication_id' => $id,
            'plan_type' => 'pengumpulan data',
            'plan_name' => "Kegiatan $base - $mName $y",
            'plan_start_date' => $start,
            'plan_end_date' => $end,
            'plan_desc' => "Tahapan kegiatan $base bulan $mName $y",
            'created_at' => now(), 'updated_at' => now()
        ]);
    }

    private function getMonthName($m) {
        return ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$m]??'';
    }
    
    public function getRouteKeyName() { return 'slug_publication'; }
}