<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;
use Kreait\Firebase\Storage;

class UserDetailController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload image to Firebase Storage
        $image = $request->file('image');
        $storage = app(Storage::class);
        $imagePath = 'users/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $firebaseStorageUrl = $storage->getStorage()->getBucket()->upload(fopen($image->getRealPath(), 'r'), [
            'name' => $imagePath,
        ]);

        // Save Firebase Storage URL in the database
        $userDetail = auth()->user()->userDetail;
        $userDetail->image = $firebaseStorageUrl;
        $userDetail->save();

        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }


    public function index()
    {
        dd("helo");
        $userDetails = UserDetail::all();
        return view('user_details.index', compact('userDetails'));
    }
    
}
