<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;
use Illuminate\Support\Facades\Auth;
/**
 * @OA\Tag(
 *     name="Operations",
 *     description="Operations related to user operations"
 * )
 * @OA\Schema(
 *     schema="Operation",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="amount", type="integer"),
 *     @OA\Property(property="recipient_id", type="integer"),
 *     @OA\Property(property="created_at", type="string"),
 *     @OA\Property(property="updated_at", type="string")
 * )
 */
class OperationsController extends Controller
{
    /**
     * Get the list of operations for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/operations",
     *     summary="Get the list of operations",
     *     tags={"Operations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="operations", type="array", @OA\Items(ref="#/components/schemas/Operation"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    // Obtenir la liste des opérations de l'utilisateur
    public function index()
    {
        $user = Auth::user();
        $operations = Operation::where('user_id', $user->id)->get();
        return response()->json(['operations' => $operations], 200);
    }

}
