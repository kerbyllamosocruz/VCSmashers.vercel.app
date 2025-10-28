document.addEventListener('DOMContentLoaded', () => {
    const bookingTabs = document.getElementById('booking-tabs');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.booking-tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.dataset.tab;

            tabButtons.forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            button.classList.add('border-primary', 'text-primary');
            button.classList.remove('border-transparent', 'text-gray-500');

            tabContents.forEach(content => {
                if (content.id === `${tab}-bookings`) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        });
    });

    // Set initial active tab
    if (tabButtons.length > 0) {
        tabButtons[0].click();
    }

    // Handle cancel booking button click
    document.querySelectorAll('.cancel-booking-btn').forEach(button => {
        button.addEventListener('click', async (event) => {
            const bookingId = event.target.dataset.bookingId;
            if (confirm('Are you sure you want to cancel this booking?')) {
                try {
                    const response = await fetch('cancel_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ booking_id: bookingId })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('Booking cancelled successfully!');
                        // Optionally, remove the cancelled booking from the DOM or update its status
                        event.target.closest('.border').remove(); // Remove the booking card
                        window.location.reload();
                    } else {
                        alert('Failed to cancel booking: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error cancelling booking:', error);
                    alert('An error occurred while cancelling the booking.');
                }
            }
        });
    });
});