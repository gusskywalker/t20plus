<?php

namespace App\Http\Controllers;

use App\Models\God;
use Illuminate\Http\JsonResponse;

class GodController extends Controller
{
    /**
     * List all gods.
     */
    public function index(): JsonResponse
    {
        return response()->json(God::orderBy('id')->get());
    }
}
