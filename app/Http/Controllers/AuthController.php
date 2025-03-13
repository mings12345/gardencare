<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:users|max:50',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'user_type' => 'required|in:homeowner,gardener,service_provider,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'user_type' => $request->user_type,
        ]);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
        ], 201);
    }

            public function login(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $credentials = $request->only('email', 'password');

            try {
                if (!auth()->attempt($credentials)) {
                    return response()->json(['error' => 'Invalid credentials'], 401);
                }

                $user = auth()->user();
                $token = $user->createToken('Access Token')->plainTextToken;

                // Explicitly include user details with user_type
                return response()->json([
                    'message' => 'Login successful.',
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'user_type' => $user->user_type, // Include the user_type here
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ],
                ], 200);

            } catch (\Exception $e) {
                // Log the exception for debugging
                \Log::error('Login error: ' . $e->getMessage());

                return response()->json(['error' => 'An unexpected error occurred.'], 500);
            }
        }
        public function getProfileData($userId)
        {
            \Log::info('Fetching profile data for user ID: ' . $userId); // Debugging line

            $user = User::find($userId);

            if (!$user) {
                \Log::error('User not found for ID: ' . $userId); // Debugging line
                return response()->json(['message' => 'User not found'], 404);
            }

            \Log::info('User found: ' . json_encode($user)); // Debugging line

            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ]);
        }

        public function getGardeners()
    {
    $gardeners = User::where('user_type', 'gardener')->get();

    return response()->json($gardeners);
    }
    
        public function getServiceProviders()
    {
        $serviceProviders = User::where('user_type', 'service_provider')->get();

        return response()->json($serviceProviders);
    }


   

public function showAdminLoginForm()
{
    return view('auth.login');
}

public function adminLogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $credentials = $request->only('email', 'password');

    if (auth()->attempt($credentials)) {
        $user = auth()->user();
        if ($user->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            auth()->logout();
            return redirect()->back()->with('error', 'You do not have access to this page.');
        }
    }

    return redirect()->back()->with('error', 'Invalid credentials');
}
}
