<div id="bookingModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-center items-center mb-4 relative">
                <h3 class="text-2xl font-bold text-primary text-center">Book an Activity</h3>
                <button id="closeBookingModal" class="absolute right-0 text-gray-500 hover:text-gray-700">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="bookingForm" method="POST" class="space-y-4">
                <input type="hidden" id="event_date" name="event_date">
                <input type="hidden" id="event_time" name="event_time">
                <!-- court_number is provided by the page (fetch_courts) -->
                <input type="hidden" id="court_number" name="court_number">

                <div>
                    <label for="title" class="block text-sm font-medium text-accent">Title</label>
                    <input type="text" id="title" name="title" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary text-accent" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-accent">Description</label>
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary text-accent"></textarea>
                </div>

                <div>
                    <label for="activity_name" class="block text-sm font-medium text-accent">Activity</label>
                    <select id="activity_name" name="activity_name" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary text-accent">
                        <option value="Pickleball">Pickleball</option>
                        <option value="Badminton">Badminton</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="event_end_time" class="block text-sm font-medium text-accent">End Time</label>
                        <select id="event_end_time" name="event_end_time" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary text-accent">
                            <option value="">Select end time</option>
                            <!-- options are populated dynamically based on chosen start time -->
                        </select>
                    </div>

                    <div class="flex-1">
                        <label for="num_of_participants" class="block text-sm font-medium text-accent">No. of participants</label>
                        <input type="number" id="num_of_participants" name="num_of_participants" min="1" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary text-accent" />
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Book Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>