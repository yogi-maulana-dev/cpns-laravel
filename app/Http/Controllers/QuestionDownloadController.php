<?php

namespace App\Http\Controllers;

use App\Exports\QuestionExport;
use App\Helpers\BasicHelper;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\QuestionType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuestionDownloadController extends Controller
{
    public function index()
    {
        return view('questions.download', [
            'title' => 'Download Soal',
            'questionTypes' => QuestionType::orderBy('name')->get(),
            'examSessions' => ExamSession::select(['id', 'code', 'name'])->orderBy('name')->get(),
        ]);
    }

    public function download(Request $request)
    {
        $default = ini_get('max_execution_time');
        set_time_limit(5000);

        $validatedData = $this->validate($request, [
            'question_type_ids' => ['sometimes', 'nullable', 'array'],
            'question_type_ids.*' => ['sometimes', 'nullable', 'numeric'],
            'exam_session_id' => ['sometimes', 'nullable', 'numeric'],
            'start_at' => ['sometimes', 'nullable', 'date'],
            'end_at' => ['sometimes', 'nullable', 'date'],
            'type' => ['required', 'in:EXCEL,PDF'],
        ], [
            'question_type_ids' => 'Tipe Soal',
            'start_at' => 'Tanggal di Buat (Mulai)',
            'end_at' => 'Tanggal di Buat (Sampai)',
            'type' => 'Jenis File',
            'exam_session_id' => 'Sesi Ujian',
        ]);

        $questions = Question::query()
            ->when(
                $validatedData['question_type_ids'] ?? false,
                fn ($query, $data) => $query->whereIn('question_type_id', $data)
            )
            ->when(
                $validatedData['start_at'] ?? false,
                fn ($query, $value) => $query->whereDate('start_at', '>=', $value)
            )->when(
                $validatedData['end_at'] ?? false,
                fn ($query, $value) => $query->whereDate('end_at', '<=', $value)
            )
            ->when(
                $validatedData['exam_session_id'] ?? false,
                fn (Builder $query, $value) => $query
                    ->whereHas('examSessions', fn ($q) => $q
                        ->where('exam_sessions.id', $value))
            )
            ->with(['answers' => fn ($query) => $query->orderBy('order_index'), 'questionType'])
            ->orderByDesc('created_at')
            ->orderByDesc('question_type_id')
            ->get();

        if ($validatedData['type'] == 'PDF') {
            $pdf = Pdf::loadView('pdfs.questions', [
                'questions' => $questions,
                'separateDiscussion' => $request->boolean('separate_discussion'),
            ]);

            $pdf->set_paper('a4');

            $filename = sprintf('DAFTAR_SOAL_PDF_%s.pdf', BasicHelper::dateForFileName());

            return $pdf->stream($filename);
        } else {
            return Excel::download(
                new QuestionExport($questions),
                sprintf('%s_%s.xlsx', 'DAFTAR_SOAL_EXCEL', BasicHelper::dateForFileName())
            );
        }

        set_time_limit($default);
    }
}
