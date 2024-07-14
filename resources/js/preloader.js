const preloader = document.querySelector(".pre-loader");

document.addEventListener("DOMContentLoaded", () => {
    preloader.classList.add("hide");
    // 300 === main.css transition duration
    setTimeout(() => {
        preloader.classList.replace("d-flex", "d-none");
    }, 300);
});

window.onbeforeunload = function (e) {
    preloader.classList.remove("hide");
    preloader.classList.replace("d-none", "d-flex");
};

// when user click back/previous page
window.addEventListener(
    "popstate",
    function (event) {
        preloader.classList.add("hide");
        preloader.classList.replace("d-flex", "d-none");
    },
    false
);

// when user click back/previous page
window.addEventListener("backbutton", function (event) {
    preloader.classList.add("hide");
    preloader.classList.replace("d-flex", "d-none");
});

window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        // This code will run when the page is shown after navigating back
        // using the previous button or when retrieved from the cache.

        // Your event detection code goes here
        // console.log("Returned to page from cache or history");
        preloader.classList.add("hide");
        preloader.classList.replace("d-flex", "d-none");
    }
});
