<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ public_path('css/pdfs/main.css') }}">
    <title>HASIL UJIAN {{ $examSession->code }} - {{ $examSession->name }} PDF</title>
</head>

<body>

    <h1 class="title text-center">DAFTAR SOAL</h1>

    <table class="question-table">
        <tbody>
            @foreach ($examSession->questions as $question)
            <tr class="align-top">
                <td rowspan="2" class="iteration">{{ $loop->iteration }}.</td>
                <td>
                    @if ($question->question_image)
                    <div class="image-and-text">
                        <img class="question-image" src="{{ public_path('storage/' . $question->question_image) }}" alt="Gambar Soal" />
                        <p class="question-text">{!! nl2br($question->question_text) !!}</p>
                    </div>
                    @else
                    <p class="question-text">{!! nl2br($question->question_text) !!}</p>
                    @endif
                    <ul class="question-answers align-top">
                        @foreach ($question->answers as $answer)
                        <li class="align-top">
                            <span class="option-alpha">{{ chr(64 + $answer->order_index) }}.</span>
                            @if ($answer->answer_image)
                            <div class="answer-image-and-text">
                                <img class="answer-image" src="{{ public_path('storage/' . $answer->answer_image) }}" alt="Gambar Jawaban" />
                                <span class="answer-text">{!! nl2br($answer->answer_text) !!}</span>
                            </div>
                            @else
                            <span class="answer-text">{!! nl2br($answer->answer_text) !!}</span>
                            @endif

                        </li>
                        @endforeach
                    </ul>
                    @php
                    $participantAnswer = $examSession->participantAnswers->where('question_id', $question->id)->first();
                    @endphp
                    @if ($participantAnswer)
                    @php
                    $answer = $question->answers->where('id', $participantAnswer->selected_answer_id)->first();
                    @endphp
                    <span class="right-answer">Jawaban Peserta: <span>{{ chr(64 +
                            $answer?->order_index)
                            }}</span></span>
                    @endif
                    @if($all or $examSession->result_display_status ==
                    \App\Enums\ResultDisplayStatus::QUESTION_PARTICIPANT_ANSWER_AND_DISCUSSION())
                    <div class="discussion">
                        @if ($question->discussion_image)
                        <div class="discussion-image-and-text">
                            <img class="discussion-image" src="{{ public_path('storage/' . $question->discussion_image) }}" alt="Gambar Pembahasan" />
                            <div class="discussion-text">
                                <span class="right-answer">Jawaban Benar: <span>{{ chr(64 +
                                        $question->order_index_correct_answer)
                                        }}</span></span>
                                <i>
                                    <div class="fw-bold">Pembahasan: </div>{!! nl2br($question->discussion) !!}
                                </i>
                            </div>
                        </div>
                        @else
                        <span class="right-answer">Jawaban Benar: <span>{{ chr(64 +
                                $question->order_index_correct_answer)
                                }}</span></span>
                        <i class="discussion-text">
                            <div class="fw-bold">Pembahasan: </div>{!! nl2br($question->discussion) !!}
                        </i>
                        @endif
                    </div>
                    @endif
                </td>
            </tr>
            <tr></tr>
            @endforeach
        </tbody>
    </table>

    {{-- @if($separateDiscussion)
    <div class="page-break"></div>

    <h1 class="title text-center">PEMBAHASAN</h1>

    <table class="question-table">
        <tbody>
            @foreach ($examSession->questions as $question)
            <tr class="align-top">
                <td rowspan="2" class="iteration">{{ $loop->iteration }}.</td>
    <td>
        <div class="discussion separate-discussion">
            @if ($question->discussion_image)
            <div class="discussion-image-and-text">
                <img class="discussion-image" src="{{ public_path('storage/' . $question->discussion_image) }}" alt="Gambar Pembahasan" />
                <div class="discussion-text">
                    <span class="right-answer">Jawaban Benar: <span>{{ chr(64 +
                                        $question->order_index_correct_answer)
                                        }}</span></span>
                    <i>
                        <div class="fw-bold">Pembahasan: </div>{!! nl2br($question->discussion) !!}
                    </i>
                </div>
            </div>
            @else
            <span class="right-answer">Jawaban Benar: <span>{{ chr(64 +
                                $question->order_index_correct_answer)
                                }}</span></span>
            <i class="discussion-text">
                <div class="fw-bold">Pembahasan: </div>{!! nl2br($question->discussion) !!}
            </i>
            @endif
        </div>
    </td>
    </tr>
    <tr></tr>
    @endforeach
    </tbody>
    </table>
    @endif --}}

</body>

</html>