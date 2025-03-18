<?php
include 'db_connect.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $chamber = $_POST['chamber'];
    $state = $_POST['state'];
    $constituency = $_POST['constituency'];
    $party = $_POST['party'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pAddress = $_POST['pAddress'];
    $pPhone = $_POST['pPhone'];
    $cAddress = $_POST['cAddress'];
    $cPhone = $_POST['cPhone'];
    $biography = $_POST['biography'];
    $imagePath = '';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    try {
        $sql = "INSERT INTO legislators 
                (name, dob, gender, position, chamber, state, constituency, party, email, phone, pAddress, pPhone, cAddress, cPhone, biography, image)
                VALUES (:name, :dob, :gender, :position, :chamber, :state, :constituency, :party, :email, :phone, :pAddress, :pPhone, :cAddress, :cPhone, :biography, :image)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':dob' => $dob,
            ':gender' => $gender,
            ':position' => $position,
            ':chamber' => $chamber,
            ':state' => $state,
            ':constituency' => $constituency,
            ':party' => $party,
            ':email' => $email,
            ':phone' => $phone,
            ':pAddress' => $pAddress,
            ':pPhone' => $pPhone,
            ':cAddress' => $cAddress,
            ':cPhone' => $cPhone,
            ':biography' => $biography,
            ':image' => $imagePath
        ]);

        $successMessage = 'Legislator added successfully!';
    } catch (PDOException $e) {
        $errorMessage = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Legislator</title>
    <link rel="stylesheet" href="lad.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</head>
<body>
<?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
<div class="container">
    <h2>Add Legislator</h2>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="form-control select2" required>
                <option value="">-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" name="position" id="position" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="chamber">Chamber</label>
            <select name="chamber" id="chamber" class="form-control select2" required>
                <option value="">-- Select Chamber --</option>
                <option value="Senate">Senate</option>
                <option value="House of Reps">House of Reps</option>
            </select>
        </div>

        <div class="form-group">
            <label for="state">State</label>
            <input type="text" name="state" id="state" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="constituency">Constituency</label>
            <input type="text" name="constituency" id="constituency" class="form-control">
        </div>

        <div class="form-group">
            <label for="party">Party</label>
            <input type="text" name="party" id="party" class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" >
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control">
        </div>

        <div class="form-group">
            <label for="pAddress">Permanent Address</label>
            <input type="text" name="pAddress" id="pAddress" class="form-control">
        </div>

        <div class="form-group">
            <label for="pPhone">Permanent Phone</label>
            <input type="text" name="pPhone" id="pPhone" class="form-control">
        </div>

        <div class="form-group">
            <label for="cAddress">Constituency Address</label>
            <input type="text" name="cAddress" id="cAddress" class="form-control">
        </div>

        <div class="form-group">
            <label for="cPhone">Constituency Phone</label>
            <input type="text" name="cPhone" id="cPhone" class="form-control">
        </div>

        <div class="form-group">
            <label for="biography">Biography</label>
            <textarea name="biography" id="biography" class="form-control" rows="5"></textarea>
        </div>

        <div class="form-group">
            <label for="image">Profile Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Add Legislator</button>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
</body>
</html>
