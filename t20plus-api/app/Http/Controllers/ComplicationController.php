<?php

namespace App\Http\Controllers;

use App\Models\Complication;
use Illuminate\Http\JsonResponse;

class ComplicationController extends Controller
{
    /**
     * List all complications.
     */
    public function index(): JsonResponse
    {
        return response()->json(Complication::all());
    }
}
