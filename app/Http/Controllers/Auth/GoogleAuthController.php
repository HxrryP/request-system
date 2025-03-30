<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function handleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find user by google_id or email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Update google_id if user exists by email but hasn't linked Google before
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                Auth::login($user);
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(24)),
                     // Generate a random password
                    'email_verified_at' => now(), // Mark as verified since it's from Google
                    // 'role' defaults to 'user' based on our migration
                ]);
                Auth::login($newUser);
            }

            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/dashboard');
            }

        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Google Auth Callback Error: ' . $e->getMessage());
            // Redirect back with an error message
            return redirect('/login')->with('error', 'Unable to login using Google. Please try again.');
        }
    }
}
