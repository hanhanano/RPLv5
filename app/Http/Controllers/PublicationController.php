<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Publication;
use App\Models\StepsPlan;
use Illuminate\Support\Str;
use App\Models\PublicationFile;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('triwulan')) {
            return $this->getStatistikPerTriwulan($request->input('triwulan'));
        }

        $query = Publication::with([
            'user',
            'stepsPlans.stepsFinals.struggles',
            'files'
        ]);

        $user = auth()->user();
    
        if ($user && in_array($user->role, ['ketua_tim', 'operator'])) {
            $query->where('publication_pic', $user->team);
        }

        $publications = $query->get();

        $rekapPublikasiTahunan = $this->getStatistikPublikasiTahunan($user);
        
        foreach ($publications as $publication) {
            $rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $rekapFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $lintasTriwulan = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $tepatWaktu = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
            $terlambat = [1 => 0, 2 => 0, 3 => 0, 4 => 0];  
            
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
                    if ($q) {
                        $rekapFinals[$q]++;
                        $listFinals[$q][] = $plan->plan_name;

                        if ($fq && $fq <= $q) {
                            $tepatWaktu[$q]++;
                        } else {
                            $terlambat[$q]++;
                            $lintasTriwulan[$q]++;
                            
                            $listLintas[$q][] = [
                                'plan_name' => $plan->plan_name,
                                'from_quarter' => $q,
                                'to_quarter' => $fq,
                                'delay' => getDelayQuarters($q, $fq)
                            ];
                        }
                    }
                }        
            }

            $totalPlans = array_sum($rekapPlans);
            $totalFinals = array_sum($rekapFinals);
            $progressKumulatif = ($totalPlans > 0) ? ($totalFinals / $totalPlans) * 100 : 0;

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
            $publication->progressKumulatif = $progressKumulatif;
            $publication->progressTriwulan = $progressTriwulan;
            $publication->listPlans = $listPlans;
            $publication->listFinals = $listFinals;
            $publication->listLintas = $listLintas;
        }

        $chartPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $chartFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $chartTepatWaktu = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
        $chartTerlambat = [1 => 0, 2 => 0, 3 => 0, 4 => 0]; 
        $chartPerTim = [];

        foreach ($publications as $publication) {
            foreach ([1, 2, 3, 4] as $q) {
                $chartPlans[$q] += $publication->rekapPlans[$q] ?? 0;
                $chartFinals[$q] += $publication->rekapFinals[$q] ?? 0;
                $chartTepatWaktu[$q] += $publication->tepatWaktu[$q] ?? 0;
                $chartTerlambat[$q] += $publication->terlambat[$q] ?? 0;
            }
            
            $pic = $publication->publication_pic;
            if (!isset($chartPerTim[$pic])) {
                $chartPerTim[$pic] = [
                    'plans' => 0,
                    'finals' => 0,
                    'tepat_waktu' => 0,
                    'terlambat' => 0
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
            'tahapanSelesai' => array_sum($dataGrafikBatang['realisasi']),
            'totalTahapan' => array_sum($dataGrafikBatang['rencana']),
        ];

        $dataGrafikRing = [
            'labels' => ['Publikasi Selesai', 'Tahapan Selesai'],
            'data' => [
                $dataRingSummary['publikasiSelesai'],  
                $dataRingSummary['tahapanSelesai'], 
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

            if ($percent == 100) {
                $color = 'text-green-600';
            } elseif ($percent >= 67) {
                $color = 'text-yellow-600';
            } elseif ($percent >= 50) {
                $color = 'text-orange-600';
            } else {
                $color = 'text-red-600';
            }

            $dataTahapanSummary[] = [
                'q' => 'Q' . $q,
                'ratio' => $f . '/' . $r,
                'percent_text' => $percent . '% selesai',
                'color' => $color,
            ];
        }

        return view('tampilan.homeketua', compact(
            'publications',
            'rekapPublikasiTahunan', 
            'dataGrafikPublikasi',
            'dataGrafikBatang', 
            'dataGrafikRing',
            'dataTahapanSummary', 
            'dataRingSummary',
            'dataGrafikPerTim'
        ));
    }

    // private function getStatistikPerTriwulan($triwulan)
    // {
    //     $publications = Publication::with([
    //         'user',
    //         'stepsPlans.stepsFinals'
    //     ])->get();

    //     // $totalPublikasi = $publications->count();
    //     // $belumBerlangsungPublikasi = 0;
    //     // $sedangBerlangsungPublikasi = 0;
    //     // $sudahSelesaiPublikasi = 0;

    //     $totalTahapan = 0;
    //     $belumBerlangsungTahapan = 0;
    //     $sedangBerlangsungTahapan = 0;
    //     $sudahSelesaiTahapan = 0;
    //     $tertundaTahapan = 0;

    //     foreach ($publications as $publication) {
    //         $rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
    //         $rekapFinals = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

    //         // Reset per publikasi
    //         $belumTahapan = 0;
    //         $berlangsungTahapan = 0;
    //         $selesaiTahapan = 0;

    //         foreach ($publication->stepsPlans as $plan) {
    //             // Belum berlangsung
    //             if (empty($plan->plan_start_date) && empty($plan->plan_end_date)) {
    //                 $belumTahapan++;
    //                 $belumBerlangsungTahapan++;
    //                 continue;
    //             }

    //             $q = getQuarter($plan->plan_start_date);

    //             if ($q == $triwulan) {
    //                 $totalTahapan++;
    //                 $rekapPlans[$q]++;

    //                 // Sudah selesai
    //                 if ($plan->stepsFinals) {
    //                     $selesaiTahapan++;
    //                     $fq = getQuarter($plan->stepsFinals->actual_started);
    //                     if ($fq && $fq != $q) {
    //                         $tertundaTahapan++;
    //                     } else {
    //                         $sudahSelesaiTahapan++;
    //                     }
    //                 }
    //                 // Sedang berlangsung
    //                 else {
    //                     $berlangsungTahapan++;
    //                     $sedangBerlangsungTahapan++;
    //                 }
    //             }
    //         }

    //         // Hitung progres publikasi di triwulan ini
    //         // $totalPlans = array_sum($rekapPlans);
    //         // $totalFinals = array_sum($rekapFinals);
    //         // $progressTriwulan = ($totalPlans > 0) ? ($totalFinals / $totalPlans) * 100 : 0;

    //         // Status publikasi
    //         // if ($selesaiTahapan > 0 && $berlangsungTahapan == 0 && $belumTahapan == 0) {
    //         //     $sudahSelesaiPublikasi++;
    //         // } elseif ($berlangsungTahapan > 0) {
    //         //     $sedangBerlangsungPublikasi++;
    //         // } elseif ($belumTahapan > 0 && $berlangsungTahapan == 0 && $selesaiTahapan == 0) {
    //         //     $belumBerlangsungPublikasi++;
    //         // }
    //     }

    //     $persentaseRealisasi = ($totalTahapan > 0) 
    //         ? round(($sudahSelesaiTahapan / $totalTahapan) * 100) 
    //         : 0;

    //     return response()->json([
    //         // 'publikasi' => [
    //         //     'total' => $totalPublikasi,
    //         //     'belumBerlangsung' => $belumBerlangsungPublikasi,
    //         //     'sedangBerlangsung' => $sedangBerlangsungPublikasi,
    //         //     'sudahSelesai' => $sudahSelesaiPublikasi,
    //         // ],
    //         'tahapan' => [
    //             'total' => $totalTahapan,
    //             'belumBerlangsung' => $belumBerlangsungTahapan,
    //             'sedangBerlangsung' => $sedangBerlangsungTahapan,
    //             'sudahSelesai' => $sudahSelesaiTahapan,
    //             'tertunda' => $tertundaTahapan,
    //             'persentaseRealisasi' => $persentaseRealisasi,
    //         ]
    //     ]);
    // }

    private function getStatistikPerTriwulan($triwulan)
    {
        $user = auth()->user();

        $selectedTriwulan = (int)$triwulan;

        $query = Publication::with([
            'user',
            'stepsPlans.stepsFinals'
        ]);

        if ($user && in_array($user->role, ['ketua_tim', 'operator'])) {
            $query->where('publication_pic', $user->team);
        }

        $publications = $query->get();

        $totalPublikasi = $publications->count();
        $sudahSelesaiPublikasi = 0;
        $sedangBerlangsungPublikasi = 0;
        
        $totalTahapanKumulatif = 0;
        $selesaiTahapanKumulatif = 0;
        $sedangTahapanKumulatif = 0;
        $tertundaTahapanKumulatif = 0;
        $belumBerlangsungTahapanKumulatif = 0;

        foreach ($publications as $publication) {

            $plansInScope = 0;
            $completedPlansInScope = 0;
            $anyPlanStartedInScope = false;

            foreach ($publication->stepsPlans as $plan) {

                if (empty($plan->plan_start_date)) {
                    $belumBerlangsungTahapanKumulatif++;
                    continue;
                }

                $q = getQuarter($plan->plan_start_date);

                if ($q && $q <= $selectedTriwulan) {
                    $totalTahapanKumulatif++;
                    $anyPlanStartedInScope = true; 

                    if ($plan->stepsFinals) {
                        $fq = getQuarter($plan->stepsFinals->actual_started);
                        
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

                    $plansInScope++;
                }
            } 

            if ($anyPlanStartedInScope) {
                if ($plansInScope > 0 && $completedPlansInScope === $plansInScope) {
                    $sudahSelesaiPublikasi++;
                } else {
                    $sedangBerlangsungPublikasi++;
                }
            }
        }

        $belumBerlangsungPublikasi = $totalPublikasi - $sudahSelesaiPublikasi - $sedangBerlangsungPublikasi;

        $persentaseRealisasi = ($totalTahapanKumulatif > 0) 
            ? round(($selesaiTahapanKumulatif / $totalTahapanKumulatif) * 100) 
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
                'persentaseRealisasi' => $persentaseRealisasi,
            ]
        ]);
    }

    private function getStatistikPublikasiTahunan($user = null)
    {  
        $query = Publication::with([
            'user',
            'stepsPlans.stepsFinals'
        ]);

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
            $jumlahSelesai = 0;
            $jumlahBelumAdaTanggal = 0;

            foreach ($publication->stepsPlans as $plan) {
                if (empty($plan->plan_start_date) && empty($plan->plan_end_date)) {
                    $jumlahBelumAdaTanggal++;
                    continue;
                }

                if ($plan->stepsFinals) {
                    $jumlahSelesai++;
                }
            }

            if ($totalTahapan === 0 || $jumlahBelumAdaTanggal === $totalTahapan) {
                $belumBerlangsungPublikasi++;
            } elseif ($jumlahSelesai === $totalTahapan) {
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

    public function getRouteKeyName()
    {
        return 'slug_publication'; 
    }

    // Menampilkan detail publikasi dengan semua relasinya
    public function show($id)
    {
        $publication = Publication::with([
            'user',
            'stepsPlans.stepsFinals.struggles',
            'files'
        ])->findOrFail($id);

        return view('publications.show', compact('publication'));
    }

    // Menampilkan form untuk membuat publikasi baru
    public function create()
    {
        $users = User::all();
        return view('publications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'publication_name'   => 'required|string|max:255|min:3|regex:/^[^<>`]+$/',
            'publication_report' => 'required|string|max:255|min:3|regex:/^[^<>`]+$/',
            'publication_pic'    => 'required|string|max:255|min:3|regex:/^[^<>`]+$/',
            'publication_report_other' => 'nullable|string|max:255|min:3|regex:/^[^<>`]+$/',
            'is_monthly' => 'nullable|boolean',
            'months' => 'nullable|array',
            'months.*' => 'integer|between:1,12',
        ],
        [
            'publication_name.regex' => 'Nama publikasi tidak boleh mengandung karakter aneh seperti <, >, atau `.',
            'publication_report.regex' => 'Laporan publikasi tidak boleh mengandung karakter aneh seperti <, >, atau `.',
            'publication_pic.regex' => 'PIC tidak boleh mengandung karakter aneh seperti <, >, atau `.',
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

        \DB::beginTransaction();

        try {
            if ($request->has('is_monthly') && $request->has('months') && is_array($request->months)) {
                \Log::info('Creating ' . count($request->months) . ' monthly publications', [
                    'user' => $user->name,
                    'team' => $user->team,
                    'pic' => $request->publication_pic,
                    'months' => $request->months
                ]);
                
                $this->generateMonthlyPublications(
                    $request->publication_name,
                    $publicationReport,
                    $request->publication_pic,
                    $request->months
                );
                
                $successMessage = count($request->months) . ' publikasi bulanan berhasil ditambahkan!';
            } else {
                \Log::info('Creating single publication', [
                    'user' => $user->name,
                    'team' => $user->team,
                    'pic' => $request->publication_pic,
                    'name' => $request->publication_name
                ]);

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

                \Log::info('Single publication created successfully');
                $successMessage = 'Publikasi berhasil ditambahkan!';
            }

            \DB::commit();
            
            return redirect()->route('daftarpublikasi')
                ->with('success', $successMessage);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error creating publication: ' . $e->getMessage(), [
                'user' => $user->name,
                'team' => $user->team,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan publikasi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Publication $publication)
    {        
        $request->validate([
            'publication_name'   => 'required|string|max:255|min:3|regex:/^[^<>`]+$/',
            'publication_report' => 'required|string|max:255|min:3|regex:/^[^<>`]+$/',
            'publication_pic'    => 'required|string|max:255|min:3|regex:/^[^<>`]+$/',
            'publication_report_other' => 'nullable|string|max:255|min:3|regex:/^[^<>`]+$/'
        ],
        [
            'publication_name.regex' => 'Nama publikasi tidak boleh mengandung karakter aneh seperti <, >, atau `.',
            'publication_report.regex' => 'Laporan publikasi tidak boleh mengandung karakter aneh seperti <, >, atau `.',
            'publication_pic.regex' => 'PIC tidak boleh mengandung karakter aneh seperti <, >, atau `.',
        ]
    );

        $publicationReport = $request->publication_report === 'other'
            ? $request->publication_report_other
            : $request->publication_report;

        // $publication = Publication::findOrFail($publication);
        $publication->update([
            'publication_name'   => $request->publication_name,
            'publication_report' => $publicationReport,
            'publication_pic'    => $request->publication_pic,
        ]);

        return redirect()->route('daftarpublikasi')
            ->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function destroy(Publication $publication)
    {
        try {
            $publication->stepsPlans()->delete();
            $publication->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Publikasi dan semua tahapan terkait berhasil dihapus!'
                ], 200);
            }

            return redirect()->route('publications.index')
                ->with('success', 'Publikasi dan semua tahapan terkait berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Error deleting publication: ' . $e->getMessage());
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus publikasi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus publikasi');
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $publications = Publication::when($query, function ($q) use ($query) {
            $q->where('publication_report', 'like', "%{$query}%")
            ->orWhere('publication_name', 'like', "%{$query}%")
            ->orWhere('publication_pic', 'like', "%{$query}%");
        })
        ->with([
            'user',
            'stepsPlans.stepsFinals.struggles',
            'files'
        ])
        ->get();

        foreach ($publications as $publication) {
            $rekapPlans = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
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
                $progressTriwulan[$q] = $rekapPlans[$q] > 0 
                    ? ($rekapFinals[$q] / $rekapPlans[$q]) * 100 
                    : 0;
            }

            $publication->rekapPlans = $rekapPlans;
            $publication->rekapFinals = $rekapFinals;
            $publication->lintasTriwulan = $lintasTriwulan;
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
                'lintasTriwulan' => $pub->lintasTriwulan,
                'progressKumulatif' => $pub->progressKumulatif,
                'progressTriwulan' => $pub->progressTriwulan,
                'listPlans' => $pub->listPlans,     
                'listFinals' => $pub->listFinals,   
                'listLintas' => $pub->listLintas,   
                'filesCount' => $pub->files->count(), 
                'filesList' => $pub->files->pluck('file_name')->toArray(),
            ];
        }));
    }

    public function uploadFiles(Request $request, Publication $publication)
    {
        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|mimes:pdf,xlsx,xls,docx,doc,zip|max:10240',
        ], [
            'files.required' => 'Pilih minimal 1 file untuk diupload',
            'files.*.mimes' => 'File harus berformat: PDF, Excel, Word, atau ZIP',
            'files.*.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            $uploadedCount = 0;

            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();
                $fileName = time() . '_' . uniqid() . '_' . $originalName;

                $filePath = $file->storeAs(
                    'publications/' . $publication->slug_publication,
                    $fileName,
                    'public'
                );

                // Simpan ke database
                PublicationFile::create([
                    'publication_id' => $publication->publication_id,
                    'file_name' => $originalName, 
                    'file_path' => $filePath,
                    'file_type' => $extension,
                    'file_size' => $fileSize,
                ]);

                $uploadedCount++;
            }

            return redirect()->back()
                ->with('success', "Berhasil mengupload {$uploadedCount} file publikasi!");

        } catch (\Exception $e) {
            \Log::error('Error uploading files: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    // Hapus file publikasi
    public function deleteFile(PublicationFile $file)
    {
        try {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            $file->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'File berhasil dihapus!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'File berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Error deleting file: ' . $e->getMessage());
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus file: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus file');
        }
    }

    // Download file publikasi
    public function downloadFile(PublicationFile $file)
    {
        try {
            $filePath = storage_path('app/public/' . $file->file_path);

            if (!file_exists($filePath)) {
                abort(404, 'File tidak ditemukan');
            }

            return response()->download($filePath, $file->file_name);

        } catch (\Exception $e) {
            \Log::error('Error downloading file: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengunduh file');
        }
    }

    // Download semua file publikasi dalam 1 ZIP
    public function downloadAllFiles(Publication $publication)
    {
        try {
            $files = $publication->files;

            if ($files->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Tidak ada file untuk diunduh');
            }

            $zipFileName = 'Publikasi_' . Str::slug($publication->publication_name) . '_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \Exception('Gagal membuat file ZIP');
            }

            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->file_name);
                }
            }

            $zip->close();

            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('Error creating ZIP: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat file ZIP: ' . $e->getMessage());
        }
    }

    /**
     * @param string $baseName - Nama kegiatan dasar (misal: "Inflasi")
     * @param string $report - Nama laporan
     * @param string $pic - Tim PIC
     * @param array $months - Array bulan yang dipilih (1-12)
     * @return void
     */
    
    private function generateMonthlyPublications($baseName, $report, $pic, array $months)
    {
        $currentYear = now()->year;
        
        foreach ($months as $monthNumber) {
            $monthNumber = (int)$monthNumber;
            $targetDate = \Carbon\Carbon::create($currentYear, $monthNumber, 1);
            
            $year = $targetDate->year;
            $month = $targetDate->month;
            $monthName = $this->getMonthName($month);
            
            $publicationName = $baseName . ' - ' . $monthName . ' ' . $year;
            $startDate = $targetDate->copy()->startOfMonth()->format('Y-m-d');
            $endDate = $targetDate->copy()->endOfMonth()->format('Y-m-d');
            
            // Insert publikasi baru
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
            
            \Log::info("Monthly publication created: $publicationName (ID: $publicationId)");
            
            $this->createDefaultStep($publicationId, $baseName, $monthName, $year, $startDate, $endDate);
        }
    }


    /**
     * 📋 Buat 1 tahapan default untuk publikasi bulanan
     * @param int $publicationId
     * @param string $baseName
     * @param string $monthName
     * @param int $year
     * @param string $startDate
     * @param string $endDate
     * @return void
     */
    private function createDefaultStep($publicationId, $baseName, $monthName, $year, $startDate, $endDate)
    {
        $planName = "Kegiatan " . $baseName . ' - ' . $monthName . ' ' . $year;
        
        \DB::table('steps_plans')->insert([
            'publication_id'    => $publicationId,
            'plan_type'         => 'monthly',
            'plan_name'         => $planName,
            'plan_start_date'   => $startDate,
            'plan_end_date'     => $endDate,
            'plan_desc'         => 'Tahapan kegiatan ' . $baseName . ' bulan ' . $monthName . ' ' . $year,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        
        \Log::info("Default step created for publication ID $publicationId: $planName");
    }

    /**
     * 📅 Get nama bulan dalam Bahasa Indonesia
     * @param int $monthNumber
     * @return string
     */
    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $months[$monthNumber] ?? 'Unknown';
    }
}