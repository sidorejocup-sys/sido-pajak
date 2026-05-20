//import './bootstrap';

import Alpine from "alpinejs";

window.Alpine = Alpine;

function setupUploadProgress() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    document.querySelectorAll(".upload-progress-form").forEach((form) => {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const fileInput = form.querySelector("input[type=file]");
            if (!fileInput || !fileInput.files.length) {
                alert("Silakan pilih file untuk diunggah.");
                return;
            }

            const progressContainer = form
                .closest(".upload-form-wrapper")
                ?.querySelector(".upload-progress");
            const progressBar = progressContainer?.querySelector(
                ".upload-progress-bar",
            );
            const statusText =
                progressContainer?.querySelector(".upload-status");
            const submitButton = form.querySelector("button[type=submit]");

            if (progressContainer) {
                progressContainer.classList.remove("hidden");
            }
            if (progressBar) {
                progressBar.style.width = "0%";
            }
            if (statusText) {
                statusText.textContent = "Mengunggah file...";
            }
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add("opacity-70", "cursor-not-allowed");
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", form.getAttribute("action"));
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken || "");

            xhr.upload.addEventListener("progress", function (e) {
                if (e.lengthComputable && progressBar) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = `${percent}%`;
                }
            });

            xhr.addEventListener("load", function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    if (statusText) {
                        statusText.textContent =
                            "Unggah selesai. Impor berhasil.";
                    }
                    if (progressBar) {
                        progressBar.style.width = "100%";
                    }
                    // Reload page after 1.5 seconds to show success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    if (statusText) {
                        statusText.textContent =
                            "Terjadi kesalahan saat mengunggah. Coba lagi.";
                        statusText.classList.add("text-red-600");
                    }
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.classList.remove(
                            "opacity-70",
                            "cursor-not-allowed",
                        );
                    }
                }
            });

            xhr.addEventListener("error", function () {
                if (statusText) {
                    statusText.textContent =
                        "Terjadi kesalahan jaringan. Coba lagi.";
                    statusText.classList.add("text-red-600");
                }
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.remove(
                        "opacity-70",
                        "cursor-not-allowed",
                    );
                }
            });

            const formData = new FormData(form);
            xhr.send(formData);
        });
    });
}

window.addEventListener("DOMContentLoaded", () => {
    setupUploadProgress();
});

Alpine.start();
