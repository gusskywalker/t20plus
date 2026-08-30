<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    /**
     * List all skills.
     */
    public function index(): JsonResponse
    {
        return response()->json(Skill::all());
    }
}
