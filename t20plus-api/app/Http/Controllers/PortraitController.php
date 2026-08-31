<?php

namespace App\Http\Controllers;

use App\Models\Portrait;
use Illuminate\Http\JsonResponse;

class PortraitController extends Controller
{
    /**
     * List all portraits.
     */
    public function index(): JsonResponse
    {
        return response()->json(Portrait::all());
    }
}
