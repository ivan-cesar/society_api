<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Claim",
 *     description="Operations related to claims"
 * )
 * 
 * @OA\Schema(
 *     schema="Claim",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="subject", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="created_at", type="string"),
 *     @OA\Property(property="updated_at", type="string")
 * )
 */


class ClaimController extends Controller
{
    /**
     * Create a new claim.
     *
     * @OA\Post(
     *     path="/api/user/claims",
     *     summary="Create a new claim",
     *     tags={"Claim"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="subject", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Claim submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    // Créer une nouvelle réclamation
    public function create(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);
        $user = Auth::user();
        $claim = new Claim();
        $claim->user_id = $user->id;
        $claim->subject = $request->subject;
        $claim->description = $request->description;
        $claim->save();

        return response()->json(['message' => 'Claim submitted successfully'], 201);
    }
/**
     * Get the list of claims.
     *
     * @OA\Get(
     *     path="/api/user/claims",
     *     summary="Get the list of claims",
     *     security={{"bearerAuth": {}}},
     *     tags={"Claim"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="claims", type="array", @OA\Items(ref="#/components/schemas/Claim"))
     *         )
     *     )
     * )
     */
    // Obtenir la liste des réclamations
    public function index()
    {
        $claims = Claim::where("user_id", Auth::user())->all();
        return response()->json(['claims' => $claims], 200);
    }

    /**
     * Get the details of a claim.
     *
     * @OA\Get(
     *     path="/api/admin/claims/{claimId}",
     *     summary="Get the details of a claim",
     *     tags={"Claim"},
     *     security={{"bearerAuth": {}}},
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
     *             @OA\Property(property="claim", ref="#/components/schemas/Claim")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Claim not found")
     * )
     */
    // Obtenir les détails d'une réclamation
    public function show($claimId)
    {
        $claim = Claim::find($claimId);

        if (!$claim) {
            return response()->json(['message' => 'Claim not found'], 404);
        }

        return response()->json(['claim' => $claim], 200);
    }

}
