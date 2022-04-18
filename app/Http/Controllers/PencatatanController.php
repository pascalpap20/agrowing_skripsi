<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Validator;
use App\Models\BlokLahan;
use App\Models\CatatHarian;
use App\Models\CatatItem;
use App\Models\CatatIndikator;
use App\Models\Tahapan;
use App\Models\ProjectTanam;
use App\Models\ItemPekerjaan;
use App\Models\IndikatorKegiatan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PencatatanController extends Controller
{
    //
    public function createCatatHarian(Request $request, $blok_id){
        $validator = Validator::make($request->all(), 
            [ 
                'tahapan_id' => [
                    'required',
                    //check the id for table sop is actually exists based on the input
                    Rule::exists('tahapan', 'id')->where('id', $request->input('tahapan_id')), 
                ]
            ]
        ); 
        if($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
        } 
        DB::beginTransaction();
        try {
            $blok = BlokLahan::findOrFail($blok_id);
            $project_tanam = ProjectTanam::findOrFail($blok->project_tanam_id);
            
            // Check if the tahapan id is belong to projek tanam SOP
            $tahapan = Tahapan::where('id', $request->input('tahapan_id'))
                        ->where('sop_id', $project_tanam->sop_id)->firstOrFail();
                        
            $catat_harian = $blok->catatHarian()->firstOrCreate([
                'tahapan_id' => $tahapan->id,
                'catatan' => $request->input('catatan')
            ]);
            foreach ($request->kegiatan as $kegiatan) {
                $item_pekerjaan = ItemPekerjaan::where('id', $kegiatan["item_pekerjaan_id"])
                                                ->where('tahapan_sop_id', $tahapan->id)->firstOrFail();

                $catat_item_pekerjaan = $catat_harian->catatItem()->firstOrCreate([
                    'item_pekerjaan_id' => $item_pekerjaan->id,
                    'filled' => $kegiatan["filled"]
                ]);
                
                if ($kegiatan["filled"] == 1) {
                    foreach ($kegiatan["indikator"] as $indikator) {
                        $indikator_kegiatan = IndikatorKegiatan::where('id', $indikator["indikator_id"])
                                                ->where('item_pekerjaan_id', $item_pekerjaan->id)->firstOrFail();

                        $catat_item_pekerjaan->catatIndikator()->firstOrCreate([
                            'indikator_id' => $indikator_kegiatan->id,
                            'catat_jawaban' => $indikator["catat_jawaban"]
                    ]);
                }
            }
        }
        DB::commit();

        return response()->json([
            "message" => "successfully create catat harian for blok {$blok->id}",
            "success" => true,
        ], 200);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);
        }
        
    }

    public function deleteCatatHarian($blok_id, $catat_harian_id){
        try {
            $blok = BlokLahan::findOrFail($blok_id);
            $catat_harian = CatatHarian::where('id', $catat_harian_id)->firstOrFail();
            $catat_harian->delete();

            return response()->json([
                "message" => "successfully delete catat harian with id {$catat_harian->id} for blok {$blok_id}",
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);
        }
    }

    public function updateCatatHarian(Request $request, $blok_id, $catat_harian_id){
        try {
            $blok = BlokLahan::findOrFail($blok_id);                       
            $catat_harian = CatatHarian::findOrFail($catat_harian_id)->update([
                'catatan' => $request->input('catatan')
            ]);

            return response()->json([
                "message" => "successfully update catat harian with id {$catat_harian_id} for blok {$blok->id}",
                "success" => true,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400); 
        }
    }


    public function addCatatItem(Request $request, $catat_harian_id){
        DB::beginTransaction();
        try {
            $catat_harian = CatatHarian::findOrFail($catat_harian_id);
            $blok = BlokLahan::findOrFail($catat_harian->blok_lahan_id);
            $project_tanam = ProjectTanam::findOrFail($blok->project_tanam_id);
            // Check if the tahapan id is belong to projek tanam SOP
            $tahapan = Tahapan::where('id', $catat_harian->tahapan_id)
                        ->where('sop_id', $project_tanam->sop_id)->firstOrFail();
            
            $item_pekerjaan = ItemPekerjaan::where('id', $request->input('item_pekerjaan_id'))
                                                ->where('tahapan_sop_id', $tahapan->id)->firstOrFail();
            $catat_item = $catat_harian->catatItem()->firstOrCreate([
                'item_pekerjaan_id' => $item_pekerjaan->id,
                'filled' => $request->input('filled'),
            ]);

            foreach ($request->input('indikator') as $indikator) {
                $indikator_kegiatan = IndikatorKegiatan::where('id', $indikator["indikator_id"])
                                        ->where('item_pekerjaan_id', $item_pekerjaan->id)->firstOrFail();

                $catat_item->catatIndikator()->firstOrCreate([
                    'indikator_id' => $indikator_kegiatan->id,
                    'catat_jawaban' => $indikator["catat_jawaban"]
                ]);
            }
            DB::commit();
            return response()->json([
                "message" => "successfully create catat item for catatan id {$catat_harian_id}",
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400); 
        }
    }

    public function updateCatatItem(Request $request, $catat_harian_id, $catat_item_id){
        try {
            $catat_harian = CatatHarian::findOrFail($catat_harian_id);
            $catat_item = CatatItem::findOrFail($catat_item_id);
            $catat_item->update([
                'filled' => $request->input('filled')
            ]);
            return response()->json([
                "message" => "successfully update catat item for catatan id {$catat_harian_id}",
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400); 
        }
    }

    public function deleteCatatItem(Request $request, $catat_harian_id, $catat_item_id){
        try {
            $catat_harian = CatatHarian::findOrFail($catat_harian_id);
            $catat_item = CatatItem::findOrFail($catat_item_id);
            $catat_item->delete();
            return response()->json([
                "message" => "successfully delete catat item for catatan id {$catat_harian_id}",
                "success" => true,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400); 
        }
    }

    public function updateCatatIndikator(Request $request, $catat_item_id, $indikator_id) {
        try {
            $catat_item = CatatItem::findOrFail($catat_item_id);
            $catat_indikator = CatatIndikator::findOrFail($indikator_id);
            $catat_indikator->update([
                'catat_jawaban' => $request->input('catat_jawaban')
            ]);
            return response()->json([
                "message" => "successfully update catat indikator for indikator id {$indikator_id} on catat item id {$catat_item->id}",
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);
        }
    }
}
