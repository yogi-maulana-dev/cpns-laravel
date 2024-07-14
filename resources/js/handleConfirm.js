document.addEventListener("click", (e) => {
    if (!e.target.classList.contains("powergrid-delete-button")) return;
    e.preventDefault();
    if (!confirm("Apakah anda yakin ingin menghapus data tersebut?")) return;
    document.querySelector(".target-powergrid-delete-button").click();
});

document.addEventListener("click", (e) => {
    if (!e.target.classList.contains("powergrid-force-delete-button")) return;
    e.preventDefault();
    if (!confirm("Apakah anda yakin ingin menghapus data tersebut?")) return;
    document.querySelector(".target-powergrid-force-delete-button").click();
});
