<?php

namespace App\Http\Controllers\FrontOffice;

use App\Models\FrontOffice\BackOffice\Test\TestCandidate;
use App\Models\FrontOffice\Message;
use App\Models\FrontOffice\BackOffice\Test\Test;
use App\Models\Notification;
use App\Utils\File;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;

class TestController
{
    private $url = '/front/test';
    private $list_view = 'pages.front-office.test.index';
    private $form_view = 'pages.front-office.test.form';
    private $pdf_view = 'pages.front-office.test.pdf';

    public function index()
    {
        if(session('user') == null)
        { return redirect('/front'); }

        $user = session('user');

        return view($this->list_view)->with([
            'messages' => Message::get(session('user')->id),
            'data' => Test::get_relevant($user->username, $user->email),
            'template_url' => $this->url
        ]);
    }

    public function edit($id_test, $id_cv)
    {
        if(session('user') == null)
        { return redirect('/front'); }

        $test = Test::with('parts')->findOrFail($id_test);

        $test->duration = $test->parts->sum('duration');
        $test->coefficient = $test->criteria->sum('coefficient');

        return view($this->form_view)->with([
            'form_action' => $this->url. '/'. $test->id.'/'.$id_cv,
            'form_method' => 'POST',
            'form_title' => 'Test',
            'url' => $this->url,
            'test' => $test
        ]);
    }

    public function update(Request $request, $id_test, $id_cv)
    {
        if(session('user') == null)
        { return redirect('/front'); }

        $test = Test::with('parts')->findOrFail($id_test);

        if($test->is_qna)
        { $test_candidate = $this->validate_qna($request, $test, $id_cv); }
        else
        { $test_candidate = $this->validate_file($request, $test, $id_cv); }

        $notification = new Notification();
        $notification->title = 'Nouveau Test';
        $notification->message = 'Un noveau test à été reçu';
        $notification->redirection = '/test-candidate/'.$test_candidate->id.'/edit';
        $notification->id_role = 2; // responsable de communication
        $notification->save();

        return redirect($this->url)->with('success', 'Test déposé avec succès');
    }

    private function validate_qna(Request $request, $test, $id_cv)
    {
        // check if all of the inputs are present
        // check the size of the inptus to match the size of the parts
        $request->validate([
            'response' => 'required|array|size:'.$test->parts->count(),
            'response.*' => 'required|string'
        ]);

        // write a pdf file
        $response = $request->input('response');
        $pdf = Pdf::loadView($this->pdf_view, compact(
            'test',
            'response'
        ));

        $filename = time().'_'.session('user')->id.'_'.$test->id.'.pdf';
        $path = storage_path('app/test-candidate/'.$filename);

        $pdf->save($path);

        // persist test
        $test_candidate = new TestCandidate();
        $test_candidate->id_cv_candidate = $id_cv;
        $test_candidate->id_test = $test->id;
        $test_candidate->date_received = (new DateTime())->format('Y-m-d');
        $test_candidate->file = $filename;

        $test_candidate->save();
        return $test_candidate;
    }

    private function validate_file(Request $request, $test, $id_cv)
    {
        // check submit file exists
        $request->validate([
            'file' => 'required|file|max:4096'
        ]);

        $filename = File::save_test($request->file('file'));

        // persist test
        $test_candidate = new TestCandidate();
        $test_candidate->id_cv_candidate = $id_cv;
        $test_candidate->id_test = $test->id;
        $test_candidate->date_received = (new DateTime())->format('Y-m-d');
        $test_candidate->file = $filename;

        $test_candidate->save();
        return $test_candidate;
    }
}
