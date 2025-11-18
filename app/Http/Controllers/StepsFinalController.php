<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StepsPlan;
use App\Models\StepsFinal;
use App\Models\Struggle;
use Closure;

class StepsFinalController extends Controller
{
    public function index(Request $request){
        $stepsfinal = StepsFinal::all();
        return view('tampilan.detail', compact('stepsfinal'));
    }

    public function update(Request $request, StepsPlan $plan)
    {
        $final = StepsFinal::firstOrNew(['step_plan_id' => $plan->step_plan_id]);
        $existingFinalDoc = $final->final_doc;

        $finalDocValidation = $existingFinalDoc ? 'nullable|file|mimes:pdf,jpg,png,jpeg,docx|max:2048' : 'required|file|mimes:pdf,jpg,png,jpeg,docx|max:2048';
        
        $validatedFinal = $request->validate([
            'actual_started' => 'required|date',
            'actual_ended'   => 'required|date|after_or_equal:actual_started',
            'final_desc'     => 'required|string',
            'next_step'      => 'required|string',
            'final_doc'      => $finalDocValidation,
        ]);

        $minThreeWordsRule = function (string $attribute, mixed $value, Closure $fail) {
            $cleanValue = strip_tags((string)$value);
            $cleanValue = trim($cleanValue);
            
            preg_match_all('/[\p{L}\d]+/u', $cleanValue, $matches);
            $wordCount = count($matches[0]);
            
            // DEBUG: Log nilai yang masuk
            \Log::info("Validating $attribute", [
                'original' => $value,
                'cleaned' => $cleanValue,
                'word_count' => $wordCount,
                'words' => $matches[0]
            ]);
            
            if ($wordCount < 3) {
                $fieldName = str_contains($attribute, 'struggle_desc') ? 'Kendala' : 'Solusi';
                if (preg_match('/struggles\.(\d+)\./', $attribute, $matches)) {
                    $index = $matches[1] + 1;
                    $fail("Bidang {$fieldName} pada item Kendala ke-{$index} harus berisi minimal 3 kata (saat ini: {$wordCount} kata).");
                } else {
                    $fail("Bidang {$fieldName} harus berisi minimal 3 kata (saat ini: {$wordCount} kata).");
                }
                return;
            }

            // Cek Karakter Khusus
            if (!preg_match('/^[\p{L}\d\s.,?!()\/"]*$/u', $cleanValue)) {
                $fieldName = str_contains($attribute, 'struggle_desc') ? 'Kendala' : 'Solusi';
                
                // DEBUG: Tampilkan karakter yang tidak valid
                preg_match_all('/[^\p{L}\d\s.,?!()\/"]/', $cleanValue, $matches);
                $invalidChars = implode(', ', array_unique($matches[0]));
                
                if (preg_match('/struggles\.(\d+)\./', $attribute, $matches)) {
                    $index = $matches[1] + 1;
                    $fail("Bidang {$fieldName} pada item Kendala ke-{$index} mengandung karakter tidak diizinkan: [{$invalidChars}]. Hanya huruf, angka, spasi, dan tanda baca umum (.,?!()/) yang diperbolehkan.");
                } else {
                    $fail("Bidang {$fieldName} mengandung karakter tidak diizinkan: [{$invalidChars}]. Hanya huruf, angka, spasi, dan tanda baca umum (.,?!()/) yang diperbolehkan.");
                }
            }
        };

        // DEBUG: Tampilkan data struggles sebelum validasi
        \Log::info('Struggles data before validation:', $request->input('struggles', []));

        $validatedStruggle = $request->validate([
            'struggles.*.struggle_desc'  => ['required', 'string'],
            'struggles.*.solution_desc'  => ['required', 'string'],
            'struggles.*.solution_doc'   => 'nullable|file|mimes:pdf,jpg,png,jpeg,docx,xlsx|max:2048',
        ]);

        \Log::info('Validation passed for struggles');
        \Log::info('Starting save process...');
        \Log::info('Final data before save:', $validatedFinal);

        if ($request->hasFile('final_doc')) {
            if ($existingFinalDoc) {
                \Storage::disk('public')->delete($existingFinalDoc);
            }
            $path = $request->file('final_doc')->store('documents', 'public');
            $validatedFinal['final_doc'] = $path;
        } else {
            $validatedFinal['final_doc'] = $existingFinalDoc;
        }

        $final->fill($validatedFinal);
        $final->save();
        $final->refresh();
        
        \Log::info('StepsFinal saved successfully', ['id' => $final->id]);

        // Simpan struggles
        if ($request->has('struggles')) {
            $submittedStruggleIds = collect($request->input('struggles'))->pluck('struggle_id')->filter()->all();

            $final->struggles()->whereNotIn('id', $submittedStruggleIds)->delete();
            
            foreach ($request->struggles as $i => $struggleData) {
                \Log::info("Processing struggle #{$i}", $struggleData);
                
                $struggle = $final->struggles()->find($struggleData['struggle_id'] ?? null) ?? new Struggle();
            
                $struggle->struggle_desc = $struggleData['struggle_desc'];
                $struggle->solution_desc = $struggleData['solution_desc'];

                if ($request->hasFile("struggles.$i.solution_doc")) {
                    if ($struggle->solution_doc) {
                        \Storage::disk('public')->delete($struggle->solution_doc);
                    }
                    $path = $request->file("struggles.$i.solution_doc")->store('documents', 'public');
                    $struggle->solution_doc = $path;
                } else {
                    if (isset($struggleData['existing_solution_doc'])) {
                        $struggle->solution_doc = $struggleData['existing_solution_doc'];
                    } else {
                        $struggle->solution_doc = null;
                    }
                }

                $final->struggles()->save($struggle);
                \Log::info("Struggle saved", ['id' => $struggle->id]);
            }
        }

        return redirect()->back()->with('success', 'Formulir realisasi berhasil diperbarui.');
    }
}