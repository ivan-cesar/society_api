<?php

namespace App\Http\Controllers;

use App\Models\Claim;
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
    *     path="/api/admin/clients",
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
     *     path="/api/admin/claims/{claimId}",
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
            // Récupérer la réclamation en fonction de l'ID
            $claim = Claim::findOrFail($claimId);

            // Mettre à jour le statut de la réclamation
            $claim->status = 'processed'; 

            return response()->json(['message' => 'Claim handled successfully'], 200);
        } else {
            return response()->json(['message' => 'Access denied'], 403);
        }
    }
/**
    * Get the list of claims.
    *
    * @OA\Get(
    *     path="/api/admin/claims",
    *     summary="Get the list of claims",
    *     tags={"Admin"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="Successful response",
    *         @OA\JsonContent(
    *             @OA\Property(property="claims", type="array", @OA\Items(ref="#/components/schemas/User"))
    *         )
    *     ),
    *     @OA\Response(response=403, description="Access denied")
    * )
    */
    // Obtenir la liste des réclamations
    public function index()
    {
        $claims = Claim::all();
        return response()->json(['claims' => $claims], 200);
    }

}
