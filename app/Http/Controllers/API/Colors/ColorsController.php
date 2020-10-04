<?php

namespace App\Http\Controllers\API\Colors;

use App\Http\Controllers\Controller;
use App\Models\Colors;
use Illuminate\Http\Request;

class ColorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $colors = Colors::all();

        return response()->json($colors);
    }
}
