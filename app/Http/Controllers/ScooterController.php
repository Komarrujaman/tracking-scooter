<?php

namespace App\Http\Controllers;

use App\Models\Scooter;
use Illuminate\Http\Request;

class ScooterController extends Controller
{

    public function index()
    {
        $scooter = Scooter::all();
        return view('scooter', compact('scooter'));
    }
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'scooter' => 'required|unique:scooters|max:255',
        ]);

        $scooter = new Scooter;
        $scooter->scooter = $request->scooter;
        $scooter->save();

        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $scooter = Scooter::find($id);
        $request->validate([
            'scooter' => 'required|unique:scooters|max:255',
        ]);
        $scooter->scooter = $request->input('scooter');
        $scooter->save();
        return redirect()->back();
    }

    public function destroy($id)
    {
        $scooter = Scooter::find($id);
        $scooter->delete();
        return redirect()->back();
    }
}
