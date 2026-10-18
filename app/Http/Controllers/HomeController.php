<?php

namespace App\Http\Controllers;

use App\Models\Home;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'note' => 'string|min:10',
        ]);

        Home::create($validatedData);

        return redirect()->back()->with('success', 'Data saved successfully!');
    }
}
