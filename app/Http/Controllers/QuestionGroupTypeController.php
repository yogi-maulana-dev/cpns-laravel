<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionGroupTypeRequest;
use App\Models\QuestionGroupType;

class QuestionGroupTypeController extends Controller
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
        return view('question-group-types.index', [
            'title' => 'Data Tipe Kelompok Soal',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('question-group-types.create', [
            'title' => 'Tambah Data Tipe Kelompok Soal Baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionGroupTypeRequest $request)
    {
        QuestionGroupType::create($request->validated());

        $route = $request->boolean('no-redirect') ?
            'question-group-types.create' : 'question-group-types.index';

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionGroupType $questionGroupType)
    {
        return view('question-group-types.show', [
            'title' => 'Detail Data Tipe Kelompok Soal',
            'questionGroupType' => $questionGroupType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionGroupType $questionGroupType)
    {
        return view('question-group-types.edit', [
            'title' => 'Edit Data Tipe Kelompok Soal',
            'questionGroupType' => $questionGroupType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionGroupTypeRequest $request, QuestionGroupType $questionGroupType)
    {
        $questionGroupType->update($request->validated());

        return redirect()
            ->route('question-group-types.index')
            ->with('success', 'Data berhasil disimpan.');
    }
}
