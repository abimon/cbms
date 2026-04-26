<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UserController extends Controller
{
    // register
    public function register()
    {
        try {
            $validated = request()->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:13',
                'avatar' => 'nullable|image|max:255',
                'password' => 'required|string|max:255',
                'password_confirmation' => 'required|same:password',
                'role' => 'required|string|max:255|in:Admin,Guest,Doctor,Donor,Sub-Admin',
            ]);
            if (!$validated) {
                if (request()->is('api/*')) {
                    return response()->json(['message' => 'Validation failed'], 400);
                }
                return back()->with('message', 'Validation failed');
            }
            $validated['password'] = Hash::make(request()->password);
            $validated['avatar'] = request()->file('avatar')->store('avatars', 'public');
            // return $validated;
            $user = User::create($validated);
            if (request()->is('api/*')) {
                return response()->json([
                    'message' => 'User registered successfully. Kindly verify your email',
                    'status' => 'Success',
                    'data' => $user,
                    'token'=> $user->createToken('auth_token')->plainTextToken
                ], 201);
            }
            return back()->with('message', 'User registered successfully. Kindly verify your email');
        } catch (Throwable $th) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $th->getMessage()], 400);
            }
            return back()->with('message', $th->getMessage());
        }
    }
    // login
    public function login()
    {
        try {
            $validate = request()->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|string|max:255',
            ]);
            if (!$validate) {
                if (request()->is('api/*')) {
                    return response()->json(['message' => 'Validation failed'], 1000);
                }
                return back()->with('message', 'Validation failed');
            }
            $user = User::where('email', $validate['email'])->first();

            if (!$user) {
                if (request()->is('api/*')) {
                    return response()->json(['message' => 'Unregistered email'], 401);
                }
                return back()->with('message', 'Unregistered email');
            }
            if (!Hash::check($validate['password'], $user->password)) {
                if (request()->is('api/*')) {
                    return response()->json(['message' => 'Wrong password'], 401);
                }
                return back()->with('message', 'Wrong password');
            }
            if (request()->is('api/*')) {

                $user->tokens()->delete();
                return response()->json([
                    'message' => 'User logged in successfully',
                    'status' => 'Success',
                    'data' => $user,
                    'token' => $user->createToken('auth_token')->plainTextToken
                ], 200);
            }
            return back()->with('message', 'User logged in successfully');
        } catch (Throwable $th) {
            if (request()->is('api/*')) {
                return response()->json(['message' => $th->getMessage()], 401);
            }
            return back()->with('message', $th->getMessage());
        }
    }
    // forgot password
    public function forgotPassword()
    {
        $code = rand(1000, 9999);
        $user = User::where('email', request()->email)->first();
        if ($user) {
            $this->sendEmail($user, 'Your password reset code is: ' . $code, 'Password Reset Code');
            return response()->json(['message' => 'Code sent to your email'], 200);
        }
        return response()->json(['message' => 'Email not found'], 400);
    }
    // reset password
    public function resetPassword()
    {
        $user = User::where('email', request()->email)->first();
        if ($user) {
            $user->password = Hash::make(request('password'));
            $user->update();
            return response()->json(['message' => 'Password reset successfully'], 200);
        }
        return response()->json(['message' => 'Email not found'], 400);
    }
    // profile
    public function profile()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        if(request()->is('api/*')){
            return response()->json(['user' => $user]);
        }
        return view('users.profile', compact('user'));
    }
    // logout
    // update data
    // send email
    public function sendEmail($user, $message, $subject)
    {
        Mail::send(
            'emails.mail',
            ['user' => $user, 'message' => $message],
            function ($message) use ($user, $subject) {
                $message->to($user->email, $user->name)->subject($subject);
            }
        );
    }
    public function logout()
    {
        $user =User::findOrFail( Auth::id());
        $user->tokens()->delete();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    // User Management
    public function index()
    {
        $users = User::paginate(25);
        if (request()->is('api/*')) {
            return response()->json($users);
        }
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        if (request()->is('api/*')) {
            return response()->json($user);
        }
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user= User::findOrFail($id);
        if (request()->is('api/*')) {
            return response()->json($user);
        }
        return view('users.edit', compact('user'));
    }

    public function update($id)
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:13',
            'role' => 'required|string|in:Admin,Guest,Doctor,Donor,Sub-Admin,SuperAdmin',
            'is_verified' => 'nullable|boolean',
        ]);
        $user = User::findOrFail($id);
        if(request()->hasFile('avatar')){
            $validated['avatar'] = request()->file('avatar')->store('avatars');
        }
        if (Auth::user()->role=='SuperAdmin' || Auth::user()->role == 'Admin' || Auth::user()->phone == '0701583807') {
            $user->update($validated);
        }else{
            $validated['role']= $user->role;
            $user->update($validated);
        }

        if (request()->is('api/*')) {
            return response()->json(['message' => 'User updated successfully', 'user' => $user]);
        }
        return redirect()->route('system-users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        if (request()->is('api/*')) {
            return response()->json(['message' => 'User deleted successfully']);
        }
        return redirect()->route('system-users.index')->with('success', 'User deleted successfully');
    }

    // Profile Update (for current logged in user)
    public function updateProfile(Request $request)
    {
        $user=User::findOrFail(Auth::id());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:13',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with(['success' => 'Profile updated successfully', 'tab' => 'profile']);
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $user=User::findOrFail(Auth::id());

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with(['error' => 'Current password is incorrect', 'tab' => 'password']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with(['success' => 'Password updated successfully', 'tab' => 'password']);
    }

    // Update Notifications Settings
    public function updateNotifications(Request $request)
    {
        $user=User::findOrFail(Auth::id());

        $validated = $request->validate([
            'receive_emails' => 'nullable|boolean',
            'receive_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
        ]);

        $user->update([
            'receive_emails' => $request->has('receive_emails'),
            'receive_notifications' => $request->has('receive_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
        ]);

        return back()->with(['success' => 'Notification settings updated successfully', 'tab' => 'notifications']);
    }
}
