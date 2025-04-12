<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Exhibitor;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public static function MyProfile()
    {
        return view('website.pages.profile.my-profile');
    }

    public static function MySubscriptions()
    {
        return view('website.pages.profile.my-subscriptions');
    }

    public function verifyEmailChange(Request $request)
    {
        $token = $request->query('token');
        $userType = $request->query('user');

        if (!$token || !$userType || !in_array($userType, ['user', 'visitor', 'exhibitor'])) {
            return redirect()->route('my-profile')
                ->with('error', __('profile.email_change_invalid_link'));
        }

        // Get the user based on the token
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$tokenData) {
            return redirect()->route('my-profile')
                ->with('error', __('profile.email_change_invalid_token'));
        }

        // Find the user with the pending email change
        $model = match ($userType) {
            'user' => User::where('email', $tokenData->email)->first(),
            'visitor' => Visitor::where('email', $tokenData->email)->first(),
            'exhibitor' => Exhibitor::where('email', $tokenData->email)->first(),
        };

        if (!$model || !$model->new_email) {
            return redirect()->route('my-profile')
                ->with('error', __('profile.email_change_no_request'));
        }

        // Update the email and clear the new_email field
        $oldEmail = $model->email;
        $model->email = $model->new_email;
        $model->new_email = null;
        $model->save();

        // Delete the token
        DB::table('password_reset_tokens')
            ->where('token', $token)
            ->delete();

        // Log the user in
        if ($userType === 'user') {
            Auth::guard('web')->login($model);
        } elseif ($userType === 'visitor') {
            Auth::guard('visitor')->login($model);
        } elseif ($userType === 'exhibitor') {
            Auth::guard('exhibitor')->login($model);
        }

        return redirect()->route('my-profile')
            ->with('success', __('profile.email_change_success'));
    }
}
