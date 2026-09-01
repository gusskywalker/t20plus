<?php

namespace App\Http\Controllers;

use App\Models\Shield;
use Illuminate\Http\JsonResponse;

class ShieldController extends Controller
{
    /**
     * List all shields.
     */
    public function index(): JsonResponse
    {
        return response()->json(Shield::all());
    }
}
