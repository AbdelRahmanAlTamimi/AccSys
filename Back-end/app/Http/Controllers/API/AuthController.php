<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    use ApiResponses;
    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        if(!Auth::attempt($request->only('email', 'password'))){
            return $this->error('invalid credentials',401);
        }

        $user = User::firstWhere('email',$request->email);

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API token for ' . $user->email,
                    ['*'],
                    now()->addMonth())->plainTextToken
            ]

        );

    }

    public function logout(Request $request)
    {
       $request->user()->currentAccessToken()->delete();
       return $this->ok('Logged out');
    }

}
