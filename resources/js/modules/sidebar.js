const sidebar = document.querySelector(".sidebar");
const sidebarBackdrop = document.querySelector(".sidebar-backdrop");
const sidebarTrigger = document.querySelector("#sidebar-trigger");
const sidebarTriggerClose = document.querySelector("#sidebar-trigger-close");

[sidebarBackdrop, sidebarTrigger, sidebarTriggerClose].forEach((el) => {
    el.addEventListener("click", () => {
        sidebar.classList.toggle("show");
        sidebarBackdrop.classList.toggle("show");
    });
});

const sidebarNavLinks = document.querySelectorAll(".sidebar-nav-link");

document.addEventListener("DOMContentLoaded", function () {
    sidebarNavLinks.forEach((el) => {
        const isActive = el.classList.contains("active");
        if (isActive) {
            el.parentElement.parentElement.parentElement.classList.add("show");
            el.parentElement.parentElement.parentElement.previousElementSibling.setAttribute(
                "aria-expanded",
                true
            );
        }
    });
});
