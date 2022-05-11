<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;

class AlamatController extends Controller
{
    //
    public function showAvailableAddress()
    {
        $alamat = Province::with(['regencies', 'regencies.districts', 'regencies.districts.villages'])->get();
        return response()->json([
            "success" => true,
            "data" => $alamat
        ]);
    }
}
