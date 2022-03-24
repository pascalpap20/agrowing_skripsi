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

class SopController extends Controller
{
    //create sop
    function create(Request $request){
        // $validator = Validator::make($request->all(), 
        //       [ 
        //          'sop_nama' => 'required',
        //         // 'estimasi_panen' => 'required',
        //         // 'deskripsi' => 'required', 
        //         //'foto' => 'required|mimes:png,jpg,jpeg,gif|max:2048',
        //      ]); 

        // if($validator->fails()) {          
        //     return response()->json(['error'=>$validator->errors()], 400);                        
        //  } 

        // else{
        $admin = Admin::find($request->admin_id);
        $sop = $admin->sop()->firstOrCreate([
            'sop_nama' => $request ->input ('sop_nama'),
            'estimasi_panen' => $request ->input ('estimasi_panen'),
            'deskripsi' => $request ->input ('deskripsi'),
            'foto' => $request -> input('foto'),
            //'foto' => $request -> file('foto')->store('public/images'),
            'kalkulasi_waktu_panen' => $request ->input ('kalkulasi_waktu_panen'),
            'kalkulasi_bobot_panen' => $request ->input ('kalkulasi_bobot_panen'),           
            ]);

        foreach ($request->kegiatan as $kegiatan) {
            $item_pekerjaan = $sop->itemPekerjaan()->firstOrCreate([
                'tahapan_sop_id' => $kegiatan["tahapan_sop_id"],
                'nama_kegiatan' => $kegiatan["nama_kegiatan"],
                'durasi_waktu' => $kegiatan["durasi_waktu"]
            ]);

            foreach($kegiatan["indikator"] as $indikator) {
                $item_pekerjaan->indikatorKegiatan()->firstOrCreate([
                    'nama_indikator' => $indikator["nama_indikator"],
                    'tipe_jawaban_id' => $indikator["tipe_jawaban"]
                ]);
            }
        }

        return response()->json(
            [
                "message" => "success",
                "status" => 200,
                "data"  => $sop
            ]
        );
    //    }
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

    function allSop(){
        $sop = Sop::all();

        return response()->json(
            [
                'sop'=> $sop
            ]
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


}
