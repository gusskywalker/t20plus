<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\JsonResponse;

class RaceController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Race::all());
    }
}
