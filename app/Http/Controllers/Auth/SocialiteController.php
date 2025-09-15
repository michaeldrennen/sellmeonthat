<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        $socialUser = Socialite::driver($provider)->user();

        // Find or create the user in your local database
        $user = User::updateOrCreate([
                                         'email' => $socialUser->getEmail(),
                                     ], [
                                         'name' => $socialUser->getName(),
                                         'provider' => $provider,
                                         'provider_id' => $socialUser->getId(),
                                         'email_verified_at' => now(), // Assume email is verified by provider
                                     ]);

        // Log the user in
        Auth::login($user);

        return redirect('/dashboard');
    }
}
