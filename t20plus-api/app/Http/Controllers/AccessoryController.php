<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\JsonResponse;

class AccessoryController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Accessory::all());
    }
}
