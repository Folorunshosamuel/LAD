<?php

// Include database connection and lad.css stylesheet
include 'memberfetch.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($member['name']); ?> - Profile</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <!-- Profile Section -->
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
                    <h2><?= htmlspecialchars($member['name']); ?></h2>
                    <p><strong>State:</strong> <?= htmlspecialchars($member['state']); ?> | <strong>Constituency:</strong> <?= htmlspecialchars($member['constituency']); ?></p>
                    <p><strong>Chamber:</strong> <?= htmlspecialchars($member['chamber']); ?></p>
                    <p><strong>Position:</strong> <?= htmlspecialchars($member['position']); ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($member['gender']); ?></p>
                    <p><strong>DOB:</strong> <?= htmlspecialchars($member['dob'] ?? 'N/A'); ?></p>
                    <p><strong>Party:</strong> <?= htmlspecialchars($member['party'] ?? 'N/A'); ?></p>
                    <p><strong>Constituency Address:</strong> <?= htmlspecialchars($member['cAddress'] ?? 'N/A'); ?></p>
                    <h5>Biography</h5>
                    <p><?php echo htmlspecialchars($member['biography'] ?? "No biography available."); ?></p>
                    <a class="bttn" href="profile_update.php?id=<?= urlencode($id); ?>">Edit Profile</a>
                </div>
            </div>
        </div>

        <!-- Sponsored Bills -->
        <div class="profile-container table-responsive">
            <h5>Sponsored Bill(s)</h5>
            <table>
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
                            <td><?= htmlspecialchars($bill['title']); ?></td>
                            <td><?= htmlspecialchars($bill['billNum']); ?></td>
                            <td><?= htmlspecialchars($bill['billStatus']); ?></td>
                            <td><?= htmlspecialchars($bill['firstReading'] ?? 'N/A' ); ?></td>
                            <td><?= htmlspecialchars($bill['secondReading'] ?? 'N/A' ); ?></td>
                            <td><?= htmlspecialchars($bill['thirdReading'] ?? 'N/A' ); ?></td>
                            <td><a href="bill.php?id=<?= $bill['id'] ?>" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                    <?php } 
                    } else {
                        echo "<tr><td colspan='7'>No bill found for this legislator.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Predecessors -->
        <div class="profile-container table-responsive">
            <h5>Predecessor(s)</h5>
            <table>
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
                    $predecessorQuery = $conn->prepare("SELECT * FROM legislators WHERE id = ?");
                    $predecessorQuery->bind_param("i", $member['predecessor_id']);
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

         <!-- Send Message Form -->
         <div class="profile-container">
            <h5>Send a message to <?= htmlspecialchars($member['name']); ?></h5>
            <form action="send_message.php" method="POST">
                <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($member['id']); ?>">
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
</body>
</html>
