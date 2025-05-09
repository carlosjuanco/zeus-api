<?php

namespace App\Http\Controllers;

use App\Models\Churche;
use Illuminate\Http\Request;

class ChurcheController extends Controller
{
    public function getChurches (Request $request) {
        $churches = Churche::select('name')->get();
        return response()->json([
            'churches' => $churches->pluck('name')->toArray()
        ], 200);
    }
}
