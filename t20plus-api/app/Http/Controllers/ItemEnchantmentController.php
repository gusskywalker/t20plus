<?php

namespace App\Http\Controllers;

use App\Models\ItemEnchantment;
use Illuminate\Http\JsonResponse;

class ItemEnchantmentController extends Controller
{
    /**
     * List all item enchantments.
     */
    public function index(): JsonResponse
    {
        return response()->json(ItemEnchantment::all());
    }
}
