<?php

namespace App\Http\Controllers;

use App\Models\Spell;
use Illuminate\Http\JsonResponse;

class SpellController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Spell::all());
    }
}
