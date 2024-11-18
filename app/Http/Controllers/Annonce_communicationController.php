<?php

namespace App\Http\Controllers;

use App\Models\Annonce_communicationModel;
use App\Models\Moyenne_commModel;
use App\Models\AnnonceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class Annonce_communicationController extends Controller
{

    public function publicite()
    {
        $publicite = Annonce_communicationModel::getplublicite();
        return view('annonce.publicite', compact('publicite'));
    }
    public function storeBatch(Request $request)
    {
        try {
            // Log détaillé des données reçues
            Log::info('Données reçues dans storeBatch:', [
                'request_data' => $request->all(),
                'items' => $request->input('items')
            ]);

            // Vérification des IDs dans la table annonce
            $annonceIds = collect($request->input('items'))->pluck('id')->unique();
            $existingAnnonces = AnnonceModel::whereIn('id', $annonceIds)->pluck('id')->toArray();

            Log::info('Vérification des IDs:', [
                'ids_reçus' => $annonceIds,
                'ids_existants' => $existingAnnonces
            ]);

            // Vérification des IDs de moyenne_comm
            $moyenneCommIds = collect($request->input('items'))->pluck('moyenneCommId')->unique();
            $existingMoyenneComm = Moyenne_commModel::whereIn('id', $moyenneCommIds)->pluck('id')->toArray();

            Log::info('Vérification des moyennes de communication:', [
                'ids_reçus' => $moyenneCommIds,
                'ids_existants' => $existingMoyenneComm
            ]);

            // Validation des données avec messages personnalisés
            $validatedData = $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required|exists:annonce,id',
                'items.*.moyenneCommId' => 'required|exists:moyenne_comm,id',
                'items.*.date' => 'required|date',
            ], [
                'items.*.id.exists' => 'L\'annonce avec l\'ID :input n\'existe pas dans la base de données',
                'items.*.moyenneCommId.exists' => 'Le moyen de communication avec l\'ID :input n\'existe pas'
            ]);

            DB::beginTransaction();

            foreach ($request->input('items') as $item) {
                $annonce_communication = new Annonce_communicationModel();

                // Log de chaque item avant la création
                Log::info('Création annonce_communication:', [
                    'id_annonce' => $item['id'],
                    'id_moyenne_comm' => $item['moyenneCommId'],
                    'date' => $item['date']
                ]);

                // Vérification si l'enregistrement existe déjà
                $exists = DB::table('annonce_communication')
                    ->where('id_annonce', $item['id'])
                    ->where('id_moyenne_comm', $item['moyenneCommId'])
                    ->where('date', $item['date'])
                    ->exists();

                if ($exists) {
                    throw new Exception("Une communication existe déjà pour l'annonce {$item['id']} avec ce moyen de communication à cette date");
                }

                $annonce_communication->setId_annonce($item['id']);
                $annonce_communication->setId_moyenne_comm($item['moyenneCommId']);
                $annonce_communication->setDate($item['date']);
                $annonce_communication->createAnnonce_communication();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Toutes les annonces communications ont été créées avec succès'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Erreur de validation:', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);

            $errorMessages = collect($e->errors())
                ->flatten()
                ->unique()
                ->values()
                ->toArray();

            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de validation des données',
                'errors' => $errorMessages
            ], 422);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
