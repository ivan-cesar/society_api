<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
/**
 * @OA\Tag(
 *     name="Admin",
 *     description="Opérations liées à la fonctionnalité d'administration"
 * )
 */
class AdminController extends Controller
{ /**
    * Get the list of clients.
    *
    * @OA\Get(
    *     path="/admin/clients",
    *     summary="Get the list of clients",
    *     tags={"Admin"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="Successful response",
    *         @OA\JsonContent(
    *             @OA\Property(property="clients", type="array", @OA\Items(ref="#/components/schemas/User"))
    *         )
    *     ),
    *     @OA\Response(response=403, description="Access denied")
    * )
    */
    // Obtenir la liste des clients
    public function getClients()
    {
        // Vérifier si l'utilisateur authentifié est un administrateur
        if (auth()->user()->is_admin) {
            $clients = User::where('is_admin', false)->get();
            return response()->json(['clients' => $clients], 200);
        } else {
            return response()->json(['message' => 'Access denied'], 403);
        }
    }
/**
     * Handle client claim.
     *
     * @OA\Post(
     *     path="/admin/claims/{claimId}",
     *     summary="Handle client claim",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="claimId",
     *         in="path",
     *         required=true,
     *         description="ID of the claim",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Claim handled successfully")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    // Gérer les réclamations des clients
    public function handleClaim(Request $request, $claimId)
    {
        // Vérifier si l'utilisateur authentifié est un administrateur
        if (auth()->user()->is_admin) {
            // Traiter la réclamation avec l'ID $claimId selon vos besoins
            // ...

            return response()->json(['message' => 'Claim handled successfully'], 200);
        } else {
            return response()->json(['message' => 'Access denied'], 403);
        }
    }

}
