<?php

namespace App\Http\Controllers;

use App\Models\Armor;
use Illuminate\Http\JsonResponse;

class ArmorController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Armor::all());
    }
}
