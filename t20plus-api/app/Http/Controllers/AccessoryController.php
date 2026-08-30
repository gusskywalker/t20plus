<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\JsonResponse;

class AccessoryController extends Controller
{
    /**
     * List all accessories.
     */
    public function index(): JsonResponse
    {
        return response()->json(Accessory::all());
    }
}
