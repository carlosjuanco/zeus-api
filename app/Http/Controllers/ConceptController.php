<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use Illuminate\Http\Request;

class ConceptController extends Controller
{
    public function getConcepts()
    {
        $concepts = Concept::select('id', 'concept')->orderBy('id', 'asc')->get();

        return response()->json([
            'concepts' => $concepts->toArray()
        ], 200);
    }
}
