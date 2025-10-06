<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = '../Landing Repository/LandingPage.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];
require_once '../Functions/Queries.php';


require_once '../Functions/MedicineCard.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIAIMS | Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Stylesheet/Design.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class='bx bx-menu'></i>
                </button>
                <h1 id="pageTitle" style="color: #002e2d;">ADMIN DASHBOARD</h1>
            </header>
            
            <!-- Contents -->
            <div class="content-container">
            <section class="content-section active" id="dashboardSection">
                <div class="top-container flex items-center justify-center gap-4">
                    <!-- Status Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full mb-10 h-[450px] w-full">

                        <!-- Total Medicines -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-3xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border border-green-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-lg text-green-800 tracking-wide">Total Medicines</h3>
                                <svg class="h-10 w-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    </svg>
                            </div>
                            <div class="mt-2">
                                <div class="text-5xl font-extrabold text-green-700 leading-tight"><?= $total_meds_count ?></div>
                                <p class="text-base text-green-500 mt-1">Items in inventory</p>
                            </div>
                        </div>

                        <!-- Low Stock -->
                        <div class="bg-gradient-to-br from-green-50 to-yellow-200 rounded-3xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border border-green-200">
                            <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-gray-700">Low Stock Items</h3>
                            <i class="bx bx-error text-4xl text-yellow-500"></i>
                            </div>
                            <div class="mt-4">
                            <div class="text-5xl font-extrabold text-yellow-400 leading-tight"><?= $low_stock_count ?></div>
                            <p class="text-sm text-gray-500">Requires restocking</p>
                            </div>
                        </div>

                        <!-- Near Expiry -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-200 rounded-3xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border border-green-200">
                            <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-gray-700">Near Expiry</h3>
                            <i class="bx bx-calendar-exclamation text-4xl text-orange-500"></i>
                            </div>
                            <div class="mt-4">
                            <div class="text-5xl font-extrabold text-orange-400 leading-tight"><?= $near_expiry_count ?></div>
                            <p class="text-sm text-gray-500">Approaching expiry</p>
                            </div>
                        </div>

                        <!-- Expired -->
                        <div class="bg-gradient-to-br from-red-50 to-red-200 rounded-3xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border border-green-200">
                            <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-gray-700">Expired</h3>
                            <i class="bx bx-trash text-4xl text-red-500"></i>
                            </div>
                            <div class="mt-4">
                            <div class="text-5xl font-extrabold text-red-500 leading-tight"><?= $expired_count ?></div>
                            <p class="text-sm text-gray-500">Should be disposed</p>
                            </div>
                        </div>

                        <!-- Users Total (Improved Design) -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-500 rounded-3xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border border-green-200">
                            <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-gray-700">Users Overview</h3>
                            <i class="bx bx-group text-4xl text-green-600"></i>
                            </div>
                            <div class="mt-6 grid grid-cols-3 gap-4 text-center">
                            <div class="bg-white rounded-xl shadow p-3">
                                <i class="bx bxs-graduation text-green-500 text-2xl"></i>
                                <p class="mt-1 text-xs text-gray-500">Students</p>
                                <div class="text-lg font-bold text-gray-700"><?= $student_count; ?></div>
                            </div>
                            <div class="bg-white rounded-xl shadow p-3">
                                <i class="bx bxs-user-badge text-blue-500 text-2xl"></i>
                                <p class="mt-1 text-xs text-gray-500">Admins</p>
                                <div class="text-lg font-bold text-gray-700"><?= $clinicPersonnel_count; ?></div>
                            </div>
                            <div class="bg-white rounded-xl shadow p-3">
                                <i class="bx bxs-user text-purple-500 text-2xl"></i>
                                <p class="mt-1 text-xs text-gray-500">Staff</p>
                                <div class="text-lg font-bold text-gray-700">15</div>
                            </div>
                            </div>
                        </div>

                        <!-- Check-In Counts -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-3xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border border-green-200">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-lg text-gray-700">Check-In Counts</h3>
                                <i class="bx bx-calendar-check text-4xl text-green-500"></i>
                            </div>
                            <div class="mt-4">
                                <div class="text-5xl font-extrabold text-green-700 leading-tight"><?= $clinicPersonnel_count; ?></div>
                                <p class="text-sm text-gray-500">Students checked in today</p>
                            </div>
                        </div>

                    </div>

                    <div class="card chart-card min-w-[400px] max-w-[900px] h-[450px] bg-white p-2 rounded-2xl shadow-lg overflow-hidden">
                        <!-- Top: Quick Scan Header -->
                        <div class="rounded-2xl bg-gradient-to-r from-green-700 to-green-900 p-4 shadow-md cursor-pointer mb-2">
                            <div class="flex items-center gap-4">
                                <!-- Icon -->
                                <div class="bg-white rounded-full p-3 shadow-inner flex items-center justify-center">
                                    <i class="bx bx-barcode text-green-800 text-2xl"></i>
                                </div>
                                <!-- Text -->
                                <div>
                                    <h1 class="text-white text-xl font-bold tracking-wide uppercase">Quick Student Scan</h1>
                                    <p class="text-green-200 text-sm mt-1">Scan student IDs quickly and efficiently</p>
                                </div>
                            </div>
                        </div>

                        <!-- Middle: Card Header -->
                        <div class="px-4 py-2 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-gray-700 font-semibold text-lg">Common Reasons For Visit</h2>
                        </div>

                        <!-- Bottom: Chart -->
                        <div class="card-content p-4 h-[calc(100%-128px)] bg-green-50 flex items-center justify-center">
                            <div class="chart-container w-full h-full rounded-xl bg-green-100 p-2 shadow-inner">
                                <canvas id="visitReasonsChart" class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
                

                

                <div class="card">
                    <div class="card-header">
                        <h2>Recent Clinic Visits</h2>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                                <!-- Search Bar -->
                                <div class="search-container" style="position: relative;">
                                    <i class='bx bx-search' style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                                    <input type="text" id="studentSearch" placeholder="Search students..." style="padding: 0.5rem 1rem 0.5rem 2rem; border: 1px solid #d1d5db; border-radius: 0.375rem; width: 250px;">
                                </div>
                                
                                <!-- Entries Filter -->
                                <div class="entries-filter" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 0.875rem; color: #4b5563;">Show</span>
                                    <select id="entriesFilter" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span style="font-size: 0.875rem; color: #4b5563;">entries</span>
                                </div>
                            </div>
                    </div>
                    <div class="card-content">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>    
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Reason for Visit</th>
                                        <th>Status/Outcome</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT sc.DateTime as DT, s.School_ID as SID, s.FirstName as FN, s.LastName as LN, sc.Reason as R, sc.Status as S FROM studentcheckins sc JOIN student s ON sc.StudentID = s.School_ID ORDER BY ID DESC";
                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["DT"] . "</td>";
                                            echo "<td>" . $row["SID"] . "</td>";
                                            echo "<td>" . $row["FN"] . " " . $row["LN"] . "</td>";
                                            echo "<td>" . $row["R"] . "</td>";
                                            echo "<td>" . $row["S"] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No recent clinic visits</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            </div>
        </main>
    </div>
</body>

<!-- Add Chart.js library with plugins -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.main-content');
        
        // Toggle sidebar
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        
            // For mobile
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
            }
        });
    
    console.log('Doctor Management System initialized successfully!');
    });
    
    
    // Register the datalabels plugin
    Chart.register(ChartDataLabels);

    // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading state
        const chartContainer = document.querySelector('.card-content');
        chartContainer.innerHTML = `
            <div class="chart-loading" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                <div class="spinner" style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            </div>
            <canvas id="visitReasonsChart" style="display: none;"></canvas>
        `;
        
        // Fetch visit reasons data from the server
        fetch('../Functions/patientFunctions.php?action=getVisitReasons')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.reasons.length > 0) {
                    const reasons = data.reasons;
                    
                    // Prepare data for the chart
                    const labels = reasons.map(item => item.reason);
                    const counts = reasons.map(item => item.count);
                    const totalVisits = counts.reduce((a, b) => a + b, 0);
                    
                    // Generate gradient colors
                    const { backgroundColors, borderColors } = generateGradientColors(labels.length);
                    
                    // Get the canvas element and show it
                    const chartElement = document.querySelector('#visitReasonsChart');
                    chartElement.style.display = 'block';
                    document.querySelector('.chart-loading').style.display = 'none';
                    
                    const ctx = chartElement.getContext('2d');
                    
                    // Create gradient for bars
                    function createGradient(ctx, chartArea, colors, index) {
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, colors[index].start);
                        gradient.addColorStop(1, colors[index].end);
                        return gradient;
                    }
                    
                    // Create the pie chart
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: counts,
                                backgroundColor: backgroundColors.map(bg => bg.start),
                                borderColor: '#ffffff',
                                borderWidth: 2,
                                hoverOffset: 15,
                                hoverBorderColor: '#ffffff',
                                hoverBorderWidth: 2,
                                weight: 0.5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            radius: '90%',
                            layout: {
                                padding: 20
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true,
                                duration: 1000,
                                easing: 'easeOutQuart'
                            },
                            plugins: {
                                legend: {
                                    position: 'right',
                                    align: 'center',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            family: 'Inter, sans-serif',
                                            size: 12
                                        },
                                        generateLabels: function(chart) {
                                            const data = chart.data;
                                            if (data.labels.length && data.datasets.length) {
                                                return data.labels.map((label, i) => {
                                                    const meta = chart.getDatasetMeta(0);
                                                    const ds = data.datasets[0];
                                                    const arc = meta.data[i];
                                                    const custom = arc && arc.custom || {};
                                                    const value = chart.data.labels && i < chart.data.labels.length ? 
                                                        `${label}: ${ds.data[i]} (${((ds.data[i] / totalVisits) * 100).toFixed(1)}%)` : 
                                                        '';
                                                    
                                                    return {
                                                        text: value,
                                                        fillStyle: ds.backgroundColor[i],
                                                        strokeStyle: ds.borderColor,
                                                        lineWidth: 1,
                                                        hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                                                        index: i
                                                    };
                                                });
                                            }
                                            return [];
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(33, 37, 41, 0.95)',
                                    titleFont: {
                                        family: 'Inter, sans-serif',
                                        size: 13,
                                        weight: '600'
                                    },
                                    bodyFont: {
                                        family: 'Inter, sans-serif',
                                        size: 13
                                    },
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: false,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const percentage = ((value / totalVisits) * 100).toFixed(1);
                                            return [
                                                `${label}: ${value} visit${value !== 1 ? 's' : ''}`,
                                                `(${percentage}% of total)`
                                            ];
                                        }
                                    }
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value * 100) / total).toFixed(1) + '%';
                                        return value > total * 0.05 ? percentage : '';
                                    },
                                    color: '#fff',
                                    font: {
                                        family: 'Inter, sans-serif',
                                        size: 11,
                                        weight: '600'
                                    },
                                    textAlign: 'center',
                                    textShadowColor: 'rgba(0,0,0,0.3)',
                                    textShadowBlur: 5
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            onHover: (event, chartElement) => {
                                const target = event.native.target;
                                target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                            },
                            onClick: (event, elements) => {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const label = this.data.labels[index];
                                    console.log('Clicked on:', label);
                                    // You can add custom click behavior here
                                }
                            }
                        }
                    });
                    
                    // Add total visits badge
                    const cardHeader = document.querySelector('.card-header');
                    const badge = document.createElement('div');
                    badge.className = 'total-visits-badge';
                    badge.innerHTML = `
                        <span class="total-count">${totalVisits}</span>
                        <span class="total-label">Total Visits</span>
                    `;
                    cardHeader.appendChild(badge);
                    
                    // Add styles
                    const style = document.createElement('style');
                    style.textContent = `
                        .card {
                            position: relative;
                            background: #ffffff;
                            border-radius: 12px;
                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                            overflow: hidden;
                            transition: transform 0.3s ease, box-shadow 0.3s ease;
                        }
                        .card:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                        }
                        .card-header {
                            padding: 1.25rem 1.5rem;
                            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            position: relative;
                        }
                        .card-header h2 {
                            margin: 0;
                            font-size: 1.25rem;
                            font-weight: 600;
                            color: #2d3436;
                            font-family: 'Inter', sans-serif;
                        }
                        .card-content {
                            padding: 1.5rem;
                            position: relative;
                        }
                        .total-visits-badge {
                            background: #f8f9fa;
                            border-radius: 20px;
                            padding: 0.35rem 0.75rem;
                            display: flex;
                            align-items: center;
                            gap: 0.5rem;
                            font-family: 'Inter', sans-serif;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                        }
                        .total-count {
                            font-weight: 700;
                            color: #3498db;
                            font-size: 1rem;
                        }
                        .total-label {
                            font-size: 0.75rem;
                            color: #6c757d;
                            font-weight: 500;
                        }
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        /* Custom scrollbar for chart tooltip */
                        .chartjs-tooltip {
                            max-height: 300px;
                            overflow-y: auto;
                        }
                        .chartjs-tooltip::-webkit-scrollbar {
                            width: 6px;
                        }
                        .chartjs-tooltip::-webkit-scrollbar-track {
                            background: rgba(0, 0, 0, 0.05);
                            border-radius: 10px;
                        }
                        .chartjs-tooltip::-webkit-scrollbar-thumb {
                            background: rgba(0, 0, 0, 0.2);
                            border-radius: 10px;
                        }
                    `;
                    document.head.appendChild(style);
                    
                } else {
                    // Show no data message
                    const message = data.success ? 'No visit data available' : 'Failed to load data';
                    chartContainer.innerHTML = `
                        <div style="height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #6c757d; font-family: 'Inter', sans-serif;">
                            <i class='bx bx-pie-chart-alt' style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p>${message}</p>
                        </div>
                    `;
                    console.error('Failed to load visit reasons:', data.message || 'No data available');
                }
            })
            .catch(error => {
                console.error('Error fetching visit reasons:', error);
                chartContainer.innerHTML = `
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #dc3545; font-family: 'Inter', sans-serif;">
                        <i class='bx bx-error-circle' style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p>Error loading data. Please try again later.</p>
                    </div>
                `;
            });
        
        // Function to generate gradient colors for the chart
        function generateGradientColors(count) {
            const backgroundColors = [];
            const borderColors = [];
            
            const colorPalette = [
                { start: 'rgba(52, 152, 219, 0.8)', end: 'rgba(41, 128, 185, 0.8)' }, // Blue
                { start: 'rgba(46, 204, 113, 0.8)', end: 'rgba(39, 174, 96, 0.8)' }, // Green
                { start: 'rgba(155, 89, 182, 0.8)', end: 'rgba(142, 68, 173, 0.8)' }, // Purple
                { start: 'rgba(241, 196, 15, 0.8)', end: 'rgba(243, 156, 18, 0.8)' }, // Yellow
                { start: 'rgba(230, 126, 34, 0.8)', end: 'rgba(211, 84, 0, 0.8)' }, // Orange
                { start: 'rgba(231, 76, 60, 0.8)', end: 'rgba(192, 57, 43, 0.8)' }, // Red
                { start: 'rgba(26, 188, 156, 0.8)', end: 'rgba(22, 160, 133, 0.8)' }, // Turquoise
                { start: 'rgba(52, 73, 94, 0.8)', end: 'rgba(44, 62, 80, 0.8)' }, // Dark
                { start: 'rgba(149, 165, 166, 0.8)', end: 'rgba(127, 140, 141, 0.8)' }, // Gray
                { start: 'rgba(22, 160, 133, 0.8)', end: 'rgba(26, 188, 156, 0.8)' }  // Teal
            ];
            
            for (let i = 0; i < count; i++) {
                const colorIndex = i % colorPalette.length;
                backgroundColors.push(colorPalette[colorIndex]);
                borderColors.push({
                    start: colorPalette[colorIndex].start.replace('0.8', '1'),
                    end: colorPalette[colorIndex].end.replace('0.8', '1')
                });
            }
            
            return { backgroundColors, borderColors };
        }
    });
</script>

<!-- Check-in Modal -->
<?php include '../Modals/Checkin_modal.php'; ?>

</html>