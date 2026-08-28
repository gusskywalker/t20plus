<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use Illuminate\Http\JsonResponse;

class OriginController extends Controller
{
    /**
     * List all origins.
     */
    public function index(): JsonResponse
    {
        return response()->json(Origin::all());
    }
}
