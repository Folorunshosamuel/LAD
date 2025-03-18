document.addEventListener("DOMContentLoaded", function () {
    const stateSelect = document.getElementById("stateSelect");
    let data = []; // Store full dataset

    // Fetch all members initially
    fetch("fetch_state_assembly_data.php?members=true")
        .then(response => response.json())
        .then(fetchedData => {
            data = fetchedData.data; // Store full data
            populateStateDropdown(data);
            updateStats(data); // Load stats initially
        })
        .catch(error => console.error("Failed to load data:", error));

    // Initialize DataTable
    let table = $("#membersTable").DataTable({
        ajax: "fetch_state_assembly_data.php?members=true",
        columns: [
            {
                data: "image",
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

    // Populate state dropdown dynamically
    function populateStateDropdown(membersData) {
        const uniqueStates = [...new Set(membersData.map(member => member.state))];
        uniqueStates.forEach(state => {
            let option = document.createElement("option");
            option.value = state;
            option.textContent = state;
            stateSelect.appendChild(option);
        });
        
    }

    // Handle State Change - Update Stats & Table
    stateSelect.addEventListener("change", function () {
        let selectedState = this.value;
        let filteredData = selectedState ? data.filter(member => member.state === selectedState) : data;
        updateStats(filteredData);

        let url = selectedState ? `fetch_state_assembly_data.php?members=true&state=${selectedState}` : `fetch_state_assembly_data.php?members=true`;
        table.ajax.url(url).load();
    });

    // Update Stats Cards
    function updateStats(filteredData) {
        let total = filteredData.length;
        let maleCount = filteredData.filter(member => member.gender.toLowerCase() === "male").length;
        let femaleCount = filteredData.filter(member => member.gender.toLowerCase() === "female").length;
        let youthCount = filteredData.filter(member => calculateAge(member.dob) < 35).length; // Age < 35 = Youth

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
    }

    // Calculate Age from DOB
    function calculateAge(dob) {
        if (!dob) return 0; // If DOB is missing, return 0
        let birthDate = new Date(dob);
        let today = new Date();
        return today.getFullYear() - birthDate.getFullYear();
    }
});

// Handle Edit Button Click
$(document).on("click", ".edit-btn", function () {
    $("#editMemberId").val($(this).data("id"));
    $("#editMemberName").val($(this).data("name"));
    $("#editMemberGender").val($(this).data("gender"));
    $("#editMemberParty").val($(this).data("party"));
    $("#editMemberState").val($(this).data("state"));
    $("#editMemberConstituency").val($(this).data("constituency"));
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
            $("#membersTable").DataTable().ajax.reload();
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
            $("#membersTable").DataTable().ajax.reload();
        } else {
            alert("Failed to delete member: " + result.message);
        }
    })
    .catch(error => {
        console.error("Error deleting member:", error);
        alert("An error occurred. Please try again.");
    });
};
