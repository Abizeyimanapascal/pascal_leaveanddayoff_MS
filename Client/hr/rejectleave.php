<?php
session_start();
require_once '../../server/config/database.php';

// Check if user is not logged in
if (!isset($_SESSION['hr_id'])) {
    // Redirect to login page
    header("Location: ../index.php#loginsection");
    exit();
}

$request_id = $_GET['request_id']; // ID of the leave request
$decision = "Rejected";

// Update the leave_requests table
$stmt = $conn->prepare("UPDATE leave_requests SET decision = ? WHERE request_id = ?");
$stmt->bind_param("si", $decision, $request_id);
$stmt->execute();
$stmt->close();

// Fetch the updated row to log into history
$stmt = $conn->prepare("SELECT * FROM leave_requests WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// Log the action in history
$staff_id = $row['staff_id'];
$event_type = 'leave_decision';
$days_requested = $row['days_requested'];
$request_date = $row['created_at'];
$event_date = " From ".$row['start_date']." To ".$row['start_date']; // or the date you want to associate
$data = [
    'request_date' => $request_date,
    'event_date' => $event_date,
    'days_requested' => $days_requested,
    'reason' => $row['leave_reason'],
    'request_type' => 'LEAVE',
    'tense' => 'has been',
    'decision' => 'Rejected',
    'staff_id' => $staff_id
];
$data_snapshot = json_encode($data);


$stmt = $conn->prepare("
    INSERT INTO history (staff_id, event_type, event_date, data_snapshot)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("isss", $staff_id, $event_type, $event_date, $data_snapshot);
$stmt->execute();
header("Location: leaverequests.php");
$stmt->close();

?>