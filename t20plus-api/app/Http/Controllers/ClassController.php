<?php

namespace App\Http\Controllers;

use App\Models\CharacterClass;
use Illuminate\Http\JsonResponse;

class ClassController extends Controller
{
    /**
     * List all classes.
     */
    public function index(): JsonResponse
    {
        return response()->json(CharacterClass::all());
    }
}
