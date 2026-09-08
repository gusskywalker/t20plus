<?php

namespace App\Http\Controllers;

use App\Models\GeneralItem;
use Illuminate\Http\JsonResponse;

class GeneralItemController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(GeneralItem::all());
    }
}
