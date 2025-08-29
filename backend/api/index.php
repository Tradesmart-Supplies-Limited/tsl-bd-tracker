<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tslbdtracker";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed."]));
} 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_SERVER['PATH_INFO'])) {
    echo json_encode(["message" => "API is running"]);
    exit;
}

// Allow CORS & JSON response
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Helper: parse request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", trim($_SERVER['PATH_INFO'] ?? '', "/"));
$endpoint = $request[0] ?? '';
$id = $request[1] ?? null;

// Handle routes
switch ($endpoint) {
    case "view-all":
        $result = $conn->query("SELECT * FROM members ORDER BY id DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
        break;

    case "create":
        if ($method !== "POST") { http_response_code(405); exit; }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $department = $_POST['department'];
        $branch = $_POST['branch'];

        // Handle picture upload
        $picturePath = null;
        if (isset($_FILES['picture'])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $picturePath = $targetDir . time() . "_" . basename($_FILES["picture"]["name"]);
            move_uploaded_file($_FILES["picture"]["tmp_name"], $picturePath);
        }

        $stmt = $conn->prepare("INSERT INTO members (member_name, email, dob, department, branch, picture) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $dob, $department, $branch, $picturePath);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Member created successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        break;

    case "edit":
        if (!$id) { echo json_encode(["error" => "ID required"]); exit; }
        $result = $conn->query("SELECT * FROM members WHERE id=$id");
        echo json_encode($result->fetch_assoc() ?: ["error" => "Member not found"]);
        break;

    case "update":
    if (!$id) { echo json_encode(["error" => "ID required"]); exit; }

    if ($method === "PUT") {
        // JSON update (old way)
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'];
        $email = $data['email'];
        $dob = $data['dob'];
        $department = $data['department'];
        $branch = $data['branch'];

        $stmt = $conn->prepare("UPDATE members SET member_name=?, email=?, dob=?, department=?, branch=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $email, $dob, $department, $branch, $id);
        $ok = $stmt->execute();
        echo json_encode(["message" => $ok ? "Member updated" : $stmt->error]);
    } elseif ($method === "POST") {
        // Handle file + form update
        $name = $_POST['name'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $department = $_POST['department'];
        $branch = $_POST['branch'];

        $picturePath = null;
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $picturePath = $targetDir . time() . "_" . basename($_FILES["picture"]["name"]);
            move_uploaded_file($_FILES["picture"]["tmp_name"], $picturePath);

            $stmt = $conn->prepare("UPDATE members SET member_name=?, email=?, dob=?, department=?, branch=?, picture=? WHERE id=?");
            $stmt->bind_param("ssssssi", $name, $email, $dob, $department, $branch, $picturePath, $id);
        } else {
            $stmt = $conn->prepare("UPDATE members SET member_name=?, email=?, dob=?, department=?, branch=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $email, $dob, $department, $branch, $id);
        }

        $ok = $stmt->execute();
        echo json_encode(["message" => $ok ? "Member updated (with file)" : $stmt->error]);
    }
    break;

    case "delete":
    if ($method !== "DELETE") { http_response_code(405); exit; }
    if (!$id) { echo json_encode(["error" => "ID required"]); exit; }

    // 1. Get the picture path before deleting
    $stmt = $conn->prepare("SELECT picture FROM members WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if (!$member) {
        echo json_encode(["error" => "Member not found"]);
        exit;
    }

    $picturePath = $member['picture'];

    // 2. Delete the record
    $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // 3. Delete the picture if it exists
        if ($picturePath && file_exists($picturePath)) {
            unlink($picturePath);
        }
        echo json_encode(["message" => "Member deleted successfully"]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }
    break;


    default:
        echo json_encode(["error" => "Invalid endpoint"]);
}
