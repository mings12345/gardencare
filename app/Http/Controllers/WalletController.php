<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json([
            'balance' => (float) $user->balance, // Explicitly cast to float
            'transactions' => $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'amount' => (float) $transaction->amount, // Cast to float
                    'transaction_type' => $transaction->transaction_type,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    public function cashIn(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'account_number' => 'required|string'
        ]);

        $user = Auth::user();
        
        // Create transaction
        $transaction = new WalletTransaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $request->amount;
        $transaction->transaction_type = 'credit';
        $transaction->description = 'Cash in from account: ' . $request->account_number;
        $transaction->save();

        // Update user balance
        $user->balance += $request->amount;
        $user->save();

        return response()->json([
            'message' => 'Cash in successful',
            'new_balance' => $user->balance
        ]);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'account_number' => 'required|string'
        ]);

        $user = Auth::user();

        // Check if user has sufficient balance
        if ($user->balance < $request->amount) {
            return response()->json([
                'message' => 'Insufficient balance'
            ], 400);
        }

        // Create transaction
        $transaction = new WalletTransaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $request->amount;
        $transaction->transaction_type = 'debit';
        $transaction->description = 'Withdrawal to account: ' . $request->account_number;
        $transaction->save();

        // Update user balance
        $user->balance -= $request->amount;
        $user->save();

        return response()->json([
            'message' => 'Withdrawal successful',
            'new_balance' => $user->balance
        ]);
    }
}