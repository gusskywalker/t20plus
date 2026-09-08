<?php

namespace App\Http\Controllers;

use App\Models\WeaponAbility;
use Illuminate\Http\JsonResponse;

class WeaponAbilityController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(WeaponAbility::all());
    }
}
