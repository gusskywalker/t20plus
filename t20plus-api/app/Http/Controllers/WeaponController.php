<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use Illuminate\Http\JsonResponse;

class WeaponController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Weapon::all());
    }
}
