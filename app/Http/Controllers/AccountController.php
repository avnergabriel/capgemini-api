<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request,
            [
                'bank_account' => 'required',
                'password' => 'required'
            ],
            [
                'bank_account.required' => 'Por favor, digite a conta bancária',
                'password.required' => 'Por favor, digite a senha',
            ]
        );

        $account = Account::where('bank_account', $request->bank_account)
            ->first();

        if (!isset($account->password)) {
            return response([
                'message' => 'Conta inexistente'
            ], 400);
        }

        if (!Hash::check($request->password, $account->password)) {
            return response([
                'message' => 'Senha incorreta'
            ], 400);
        }

        $account->hash = self::hashGenerator();
        $account->save();

        return response([
            'message' => 'Sucesso',
            'data' => $account->hash,
        ], 200);
    }

    public function register(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required',
                'bank_account' => 'required',
                'password' => 'required'
            ],
            [
                'name.required' => 'Por favor, digite seu nome',
                'bank_account.required' => 'Por favor, digite a conta bancária',
                'password.required' => 'Por favor, digite a senha',
            ]
        );

        $account = Account::where('bank_account', $request->bank_account)->first();
        if (isset($account->bank_account)) {
            return response([
                'message' => 'Insira outra conta bancária',
            ], 400);
        }

        $account = new Account();
        $account->name = $request->name;
        $account->bank_account = $request->bank_account;
        $account->password = bcrypt($request->password);
        $account->hash = self::hashGenerator();

        $account->save();

        return response([
            'message' => 'Sucesso',
        ], 200);
    }

    public function checkLogin(Request $request)
    {
        $account = Account::where('hash', $request->hash)->first();
        if (!isset($account->hash)) {
            return response([
                'message' => 'Falha no login',
            ], 400);
        }

        return response([
            'message' => 'Sucesso',
        ], 200);
    }

    private function hashGenerator()
    {
        $bytes = 12;
        $random = random_bytes($bytes);
        $hash = bin2hex($random);
        $check_account = Account::where('hash',$hash)->first();
        if (isset($check_account->hash)) {
            $hash = self::hashGenerator();
        }

        return $hash;
    }
}
