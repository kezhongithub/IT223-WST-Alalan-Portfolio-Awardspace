<?php
// Database connection for AwardSpace
$servername = "fdb30.awardspace.net";  // AwardSpace MySQL hostname
$username = "4630214_keenan";  // Your AwardSpace database username
$password = "peaceofshet450157";  // Your AwardSpace database password
$dbname = "4630214_keenan";  // Your AwardSpace database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if it doesn\'t exist
$sql_create_table = "CREATE TABLE IF NOT EXISTS message_tbl (
    Message_ID INT AUTO_INCREMENT PRIMARY KEY,
    Full_Name VARCHAR(100) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    Message_Content TEXT NOT NULL,
    Date_posted DATE NOT NULL
)";

if (!$conn->query($sql_create_table)) {
    die("Error creating table: " . $conn->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);
    $date_posted = date('Y-m-d');

    // Corrected SQL INSERT statement: No backslashes needed for single quotes in double-quoted string
    $sql_insert = "INSERT INTO message_tbl (Full_Name, Email, Message_Content, Date_posted) 
            VALUES ('$full_name', '$email', '$message', '$date_posted')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch messages
$sql_fetch_messages = "SELECT * FROM message_tbl ORDER BY Date_posted DESC";
$result = $conn->query($sql_fetch_messages);

// Check if query was successful
if ($result === FALSE) {
    die("Error fetching messages: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Board - Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <style>
        .message-board {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .message-form {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);\n        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: var(--bg-color);
            color: var(--text-color);
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: var(--main-color);
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .messages-container {
            display: grid;
            gap: 20px;
        }

        .message-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .message-name {
            font-weight: bold;
            color: var(--text-color);
        }

        .message-date {
            color: var(--main-color);
        }

        .message-email {
            color: var(--text-color);
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .message-content {
            color: var(--text-color);
            line-height: 1.6;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            color: var(--main-color);
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="index.html" class="logo">PORTFOLIO.</a>
        <nav class="navbar">
            <a href="index.html#home" style="--i:1">Home</a>
            <a href="index.html#about" style="--i:2">About</a>
            <a href="index.html#resume" style="--i:3">Resume</a>
            <a href="index.html#skills" style="--i:4">Skills</a>
            <a href="index.html#hobbies" style="--i:5">Hobbies</a>
            <a href="index.html#contact" style="--i:6">Contact</a>
        </nav>
    </header>

    <div class="message-board">
        <a href="index.html" class="back-btn">
            <i class="uil uil-arrow-left"></i> Back to Portfolio
        </a>

        <div class="message-form">
            <h2>Leave a Message</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>

        <div class="messages-container">
            <h2>Messages</h2>
            <?php
            // Only attempt to read num_rows if $result is a valid object
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="message-card">';
                    echo '<div class="message-header">';
                    echo '<span class="message-name">' . htmlspecialchars($row['Full_Name']) . '</span>';
                    echo '<span class="message-date">' . $row['Date_posted'] . '</span>';
                    echo '</div>';
                    echo '<div class="message-email">' . htmlspecialchars($row['Email']) . '</div>';
                    echo '<div class="message-content">' . nl2br(htmlspecialchars($row['Message_Content'])) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No messages yet.</p>';
            }
            ?>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>