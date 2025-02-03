<?php

// Include database connection and lad.css stylesheet
include 'memberfetchsen.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($member['name']); ?> - Profile</title>
    <link rel="stylesheet" href="lad.css"> <!-- Ensure this path matches the location of the uploaded stylesheet -->

    <style>
        /* Additional styling for profile card layout */
        .profile-container {
            margin: 20px auto;
            max-width: 80%;
        }
        .profile-card {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-image {
            flex: 1;
            max-width: fit-content;
            margin-right: 20px;
            text-align: center;
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #cdd4e0;
            border-radius: 3px;
            max-width: 100%;
            height: auto;
        }
        .profile-image img {
            width: 100%;
            border-radius: 0;
        }
        .profile-details {
            flex: 2;
            line-height: normal;
        }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
<div class="profile-container">
        <div class="profile-card">
            <div class="profile-image">
                <?php if ($member['image']): ?>
                    <img src="<?= htmlspecialchars($member['image']) ?>" alt="Profile Image">
                <?php else: ?>
                    <p>No profile image available.</p>
                <?php endif; ?>
            </div>
            <div class="profile-details">
                <h2><?php echo htmlspecialchars($member['name']); ?></h2>
                <p class="text-muted"><strong><?php echo htmlspecialchars($member['position']); ?></strong> | <?php echo htmlspecialchars($member['state']); ?> | <?php echo htmlspecialchars($member['constituency']); ?></p>
                <p><strong>Position:</strong> <?php echo htmlspecialchars($member['position']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($member['gender']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($member['dob']); ?></p>
                <p><strong>Party:</strong> <?php echo htmlspecialchars($member['party']); ?></p>
                <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>"><?php echo htmlspecialchars($member['email']); ?></a></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($member['phone']); ?></p>
                <p><strong>Parliament Address:</strong> <?php echo htmlspecialchars($member['pAddress']); ?></p>
                <p><strong>Constituency Address:</strong> <?php echo htmlspecialchars($member['cAddress']); ?></p>
                <h5>Biography</h4>
                <p><?php echo htmlspecialchars($member['biography']) ?: "No biography available."; ?></p>
                <!-- Edit Profile Button -->
                <a class="btn btn-az-primary" href="senateupdate.php?id=<?php echo urlencode($id); ?>" class="edit-button">Edit Profile</a>  
            </div>
        </div>

        <div class="bill-section mt-4">
            <h5>Sponsored Bills</h5>
            <table class="table table-striped table-hover bill-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>BN</th>
                        <th>Status</th>
                        <th>First Reading</th>
                        <th>Second Reading</th>
                        <th>Third Reading</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($bill = $bills->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bill['billNum']); ?></td>
                            <td><?php echo htmlspecialchars($bill['title']); ?></td>
                            <td><?php echo htmlspecialchars($bill['billStatus']); ?></td>
                            <td><?php echo htmlspecialchars($bill['firstReading']) ?: 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($bill['secondReading']) ?: 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($bill['thirdReading']) ?: 'N/A'; ?></td>
                            <td><a href="bill.php?id=<?= $bill['id'] ?>" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="bill-section mt-4">
            <h5>Motions Moved</h5>
            <table class="table table-striped table-hover bill-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>First Reading</th>
                        <th>Second Reading</th>
                        <th>Third Reading</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($bill = $bills->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bill['title']); ?></td>
                            <td><?php echo htmlspecialchars($bill['billStatus']); ?></td>
                            <td><?php echo htmlspecialchars($bill['firstReading']) ?: 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($bill['secondReading']) ?: 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($bill['thirdReading']) ?: 'N/A'; ?></td>
                            <td><a href="uploads/<?php echo htmlspecialchars($bill['billFile']); ?>">Download</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
