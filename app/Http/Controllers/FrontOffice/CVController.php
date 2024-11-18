<?php

namespace App\Http\Controllers\FrontOffice;

use App\Models\FrontOffice\BackOffice\Dossier;
use App\Models\FrontOffice\Message;
use App\Models\Notification;
use App\Utils\File;
use DateTime;
use Illuminate\Http\Request;

class CVController
{
    private $url = '/front/cv';
    private $index_view = 'pages.front-office.cv.index';
    private $form_view = 'pages.front-office.cv.form';
    private $show_view = 'pages.front-office.cv.index';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(session('user') == null)
        { return redirect('/front'); }

        $user = session('user');

        return view($this->index_view)->with([
            'messages' => Message::get(session('user')->id),
            'url' => $this->url,
            'cvs' => Dossier::get($user->username, $user->email),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(session('user') == null)
        { return redirect('/front'); }

        return view($this->form_view)->with([
            'messages' => Message::get(session('user')->id),
            'postes' => Dossier::options(),
            'form_action' => $this->url,
            'form_method' => 'POST',
            'form_title' => 'Dépôt de Dossier',
            'url' => $this->url,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id-poste' => 'required|exists:besoin_poste,id',
            'cv' => 'required|mimes:pdf|max:4096',
            'motivation-letter' => 'required|mimes:pdf|max:4096'
        ]);

        $user = session('user');

        $dossier = new Dossier();
        $dossier->candidat = $user->username;
        $dossier->email = $user->email;
        $dossier->id_besoin_poste = $request->input('id-poste');
        $dossier->statut = 'Nouveau';
        $dossier->date_reception = (new DateTime())->format('Y-m-d');

        $dossier->cv = File::save($request->file('cv'), 'dossiers/cv');
        $dossier->lettre_motivation = File::save($request->file('motivation-letter'), 'dossiers/lettres');

        $dossier->save();

        $notification = new Notification();
        $notification->title = 'Nouveau Dossiers';
        $notification->message = 'Un dossier à été reçu';
        $notification->redirection = '/dossiers';
        $notification->id_role = 5; // chargé de recrutement
        $notification->save();

        return redirect('/front/home')->with('success', 'Dossiers déposés avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(session('user') == null)
        { return redirect('/front'); }

        return view($this->show_view)->with('cv', null);
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
