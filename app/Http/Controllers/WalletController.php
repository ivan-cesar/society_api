<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Operation;
use Illuminate\Support\Facades\Auth;
/**
 * @OA\Tag(
 *     name="Wallet",
 *     description="Operations related to user wallet"
 * )
 * * @OA\Schema(
 *     schema="Wallet",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="amount", type="number"),
 *     @OA\Property(property="recipient_id", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string"),
 *     @OA\Property(property="updated_at", type="string")
 * )
 */
class WalletController extends Controller
{
     /**
     * Reload the user's electronic wallet.
     *
     * @OA\Post(
     *     path="/wallet/reload",
     *     summary="Reload the user's electronic wallet",
     *     tags={"Wallet"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wallet reloaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    // Recharger le portefeuille électronique de l'utilisateur
    public function reload(Request $request)
    {
        $this->validate($request,[
            'amount' => 'required|numeric|min:1',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            $amount = $request->amount;
    
            User::where('id', $user->id)->update([
                "wallet_balance" => $user->wallet_balance + $amount
            ]);
    
            // Enregistrer l'opération de recharge dans la table des opérations
            $this->recordOperation($user->id, 'Reload', $amount);
    
            return response()->json(['message' => 'Wallet reloaded successfully'], 200);
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }
/**
     * Transfer money to another user.
     *
     * @OA\Post(
     *     path="/wallet/transfer",
     *     summary="Transfer money to another user",
     *     tags={"Wallet"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="recipient_id", type="integer"),
     *             @OA\Property(property="amount", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transfer successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    // Transférer de l'argent à un autre utilisateur
    public function transfer(Request $request)
    {
        $this->validate($request, [
            'recipient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $recipient = User::find($request->recipient_id);
        $amount = $request->amount;

        // Vérifier si l'utilisateur a suffisamment de solde
        if ($user->wallet_balance >= $amount) {
            // Débiter le montant du portefeuille de l'utilisateur
            User::where('id', $user->id)->update([
                "wallet_balance" => $user->wallet_balance - $amount
            ]);

            // Créditer le montant au portefeuille du destinataire
            $recipient->wallet_balance += $amount;
            $recipient->save();

            // Enregistrer l'opération de transfert dans la table des opérations
            $this->recordOperation($user->id, 'Transfer', $amount, $recipient->id);

            return response()->json(['message' => 'Transfer successful'], 200);
        } else {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }
    }
/**
     * Make a payment for goods or services.
     *
     * @OA\Post(
     *     path="/wallet/pay",
     *     summary="Make a payment for goods or services",
     *     tags={"Wallet"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    // Effectuer un paiement pour des biens ou services
    public function pay(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        // Vérifier si l'utilisateur a suffisamment de solde
        if ($user->wallet_balance >= $amount) {
            // Débiter le montant du portefeuille de l'utilisateur
            
            User::where('id', $user->id)->update([
                "wallet_balance" => $user->wallet_balance - $amount
            ]);

            // Enregistrer l'opération de paiement dans la table des opérations
            $this->recordOperation($user->id, 'Payment', $amount);

            return response()->json(['message' => 'Payment successful'], 200);
        } else {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }
    }

    // Enregistrer une opération dans la table des opérations
    private function recordOperation($userId, $type, $amount, $recipientId = null)
    {
        $operation = new Operation();
        $operation->user_id = $userId;
        $operation->type = $type;
        $operation->amount = $amount;
        $operation->recipient_id = $recipientId;
        $operation->save();
    }

}
