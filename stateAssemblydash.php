<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>State Assembly Dashboard</title>
    <link rel="stylesheet" href="lad.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <h2>State Assembly Overview</h2>

    <!-- Loading Indicator -->
    <p id="loading">Loading data...</p>

    <!-- Dropdown to select state -->
    <label for="stateSelect">Select a State:</label>
    <select id="stateSelect" class="form-control">
        <option value="">All States</option>
    </select>

    <!-- No Data Message -->
    <p id="noData" style="display: none; color: red;">No data available for the selected state.</p>

    <!-- Charts -->
    <div class="chart-container">
        <canvas id="partyBarChart"></canvas>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const stateSelect = document.getElementById("stateSelect");
    const loading = document.getElementById("loading");
    const noDataMsg = document.getElementById("noData");

    fetch('fetch_state_assembly_data.php')
        .then(response => response.json())
        .then(data => {
            loading.style.display = "none"; // Hide loading message

            if (Object.keys(data).length === 0) {
                noDataMsg.style.display = "block";
                return;
            }

            populateDropdown(data);
            renderCharts(data);

            // Update charts when state is selected
            stateSelect.addEventListener("change", function () {
                let selectedState = this.value;
                let filteredData = selectedState ? { [selectedState]: data[selectedState] } : data;
                renderCharts(filteredData);
            });
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            loading.textContent = "Failed to load data.";
        });

    function populateDropdown(data) {
        let states = Object.keys(data);
        states.forEach(state => {
            let option = document.createElement("option");
            option.value = state;
            option.textContent = state;
            stateSelect.appendChild(option);
        });
    }

    function renderCharts(stateData) {
        let parties = {};
        Object.values(stateData).forEach(state => {
            state.forEach(item => {
                parties[item.party] = (parties[item.party] || 0) + item.count;
            });
        });

        let partyLabels = Object.keys(parties);
        let partyCounts = Object.values(parties);

        // Show/hide "No Data" message
        noDataMsg.style.display = partyLabels.length ? "none" : "block";

        // Destroy existing charts if they exist
        if (window.barChart) window.barChart.destroy();
        if (window.pieChart) window.pieChart.destroy();

        // Generate dynamic colors
        let colors = generateColors(partyLabels.length);

        // Bar Chart
        let barCtx = document.getElementById("partyBarChart").getContext("2d");
        window.barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: partyLabels,
                datasets: [{
                    label: "Number of Members",
                    data: partyCounts,
                    backgroundColor: colors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            }
        });

        // Pie Chart
        let pieCtx = document.getElementById("partyPieChart").getContext("2d");
        window.pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: partyLabels,
                datasets: [{
                    data: partyCounts,
                    backgroundColor: colors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" },
                    tooltip: { enabled: true }
                }
            }
        });
    }

    function generateColors(count) {
        const colors = ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#FFC300', '#8A33FF', '#33FFF5'];
        return Array.from({ length: count }, (_, i) => colors[i % colors.length]);
    }
});
</script>

</body>
</html>
