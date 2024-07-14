import { builtValidationElement, fetchWithToken } from "../utils.js";
import toast from "../toast.js";
import { setLoadingButton } from "../loading.js";

const loginForm = document.getElementById("login-form");

loginForm.addEventListener("submit", async function (e) {
    e.preventDefault();
    const loginFormButton = this.querySelector("#login-form-button");
    const formData = new FormData(this);

    try {
        setLoadingButton(loginFormButton, true);
        let { res, data } = await fetchWithToken(this.action, {
            method: "POST",
            body: formData,
        });

        if (res.status == 422) {
            const errorElement = builtValidationElement(data.errors);
            toast.show({
                title: "Error",
                body: errorElement,
                colorClass: toast.TOAST_DANGER,
            });
        }

        if (res.status == 400) {
            toast.show({
                title: "Informasi",
                body: `<span>${data.message}</span>`,
                colorClass: toast.TOAST_DANGER,
            });
        }

        if (res.status == 200) {
            toast.show({
                title: "Informasi",
                body: `<span>${data.message}</span>`,
                colorClass: toast.TOAST_SUCCESS,
            });
            window.location.assign(data.redirect_to);
        }
    } catch ({ error, res, data }) {
        if (res.status == 429) {
            toast.show({
                title: "Terlalu Banyak Request Login",
                body: `<span>${res.statusText}</span>`,
                colorClass: toast.TOAST_DANGER,
            });
        }
        if (res.status == 500) {
            toast.show({
                title: "Ada Masalah Pada Server",
                body: `<span>${res.statusText}</span>`,
                colorClass: toast.TOAST_DANGER,
            });
        }
    } finally {
        loginForm.password.value = "";
        setLoadingButton(loginFormButton, false);
    }
});
