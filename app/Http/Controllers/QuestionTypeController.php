<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionTypeRequest;
use App\Models\QuestionGroupType;
use App\Models\QuestionType;

class QuestionTypeController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('question-types.index', [
            'title' => 'Data Tipe Soal',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('question-types.create', [
            'title' => 'Tambah Data Tipe Soal Baru',
            'questionGroupTypes' => QuestionGroupType::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionTypeRequest $request)
    {
        QuestionType::create($request->validated());

        $route = $request->boolean('no-redirect') ?
            'question-types.create' : 'question-types.index';

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionType $questionType)
    {
        $questionType->load('questionGroupType');

        return view('question-types.show', [
            'title' => 'Detail Data Tipe Soal',
            'questionType' => $questionType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionType $questionType)
    {
        return view('question-types.edit', [
            'title' => 'Edit Data Tipe Soal',
            'questionType' => $questionType,
            'questionGroupTypes' => QuestionGroupType::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionTypeRequest $request, QuestionType $questionType)
    {
        $questionType->update($request->validated());

        return redirect()
            ->route('question-types.index')
            ->with('success', 'Data berhasil disimpan.');
    }
}
