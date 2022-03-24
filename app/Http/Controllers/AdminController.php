<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManagerKebun;
use App\Models\ProjectTanam;
use App\Models\Sop;
use App\Models\Alamat;
use App\Models\Regency;
use App\Models\Province;
use App\Models\CatatHarian;
use Carbon\Carbon;

class AdminController extends Controller
{
    

        public function laporanHarian(){
            $projectTanam = CatatHarian::whereDate('created_at', '=', Carbon::today()->toDateString())
                            ->with('tahapan','blokLahan.ProjectTanam.managerKebun','blokLahan.ProjectTanam.sop')->get();
    
            return response()->json(
                [
                    "data" => $projectTanam
                ]
            );
        }

        public function searchLaporan(Request $request){
            $date = Carbon::parse($request->date)->toDateTimeString();
            $tes = Carbon::today()->toDateString();
            $catatanHarian = CatatHarian::whereDate('created_at', '=', $date)
                            ->with('tahapan','blokLahan.ProjectTanam.managerKebun','blokLahan.ProjectTanam.sop')->get();
            //dd($catatanHarian);

            return response()->json(
                [
                    "data" => $catatanHarian
                ]
            );
        }
}
