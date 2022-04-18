<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use App\Models\CatatHarian;
use App\Models\ItemPekerjaan;
use App\Models\ManagerKebun;
use App\Models\ProjectTanam;
use App\Models\Sop;
use App\Models\Tahapan;
use App\Models\Regency;
use App\Models\BlokLahan;
use Exception;

class ProjectTanamController extends Controller
{
    function show($id)
    {

        $projectTanam = ProjectTanam::with(['alamat', 'blokLahan'])->where('id', $id)->first();

        return response()->json(
            [
                'data' => $projectTanam
            ]
        );
    }

    function showall()
    {

        $projectTanam = ProjectTanam::with(['alamat', 'blokLahan', 'sop', 'alamat.regency.province', 'tahapanSop', 'alamat.regency' => function ($query) {
            $query->select('id', 'name');
        }])->get();

        return response()->json(
            [
                'data' => $projectTanam
            ]
        );
    }

    function showLahan()
    {
        $projectTanam = ProjectTanam::with(['alamat'])->get();

        //$project = ProjectTanam::all()->count();

        $res = [];
        foreach ($projectTanam as $item) {

            $sop_id = $item->sop_id;                            //get id sop
            $manager_kebun_id = $item->manager_kebun_id;
            $tahapan_id = $item->tahapan_sop_id;
            //dd($sop_id);
            $sop = Sop::find($sop_id);                          //get id dari database
            $manager = ManagerKebun::find($manager_kebun_id);
            $tahapan = Tahapan::find($tahapan_id);

            array_push($res, [
                'id' => $item->id,
                'nama' => $manager->nama,       //get nama from id
                'no_hp' => $manager->no_hp,
                'lokasi_lahan' => $item->alamat->alamat,
                'nama_buah' => $sop->sop_nama,
                'tahapan' => $tahapan->nama_tahapan

            ]);
        }

        return response()->json($res);
    }

    function detailLahan($id)
    {
        $projectTanam = ProjectTanam::with(['managerKebun', 'alamat', 'sop'])->findOrFail($id);

        return response()->json($projectTanam);
    }

    function showSopPanduan(Request $request, $project_tanam_id)
    {
        //$project_id = $request->project_tanam_id;
        $tahapan_id = $request->tahapan_id;
        // $blok = $request->blok;
        $projectTanam = ProjectTanam::with(['sop.itemPekerjaan.indikatorKegiatan'])
            ->where('id', '=', $project_tanam_id)->first();
        $sop = ItemPekerjaan::with(['indikatorKegiatan.tipeJawaban'])
            ->where('tahapan_sop_id', '=', $tahapan_id)
            ->where('sop_id', '=', $projectTanam->sop_id)->get();
        $tahapan = Tahapan::where('id', '=', $tahapan_id)->first();
        //dd($sop);
        //dd($projectTanam[0]->sop);


        return response()->json(
            [
                'tahapan' => $tahapan->nama_tahapan,
                // 'blok' => $blok,
                'sop' => $sop //->where('tahapan_sop_id', '=', $projectTanam[0]->tahapan_sop_id)
            ]
        );
    }

    public function deleteProjectTanam($project_id){
        try {
            $project = ProjectTanam::findOrFail($project_id);
            $project->delete();

            return response()->json([
                "message" => "successfully delete project tanam id {$project->id}",
                "success" => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false
            ], 400);
        }
    }

    public function addBlokLahan(Request $request, $project_id){
        try {
            $project = ProjectTanam::findOrFail($project_id);
            $project->blokLahan()->create([
                "luas_blok" => $request->input('luas_blok'),
                "periode" => $request->input('periode'),
                "jumlah_tanaman" => $request->input('jumlah_tanaman'),
                "umur_tanaman" => $request->input('umur_tanaman')
            ]);

            return response()->json([
                "message" => "successfully added new blok lahan to project tanam id {$project->id}",
                "status" => true
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false
            ], 400);
        }
    }

    public function updateBlokLahan(Request $request, $project_id, $blok_id){
        try {
            $blok = BlokLahan::where('id', $blok_id)->where('project_tanam_id', $project_id)->firstOrFail();
            $blok->update([
                "luas_blok" => $request->input('luas_blok'),
                "periode" => $request->input('periode'),
                "jumlah_tanaman" => $request->input('jumlah_tanaman'),
                "umur_tanaman" => $request->input('umur_tanaman')
            ]);

            return response()->json([
                "message" => "successfully added new blok lahan to project tanam id {$project_id}",
                "status" => true
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false
            ], 400);
        }
    }

    public function deleteBlokLahan($project_id, $blok_id){
        try {
            $blok = BlokLahan::where('id', $blok_id)->where('project_tanam_id', $project_id)->firstOrFail();
            $blok->delete();

            return response()->json([
                "message" => "successfully delete blok lahan from project tanam id {$project_id}",
                "status" => true
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false
            ], 400);
        }
    }
}
