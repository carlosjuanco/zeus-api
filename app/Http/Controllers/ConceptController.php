<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use Illuminate\Http\Request;

class ConceptController extends Controller
{
    public function getConcepts()
    {
        $concepts = Concept::select('name')->all();

        return response()->json([
            'concepts' => $concepts->pluck('concept')->toArray()
        ], 200);
    }
}
