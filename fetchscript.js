document.addEventListener("DOMContentLoaded", function () {
    const stateSelect = document.getElementById("stateSelect");
    let data; // Declare globally to use across functions

    fetch("fetch_state_assembly_data.php")
        .then(response => response.json())
        .then(fetchedData => {
            data = fetchedData;
            let states = Object.keys(data);

            // Populate state dropdown
            states.forEach(state => {
                let option = document.createElement("option");
                option.value = state;
                option.textContent = state;
                stateSelect.appendChild(option);
            });

            // Render charts initially
            renderCharts(data);
        })
        .catch(error => console.error("Failed to load data:", error));

    stateSelect.addEventListener("change", function () {
        let selectedState = this.value;
        let filteredData = selectedState ? { [selectedState]: data[selectedState] } : data;
        renderCharts(filteredData);
        $("#membersTable").DataTable().ajax.url(`fetch_state_assembly_data.php?members=true&state=${selectedState}`).load();
    });

    // Function to Render Charts
    function renderCharts(stateData) {
        let parties = {};

        Object.values(stateData).forEach(state => {
            state.forEach(item => {
                parties[item.party] = (parties[item.party] || 0) + item.count;
            });
        });

        let partyLabels = Object.keys(parties);
        let partyCounts = Object.values(parties);

        if (window.barChart) window.barChart.destroy();
        if (window.pieChart) window.pieChart.destroy();

        // Bar Chart
        let barCtx = document.getElementById("partyBarChart").getContext("2d");
        window.barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: partyLabels,
                datasets: [{
                    label: "Number of Members",
                    data: partyCounts,
                    backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#FFC300'],
                }]
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
                    backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#FFC300'],
                }]
            }
        });
    }
});

$(document).ready(function () {
    let table = $("#membersTable").DataTable({
        ajax: "fetch_state_assembly_data.php?members=true",
        columns: [
            {
                data: "image",
                defaultContent: "uploads/avatar.webp", // Handle missing images
                render: function (data) {
                    let imageUrl = data ? data : "uploads/avatar.webp";
                    return `<img src="${imageUrl}" class="rounded-circle" width="40" height="40" alt="Member">`;
                }
            },
            { data: "name" },
            { data: "gender" },
            { data: "party" },
            { data: "state" },
            { data: "constituency" },
            { data: "position" },
            {
                data: "id",
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="${data}" 
                            data-name="${row.name}" 
                            data-gender="${row.gender}" 
                            data-party="${row.party}" 
                            data-state="${row.state}" 
                            data-constituency="${row.constituency}"
                            data-position="${row.position}">
                            Edit
                        </button>
                        <button class="delete-btn btn btn-danger btn-sm" onclick="deleteMember(${data})">Delete</button>
                        <button class="view-btn btn btn-success btn-sm" onclick="viewMember(${data})">View</button>
                    `;
                }
            }
        ],
        paging: true,
        searching: true
    });

    // Handle Edit Button Click
    $(document).on("click", ".edit-btn", function () {
        $("#editMemberId").val($(this).data("id"));
        $("#editMemberName").val($(this).data("name"));
        $("#editMemberGender").val($(this).data("gender"));
        $("#editMemberParty").val($(this).data("party"));
        $("#editMemberState").val($(this).data("state"));
        $("#editMemberConstituency").val($(this).data("constituency"))
        $("#editMemberPosition").val($(this).data("position"));
        $("#editModal").modal("show");
    });

    // Submit Edit Form
    $("#editMemberForm").submit(function (e) {
        e.preventDefault();
        
        let formData = $(this).serialize();

        fetch("fetch_state_assembly_data.php", {
            method: "PUT",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("Member updated successfully!");
                $("#editModal").modal("hide");
                table.ajax.reload();
            } else {
                alert("Failed to update member: " + result.message);
            }
        })
        .catch(error => console.error("Error updating member:", error));
    });


    // Delete function
    window.deleteMember = function (memberId) {
        if (!confirm("Are you sure you want to delete this member?")) return;

        fetch("fetch_state_assembly_data.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ id: memberId })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("Member deleted successfully!");
                table.ajax.reload();
            } else {
                alert("Failed to delete member: " + result.message);
            }
        })
        .catch(error => {
            console.error("Error deleting member:", error);
            alert("An error occurred. Please try again.");
        });
    };
});

// Fetch Member Stats
function fetchMemberStats() {
    fetch("fetch_state_assembly_data.php?members=true")
        .then(response => response.json())
        .then(data => {
            let total = data.data.length;
            let maleCount = data.data.filter(member => member.gender.toLowerCase() === "male").length;
            let femaleCount = data.data.filter(member => member.gender.toLowerCase() === "female").length;
            let youthCount = data.data.filter(member => member.dob !== null && member.dob < 35).length; // Age < 35 = Youth

            let malePercentage = total > 0 ? ((maleCount / total) * 100).toFixed(1) : 0;
            let femalePercentage = total > 0 ? ((femaleCount / total) * 100).toFixed(1) : 0;
            let youthPercentage = total > 0 ? ((youthCount / total) * 100).toFixed(1) : 0;

            document.getElementById("totalMembers").textContent = total;
            document.getElementById("maleMembers").textContent = maleCount;
            document.getElementById("malePercentage").innerHTML = `<small>${malePercentage}% of Total</small>`;
            document.getElementById("femaleMembers").textContent = femaleCount;
            document.getElementById("femalePercentage").innerHTML = `<small>${femalePercentage}% of Total</small>`;
            document.getElementById("youthMembers").textContent = youthCount;
            document.getElementById("youthPercentage").innerHTML = `<small>${youthPercentage}% of Total</small>`;
        })
        .catch(error => console.error("Failed to load stats:", error));
}

// Call function on page load
document.addEventListener("DOMContentLoaded", fetchMemberStats);