<?php

namespace App\Http\Controllers;

use App\Models\TipeJawaban;
use Illuminate\Http\Request;

class TipeJawabanController extends Controller
{
    public function index()
    {
        $tipeJawabans = TipeJawaban::all();

        return response()->json([
            'message' => 'Index tipe jawaban success',
            'status' => 'success',
            'data' => $tipeJawabans
        ], 200);
    }
}
