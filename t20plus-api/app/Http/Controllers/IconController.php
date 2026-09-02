<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use Illuminate\Http\JsonResponse;

class IconController extends Controller
{
    /**
     * List all icons.
     */
    public function index(): JsonResponse
    {
        return response()->json(Icon::all());
    }
}
