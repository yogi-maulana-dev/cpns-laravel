import "./bootstrap";

import Alpine from "alpinejs";
import flatpickr from "flatpickr";
import feather from "feather-icons";

window.Alpine = Alpine;
window.flatpickr = flatpickr;

import TomSelect from "tom-select";
window.TomSelect = TomSelect;

Alpine.start();

feather.replace({ "aria-hidden": "true" });

// init all flatpickr elements
const flatpickrElements = document.querySelectorAll(".flatpickr");
const flatpickrCustomElements = document.querySelectorAll(".flatpickr-custom");
flatpickr(flatpickrElements, {
    altInput: true,
    altFormat: "d F Y",
});
flatpickrCustomElements.forEach((el) => {
    flatpickr(el, {
        altInput: true,
        altFormat: "d F Y",
        ...el.dataset,
    });
});

const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

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
