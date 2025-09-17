<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tsl_bd_tracker";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB connection failed");
}

// Management emails
$management_emails = [
    "m.nakazwe@tradesmartzm.com",
    "andrew@tradesmartzm.com",
    "hr@ug.tradesmartzm.com",
    "it.officer@ug.tradesmartzm.com",
    "bupe@tradesmartzm.com"
];

// Get the date 3 days from now
$three_days = date('m-d', strtotime('+3 days'));

// Query for birthdays in 3 days (ignoring year)
$sql = "SELECT member_name, email, dob FROM members WHERE DATE_FORMAT(dob, '%m-%d') = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $three_days);
$stmt->execute();
$result = $stmt->get_result();

$subject = "Upcoming Birthdays in 3 Days";
$body = "";

while ($row = $result->fetch_assoc()) {
    $body .= "{$row['member_name']} ({$row['email']}) has a birthday on " . date('F j', strtotime($row['dob'])) . ".\n";
}

if ($body) {
    foreach ($management_emails as $to) {
        mail($to, $subject, $body);
    }
    echo "Reminder sent to management.";
} else {
    echo "No birthdays in 3 days.";
}
