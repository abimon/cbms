<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BloodBank;
use App\Models\User;
use App\Models\User_bank;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $banks = BloodBank::select('name','id')->get();
        return view('auth.register', compact('banks'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(): RedirectResponse
    {
        $validated=request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string','regex:/^[\+]?[0-9]{10,13}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'bank_id' => ['required', 'exists:blood_banks,id']
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone'=> $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);
        User_bank::create([
            'user_id' => $user->id,
            'bank_id' => $validated['bank_id']
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
