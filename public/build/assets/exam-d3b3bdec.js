const l=async(e,s)=>{const t=document.getElementById("status-saved-question-"+e.dataset.questionId);t.textContent="...";try{const n=await(await fetch(s,{method:"POST",body:JSON.stringify({question_id:e.dataset.questionId,answer_id:e.dataset.answerId}),headers:{"Content-Type":"application/json",Accept:"application/json","X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":csrfToken}})).json();if(n.success){t.classList.remove("d-none"),t.textContent=`Tersimpan pada ${n.saved_at}`;return}else console.log("ERROR"),t.classList.remove("d-none"),t.textContent="Gagal tersimpan"}catch{t.classList.remove("d-none"),t.textContent="Gagal tersimpan"}},f=async(e,s,t)=>{let o=e.textContent;e.textContent="...";const n=document.getElementById("status-saved-question-"+s);n.textContent="...";try{const a=await(await fetch(t,{method:"POST",body:JSON.stringify({question_id:s,is_ragu:e.dataset.isRagu=="true"}),headers:{"Content-Type":"application/json",Accept:"application/json","X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":csrfToken}})).json();a.success&&(n.classList.remove("d-none"),n.textContent=`Tersimpan pada ${a.saved_at}`,e.textContent=o)}catch{n.classList.remove("d-none"),n.textContent="Gagal tersimpan"}},r=document.getElementById("timeleft-of-exam");r&&m(r.dataset.endTimeExam);function m(e){const s=new Date(e),t=new Date,o=setInterval(function(){const n=new Date().getTime(),i=s.getTime()-n;let a=Math.floor(i%(1e3*60*60*60)/(1e3*60*60)),c=Math.floor(i%(1e3*60*60)/(1e3*60)),d=Math.floor(i%(1e3*60)/1e3);a=("0"+a).slice(-2),c=("0"+c).slice(-2),d=("0"+d).slice(-2),r.textContent=a+":"+c+":"+d},1e3);setTimeout(function(){clearInterval(o),u()},s.getTime()-t.getTime())}async function u(){alert("Waktu Sudah Habis, Tekan OK untuk menyimpan jawaban ujian anda saat ini!"),document.getElementById("finish-form").submit()}window.handleSubmitFinishExam=u;window.timeleft=m;window.sendAnswerRequest=l;window.sendRaguAnswerRequest=f;
