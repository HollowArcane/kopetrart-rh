<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;

class CartController extends Controller
{
    public function add(Request $request)
    {
        // try {
        //     $annonceId = $request->input('annonce_id');
        //     $moyenneCommId = $request->input('moyenne_comm_id');

        //     // Récupérer les détails de l'annonce
        //     $annonce = Annonce::find($annonceId);

        //     if (!$annonce) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Annonce non trouvée'
        //         ]);
        //     }

        //     // Récupérer le panier actuel
        //     $cart = session()->get('cart', []);

        //     // Vérifier si l'annonce est déjà dans le panier
        //     $existingItem = collect($cart)->firstWhere('id', $annonceId);

        //     if ($existingItem) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Cette annonce est déjà dans votre panier'
        //         ]);
        //     }

        //     // Ajouter l'élément au panier
        //     $cart[] = [
        //         'id' => $annonce->id,
        //         'poste' => $annonce->poste,
        //         'departement' => $annonce->departement,
        //         'moyenne_comm_id' => $moyenneCommId
        //     ];

        //     // Mettre à jour la session
        //     session()->put('cart', $cart);

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Annonce ajoutée au panier avec succès',
        //         'cartItems' => $cart
        //     ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Une erreur est survenue lors de l\'ajout au panier: ' . $e->getMessage()
        //     ]);
        // }
    }

    // public function validate(Request $request)
    // {
    //     // try {
    //     //     $cart = session()->get('cart', []);

    //     //     if (empty($cart)) {
    //     //         return response()->json([
    //     //             'success' => false,
    //     //             'message' => 'Le panier est vide'
    //     //         ]);
    //     //     }

    //     //     // Ici vous pouvez ajouter la logique pour sauvegarder la commande
    //     //     // Par exemple :
    //     //     // Order::create([
    //     //     //     'items' => $cart,
    //     //     //     'user_id' => auth()->id()
    //     //     // ]);

    //     //     // Vider le panier après validation
    //     //     session()->forget('cart');

    //     //     return response()->json([
    //     //         'success' => true,
    //     //         'message' => 'Commande validée avec succès'
    //     //     ]);

    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'Une erreur est survenue lors de la validation: ' . $e->getMessage()
    //     //     ]);
    //     // }
    // }
}
