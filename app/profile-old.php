<?php

// Include database connection and lad.css stylesheet
include 'memberfetch.php';

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
        /* .profile-container {
            margin: 20px auto;
            max-width: 80%;
        } */
        .profile-container {
            margin: 20px auto;
            padding: 10px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* .profile-card {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        } */

        .profile-card {
            display: flex;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
            gap: 20px; /* Add spacing between items */
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
            width: 70%;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .profile-details {
            flex: 1;
            line-height: normal;
        }
        @media (max-width: 768px) {
            .profile-card {
                flex-direction: column; /* Stack elements vertically */
                align-items: flex-start;
            }

            .profile-image {
                text-align: center;
                margin-bottom: 20px;
            }

            .profile-image img {
                max-width: 100px; /* Reduce image size for smaller screens */
            }

            .profile-details h2 {
                font-size: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }

            table {
                font-size: 14px; /* Reduce font size for tables */
            }
        }

        @media (max-width: 480px) {
            .profile-details h2 {
                font-size: 18px;
            }

            .btn {
                font-size: 12px;
                padding: 6px 12px;
            }

            table th, table td {
                padding: 8px;
            }
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
            <?php if (!empty($member['image'])): ?>
                <img src="<?= htmlspecialchars($member['image']); ?>" alt="Profile Image">
            <?php else: ?>
                <img src="uploads/avatar.webp" alt="Default Profile Image">
            <?php endif; ?>
        </div>
        <div class="profile-details">
            <h2><?php echo htmlspecialchars($member['name']); ?></h2>
            <strong><p class="text-muted"><?php echo htmlspecialchars($member['state']); ?> | <?php echo htmlspecialchars($member['constituency']); ?></p></strong>
            <p><strong>Chamber:</strong> <?php echo htmlspecialchars($member['chamber']); ?></p>
            <p><strong>Position:</strong> <?php echo htmlspecialchars($member['position']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($member['gender']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($member['dob'] ?? 'N/A'); ?></p>
            <p><strong>Party:</strong> <?php echo htmlspecialchars($member['party'] ?? 'N/A'); ?></p>
            <!-- <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>"><?php echo htmlspecialchars($member['email'] ?? 'N/A'); ?></a></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($member['phone'] ?? 'N/A'); ?></p> -->
            <p><strong>Parliament Address:</strong> <?php echo htmlspecialchars($member['pAddress']?? 'N/A'); ?></p>
            <p><strong>Constituency Address:</strong> <?php echo htmlspecialchars($member['cAddress']?? 'N/A'); ?></p>
            <h5>Biography</h4>
            <p><?php echo htmlspecialchars($member['biography'] ?? "No biography available."); ?></p>
            <!-- Edit Profile Button -->
            <a class="btn btn-az-primary" href="profile_update.php?id=<?php echo urlencode($id); ?>" class="edit-button">Edit Profile</a>  
        </div>
    </div>
    
    <div class="profile-container profile-card">
        <div class="card-body">
            <!-- Send Message Form on Legislator Profile Page -->
            <form action="send_message.php" method="POST">
                <input type="hidden" name="receiver_id" value="<?= ($member['id']); ?>"> <!-- Legislator's ID -->
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
    <div class="bill-section mt-4">
        <h5>Sponsored Bill(s)</h5>
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
                <?php 
                    if ($bills->num_rows > 0) {
                        while ($bill = $bills->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bill['billNum']); ?></td>
                        <td><?php echo htmlspecialchars($bill['title']); ?></td>
                        <td><?php echo htmlspecialchars($bill['billStatus']); ?></td>
                        <td><?php echo htmlspecialchars($bill['firstReading'] ?? 'N/A' ); ?></td>
                        <td><?php echo htmlspecialchars($bill['secondReading'] ?? 'N/A' ); ?></td>
                        <td><?php echo htmlspecialchars($bill['thirdReading'] ?? 'N/A' ); ?></td>
                        <td><a href="bill.php?id=<?= $bill['id'] ?>" class="btn btn-primary btn-sm">View</a></td>
                    </tr>
                <?php } 
                } else {
                    echo "<tr><td colspan='6'>No bill found for this legislator.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="predecessor-section mt-4">
        <h5>Predecessor(s)</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>State</th>
                    <th>Constituency</th>
                    <th>Party</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to fetch predecessors for the legislator
                $predecessorQuery = $conn->prepare("SELECT * FROM legislators WHERE id = ?");
                $predecessorQuery->bind_param("i", $member['predecessor_id']); // Use predecessor_id from the current member's data
                $predecessorQuery->execute();
                $predecessors = $predecessorQuery->get_result();

                if ($predecessors->num_rows > 0) {
                    while ($predecessor = $predecessors->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($predecessor['name']); ?></td>
                            <td><?= htmlspecialchars($predecessor['position']); ?></td>
                            <td><?= htmlspecialchars($predecessor['state']); ?></td>
                            <td><?= htmlspecialchars($predecessor['constituency']); ?></td>
                            <td><?= htmlspecialchars($predecessor['party']); ?></td>
                            <td><a href="profile.php?id=<?= htmlspecialchars($predecessor['id']); ?>" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='6'>No predecessors found for this legislator.</td></tr>";
                }
                ?>
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
