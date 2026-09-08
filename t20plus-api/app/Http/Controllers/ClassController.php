<?php

namespace App\Http\Controllers;

use App\Models\CharacterClass;
use Illuminate\Http\JsonResponse;

class ClassController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(CharacterClass::all());
    }
}
