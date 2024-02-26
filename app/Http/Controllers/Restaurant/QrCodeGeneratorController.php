<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeGeneratorController extends Controller
{
    public function generate()
    {
        $restaurant = Restaurant::find(Auth::user()->restaurant_id);


        if (!$restaurant) {

            return redirect()->back()->with('error', 'Restaurant not found.');
        }

        try {

            $qrCode = QrCode::size(500)->format('png')->generate(url('/' . $restaurant->name));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to generate QR code.');
        }

        return view('restaurant.qrcode', ['qrCode' => $qrCode]);
    }

    public function generateWithColors(Request $request)
    {

        $foregroundColor = $request->input('foregroundColor', '#000000'); // Default to black if not provided
        $backgroundColor = $request->input('backgroundColor', '#ffffff'); // Default to white if not provided


        $restaurant = Restaurant::where('id', auth()->user()->restaurant_id)->firstOrFail();

        $qrCode = QrCode::size(500)->format('png')->color($foregroundColor, $backgroundColor)->generate(url('/' . $restaurant->name));

        return response()->json(['qrCode' => $qrCode]);
    }
}