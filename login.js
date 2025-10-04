function initLoginModal() {
    const modal = document.getElementById("loginModal");
    const closeBtn = document.getElementById("closeModal");
    const openBtns = [document.getElementById("loginBtn"), document.getElementById("loginBtn2")];
  
    if (!modal) return;
  
    // Show modal
    openBtns.forEach((btn) => {
      if (btn) {
        btn.addEventListener("click", () => {
          modal.classList.remove("modal-hidden");
        });
      }
    });
  
    // Close modal (X button)
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        modal.classList.add("modal-hidden");
      });
    }
  
    // Close modal by clicking outside
    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.add("modal-hidden");
      }
    });
  }
  