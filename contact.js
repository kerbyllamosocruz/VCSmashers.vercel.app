document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.getElementById("contactForm");
    const formStatus = document.getElementById("form-status");
    const submitButton = contactForm.querySelector("button[type='submit']");

    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            e.preventDefault();
            submitButton.disabled = true;

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
                        let seconds = 300;
                        const countdown = setInterval(function () {
                            submitButton.innerHTML = `Wait ${seconds}s`;
                            seconds--;
                            if (seconds < 0) {
                                clearInterval(countdown);
                                submitButton.disabled = false;
                                submitButton.innerHTML = "Send Message";
                                formStatus.innerHTML = "";
                            }
                        }, 1000);
                    } else {
                        formStatus.innerHTML = `<p class="text-red-500">${data.message}</p>`;
                        submitButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    formStatus.innerHTML = `<p class="text-red-500">An error occurred. Please try again later.</p>`;
                    submitButton.disabled = false;
                });
        });
    }
});
