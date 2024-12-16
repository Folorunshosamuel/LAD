<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="container">
        <div class="row">
            <?php
            include 'db_connect.php'; // Database connection

            // Fetch Hor from the horMembers table
            $query = "SELECT id, name, position, constituency, state, image FROM legislators where chamber = 'House of reps' and position IN ('Speaker', 'Deputy Speaker', 'House Leader', 'Deputy Chief Whip') ORDER BY FIELD(position, 'Speaker', 'Deputy Speaker', 'House Leader', 'Deputy Chief Whip');";
            $result = $db->query($query);

            while ($hor = $result->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center">
                        <?php if (!empty($hor['image'])): ?>
                        <img src="<?= htmlspecialchars($hor['image']); ?>" alt="<?= htmlspecialchars($hor['name']); ?>" class="card-img-top img-fluid rounded">
                        <?php else: ?>
                            <img src="uploads/avatar.webp" alt="Default Profile Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <p class="text-muted"><?= htmlspecialchars($hor['position']); ?></p>
                            <h5 class="card-title"><?= htmlspecialchars($hor['name']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($hor['constituency']); ?>, <?= htmlspecialchars($hor['state']); ?></p>
                            <td><a href="profile.php?id=<?= $hor['id'] ?>" class="btn btn-primary">View profile</a></td>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
