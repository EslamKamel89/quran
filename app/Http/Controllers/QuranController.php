<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuranController extends Controller {

    public function suraIndex() {
        $suras = DB::table('sura')->get();

        return response()->json([
            'status' => 200,
            'data' => $suras
        ]);
    }
}
