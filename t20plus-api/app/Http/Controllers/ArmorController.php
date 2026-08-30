<?php

namespace App\Http\Controllers;

use App\Models\Armor;
use Illuminate\Http\JsonResponse;

class ArmorController extends Controller
{
    /**
     * List all armors.
     */
    public function index(): JsonResponse
    {
        return response()->json(Armor::all());
    }
}
