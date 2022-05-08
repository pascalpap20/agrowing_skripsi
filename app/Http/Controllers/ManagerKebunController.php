<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatatHarian;
use App\Models\ItemPekerjaan;
use App\Models\ManagerKebun;
use App\Models\ProjectTanam;
use App\Models\User;
use App\Models\Alamat;
use App\Models\BlokLahan;
use Error;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

class ManagerKebunController extends Controller
{
    //create project tanam
    function createProjectTanam(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [ 
                'sop_id' => [
                    'required',
                    //check the id for table sop is actually exists based on the input
                    Rule::exists('sop', 'id')->where('id', $request->input('sop_id')), 
                ],
                'regencies_id' => [
                    'required',
                    Rule::exists('regencies', 'id')->where('id', $request->input('regencies_id')), 
                ]
            ]
        ); 

        if($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
        } 

        $managerKebun = ManagerKebun::where('user_id', auth()->user()->id)->first();
        
  
        DB::transaction(function () use ($request, $managerKebun) {
            $alamat = Alamat::firstOrCreate([
                'alamat' => $request->input('alamat'),
                'regencies_id' => $request->input('regencies_id')
            ]);

            $projectTanam = $managerKebun->projectTanam()->firstOrCreate([
                'sop_id' => $request->input('sop_id'),
                'alamat_id' => $alamat['id']
            ]);

            foreach ($request->blok as $blok) {
                $projectTanam->blokLahan()->create([
                    'luas_blok' => $blok['luas_blok'],
                    'jumlah_tanaman' => $blok['jumlah_tanaman'],
                    'umur_tanaman' => $blok['umur_tanaman'],
                    'periode' => $blok['periode']
                    // 'tahapan_id' => $blok['tahapan_id']
                ]);
            }
        });

        return response()->json(
            [
                "message" => "success create project tanam",
                "success" => true,
            ], 201
        );
    }


    function catatHarianSop(Request $request)
    {
        //$project_id = ProjectTanam::find($request->project_tanam_id);

        DB::transaction(function () use ($request) {
            $catatHarian = CatatHarian::Create([
                'blok_lahan_id' => $request->blok_lahan_id,
                'tahapan_id' => $request->tahapan_id,
                'catatan' => $request->catatan
            ]);

            foreach ($request->kegiatan as $kegiatan) {
                $catat_item_pekerjaan = $catatHarian->catatItem()->Create([
                    'item_pekerjaan' => $kegiatan["item_pekerjaan"],
                    'filled' => $kegiatan["filled"]
                ]);

                if ($kegiatan["filled"] == 1) {
                    foreach ($kegiatan["indikator"] as $indikator) {
                        $catat_item_pekerjaan->catatIndikator()->Create([
                            'indikator_id' => $indikator["id"],
                            'nama_indikator' => $indikator["nama_indikator"],
                            'catat_jawaban' => $indikator["catat_jawaban"]
                        ]);
                    }
                }
            }
        });

        // if($request->berbuah){

        // }

        return response()->json([
            "message" => "berhasil mencatat harian",
            "status" => 200,
        ]);
    }

    function catatHarianPanen(Request $request)
    {

        $catatHarian = CatatHarian::Create([
            'blok_lahan_id' => $request->blok_lahan_id,
            'tahapan_id' => $request->tahapan_id,
            'catatan' => $request->catatan
        ]);

        $catatPanen = $catatHarian->catatPanen()->Create([
            'panen_aktual' => $request->panen_aktual,
            'panen_gradeA' => $request->panen_gradeA,
            'panen_gradeB' => $request->panen_gradeB
        ]);

        $status = $request->status;

        return response()->json([
            "message" => "berhasil mencatat harian",
            "status" => 200,
            "blok status" => $catatHarian
        ]);
    }

    public function showCatatHarian($catat_harian_id)
    {
        $err = CatatHarian::where('id', '=', $catat_harian_id)->exists();
        if (!$err) {
            return response()->json([
                "status" => '404',
                "message" => "data not found"
            ], 404);
        }
        $catatHarian = CatatHarian::with('blokLahan', 'tahapan', 'catatItem.catatIndikator.indikator.tipeJawaban', 'catatPanen')->where('id', '=', $catat_harian_id)->first();
        $project_tanam_id = $catatHarian->blokLahan['project_tanam_id'];
        //dd($project_tanam_id);
        $projectTanam = ProjectTanam::with('alamat', 'sop')->where('id', '=', $project_tanam_id)->first();
        $manager = ManagerKebun::find($projectTanam->manager_kebun_id);

        $res = [];

        array_push($res, [
            'nama manager kebun' => $manager->nama,       //get nama from id
            'lokasi_lahan' => $projectTanam->alamat->alamat,
            'nama_buah' => $projectTanam->sop->sop_nama,
            //'tahapan' => $tahapan->nama_tahapan
            "catat" => $catatHarian

        ], 200);
        return response()->json(
            $res
        );
    }
}
