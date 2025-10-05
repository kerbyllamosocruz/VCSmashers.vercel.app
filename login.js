function initLoginModal() {
  const modal = document.getElementById("loginModal");
  const closeBtn = document.getElementById("closeModal");
  const openBtns = [document.getElementById("loginBtn"), document.getElementById("loginBtn2")];
  const loginForm = document.getElementById("loginForm");
  const errorEl = document.getElementById("loginError");
  const signInBtn = document.getElementById("signinBtn");

  if (!modal) return;

  openBtns.forEach((btn) => {
    if (btn) btn.addEventListener("click", () => modal.classList.remove("modal-hidden"));
  });

  if (closeBtn) closeBtn.addEventListener("click", () => modal.classList.add("modal-hidden"));

  modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.classList.add("modal-hidden");
  });

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (errorEl) errorEl.textContent = "";

      if (signInBtn) signInBtn.disabled = true;

      try {
        const formData = new FormData(loginForm);

        const res = await fetch("index.php", {
          method: "POST",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
          body: formData,
        });

        const contentType = res.headers.get("content-type") || "";

        if (res.ok && contentType.includes("application/json")) {
          const data = await res.json();
          if (data.success) {
            window.location.href = data.redirect ?? "landing.html";
            return;
          } else {
            errorEl.textContent = data.message || "Invalid credentials";
          }
        } else {
          const text = await res.text();
          console.error("Unexpected login response:", res.status, text);
          errorEl.textContent = "Server error — check console (see network tab).";
        }
      } catch (err) {
        console.error("Login fetch error:", err);
        errorEl.textContent = "Network error. Try again.";
      } finally {
        if (signInBtn) signInBtn.disabled = false;
      }
    });
  } else {
    console.warn("loginForm not found when initLoginModal ran");
  }
}
