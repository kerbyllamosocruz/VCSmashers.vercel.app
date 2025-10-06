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