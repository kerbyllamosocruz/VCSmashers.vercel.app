<?php
require_once "../config/config.php";

// Total Bookings
$total_bookings_result = $conn->query("SELECT COUNT(*) as count FROM bookings");
$total_bookings = $total_bookings_result->fetch_assoc()['count'];

// Total Users
$total_users_result = $conn->query("SELECT COUNT(*) as count FROM users");
$total_users = $total_users_result->fetch_assoc()['count'];

// Total Revenue
$total_revenue_result = $conn->query("SELECT SUM(total_fee) as total FROM bookings WHERE status = 'COMPLETED'");
$total_revenue = $total_revenue_result->fetch_assoc()['total'];

// Booking activity - last 14 days (fill zeros for missing days)
$days = [];
for ($i = 13; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-{$i} days"));
    $days[$d] = 0;
}
$day_res = $conn->query("SELECT DATE(event_date) as dt, COUNT(*) as cnt FROM bookings WHERE event_date >= CURDATE() - INTERVAL 13 DAY GROUP BY dt ORDER BY dt");
if ($day_res) {
    while ($r = $day_res->fetch_assoc()) {
        $days[$r['dt']] = (int)$r['cnt'];
    }
}
$bookings_days_labels = array_keys($days);
$bookings_days_data = array_values($days);

// Booking activity - last 12 months
$months = [];
for ($i = 11; $i >= 0; $i--) {
    $m = date('Y-m', strtotime("-{$i} months"));
    $months[$m] = 0;
}
$month_res = $conn->query("SELECT DATE_FORMAT(event_date, '%Y-%m') as ym, COUNT(*) as cnt FROM bookings WHERE event_date >= DATE_FORMAT(CURDATE() - INTERVAL 11 MONTH, '%Y-%m-01') GROUP BY ym ORDER BY ym");
if ($month_res) {
    while ($r = $month_res->fetch_assoc()) {
        $months[$r['ym']] = (int)$r['cnt'];
    }
}
$bookings_months_labels = array_keys($months);
$bookings_months_data = array_values($months);

// Court utilization (exclude cancelled bookings)
$courts = [1 => 0, 2 => 0, 3 => 0];
$court_res = $conn->query("SELECT court_number, COUNT(*) as cnt FROM bookings WHERE status != 'CANCELLED' GROUP BY court_number");
if ($court_res) {
    while ($r = $court_res->fetch_assoc()) {
        $num = (int)$r['court_number'];
        if (isset($courts[$num])) {
            $courts[$num] = (int)$r['cnt'];
        } else {
            $courts[$num] = (int)$r['cnt'];
        }
    }
}
$court_labels = array_map(function ($n) {
    return 'Court ' . $n;
}, array_keys($courts));
$court_data = array_values($courts);

?>
<h1 class="text-3xl font-bold text-primary mb-6">Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 overflow-visible">
        <h3 class="text-xl font-bold text-accent">Total Bookings</h3>
        <p class="text-4xl font-bold mt-2"><?php echo $total_bookings; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 overflow-visible">
        <h3 class="text-xl font-bold text-accent">Total Users</h3>
        <p class="text-4xl font-bold mt-2"><?php echo $total_users; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-accent">Revenue</h3>
        <p class="text-4xl font-bold mt-2">&#8369; <?php echo number_format($total_revenue, 2); ?></p>
    </div>
</div>

<!-- Charts -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-accent mb-4">Booking Activity (Last 14 days)</h3>
        <canvas id="bookingsActivityChart"></canvas>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-accent mb-4">Court Utilization</h3>
        <canvas id="courtUtilizationChart"></canvas>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-2 border border-gray-200 overflow-visible">
        <h3 class="text-xl font-bold text-accent mb-4">Booking Activity (Last 12 months)</h3>
        <canvas id="bookingsMonthlyChart"></canvas>
    </div>
</div>

<!-- Chart.js (pinned version) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<!-- html2canvas + jsPDF for client-side PDF export -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<link rel="icon" type="image/x-icon" href="../Assets/logo.png" />
<script>
    // Raw data from PHP
    const rawDays = <?php echo json_encode($bookings_days_labels); ?>;
    const daysData = <?php echo json_encode($bookings_days_data); ?>;
    const rawMonths = <?php echo json_encode($bookings_months_labels); ?>;
    const monthsData = <?php echo json_encode($bookings_months_data); ?>;
    const courtLabels = <?php echo json_encode($court_labels); ?>;
    const courtData = <?php echo json_encode($court_data); ?>;

    const daysLabels = rawDays.map(d => {
        try {
            const dt = new Date(d + 'T00:00:00');
            return dt.toLocaleDateString(undefined, {
                day: '2-digit',
                month: 'short'
            });
        } catch (e) {
            return d;
        }
    });

    const monthsLabels = rawMonths.map(m => {
        try {
            const dt = new Date(m + '-01T00:00:00');
            return dt.toLocaleDateString(undefined, {
                month: 'short',
                year: 'numeric'
            });
        } catch (e) {
            return m;
        }
    });

    document.querySelectorAll('canvas').forEach(c => {
        const parent = c.parentElement;
        if (parent && !parent.style.height) parent.style.height = '220px';
    });

    const ctxDays = document.getElementById('bookingsActivityChart').getContext('2d');
    new Chart(ctxDays, {
        type: 'line',
        data: {
            labels: daysLabels,
            datasets: [{
                label: 'Bookings',
                data: daysData,
                backgroundColor: 'rgba(59,130,246,0.15)',
                borderColor: 'rgba(59,130,246,1)',
                borderWidth: 3,
                pointBorderColor: 'rgba(255,255,255,0.9)',
                pointBackgroundColor: 'rgba(59,130,246,1)',
                fill: true,
                tension: 0.25,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    bottom: 24
                }
            },
            plugins: {
                legend: {
                    labels: {
                        boxWidth: 12
                    }
                }
            },
            scales: {
                x: {
                    display: true
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Court utilization (doughnut)
    const ctxCourt = document.getElementById('courtUtilizationChart').getContext('2d');
    new Chart(ctxCourt, {
        type: 'doughnut',
        data: {
            labels: courtLabels,
            datasets: [{
                label: 'Utilization',
                data: courtData,
                backgroundColor: [
                    'rgba(99,102,241,0.85)',
                    'rgba(16,185,129,0.85)',
                    'rgba(249,115,22,0.85)'
                ],
                borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                borderWidth: 2,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    bottom: 24
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12
                    }
                }
            }
        }
    });

    // Bookings per month (bar)
    const ctxMonths = document.getElementById('bookingsMonthlyChart').getContext('2d');
    new Chart(ctxMonths, {
        type: 'bar',
        data: {
            labels: monthsLabels,
            datasets: [{
                label: 'Bookings',
                data: monthsData,
                backgroundColor: 'rgba(14,165,233,0.78)',
                borderColor: 'rgba(3,105,161,1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    bottom: 24
                }
            },
            plugins: {
                legend: {
                    labels: {
                        boxWidth: 12
                    }
                }
            },
            scales: {
                x: {
                    display: true
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>

<!-- Export panel -->
<div class="mt-6 bg-white p-6 rounded-lg shadow-md border border-gray-200">
    <h3 class="text-lg font-bold mb-2">Generate reservation logs & reports</h3>
    <div class="flex flex-col md:flex-row md:items-end gap-3">
        <div>
            <label class="block text-sm">From</label>
            <input id="report_from" type="date" class="border rounded px-2 py-1" />
        </div>
        <div>
            <label class="block text-sm">To</label>
            <input id="report_to" type="date" class="border rounded px-2 py-1" />
        </div>
        <div>
            <label class="block text-sm">Status (optional)</label>
            <select id="report_status" class="border rounded px-2 py-1">
                <option value="">All</option>
                <option value="CONFIRMED">CONFIRMED</option>
                <option value="COMPLETED">COMPLETED</option>
                <option value="CANCELLED">CANCELLED</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button id="exportCsvBtn" class="bg-primary text-white px-4 py-2 rounded">Export CSV (Excel)</button>
            <button id="exportPdfBtn" class="bg-gray-800 text-white px-4 py-2 rounded">Export PDF</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        const from = document.getElementById('report_from').value;
        const to = document.getElementById('report_to').value;
        const status = document.getElementById('report_status').value;
        const params = new URLSearchParams();
        if (from) params.set('from', from);
        if (to) params.set('to', to);
        if (status) params.set('status', status);
        params.set('format', 'csv');
        // navigate to the CSV download
        window.location = 'export_reservations.php?' + params.toString();
    });

    // Export PDF: fetch JSON and render table, then convert to PDF via html2canvas + jsPDF
    document.getElementById('exportPdfBtn').addEventListener('click', async function() {
        const from = document.getElementById('report_from').value;
        const to = document.getElementById('report_to').value;
        const status = document.getElementById('report_status').value;
        const params = new URLSearchParams();
        if (from) params.set('from', from);
        if (to) params.set('to', to);
        if (status) params.set('status', status);
        params.set('format', 'json');
        const res = await fetch('export_reservations.php?' + params.toString());
        if (!res.ok) {
            alert('Failed to fetch reservation data');
            return;
        }
        const data = await res.json();

        // Build a table element
        const container = document.createElement('div');
        container.style.padding = '20px';
        container.style.background = '#fff';
        const title = document.createElement('h2');
        title.textContent = 'Badminton Reservation Report';
        title.style.marginBottom = '12px';
        container.appendChild(title);
        const info = document.createElement('div');
        info.textContent = `Generated: ${new Date().toLocaleString()}`;
        info.style.marginBottom = '8px';
        container.appendChild(info);

        const tbl = document.createElement('table');
        tbl.style.borderCollapse = 'collapse';
        tbl.style.width = '100%';
        tbl.style.fontSize = '12px';
        tbl.style.minWidth = 'fit-content';
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        const headers = ['Booking ID', 'User ID', 'Title', 'Activity', 'Court', 'Participants', 'Total Fee', 'Event Date', 'Start', 'End', 'Status'];
        headers.forEach(h => {
            const th = document.createElement('th');
            th.textContent = h;
            th.style.border = '1px solid #ddd';
            th.style.padding = '6px';
            th.style.background = '#f7f7f7';
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        tbl.appendChild(thead);
        const tbody = document.createElement('tbody');
        data.forEach(row => {
            const tr = document.createElement('tr');
            const cells = [row.booking_id, row.user_id, row.title, row.activity_name, row.court_number, row.num_of_participants, row.total_fee, row.event_date, row.event_time, row.event_end_time, row.status];
            cells.forEach(c => {
                const td = document.createElement('td');
                td.textContent = c ?? '';
                td.style.border = '1px solid #ddd';
                td.style.padding = '12px';
                td.style.fontSize = '12px';
                td.style.whiteSpace = 'nowrap';
                td.style.wordBreak = 'break-all';
                td.className = 'md:break-words [@media(min-width:1100px)]:break-normal';
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        tbl.appendChild(tbody);
        container.appendChild(tbl);

        document.body.appendChild(container);

        try {
            const canvas = await html2canvas(container, {
                scale: 2
            });
            const imgData = canvas.toDataURL('image/png');
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'landscape',
                unit: 'pt',
                format: 'a4'
            });
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pageWidth - 40;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            pdf.addImage(imgData, 'PNG', 20, 20, pdfWidth, pdfHeight);
            pdf.save('reservation-report.pdf');
        } catch (e) {
            alert('Failed to generate PDF: ' + e.message);
        }

        document.body.removeChild(container);
    });
</script>