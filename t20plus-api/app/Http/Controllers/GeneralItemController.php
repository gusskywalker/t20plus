<?php

namespace App\Http\Controllers;

use App\Models\GeneralItem;
use Illuminate\Http\JsonResponse;

class GeneralItemController extends Controller
{
    /**
     * List all general items.
     */
    public function index(): JsonResponse
    {
        return response()->json(GeneralItem::all());
    }
}
