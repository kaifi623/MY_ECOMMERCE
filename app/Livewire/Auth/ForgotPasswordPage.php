<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Forgot Password')]
class ForgotPasswordPage extends Component
{
    public $email;


    public function save(){
        $this->validate([
            'email'   =>  'required|email|exists:users,email|max:255',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === password::RESET_LINK_SENT) {
           session()->flash('success' , 'password reset link has been sent to your email address');
           $this->email= '';
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
