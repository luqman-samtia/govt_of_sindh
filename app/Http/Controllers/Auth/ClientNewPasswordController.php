<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rules;

class ClientNewPasswordController extends Controller
{
    
    public function create(Request $request)
    {
        $user = User::with('client')->where('id',Crypt::decrypt($request->id))->first();
        $is_password_set = $user->client->is_password_set;
        
        if($is_password_set == 0){     
            return view('emails.reset_client_password', compact('request'));           
        }
        else{         
            return redirect(route('login'));
        }
    }

    public function store(Request $request)
    {   
        $request->validate([
            'password' => ['required', 'confirmed'],
        ]);

        User::where('id',$request->id)->update([
            'password' => Hash::make($request->password),
        ]);

        Client::where('user_id',$request->id)->update([
            'is_password_set' => 1,
        ]);

        return redirect(route('login'));
        
    }
}   
