<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sop;
use App\Models\Admin;
use App\Models\Tahapan;
use App\Models\ProjectTanam;
use App\Models\ItemPekerjaan;
use App\Models\IndikatorKegiatan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\JenisKomoditas;

use Exception;

class SopController extends Controller
{
    //create sop
    function create(Request $request){
        $validator = Validator::make($request->all(), 
            [ 
                'estimasi_panen' => 'required',
                'jenis_komoditas_id' => [
                    'required',
                    //check the id for table jenis_komoditas is actually exists based on the input
                    Rule::exists('jenis_komoditas', 'id')->where('id', $request->input('jenis_komoditas_id')), 
                ]
            ],
            [
                'estimasi_panen.required' => 'estimasi_panen need to be decided',
                'jenis_komoditas_id.required' => 'jenis_komoditas_id need to be selected',
                'jenis_komoditas_id.exists' => 'jenis_komoditas is not available',
                // 'detail_sop.*.kegiatan.*.indikator.tipe_jawaban_id.required' => 'tipe_jawaban_id is required',
                // 'detail_sop.*.kegiatan.*.indikator.tipe_jawaban_id.exists' => 'tipe_jawaban_id not available'
            ]
        ); 

        if($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
         } 

        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            // $admin = Admin::find($request->admin_id);
            try {
                $admin = Admin::where('user_id', $user["id"])->first();

                $sop = $admin->sop()->firstOrCreate([
                    'estimasi_panen' => $request ->input ('estimasi_panen'),
                    'deskripsi' => $request ->input ('deskripsi'),
                    // 'foto' => $request -> input('foto'),
                    'kalkulasi_waktu_panen' => $request ->input ('kalkulasi_waktu_panen'),
                    'kalkulasi_bobot_panen' => $request ->input ('kalkulasi_bobot_panen'), 
                    'jenis_komoditas_id' => $request->input('jenis_komoditas_id')          
                    ]);

                foreach($request->detail_sop as $detail_sop){
                    $tahapan = $sop->tahapan()->firstOrCreate([
                        'nama_tahapan' => $detail_sop["nama_tahapan"],
                        'sop_id' => $sop["id"],
                        'admin_id' => $admin["id"]
                    ]);

                    foreach ($detail_sop["kegiatan"] as $kegiatan) {
                        $item_pekerjaan = $sop->itemPekerjaan()->firstOrCreate([
                            'tahapan_sop_id' => $tahapan["id"],
                            'nama_kegiatan' => $kegiatan["nama_kegiatan"],
                            'durasi_waktu' => $kegiatan["durasi_waktu"]
                        ]);
            
                        foreach($kegiatan["indikator"] as $indikator) {
                            $item_pekerjaan->indikatorKegiatan()->firstOrCreate([
                                'nama_indikator' => $indikator["nama_indikator"],
                                'tipe_jawaban_id' => $indikator["tipe_jawaban_id"]
                            ]);
                        }
                    }
                } 

                return response()->json([
                    "message" => "success",
                    "success" => true,
                    "data"  => $sop
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

    public function update(Request $request, $sop_id){
        $validator = Validator::make($request->all(), 
            [ 
                'estimasi_panen' => 'required',
                'jenis_komoditas_id' => [
                    'required',
                    //check the id for table jenis_komoditas is actually exists based on the input
                    Rule::exists('jenis_komoditas', 'id')->where('id', $request->input('jenis_komoditas_id')), 
                ]
            ],
            [
                'estimasi_panen.required' => 'estimasi_panen need to be decided',
                'jenis_komoditas_id.required' => 'jenis_komoditas_id need to be selected',
                'jenis_komoditas_id.exists' => 'jenis_komoditas is not available',
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
                $sop->update([
                    'estimasi_panen' => $request->input ('estimasi_panen'),
                    'deskripsi' => $request->input ('deskripsi'),
                    // 'foto' => $request-> input('foto'),
                    'kalkulasi_waktu_panen' => $request ->input ('kalkulasi_waktu_panen'),
                    'kalkulasi_bobot_panen' => $request ->input ('kalkulasi_bobot_panen'), 
                    'jenis_komoditas_id' => $request->input('jenis_komoditas_id')          
                ]);

                return response()->json([
                    "message" => "success",
                    "success" => true,
                    "data"  => $sop
                ], 200);

            } catch (Exception $e) {
                return response()->json([
                    "message" => "failed to update",
                    "error" => $e->getMessage(),
                    "success" => false
                ], 400);        
            }

        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    public function delete($sop_id){
        $id = auth()->id();
        $user = User::find($id);

        if($user["role_id"] == 2){
            try{
                $sop = Sop::findOrFail($sop_id);
                $sop->delete();
                
                return response()->json([
                    "message" => "SOP " . $sop->id ." successfully deleted",
                    "success" => true
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "message" => "failed to delete",
                    "error" => $e->getMessage(),
                    "success" => false
                ], 400);
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    function show($id){

        $sop = Sop::with(['itemPekerjaan', 'itemPekerjaan.indikatorKegiatan'])->where('id', $id)->first();
        
        return response()->json(
            [
                'data' => $sop
            ]);
    }

    function showByTahapan($id,$tahapan_id){

        $sop = Sop::with(['itemPekerjaan', 'itemPekerjaan.indikatorKegiatan'])->where('id', $id,)->first();
        
        return response()->json(
            [
                'data' => $sop['itemPekerjaan']->where('tahapan_sop_id', $tahapan_id)
            ]);
    }

    function allSop(Request $request){
        $sop = Sop::all();
        $nama_komoditas = $request->query('komoditas');

        if($nama_komoditas){
            $sop_komoditas = JenisKomoditas::where('nama_komoditas', strtolower($nama_komoditas))->first();
            $sop_komoditas = Sop::where('jenis_komoditas_id', $sop_komoditas->id)->get();
            return response()->json([
                "sop" => $sop_komoditas
            ], 200);
        }

        return response()->json(
            [
                'sop'=> $sop
            ], 200
            );
    }

    function tahapan(){
        $tahapan = Tahapan::all();

        return response()->json(
            [
                'tahapan'=> $tahapan
            ]
        );

    }

    // ini contoh buat nested array di requestnya
    public function testing (Request $request){
        $tes = $request->input('detail_sop.*.kegiatan.*.indikator');
        return response()->json($tes);
    }

    // REMINDER
    // ALL KEY NEED TO BE ON REQUEST TO UPDATE, EXAMPLE:
    // IF indikator_kegiatan HAVE 2 item_pekerjaan_id, THEN NEED TO PUT THE REQUEST VALUE FOR 2 indikator_kegiatan AS WELL.
    public function updateAll(Request $request, $sop_id){
        $validator = Validator::make($request->all(), 
            [ 
                'estimasi_panen' => 'required'
            ],
            [
                'estimasi_panen.required' => 'estimasi_panen need to be decided'
            ]
        ); 

        if($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
         } 

        // check user == admin
        $id = auth()->id();
        $user = User::find($id);

        if($user["role_id"] == 2){
            try{
                // Update SOP
                $sop = Sop::findOrFail($sop_id);
                $sop->update([
                    'estimasi_panen' => $request->input ('estimasi_panen'),
                    'deskripsi' => $request->input ('deskripsi'),
                    // 'foto' => $request-> input('foto'),
                    'kalkulasi_waktu_panen' => $request ->input ('kalkulasi_waktu_panen'),
                    'kalkulasi_bobot_panen' => $request ->input ('kalkulasi_bobot_panen'), 
                    'jenis_komoditas_id' => $request->input('jenis_komoditas_id')  
                ]);

                // Update Tahapan
                $tahapan = Tahapan::where('sop_id', $sop_id)->get();

                $index = 0;
                foreach($tahapan as $tahap) {
                    $tahap->update([
                        'nama_tahapan' => $request->input('detail_sop.'. $index .'.nama_tahapan')
                    ]);
                    $index++;
                }

                // Update Item Pekerjaan
                $item_pekerjaan = ItemPekerjaan::where('sop_id', $sop_id)->get();

                // filter by tahapan_sop_id
                $tahapan_sop_id_count = $item_pekerjaan->unique('tahapan_sop_id')->values()->all();
                $index = 0;
                foreach ($tahapan_sop_id_count as $item) {
                    $indexKegiatan = 0;
                    // for every tahapan_sop_id, update kegiatan 
                    $kegiatans = ItemPekerjaan::where('tahapan_sop_id', $item->tahapan_sop_id)->get();
                    foreach ($kegiatans as $kegiatan){
                        $kegiatan->update([
                            'nama_kegiatan' => $request->input("detail_sop.{$index}.kegiatan.{$indexKegiatan}.nama_kegiatan"),
                            'durasi_waktu' => $request->input("detail_sop.{$index}.kegiatan.{$indexKegiatan}.durasi_waktu")
                        ]);

                        $indexIndikator = 0;
                        $indikator_kegiatan = IndikatorKegiatan::where('item_pekerjaan_id', $kegiatan->id)->get();
                        foreach ($indikator_kegiatan as $indikator) {
                            $indikator->update([
                                'nama_indikator' => $request->input("detail_sop.{$index}.kegiatan.{$indexKegiatan}.indikator.{$indexIndikator}.nama_indikator"),
                                'tipe_jawaban_id' => $request->input("detail_sop.{$index}.kegiatan.{$indexKegiatan}.indikator.{$indexIndikator}.tipe_jawaban_id")
                            ]);
                            $indexIndikator++;
                        }

                        $indexKegiatan++;
                    }
                    $index++;
                }

                return response()->json([
                    'message' => 'successfully update SOP',
                    'success' => true
                ], 200);

            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 400);            
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

}
