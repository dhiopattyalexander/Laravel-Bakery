<?php

namespace App\Http\Controllers;

use App\Models\Bread;

class BreadController extends Controller
{
    public function show(int $id)
    {
        $bread = Bread::with('category')->findOrFail($id);

        return view('breads.show', compact('bread'));
    }
}