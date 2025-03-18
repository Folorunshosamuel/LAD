<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>State Assembly Dashboard</title>
    <link rel="stylesheet" href="lad.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container">
    <h2>State Assembly Overview</h2>

    <!-- Dropdown to select state -->
    <label for="stateSelect">Select a State:</label>
    <select id="stateSelect" class="form-control">
        <option value="">All States</option>
    </select>

    
    <!--Data Cards-->
    <div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h5 class="card-title">Total Members</h5>
                <h3 id="totalMembers">0</h3>
                <p><small>Total in Assembly</small></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5 class="card-title">Male Members</h5>
                <h3 id="maleMembers">0</h3>
                <p id="malePercentage"><small>0% of Total</small></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-danger shadow">
            <div class="card-body">
                <h5 class="card-title">Female Members</h5>
                <h3 id="femaleMembers">0</h3>
                <p id="femalePercentage"><small>0% of Total</small></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title">Youths (Under 35)</h5>
                <h3 id="youthMembers">0</h3>
                <p id="youthPercentage"><small>0% of Total</small></p>
            </div>
        </div>
    </div>
</div>
<!---End Data Card-->
<!-- Charts -->
    <div class="chart-container">
        <canvas id="partyBarChart"></canvas>
        <!-- <canvas id="partyPieChart"></canvas> -->
    </div>

    <!-- Members List -->
    <h3>Assembly Members</h3>
    <!-- <ul id="membersList" class="list-group wd-md-50p"></ul> -->
    <table id="membersTable" class="display">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Party</th>
                <th>State</th>
                <th>Constituency</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMemberForm">
                        <input type="hidden" id="editMemberId" name="id">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" id="editMemberName" name="name" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Gender:</label>
                            <input type="text" id="editMemberGender" name="gender" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Party:</label>
                            <input type="text" id="editMemberParty" name="party" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>State:</label>
                            <input type="text" id="editMemberState" name="state" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Constituency:</label>
                            <input type="text" id="editMemberConstituency" name="constituency" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Position:</label>
                            <input type="text" id="editMemberPosition" name="postition" class="form-control" >
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Member Form -->
    <!-- <h3>Add New Member</h3>
    <form id="addMemberForm">
        <input type="text" id="name" placeholder="Name" required>
        <input type="date" id="dob" required>
        <select id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <input type="text" id="state" placeholder="State" required>
        <input type="text" id="constituency" placeholder="Constituency" required>
        <input type="text" id="party" placeholder="Party" required>
        <button type="submit">Add Member</button>
    </form> -->
</div>

<script src="fetchscript_newchart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
