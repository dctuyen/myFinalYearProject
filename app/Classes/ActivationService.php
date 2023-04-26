<?php
namespace App\Classes;

use App\Library\Constants;
use App\Mail\PasswordRequestEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ActivationService
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function sendActivationMail($user)
    {
        $token = $user->remember_token;
        $user->activation_link = route('activation', ['token' => $token]);
        $mailable = new PasswordRequestEmail($user);
        Mail::to($user->email)->send($mailable);
    }

    public function sendPasswordRequestMail($user)
    {
        $token = $user->remember_token;
        $user->activation_link = route('resetpassword', ['token' => $token, 'userid' => $user->id]);
        $mailable = new PasswordRequestEmail($user);
        Mail::to($user->email)->send($mailable);
    }
}
