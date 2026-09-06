<?php

namespace App\Http\Controllers;

use App\Models\ItemImprovement;
use Illuminate\Http\JsonResponse;

class ItemImprovementController extends Controller
{
    /**
     * List all item improvements.
     */
    public function index(): JsonResponse
    {
        return response()->json(ItemImprovement::all());
    }
}
