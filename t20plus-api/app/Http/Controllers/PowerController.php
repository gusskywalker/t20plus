<?php

namespace App\Http\Controllers;

use App\Models\Power;
use Illuminate\Http\JsonResponse;

class PowerController extends Controller
{
    /**
     * List all powers.
     */
    public function index(): JsonResponse
    {
        return response()->json(Power::all());
    }
}
