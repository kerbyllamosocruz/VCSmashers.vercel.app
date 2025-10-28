document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.getElementById("contactForm");
    const formStatus = document.getElementById("form-status");

    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(contactForm);
            const urlEncodedData = new URLSearchParams(formData).toString();

            fetch("contact_process.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: urlEncodedData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        formStatus.innerHTML = `<p class="text-green-500">${data.message}</p>`;
                        contactForm.reset();
                    } else {
                        formStatus.innerHTML = `<p class="text-red-500">${data.message}</p>`;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    formStatus.innerHTML = `<p class="text-red-500">An error occurred. Please try again later.</p>`;
                });
        });
    }
});
