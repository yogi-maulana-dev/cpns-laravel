<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Exports\ParticipantTemplateExport;
use App\Helpers\BasicHelper;
use App\Http\Requests\ParticipantRequest;
use App\Imports\ParticipantsImport;
use App\Models\ExamSession;
use App\Models\Participant;
use App\Models\User;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ParticipantController extends Controller
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
        return view('participants.index', [
            'title' => 'Data Peserta',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('participants.create', [
            'title' => 'Tambah Data Peserta Baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ParticipantRequest $request)
    {
        Participant::create(array_merge($request->validated(), [
            'picture' => $request
                ->hasFile('picture') ? $request
                ->file('picture')
                ->store('participant-pictures', ['disk' => 'public']) : 'participant-pictures/default.jpg',
        ]));

        $route = $request->boolean('no-redirect') ?
            'participants.create' : 'participants.index';

        return redirect()
            ->route($route)
            ->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Participant $participant)
    {
        return view('participants.show', [
            'title' => 'Detail Data Peserta',
            'participant' => $participant,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Participant $participant)
    {
        return view('participants.edit', [
            'title' => 'Edit Data Peserta',
            'participant' => $participant,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ParticipantRequest $request, Participant $participant)
    {
        $participant->update(array_merge($request->validated(), [
            'picture' => (new StorageService)
                ->public()
                ->uploadOrReturnDefault('picture', $participant->picture, 'participant-pictures'),
        ]));

        return redirect()
            ->route('participants.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function participantExamSessionAccess(Participant $participant)
    {
        $examSessions = ExamSession::orderByDesc('created_at')->get();

        $participant->load('examSessions');

        return view('participants.access', [
            'title' => 'Akses Sesi Ujian Data Peserta',
            'participant' => $participant,
            'examSessions' => $examSessions,
            'participantExamSessionIds' => $participant->examSessions->pluck('id')->toArray(),
        ]);
    }

    public function participantExamSessionAccessPost(
        Request $request,
        Participant $participant
    ) {
        $participant->examSessions()->sync($request->exam_session_ids ?? []);

        return redirect()
            ->route('participants.access', $participant)
            ->with('success', 'Data berhasil disimpan.');
    }

    public function active()
    {
        $default = ini_get('max_execution_time');
        set_time_limit(5000);

        Participant::query()
            ->select(['id', 'name', 'nik', 'email'])
            ->where('user_id', null)
            ->chunkById(200, function ($participants) {
                try {
                    DB::beginTransaction();

                    foreach ($participants as $participant) {
                        $user = User::create([
                            'name' => $participant->name,
                            // 'email' => $participant->nik.'@gmail.com',
                            'email' => $participant->email,
                            'password' => Hash::make($participant->nik),
                            'role' => UserRole::PARTICIPANT(),
                        ]);

                        $participant->update([
                            'user_id' => $user->id,
                        ]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error(json_encode($e));

                    return back()->with('failed', 'Proses pengaktifan gagal, Silahkan coba lagi!');
                }
            });

        set_time_limit($default);

        return back()->with('success', 'Proses pengaktifan data peserta berhasil dilakukan.');
    }

    public function sync()
    {
        $default = ini_get('max_execution_time');
        set_time_limit(5000);

        Participant::query()
            ->select(['id', 'name', 'user_id', 'nik', 'email'])
            ->whereNot('user_id', null)
            ->chunkById(200, function ($participants) {
                $participants->load('user');
                foreach ($participants as $participant) {
                    $update = [];
                    $user = optional($participant->user);

                    if ($user) {
                        $user->name !== $participant->name && $update['name'] = $participant->name;
                        // $user->email !== $participant->email && $update['email'] = $participant->nik . '@gmail.com';
                        $user->email !== $participant->email && $update['email'] = $participant->email;
                    }

                    count($update) > 0 && $user->update($update);
                }
            });

        set_time_limit($default);

        return back()->with('success', 'Proses sinkronisasi nama dan email data peserta berhasil dilakukan.');
    }

    public function template(Request $request)
    {
        return Excel::download(
            new ParticipantTemplateExport,
            sprintf('%s_%s.xlsx', 'TEMPLATE_PESERTA', BasicHelper::dateForFileName())
        );
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx,ods,odt,odp',
        ], attributes: [
            'file' => 'File Excel',
        ]);
        try {
            Excel::import(new ParticipantsImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            dd($failures);
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }

        return redirect()
            ->route('participants.index')
            ->with('success', 'Data berhasil disimpan.');
    }
}
