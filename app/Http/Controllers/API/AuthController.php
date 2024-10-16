<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|confirmed|string|min:8',
            'photo' => 'nullable|image|max:2048',  // Optional photo field
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'notes' => ['Invalid data']], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
        ]);

        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }

        return response()->json(['user' => $user, 'notes' => ['User registered successfully']], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'notes' => ['Invalid data']], 400);
        }


        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'notes' => ['Invalid credentials']], 401);
        }

        $user = User::where("email", $request->email)->first();
        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user, 'notes' => ['Login successful']], 200);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }


        return response()->json(['user' => $user, 'notes' => ['User profile fetched']], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15|unique:users,phone,' . $user->id,
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'notes' => ['Invalid data']], 400);
        }

        $user->update($request->only(['name', 'phone']));
        if ($request->hasFile('photo')) {
            $user->update([
                'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null,
            ]);
        }

        // Get the full path of the photo
        $photoPath = $user->photo;

        // If the photo exists, get the path after the domain
        if ($photoPath) {
            $photoUrl = asset('storage/' . $user->photo);
            $user->photo = $photoUrl;
        }

        return response()->json(['user' => $user, 'notes' => ['Profile updated successfully']], 200);
    }
}
