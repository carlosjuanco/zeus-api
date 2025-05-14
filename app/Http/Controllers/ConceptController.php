<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use Illuminate\Http\Request;

class ConceptController extends Controller
{
    public function getConcepts()
    {
        $concepts = Concept::select('id', 'concept')->get();

        return response()->json([
            'concepts' => $concepts->toArray()
        ], 200);
    }
}
