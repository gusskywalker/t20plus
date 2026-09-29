<?php

namespace App\Http\Controllers;

use App\Models\Companion;
use Illuminate\Http\JsonResponse;

class CompanionController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Companion::all());
    }
}
