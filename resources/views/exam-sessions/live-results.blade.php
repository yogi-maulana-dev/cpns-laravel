@extends('layouts.app')

@section('buttons')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div>
            <a href="{{ route('exam-sessions.results', $examSession) }}" class="btn btn-light border">
                <x-feather name="arrow-left" class="me-2" />
                Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <span class="text-bg-dark d-inline-block mb-2 py-1 px-3 rounded" id="last-updated-time"></span>
            <table class="table table-bordered m-0">
                <thead id="head-table-result">
                    <tr>

                    </tr>
                </thead>
                <tbody id="body-table-result">
                    <tr>
                        <th scope="row"></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const bodyTableResult = document.getElementById('body-table-result');
        const headTableResult = document.getElementById('head-table-result');
        const lastUpdatedTimeBadge = document.getElementById('last-updated-time');

        const getLiveParticipantExamResults = async () => {
            const res = await fetch("{{ route('exam-sessions.json-live-results', $examSession) }}");
            const data = await res.json();
            return data;
        }

        const updateUi = async () => {
            try {
                lastUpdatedTimeBadge.textContent = new Date().toLocaleTimeString();
                const data = await getLiveParticipantExamResults();
                let trs = '';
                headTableResult.innerHTML = `
            <th scope="col" class="text-center">NO</th>
                    <th scope="col">Peserta</th>
                    <th scope="col">Soal di Jawab</th>
                    ${data.columns ? data.columns.map((column) => `<th scope="col">${column}</th>`).join('') : ''}
                    <th scope="col">Total Nilai</th>
                    `;
                data.data.forEach((result, i) => {
                    trs += `
                <tr>
                    <th scope="row" class="text-center">${i+1}</th>
                    <td>${result.participant.nik} - ${result.participant.name}</td>
                    <td>${result.answered_count} / ${data.questions_count}</td>
                    ${result.scores ? Object.values(result.scores).map((value) => `<td>${value}</td>`).join('') : ''}
                    <td>${result.total_score}</td>
                </tr>
                `
                })
                bodyTableResult.innerHTML = trs;
            } catch (error) {
                alert("SERVER BERMASALAH");
                console.error(error);
            }
        }
        updateUi();
        setInterval(async () => {
            updateUi();
        }, 5000);
    </script>
@endpush
