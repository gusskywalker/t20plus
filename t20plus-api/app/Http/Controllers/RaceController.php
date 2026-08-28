<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\JsonResponse;

class RaceController extends Controller
{
    /**
     * List all races.
     */
    public function index(): JsonResponse
    {
        return response()->json(Race::all());
    }
}
