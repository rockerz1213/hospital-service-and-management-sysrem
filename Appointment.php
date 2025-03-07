<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "hospitol");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the action from URL (default is 'book')
$action = isset($_GET['action']) ? $_GET['action'] : 'book';

// Handle appointment booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_appointment"])) {
    $patient_name = $_POST["patient_name"];
    $doctor_name = $_POST["doctor_name"];
    $appointment_date = $_POST["appointment_date"];

    $sql = "INSERT INTO appointments (patient_name, doctor_name, appointment_date) VALUES ('$patient_name', '$doctor_name', '$appointment_date')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Appointment booked successfully!');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle appointment cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $sql = "DELETE FROM appointments WHERE id=$cancel_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Appointment canceled successfully!'); window.location='Appointment.php?action=view';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment System</title>
    <style>
        /* General Styles */
        body {
    background: linear-gradient(to bottom, #d8e157, #274875);
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
}


/* Container Styling */
.container {
    width: 70%;
    margin: 30px auto;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

/* Button Styles */
.btn {
    padding: 10px 20px;
    margin: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

.btn.cancel {
    background-color: #e74c3c;
}

.btn.cancel:hover {
    background-color: #c0392b;
}

/* Navigation Bar */
.nav {
    text-align: center;
    margin-bottom: 30px;
}

.nav a {
    text-decoration: none;
    padding: 12px 25px;
    background-color: #007bff;
    color: white;
    margin: 0 10px;
    border-radius: 5px;
    font-size: 16px;
}

.nav a:hover {
    background-color: #0056b3;
}

/* Form Styling */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-size: 18px;
    color: #333;
}

.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    margin-top: 8px;
    box-sizing: border-box;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #007bff;
    color: white;
}

table tr:hover {
    background-color: #f1f1f1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    .nav a {
        padding: 8px 15px;
        font-size: 14px;
    }

    .form-group input, .form-group select, .form-group textarea {
        font-size: 14px;
    }
}

    </style>
</head>
<body>

<div class="container">
    <div class="nav">
        <a href="Appointment.php?action=book">Book Appointment</a>
        <a href="Appointment.php?action=view">View Appointments</a>
    </div>

    <?php if ($action == "book") { ?>
        <h2>Book an Appointment</h2>
        <form method="post">
            <div class="form-group">
                <label>Patient Name:</label>
                <input type="text" name="patient_name" required>
            </div>
            <div class="form-group">
                <label>Select Doctor:</label>
                <select name="doctor_name" required>
                    <?php
                    $result = $conn->query("SELECT smfname, smlname FROM staff WHERE smtype = 'Doctor'");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['smfname'] . " " . $row['smlname'] . "'>" . $row['smfname'] . " " . $row['smlname'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Appointment Date:</label>
                <input type="date" name="appointment_date" required>
            </div>
            <button type="submit" name="book_appointment" class="btn">Book</button>
        </form>
    
    <?php } elseif ($action == "view") { ?>
        <h2>View Appointments</h2>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Appointment Date</th>
                <th>Action</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM appointments");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['patient_name']}</td>
                        <td>{$row['doctor_name']}</td>
                        <td>{$row['appointment_date']}</td>
                        <td><a href='Appointment.php?action=view&cancel_id={$row['id']}' class='btn cancel'>Cancel</a></td>
                      </tr>";
            }
            ?>
        </table>
    <?php } ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
