<?php

namespace App\Http\Controllers;

use App\Models\Shield;
use Illuminate\Http\JsonResponse;

class ShieldController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Shield::all());
    }
}
