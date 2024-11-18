<?php

namespace App\Http\Controllers\Test;

use App\Models\Notification;
use App\Models\Test\Test;
use App\Models\Test\TestCriterion;
use App\Models\Test\TestPart;
use App\Models\Test\TestPoint;
use App\Utils\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController
{
    private $url = '/test';
    private $list_view = 'pages.test.list';
    private $form1_view = 'pages.test.form1';
    private $form2_view = 'pages.test.form2';
    private $form3_view = 'pages.test.form3';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view($this->list_view)->with([
            'data' => DB::table('v_test')->get(),
            'template_url' => $this->url
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        session(['test' => session('test') ?? new Test()]);
        return $this->form1();
    }

    public function form1()
    {
        if(!session('test'))
        { return redirect($this->url.'/create'); }

        return view($this->form1_view)->with([
            'item' => session('test'),
            'needs' => DB::table('besoin_poste as bp')
            ->join('postes as p', 'bp.id_poste', '=', 'p.id')
            ->pluck('p.libelle', 'bp.id'),
            'template_url' => $this->url,
            'form_action' => '/test/store1',
            'form_method' => 'POST',
            'form_title' => 'Création de Test'
        ]);
    }

    public function form2()
    {
        if(!session('test') || (session('test-step') ?? 0) < 2)
        { return redirect($this->url.'/create'); }

        return view($this->form2_view)->with([
            'item' => session('test'),
            'parts' => session('parts'),
            'template_url' => $this->url,
            'form_action' => '/test/store2',
            'form_method' => 'POST',
            'form_title' => 'Création de Test'
        ]);
    }

    public function form3()
    {
        if(!session('test') || (session('test-step') ?? 0) < 3)
        { return redirect($this->url.'/create'); }

        return view($this->form3_view)->with([
            'item' => session('test'),
            'test_point_importances' => DB::table('test_point_importance')->pluck('label', 'id'),
            'template_url' => $this->url,
            'item' => session('test'),
            'form_action' => '/test/store3',
            'form_method' => 'POST',
            'form_title' => 'Création de Test'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { return $this->store1($request); }

    public function store1(Request $request)
    {
        if(!session('test'))
        { return redirect($this->url.'/create'); }

        $request->validate([
            'id-need' => 'required|exists:besoin_poste,id',
            'title' => 'required|string',
            'goal' => 'required|string',
            'qna' => 'required|in:true,false',
            'requirements' => 'required|array',
            'requirements.*' => 'required|string',
            'file' => 'nullable|file|max:4096'
        ]);

        $test = session('test');
        $test->is_qna = $request->input('qna');
        $test->id_need = $request->input('id-need');
        $test->title = $request->input('title');
        $test->goal = $request->input('goal');
        $test->requirements = implode(';', $request->input('requirements'));

        if($request->hasFile('file'))
        {
            $filename = File::save($request->file('file'), 'test-attachment');
            $test->file = $filename;
        }

        if(!$test->id)
        { $test->save(); }
        else
        { $test->update(); }

        session(['test-step' => 2]);
        return redirect($this->url.'/form2');
    }

    public function store2(Request $request)
    {
        if(!session('test') || (session('test-step') ?? 0) < 2)
        { return redirect($this->url.'/create'); }

        $request->validate([
            'contents' => 'required|array',
            'contents.*' => 'required|string',
            'durations' => 'required|array',
            'durations.*' => 'required|numeric'
        ]);

        $test = session('test');
        DB::table('test_part')->where('id_test', $test->id)->delete();

        $contents = $request->input('contents');
        $durations = $request->input('durations');

        $count = min(count($contents), count($durations));

        $parts = [];
        for($i = 0; $i < $count; $i++)
        {
            $part = new TestPart();
            $part->id_test = $test->id;
            $part->content = $contents[$i];
            $part->duration = $durations[$i];

            $part->save();
            $parts[] = $part;
        }
        session(['parts' => collect($parts)]);

        session(['test-step' => 3]);
        return redirect($this->url.'/form3');
    }

    public function store3(Request $request)
    {
        if(!session('test') || (session('test-step') ?? 0) < 3)
        { return redirect($this->url.'/create'); }

        $request->validate([
            'point-labels' => 'required|array',
            'point-labels.*' => 'required|string',
            'id-importances' => 'required|array',
            'id-importances.*' => 'required|exists:test_point_importance,id',
            'criterion-labels' => 'required|array',
            'criterion-labels.*' => 'required|string',
            'coefficients' => 'required|array',
            'coefficients.*' => 'required|numeric'
        ]);

        $test = session('test');
        DB::table('test_point')->where('id_test', $test->id)->delete();
        DB::table('test_criterion')->where('id_test', $test->id)->delete();

        $point_labels = $request->input('point-labels');
        $id_importances = $request->input('id-importances');
        $criterion_labels = $request->input('criterion-labels');
        $coefficients = $request->input('coefficients');

        $count1 = min(count($point_labels), count($id_importances));
        $points = [];
        for($i = 0; $i < $count1; $i++)
        {
            $point = new TestPoint();
            $point->id_test = $test->id;
            $point->label = $point_labels[$i];
            $point->id_importance = $id_importances[$i];

            $point->save();
            $points[] = $point;
        }
        session(['points' => collect($points)]);

        $count2 = min(count($criterion_labels), count($coefficients));
        $criteria = [];
        for($i = 0; $i < $count2; $i++)
        {
            $criterion = new TestCriterion();
            $criterion->id_test = $test->id;
            $criterion->label = $criterion_labels[$i];
            $criterion->coefficient = $coefficients[$i];

            $criterion->save();
            $criteria[] = $criterion;
        }
        session(['criteria' => collect($criteria)]);

        session(['test' => null, 'parts' => null, 'test-step' => 0]);

        return redirect($this->url)->with('success', 'Test inséré avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO Export PDF
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Test::destroy($id);
        return redirect($this->url)->with('success', 'Test supprimé avec succès');
    }
}
