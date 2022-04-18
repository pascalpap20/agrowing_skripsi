<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Admin;
use App\Models\Sop;
use App\Models\Tahapan;
use App\Models\ItemPekerjaan;
use App\Models\IndikatorKegiatan;
use Exception;

use Validator;

class ItemPekerjaanController extends Controller
{
    //
    public function addKegiatan(Request $request, $sop_id){
        $validator = Validator::make($request->all(), 
            [ 
                'tahapan_sop_id' => [
                    'required',
                    //check the id for table jenis_komoditas is actually exists based on the input
                    Rule::exists('tahapan', 'id')->where('id', $request->input('tahapan_sop_id')), 
                ]
            ]
        ); 

        if($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
         } 

        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {
                $sop = Sop::findOrFail($sop_id);
                // find tahapan that is available for SOP, it will throw err if the tahapan_sop_id not belong to tahapan that exists in the sop
                $tahapan = Tahapan::where('id', $request->tahapan_sop_id)
                    ->where('sop_id', $sop->id)->firstOrFail();

                $item_pekerjaan = $tahapan->itemPekerjaan()->firstOrCreate([      
                    'sop_id' => $sop->id,
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'durasi_waktu' => $request->durasi_waktu
                ]);
                foreach ($request["indikator"] as $indikator){
                    $item_pekerjaan->indikatorKegiatan()->create([
                        'nama_indikator' => $indikator["nama_indikator"],
                        'tipe_jawaban_id' => $indikator["tipe_jawaban_id"]
                    ]);
                }

                return response()->json([
                    "message" => "success",
                    "success" => true,
                    "data"  => $item_pekerjaan
                ], 201);

            } catch (Exception $e) {
                return response()->json([
                    "message" => $e->getMessage(),
                    "success" => false
                ], 400);        
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    public function updateKegiatan(Request $request, $sop_id, $kegiatan_id){
        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){   
            try {
                // $item_pekerjaan = ItemPekerjaan::where('sop_id', $sop_id)->get();
                $sop = Sop::findOrFail($sop_id);
                $item_pekerjaan = ItemPekerjaan::where('id', $kegiatan_id)
                    ->where('sop_id', $sop->id)->firstOrFail();
                $item_pekerjaan->update([
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'durasi_waktu' => $request->durasi_waktu
                ]);

                return response()->json([
                    "success" => true,
                    "message" => "successfully update item_pekerjaan on sop id {$sop_id} for kegiatan id {$kegiatan_id}"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "message" => $e->getMessage(),
                    "success" => false
                ], 400);        
            }
        } 

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    public function deleteKegiatan($sop_id, $kegiatan_id){
    // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){   
            try {
                // $item_pekerjaan = ItemPekerjaan::where('sop_id', $sop_id)->get();
                $sop = Sop::findOrFail($sop_id);
                $item_pekerjaan = ItemPekerjaan::where('id', $kegiatan_id)
                    ->where('sop_id', $sop->id)->firstOrFail();
                
                $item_pekerjaan->delete();

                return response()->json([
                    "success" => true,
                    "message" => "successfully delete item_pekerjaan on sop id {$sop_id} for kegiatan id {$kegiatan_id}"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "message" => $e->getMessage(),
                    "success" => false
                ], 400);        
            }
        } 

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    public function getKegiatanById($sop_id, $kegiatan_id){
        try {
            $item_pekerjaan = ItemPekerjaan::where('sop_id', $sop_id)
                ->where('id', $kegiatan_id)->with('indikatorKegiatan')->firstOrFail();

            return response()->json([
                "success" => true,
                "data" => $item_pekerjaan
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false
            ], 400);
        }
    }

    public function getKegiatanBySop($sop_id){      
        $item_pekerjaan = ItemPekerjaan::where('sop_id', $sop_id)
            ->with('indikatorKegiatan')->get();

        if($item_pekerjaan->isNotEmpty()){
            return response()->json([
                "success" => true,
                "data" => $item_pekerjaan
            ], 200);
        }

        return response()->json([
            "message" => "No data for sop {$sop_id}",
            "success" => false
        ], 400);
        
    }

    public function addIndikator(Request $request, $kegiatan_id){
        $validator = Validator::make($request->all(), 
            [ 
                'tipe_jawaban_id' => [
                    'required',
                    //check the id for table jenis_komoditas is actually exists based on the input
                    Rule::exists('tipe_jawaban', 'id')->where('id', $request->input('tipe_jawaban_id')), 
                ]
            ]
        ); 

        if($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
         } 

        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {
                $indikator = ItemPekerjaan::where('id', $kegiatan_id)->firstOrFail();
                $indikator->indikatorKegiatan()->create([
                    'nama_indikator' => $request->nama_indikator,
                    'tipe_jawaban_id' => $request->tipe_jawaban_id
                ]);

                return response()->json([
                    "message" => "successfully create new indikator at sop {$indikator->sop_id}, tahapan {$indikator->tahapan_sop_id}, kegiatan id {$kegiatan_id}",
                    "success" => true,
                ], 201);

            } catch (Exception $e) {
                return response()->json([
                    "message" => $e->getMessage(),
                    "success" => false
                ], 400);        
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    public function updateIndikator(Request $request, $kegiatan_id, $indikator_id){
        $validator = Validator::make($request->all(), 
            [ 
                'tipe_jawaban_id' => [
                    'required',
                    //check the id for table jenis_komoditas is actually exists based on the input
                    Rule::exists('tipe_jawaban', 'id')->where('id', $request->input('tipe_jawaban_id')), 
                ]
            ]
        ); 

        if($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
         } 

        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {
                // $indikator = ItemPekerjaan::where('id', $kegiatan_id)->firstOrFail();
                $indikator = IndikatorKegiatan::where('item_pekerjaan_id', $kegiatan_id)
                    ->where('id', $indikator_id)->firstOrFail();
                $indikator->update([
                    'nama_indikator' => $request->nama_indikator,
                    'tipe_jawaban_id' => $request->tipe_jawaban_id
                ]);

                return response()->json([
                    "message" => "successfully update indikator for kegiatan id {$kegiatan_id}, indikator id {$indikator_id}",
                    "success" => true
                ], 201);

            } catch (Exception $e) {
                return response()->json([
                    "message" => $e->getMessage(),
                    "success" => false
                ], 400);        
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    public function deleteIndikator($kegiatan_id, $indikator_id){
        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {
                // $indikator = ItemPekerjaan::where('id', $kegiatan_id)->firstOrFail();
                $indikator = IndikatorKegiatan::where('item_pekerjaan_id', $kegiatan_id)
                    ->where('id', $indikator_id)->firstOrFail();
                $indikator->delete();

                return response()->json([
                    "message" => "successfully delete indikator for kegiatan id {$kegiatan_id}, indikator id {$indikator_id}",
                    "success" => true
                ], 201);

            } catch (Exception $e) {
                return response()->json([
                    "message" => $e->getMessage(),
                    "success" => false
                ], 400);        
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

}
