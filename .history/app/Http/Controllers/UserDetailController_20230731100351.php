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
     // Add a new user detail
     public function store(Request $request)
     {
         $validatedData = $request->validate([
             'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
             'city' => 'required|string|max:255',
         ]);
 
         // Save the image to Firebase Storage and get the URL
         $imageUrl = $this->uploadImageToFirebase($request->file('image'));
 
         // Create a new UserDetail record and save it to the database
         $userDetail = new UserDetail([
             'image_url' => $imageUrl,
             'city' => $validatedData['city'],
         ]);
 
         $userDetail->save();
 
         return redirect()->route('userdetails.index')->with('success', 'User detail added successfully.');
     }
     public function update(Request $request, $id)
     {
         $userDetail = UserDetail::find($id);
 
         // Validate the request data here (e.g., name, email, phone, country, image, and city)
         $validatedData = $request->validate([
            
             'country' => 'required|string|max:255',
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
             'city' => 'required|string|max:255',
         ]);
 
         if ($request->hasFile('image')) {
             // Save the new image to Firebase Storage and get the URL
             $imageUrl = $this->uploadImageToFirebase($request->file('image'));
 
             // Delete the old image from Firebase Storage (if applicable)
             $this->deleteImageFromFirebase($userDetail->image_url);
 
             // Update the image URL in the database
             $userDetail->image_url = $imageUrl;
         }
 
         // Update the other user detail fields
        
         
         $userDetail->city = $validatedData['city'];
 
         $userDetail->save();
 
         return redirect()->route('userdetails.index')->with('success', 'User detail updated successfully.');
     }
 
 
     // Show the form to edit a user 
}
