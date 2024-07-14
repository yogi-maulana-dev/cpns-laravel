import "./bootstrap";

// import Alpine from "alpinejs";
import flatpickr from "flatpickr";
import feather from "feather-icons";
import currency from "currency.js";

window.currency = currency;

import toast from "./toast.js";

// Import all of Bootstrap's JS
import * as bootstrap from "bootstrap";

// window.Alpine = Alpine;
window.flatpickr = flatpickr;

import TomSelect from "tom-select";
window.TomSelect = TomSelect;

// Alpine.start();

import "../../vendor/power-components/livewire-powergrid/dist/powergrid";

feather.replace({ "aria-hidden": "true" });

const csrfToken = document.head.querySelector(
    "[name~=csrf-token][content]"
).content;

// init all flatpickr elements
const flatpickrElements = document.querySelectorAll(".flatpickr");
const flatpickrCustomElements = document.querySelectorAll(".flatpickr-custom");
flatpickr(flatpickrElements, {
    altInput: true,
    altFormat: "d F Y",
    time_24hr: true,
});
flatpickrCustomElements.forEach((el) => {
    flatpickr(el, {
        altInput: true,
        altFormat: "d F Y",
        time_24hr: true,
        ...el.dataset,
    });
});

const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);
document.addEventListener('livewire:initialized', () => {

    // handle livewire event
    window.addEventListener("show-toast", (event) => {
        const detail = event.detail[0] ?? {};
        let data = {
            title: "Informasi",
            body: detail.message,
            colorClass: toast.TOAST_DANGER, // default
        };
        if (detail.success) data["colorClass"] = toast.TOAST_SUCCESS;
        toast.show(data);
    });

    // modals
    window.addEventListener("hide-modal", (event) => {
        const detail = event.detail[0] ?? {};
        new bootstrap.Modal(detail.modalId).hide();
    });

    window.addEventListener("redirect", (event) => {
        const detail = event.detail[0] ?? {};
        window.location.assign(detail.url);
    });

    window.addEventListener("livewire-scroll", (event) => {
        const detail = event.detail[0] ?? {};
        window.scrollTo({
            top: detail.top,
            behavior: "smooth",
        });
    });
});

const showPreviewImage = (event, idImgTagPreview) => {
    const reader = new FileReader();
    const imgElement = document.querySelector(idImgTagPreview);

    reader.onload = function () {
        if (reader.readyState == 2) {
            imgElement.src = reader.result;
        }
    };

    reader.readAsDataURL(event.target.files[0]);
};

window.showPreviewImage = showPreviewImage;

import "./handleConfirm";
import "./preloader";
import "./utils";
