<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sop;
use App\Models\Tahapan;
use App\Models\User;
use App\Models\Admin;
use App\Models\ItemPekerjaan;
use Exception;
class TahapanController extends Controller
{
    //
    public function create(Request $request, $sop_id){
        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try{
                $admin = Admin::where('user_id', $user["id"])->first();
                $sop = Sop::findOrFail($sop_id);
                $tahapan = $sop->tahapan()->firstOrCreate([
                    'nama_tahapan' => $request->nama_tahapan,
                    'admin_id' => $admin->id
                ]);

                return response()->json([
                    "message" => "new tahapan successfully added on sop id {$sop_id}",
                    "success" => true
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                    "success" => false,
                    "message" => $e->getMessage()
                ], 400);
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);

    }

    public function update(Request $request, $sop_id, $tahapan_id){
        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {
                $sop = Sop::findOrFail($sop_id);
                $tahapan = Tahapan::where('sop_id', $sop->id)->findOrFail($tahapan_id);
                $tahapan->update([
                    'nama_tahapan' => $request->nama_tahapan
                ]);
                return response()->json([
                    "message" => "tahapan successfully updated on sop id {$sop_id} with id tahapan {$tahapan_id}",
                    "success" => true
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "success" => false,
                    "message" => $e->getMessage()
                ], 400);
            }

        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }


    public function delete($sop_id, $tahapan_id){
        // check user == admin
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {
                $sop = Sop::findOrFail($sop_id);
                $tahapan = Tahapan::findOrFail($tahapan_id);
                $tahapan->delete();
                return response()->json([
                    "message" => "tahapan successfully deleted on sop id {$sop_id} with id tahapan {$tahapan_id}",
                    "success" => true
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "success" => false,
                    "message" => $e->getMessage()
                ], 400);
            }

        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ], 401);
    }

    public function getTahapanBySopId($sop_id){
            try {
                $sop = Sop::findOrFail($sop_id);
                $tahapan = Tahapan::with(['itemPekerjaan', 'itemPekerjaan.indikatorKegiatan'])->where('sop_id', $sop->id)->get();

                return response()->json([
                    "success" => true,
                    "message" => "There is " .count($tahapan). " tahapan on SOP id {$sop->id}",
                    "count" => count($tahapan),
                    "data" => $tahapan
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "success" => false,
                    "message" => $e->getMessage()
                ], 400);
            }
    }

}
