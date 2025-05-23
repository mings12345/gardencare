<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
            'fcm_token' => 'nullable|string', // Allow FCM token
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

        if ($request->filled('fcm_token')) {
            $user->update(['fcm_token' => $request->fcm_token]);
        }

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
            'fcm_token' => 'nullable|string', // Accept FCM token
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
            $token = $user->createToken('Access Token', ['broadcast'])->plainTextToken;

            // Store or update FCM token
            if ($request->filled('fcm_token')) {
                $user->update(['fcm_token' => $request->fcm_token]);
            }

            // Explicitly include user details with user_type
            return response()->json([
                'message' => 'Login successful.',
                'token' => $token,
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Login error: ' . $e->getMessage());

            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    // Add the updateFcmToken method here
    public function updateFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);

        $user = auth()->user(); // Get the authenticated user
        $user->update(['fcm_token' => $request->fcm_token]); // Update the FCM token

        return response()->json(['message' => 'FCM token updated successfully']);
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
            'account' => $user->account,
        ]);
    }

            public function updateProfile(Request $request)
        {
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:50',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'sometimes|string|max:15',
                'address' => 'sometimes|string|max:255',
                'account' => 'sometimes|string|max:11|unique:users,account,' . $user->id,
                'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $request->only(['name', 'email', 'phone', 'address', 'account']);

            // Handle image upload - Store in public/images/profile
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($user->profile_image) {
                    $oldImagePath = public_path('images/profile_images/' . basename($user->profile_image));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $image = $request->file('profile_image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('images/profile_images'), $imageName);
                $data['profile_image'] = 'images/profile_images/' . $imageName;
            }

            $user->update($data);

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user,
                'profile_image_url' => $user->profile_image 
                    ? asset($user->profile_image)
                    : null,
            ], 200);
        }
    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully.',
        ], 200);
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

    public function updateAccount(){
        return auth()->user()->update(['account' => request('account')]);
    }

    public function getHomeowners()
    {
        $homeowners = User::where('user_type', 'homeowner')->get();

        return response()->json($homeowners);
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

        if (auth()->guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            if ($user->user_type === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                \Log::warning('Non-admin user attempted to access admin dashboard', ['user' => $user]);
                return redirect()->back()->with('error', 'You do not have access to this page.');
            }
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function index()
    {
        $totalHomeowners = User::where('user_type', 'homeowner')->count();
        $totalGardeners = User::where('user_type', 'gardener')->count();
        $totalServiceProviders = User::where('user_type', 'service_provider')->count();

        return view('admin.manage-users', compact(
            'totalHomeowners',
            'totalGardeners',
            'totalServiceProviders'
        ));
    }
}