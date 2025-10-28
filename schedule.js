let selectedDate = null;

// -----------------
// Initialize Calendar
// -----------------
document.addEventListener("DOMContentLoaded", () => {
  const today = new Date();
  selectedDate = today.toISOString().split("T")[0];
  loadCalendar(today.getMonth() + 1, today.getFullYear());
});

// -----------------
// Load Calendar via AJAX
// -----------------
function loadCalendar(month, year) {
  fetch(`fetch_calendar.php?month=${month}&year=${year}`)
    .then((res) => res.text())
    .then((html) => {
      const calendar = document.getElementById("calendar-container");
      calendar.innerHTML = html;

      requestAnimationFrame(() => {
        feather.replace();
        attachCalendarListeners();
        restoreHighlight();
      });
    });
}

// -----------------
// Calendar Listeners
// -----------------
function attachCalendarListeners() {
  // Month navigation
  document.querySelectorAll("[data-nav]").forEach((btn) => {
    btn.addEventListener("click", () => {
      loadCalendar(btn.dataset.month, btn.dataset.year);
    });
  });

  // Calendar day click
  document.querySelectorAll(".calendar-day").forEach((cell) => {
    cell.addEventListener("click", () => {
      selectedDate = cell.dataset.date;
      restoreHighlight();
      openCourtModal(selectedDate);
    });
  });
}

// -----------------
// Highlight selected date
// -----------------
function restoreHighlight() {
  document.querySelectorAll(".calendar-day").forEach((c) => {
    c.classList.remove("bg-primary", "text-white", "font-bold");
    if (!c.classList.contains("today")) {
      c.classList.add(
        "bg-secondary/30",
        "hover:bg-primary",
        "hover:text-white"
      );
    }
  });

  if (selectedDate) {
    const activeCell = document.querySelector(
      `.calendar-day[data-date="${selectedDate}"]`
    );
    if (activeCell) {
      activeCell.classList.remove(
        "bg-secondary/30",
        "hover:bg-primary",
        "hover:text-white"
      );
      activeCell.classList.add("bg-primary", "text-white", "font-bold");
    }
  }
}

// -----------------
// Court Modal Functions
// -----------------
const courtModal = document.getElementById("courtModal");
const courtModalBox = document.getElementById("courtModalBox");

function openCourtModal(date) {
  document.body.classList.add("overflow-hidden");
  courtModal.classList.remove("hidden");
  requestAnimationFrame(() => {
    courtModal.classList.add("flex");
    courtModal.classList.remove("opacity-0");
    courtModalBox.classList.remove("scale-95");
    courtModalBox.classList.add("scale-100");
  });

  // Set modal date
  courtModal.querySelector("#modal-date").textContent = new Date(
    date
  ).toDateString();

  // Load courts dynamically
  loadCourts(date);
}

function closeCourtModal() {
  document.body.classList.remove("overflow-hidden");
  courtModal.classList.add("opacity-0");
  courtModalBox.classList.remove("scale-100");
  courtModalBox.classList.add("scale-95");
  setTimeout(() => {
    courtModal.classList.remove("flex");
    courtModal.classList.add("hidden");
    restoreHighlight();
  }, 300);
}

document
  .getElementById("closeModal")
  .addEventListener("click", closeCourtModal);
courtModal.addEventListener("click", (e) => {
  if (e.target.id === "courtModal") closeCourtModal();
});

// -----------------
// Fetch courts
// -----------------
function loadCourts(date) {
  fetch(`fetch_courts.php?date=${date}`)
    .then((res) => res.text())
    .then((html) => {
      const container = document.getElementById("court-container");
      container.innerHTML = html;
    });
}

// -----------------
// Booking Modal (delegated for dynamic buttons)
// -----------------
// Delegated click listener on #court-container
document
  .getElementById("court-container")
  .addEventListener("click", function (e) {
    const btn = e.target.closest(".openBookingModal");
    if (!btn) return;

    // If not logged in, show login modal instead of booking
    if (typeof window.IS_LOGGED_IN !== "undefined" && !window.IS_LOGGED_IN) {
      const loginModal = document.getElementById("loginModal");
      if (loginModal) loginModal.classList.remove("modal-hidden");
      return;
    }

    const modal = document.getElementById("bookingModal");

    // Remove classes that hide the modal
    modal.classList.remove("modal-hidden", "hidden");

    // Optional: force it to display flex
    modal.classList.add("flex");

    const date = btn.dataset.date;
    const time = btn.dataset.time;
    const court = btn.dataset.court;

    modal.querySelector("#event_date").value = date;
    modal.querySelector("#event_time").value = time;
    modal.querySelector("#court_number").value = court;

    feather.replace();
  });

document
  .getElementById("closeBookingModal")
  .addEventListener("click", function () {
    const modal = document.getElementById("bookingModal");
    modal.classList.add("modal-hidden", "hidden");
  });

document.addEventListener("click", function (e) {
  const closeBtn = e.target.closest("#closeBookingModal");
  if (closeBtn) {
    const modal = document.getElementById("bookingModal");
    modal.classList.add("modal-hidden", "hidden");
  }
});

document.getElementById("bookingForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("start_gcash_payment.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "ok" && data.checkout_url) {
        window.location.href = data.checkout_url;
      } else {
        alert(data.message || "Failed to start payment.");
      }
    })
    .catch((err) => {
      console.error(err);
      alert("An error occurred while starting payment.");
    });
});
