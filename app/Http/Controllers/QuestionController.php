<?php

namespace App\Http\Controllers;

use App\Exports\QuestionTemplateExport;
use App\Helpers\BasicHelper;
use App\Http\Requests\QuestionRequest;
use App\Imports\QuestionsImport;
use App\Models\Question;
use App\Models\QuestionType;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
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
        return view('questions.index', [
            'title' => 'Data Soal',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create', [
            'title' => 'Tambah Data Soal Baru',
            'questionTypes' => QuestionType::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $question = Question::create(array_merge($request->validated(), [
            'question_image' => $request
                ->hasFile('question_image') ? $request
                ->file('question_image')
                ->store('question-images', ['disk' => 'public']) : null,
            'discussion_image' => $request
                ->hasFile('discussion_image') ? $request
                ->file('discussion_image')
                ->store('discussion-images', ['disk' => 'public']) : null,
        ]));

        $question
            ->answers()
            ->createMany(
                Arr::map(
                    $request->get('answers'),
                    function ($answer) use ($request) {
                        $fileKey = 'answers.'.$answer['order_index'].'.answer_image';

                        return [
                            'answer_text' => $answer['answer_text'],
                            'order_index' => $answer['order_index'],
                            'weight_score' => $answer['weight_score'],
                            'answer_image' => $request
                                ->hasFile($fileKey) ? $request
                                ->file($fileKey)
                                ->store('answer-images', ['disk' => 'public']) : null,
                        ];
                    }
                )
            );

        $route = $request->boolean('no-redirect') ?
            'questions.create' : 'questions.index';

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->load(['questionType', 'answers']);

        return view('questions.show', [
            'title' => 'Detail Data Soal',
            'question' => $question,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $question->load(['answers']);

        return view('questions.edit', [
            'title' => 'Edit Data Soal',
            'question' => $question,
            'questionTypes' => QuestionType::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->update(array_merge($request->validated(), [
            'question_image' => (new StorageService)
                ->public()
                ->uploadOrReturnDefault('question_image', $question->question_image, 'question-images'),
            'discussion_image' => (new StorageService)
                ->public()
                ->uploadOrReturnDefault('discussion_image', $question->discussion_image, 'discussion-images'),
        ]));

        foreach ($question->answers as $answer) {
            $requestAnswer = Arr::first(
                Arr::where($request->get('answers'), fn ($_answer) => $_answer['order_index'] == $answer['order_index'])
            );
            $fileKey = 'answers.'.$requestAnswer['order_index'].'.answer_image';
            $answer->update([
                'answer_text' => $requestAnswer['answer_text'],
                'order_index' => $requestAnswer['order_index'],
                'weight_score' => $requestAnswer['weight_score'],
                'answer_image' => (new StorageService)
                    ->public()
                    ->uploadOrReturnDefault($fileKey, $answer['answer_image'], 'answer-images'),
            ]);
        }

        return redirect()
            ->route('questions.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function template(Request $request)
    {
        return Excel::download(
            new QuestionTemplateExport,
            sprintf('%s_%s.xlsx', 'TEMPLATE_SOAL', BasicHelper::dateForFileName())
        );
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx,ods,odt,odp',
        ], attributes: [
            'file' => 'File Excel',
        ]);

        Excel::import(new QuestionsImport, $request->file('file'));

        return redirect()
            ->route('questions.index')
            ->with('success', 'Data berhasil disimpan.');
    }
}
