const sendAnswerRequest = async (_el, route) => {
    const savedStatus = document.getElementById('status-saved-question-' + _el.dataset.questionId);
    savedStatus.textContent = '...';
    try {
        const res = await fetch(route, {
            method: "POST",
            body: JSON.stringify({
                question_id: _el.dataset.questionId,
                answer_id: _el.dataset.answerId
            }),
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken,
            },
        });
        const data = await res.json();
        if (data.success) {
            savedStatus.classList.remove('d-none');
            savedStatus.textContent = `Tersimpan pada ${data.saved_at}`;
            return
        } else {
            console.log("ERROR");
            savedStatus.classList.remove('d-none');
            savedStatus.textContent = `Gagal tersimpan`;
        }
    } catch (error) {
        savedStatus.classList.remove('d-none');
        savedStatus.textContent = `Gagal tersimpan`;
    }
}

const sendRaguAnswerRequest = async (_el, questionId, route) => {
    let lastTextContent = _el.textContent;
    _el.textContent = '...';
    const savedStatus = document.getElementById('status-saved-question-' + questionId);
    savedStatus.textContent = '...';
    try {
        const res = await fetch(route, {
            method: "POST",
            body: JSON.stringify({
                question_id: questionId,
                is_ragu: _el.dataset.isRagu == 'true'
            }),
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken,
            },
        });
        const data = await res.json();

        if (data.success) {
            savedStatus.classList.remove('d-none');
            savedStatus.textContent = `Tersimpan pada ${data.saved_at}`;
            _el.textContent = lastTextContent;
        }
    } catch (error) {
        savedStatus.classList.remove('d-none');
        savedStatus.textContent = `Gagal tersimpan`;
    }
}

const timeLeftOfExam = document.getElementById("timeleft-of-exam");

timeLeftOfExam && timeleft(timeLeftOfExam.dataset.endTimeExam);

// handle countdown waktu ujian
function timeleft(endTime) {
    const endDatetime = new Date(endTime);
    const now = new Date();

    const examTimeleftInterval = setInterval(function () {
        const now = new Date().getTime();
        const timeleft = endDatetime.getTime() - now;

        let hours = Math.floor(
            (timeleft % (1000 * 60 * 60 * 60)) / (1000 * 60 * 60)
        );
        let minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((timeleft % (1000 * 60)) / 1000);

        // add prefix number
        hours = ("0" + hours).slice(-2);
        minutes = ("0" + minutes).slice(-2);
        seconds = ("0" + seconds).slice(-2);

        timeLeftOfExam.textContent = hours + ":" + minutes + ":" + seconds;
    }, 1000);

    setTimeout(function () {
        // akan dieksekusi jika waktu ujian habis
        clearInterval(examTimeleftInterval);
        handleSubmitFinishExam();
    }, endDatetime.getTime() - now.getTime());
}

async function handleSubmitFinishExam() {
    alert(
        "Waktu Sudah Habis, Tekan OK untuk menyimpan jawaban ujian anda saat ini!"
    );
    document.getElementById("finish-form").submit();
}

window.handleSubmitFinishExam = handleSubmitFinishExam;
window.timeleft = timeleft;
window.sendAnswerRequest = sendAnswerRequest;
window.sendRaguAnswerRequest = sendRaguAnswerRequest;
