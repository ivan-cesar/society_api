<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
/**
 * @OA\Tag(
 *     name="User",
 *     description="Operations related to user management"
 * )
 * 
 * @OA\Schema(
 *     schema="UserInscriptionRequest",
 *     @OA\Property(property="nom", type="string"),
 *     @OA\Property(property="prenoms", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="telephone", type="string"),
 *     @OA\Property(property="password", type="string"),
 *     @OA\Property(property="password_confirmation", type="string")
 * )
 * 
 * @OA\Schema(
 *     schema="UserConnexionRequest",
 *     @OA\Property(property="telephone", type="string"),
 *     @OA\Property(property="password", type="string")
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nom", type="string"),
 *     @OA\Property(property="prenoms", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="telephone", type="string"),
 *     @OA\Property(property="created_at", type="string"),
 *     @OA\Property(property="updated_at", type="string")
 * )
 */
class UserController extends Controller
{
    /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/user/inscription",
     *     summary="Register a new user",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserInscriptionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function inscription(Request $request)
    {
        $utilisateurDonnee = $request->validate([
            "nom" =>["required","string","min:2","max:255"],
            "prenoms" =>["required","string","min:2","max:255"],
            "email"=>["required","email","unique:users,email"],
            "telephone"=>["required","unique:users,telephone"],
            "password"=>["required","string","min:8","max:30","confirmed"]
        ]);
        $utilisateurs = User::create([
            "nom" => $utilisateurDonnee["nom"],
            "prenoms" => $utilisateurDonnee["prenoms"],
            "email" => $utilisateurDonnee["email"],
            "telephone" => $utilisateurDonnee["telephone"],
            "password"=>bcrypt($utilisateurDonnee["password"])
        ]);
       return response($utilisateurs, 201); 
    }

/**
     * User login.
     *
     * @OA\Post(
     *     path="/user/connexion",
     *     summary="User login",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserConnexionRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="utilisateur", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function connexion(Request $request)
    {
        $utilisateurDonnee = $request->validate([
            "telephone"=>["required"],
            "password"=>["required","string","min:8","max:30"]
        ]); 
        $utilisateur = User::where("telephone",$utilisateurDonnee["telephone"])->first();
        if (!$utilisateur) return response(["message" =>"Aucune utilisateur de trouver avec le numero de telephone suivant $utilisateurDonnee[telephone]"],401);
        if (!Hash::check($utilisateurDonnee["password"],$utilisateur->password)) return response(["message" =>"Aucune utilisateur de trouver avec ce mot de passe"], 401);

        $token = $utilisateur->createToken('CLE_SECRETE')->plainTextToken;
        return response([
            "utilisateur" => $utilisateur,
            "token" => $token
        ]);       
        return $utilisateur;
    }

    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
