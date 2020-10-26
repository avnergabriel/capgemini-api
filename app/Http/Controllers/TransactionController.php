<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $account = Account::where('hash', $request->hash)->first();
        if (!isset($account->hash)) {
            return response([
                'message' => 'Erro na autenticação',
            ], 400);
        }

        $transactions = Transaction::where('account_id', $account->id)
            ->latest()->get();

        $balance =  0;
        foreach ($transactions as $transaction) {
            $balance += $transaction->amount / 100;
            $transaction->amount = $transaction->amount / 100;
        }

        return response([
            'message' => 'Sucesso',
            'transactions' => $transactions,
            'balance' => number_format($balance, 2, ',', ' '),
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'amount' => 'required',
                'type' => 'required',
                'hash' => 'required'
            ],
            [
                'amount.required' => 'Por favor, digite um valor',
                'type.required' => 'Tipo de transação não encontrada',
                'hash.required' => 'Erro na autenticação',
            ]
        );

        $account = Account::where('hash', $request->hash)->first();
        if (!isset($account->hash)) {
            return response([
                'message' => 'Erro na autenticação',
            ], 400);
        }

        $transaction = new Transaction();
        $transaction->account_id = $account->id;
        $transaction->amount = (INT) ($request->amount * 100);
        if ($request->type == 'withdraw') {
            $transaction->amount *= -1;
        }

        $transaction->save();

        return response([
            'message' => 'Sucesso',
        ], 200);
    }
}
