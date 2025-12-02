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
      // Check if the date is disabled (past date)
      if (cell.dataset.disabled === "true") {
        return; // Do nothing for past dates
      }
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
    
    // Skip styling for past dates
    if (c.dataset.disabled === "true") {
      return;
    }
    
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
    // Check if any button was clicked and if it's disabled
    const clickedBtn = e.target.closest("button");
    if (clickedBtn && clickedBtn.disabled) {
      e.preventDefault();
      e.stopPropagation();
      return;
    }
    
    const btn = e.target.closest(".openBookingModal");
    if (!btn) return;
    
    // Check if the button is disabled
    if (btn.disabled) return;

    // If not logged in, show login modal instead of booking
    if (typeof window.IS_LOGGED_IN !== "undefined" && !window.IS_LOGGED_IN) {
      const loginModal = document.getElementById("loginModal");
      if (loginModal) {
        loginModal.classList.remove("modal-hidden");
      }
      return;
    }

    const modal = document.getElementById("bookingModal");

    // Remove classes that hide the modal
    modal.classList.remove("modal-hidden", "hidden");

    // Optional: force it to display flex
    modal.classList.add("flex");

    // Re-attach form submit handler every time modal is opened
    const bookingForm = document.getElementById("bookingForm");
    if (bookingForm) {
      // Remove existing listener if any
      const newBookingForm = bookingForm.cloneNode(true);
      bookingForm.parentNode.replaceChild(newBookingForm, bookingForm);

      // Attach new listener
      newBookingForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const startTime = this.querySelector("#event_time").value;
        const endTime = this.querySelector("#event_end_time").value;
        const courtNum = this.querySelector("#court_number").value;

        // Check for conflicts before proceeding
        if (hasConflictingBookings(startTime, endTime, courtNum)) {
          alert(
            "Cannot book this time range. One or more slots between your selected times are already booked."
          );
          return;
        }

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
    }

    const date = btn.dataset.date;
    const time = btn.dataset.time;
    // Normalize time to HH:MM:SS if needed (some data-time values may be HH:MM)
    const normTime = time && time.split(":").length === 2 ? time + ":00" : time;
    const court = btn.dataset.court;

    modal.querySelector("#event_date").value = date;
    // store normalized time (with seconds) so server receives consistent format
    modal.querySelector("#event_time").value = normTime;
    modal.querySelector("#court_number").value = court;

    // Function to check if a slot between start and end is already booked
    function hasConflictingBookings(startTime, endTime, courtNum) {
      const slotKeys = Object.keys(TIMES);
      const startIdx = slotKeys.indexOf(startTime);
      const endIdx = slotKeys.indexOf(endTime);

      if (startIdx === -1 || endIdx === -1) return false;

      // Check each slot between start and end (inclusive of start, exclusive of end)
      for (let i = startIdx; i < endIdx; i++) {
        const timeSlot = slotKeys[i];
        // Find the button for this court and time slot
        const slotBtn = document.querySelector(
          `.openBookingModal[data-court="${courtNum}"][data-time="${timeSlot}"]`
        );
        // If button not found (already booked/pending) or its parent has a "Booked" or "Pending" status text
        if (
          !slotBtn ||
          slotBtn
            .closest(".flex")
            .querySelector(".text-red-500, .text-yellow-500")
        ) {
          return true; // Conflict found
        }
      }
      return false;
    }

    // Populate end-time options based on available slot times.
    // Keep this list in sync with server (fetch_courts.php)
    const TIMES = {
      "08:00:00": "8:00 AM",
      "09:00:00": "9:00 AM",
      "10:00:00": "10:00 AM",
      "11:00:00": "11:00 AM",
      "12:00:00": "12:00 PM",
      "13:00:00": "1:00 PM",
      "14:00:00": "2:00 PM",
      "15:00:00": "3:00 PM",
      "16:00:00": "4:00 PM",
      // Allow end-time up to 5:00 PM (end boundary for last slot 4:00-5:00)
      "17:00:00": "5:00 PM",
    };

    const endSelect = modal.querySelector("#event_end_time");
    if (endSelect) {
      // Clear and add options that are strictly after the start time
      endSelect.innerHTML = '<option value="">Select end time</option>';
      const slotKeys = Object.keys(TIMES);
      // find index of start time
      const startIdx = slotKeys.indexOf(normTime);
      if (startIdx !== -1) {
        // allow end times that are after the selected start (so minimum 1 hour)
        for (let i = startIdx + 1; i < slotKeys.length; i++) {
          const k = slotKeys[i];
          const opt = document.createElement("option");
          opt.value = k;
          opt.textContent = TIMES[k];
          endSelect.appendChild(opt);
        }
        // default end time to next slot
        if (startIdx + 1 <= slotKeys.length - 1) {
          endSelect.value = slotKeys[startIdx + 1];
        }
      }
    }

    feather.replace();
  });

const closeBookingModalBtn = document.getElementById("closeBookingModal");
if (closeBookingModalBtn) {
  closeBookingModalBtn.addEventListener("click", function () {
    const modal = document.getElementById("bookingModal");
    if (modal) {
      modal.classList.add("modal-hidden", "hidden");
    }
  });
}

document.addEventListener("click", function (e) {
  const closeBtn = e.target.closest("#closeBookingModal");
  if (closeBtn) {
    const modal = document.getElementById("bookingModal");
    modal.classList.add("modal-hidden", "hidden");
  }
});

// -----------------
// Validate participants input
// -----------------
document.addEventListener("input", function (e) {
  if (e.target.id === "num_of_participants") {
    let value = parseInt(e.target.value);
    
    // If value exceeds 100, set it to 100
    if (value > 100) {
      e.target.value = 100;
    }
    // If value is less than 1, set it to 1
    else if (value < 1 && e.target.value !== "") {
      e.target.value = 1;
    }
  }
});

// Prevent typing more than 3 digits in participants field
document.addEventListener("keydown", function (e) {
  if (e.target.id === "num_of_participants") {
    const value = e.target.value;
    
    // Allow backspace, delete, tab, escape, enter
    if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
        // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (e.keyCode === 65 && e.ctrlKey === true) ||
        (e.keyCode === 67 && e.ctrlKey === true) ||
        (e.keyCode === 86 && e.ctrlKey === true) ||
        (e.keyCode === 88 && e.ctrlKey === true)) {
      return;
    }
    
    // Prevent if already 3 digits and trying to add more
    if (value.length >= 3) {
      e.preventDefault();
    }
    
    // Only allow numbers
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
      e.preventDefault();
    }
  }
});

// -----------------
// Handle login modal close button in schedule page
// -----------------
document.addEventListener("click", function (e) {
  // Check if clicked element is the close button inside login modal
  if (e.target.closest("#closeModal") && e.target.closest("#loginModal")) {
    e.preventDefault();
    e.stopPropagation();
    const loginModal = document.getElementById("loginModal");
    if (loginModal) {
      loginModal.classList.add("modal-hidden");
    }
  }
});

// -----------------
// Handle receipt modal
// -----------------
let currentReceiptData = null;

// Open receipt modal
document.addEventListener("click", function (e) {
  const btn = e.target.closest(".view-receipt-btn");
  if (!btn) return;

  const bookingId = btn.dataset.bookingId;
  if (!bookingId) return;

  const modal = document.getElementById("receiptModal");
  const content = document.getElementById("receiptContent");

  if (!modal || !content) return;

  // Show modal
  modal.classList.remove("hidden");

  // Reset content to loading state
  content.innerHTML = `
    <div class="text-center text-gray-500">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-2"></div>
      Loading receipt...
    </div>
  `;

  // Fetch receipt data
  fetch(`fetch_receipt.php?booking_id=${bookingId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success && data.receipt) {
        currentReceiptData = data.receipt;
        content.innerHTML = `
          <div class="space-y-4">
            <div class="text-center border-b border-gray-200 pb-4">
              <h2 class="text-xl font-bold text-primary">BOOKING RECEIPT</h2>
              <p class="text-sm text-gray-600">Transaction ID: <span class="font-mono font-bold">${data.receipt.transaction_id || 'N/A'}</span></p>
              <p class="text-sm text-gray-600">Date: <span class="font-mono font-bold">${data.receipt.date_now}</span></p>
            </div>
            
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">Customer:</span>
                <span class="font-bold">${data.receipt.customer_name}</span>
              </div>
              
              <div class="flex justify-between">
                <span class="text-gray-600">Activity:</span>
                <span class="font-bold">${data.receipt.title}</span>
              </div>
              
              <div class="flex justify-between">
                <span class="text-gray-600">Court:</span>
                <span class="font-bold">Court ${data.receipt.court_number}</span>
              </div>
              
              <div class="flex justify-between">
                <span class="text-gray-600">Date:</span>
                <span class="font-bold">${data.receipt.event_date}</span>
              </div>
              
              <div class="flex justify-between">
                <span class="text-gray-600">Time:</span>
                <span class="font-bold">${data.receipt.event_time}</span>
              </div>
              
              <div class="flex justify-between">
                <span class="text-gray-600">Participants:</span>
                <span class="font-bold">${data.receipt.num_of_participants}</span>
              </div>
              
              <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold">
                <span>Total Amount:</span>
                <span class="text-green-600">PHP ${data.receipt.total_fee}</span>
              </div>
              
              <div class="bg-gray-50 p-3 rounded-lg text-center">
                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium ${data.receipt.status === 'CONFIRMED' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                  ${data.receipt.status}
                </span>
              </div>
            </div>
          </div>
        `;
      } else {
        content.innerHTML = `<p class="text-red-600 text-center">Error loading receipt. Please try again.</p>`;
      }
    })
    .catch(error => {
      console.error('Error fetching receipt:', error);
      content.innerHTML = `<p class="text-red-600 text-center">Error loading receipt. Please try again.</p>`;
    });
});

// Close receipt modal
document.addEventListener("click", function (e) {
  if (e.target.closest("#closeReceiptModal")) {
    document.getElementById("receiptModal").classList.add("hidden");
  }
  
  // Close if clicking outside modal
  if (e.target.id === "receiptModal") {
    document.getElementById("receiptModal").classList.add("hidden");
  }
});

// Download receipt
document.addEventListener("click", function (e) {
  if (e.target.closest("#downloadReceiptBtn")) {
    const content = document.getElementById("receiptContent");
    const btn = e.target.closest("#downloadReceiptBtn");
    
    if (!content || !currentReceiptData) return;
    
    btn.innerHTML = "Generating...";
    btn.disabled = true;
    
    // Create a temporary element for html2canvas
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = content.innerHTML;
    tempDiv.style.background = 'white';
    tempDiv.style.padding = '20px';
    tempDiv.style.width = '400px';
    tempDiv.style.position = 'absolute';
    tempDiv.style.left = '-9999px';
    document.body.appendChild(tempDiv);
    
    // Use html2canvas if available
    if (typeof html2canvas !== 'undefined') {
      html2canvas(tempDiv, { scale: 2, useCORS: true }).then(canvas => {
        const dataUrl = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = `receipt-booking-${currentReceiptData.booking_id}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        document.body.removeChild(tempDiv);
        btn.innerHTML = "Download Receipt";
        btn.disabled = false;
      }).catch(err => {
        console.error('Error generating receipt:', err);
        document.body.removeChild(tempDiv);
        btn.innerHTML = "Error - Try again";
        btn.disabled = false;
        setTimeout(() => {
          btn.innerHTML = "Download Receipt";
        }, 2000);
      });
    } else {
      // Fallback: open in new window for printing
      const newWindow = window.open('', '_blank');
      newWindow.document.write(`
        <html>
          <head><title>Receipt</title></head>
          <body style="font-family: Arial, sans-serif; padding: 20px;">
            ${content.innerHTML}
          </body>
        </html>
      `);
      newWindow.document.close();
      newWindow.print();
      document.body.removeChild(tempDiv);
      btn.innerHTML = "Download Receipt";
      btn.disabled = false;
    }
  }
});
