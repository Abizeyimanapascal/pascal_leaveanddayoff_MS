<?php
session_start();
require_once '../../server/config/database.php';

// Check if user is not logged in
if (!isset($_SESSION['hr_id'])) {
    // Redirect to login page

    exit();
}

$request_id = $_GET['request_id']; // ID of the day_off request
$decision = "Approved";

// Update the day_off_requests table
$stmt = $conn->prepare("UPDATE day_off_requests SET decision = ? WHERE request_id = ?");
$stmt->bind_param("si", $decision, $request_id);
$stmt->execute();
$stmt->close();

// Fetch the updated row to log into history
$stmt = $conn->prepare("SELECT * FROM day_off_requests WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// Log the action in history
$staff_id = $row['staff_id'];
$event_type = 'day_off_decision';
$event_date = $row['day_off_date'];
$request_date = $row['created_at'];

$data = [
    'event_date' => $event_date,
    'days_requested' => '1',
    'reason' => $day_off_reason,
    'request_type' => 'DAY OFF',
    'tense' => 'has been',
    'decision' => 'Approved',
    'staff_id' => $staff_id
];

$data_snapshot = json_encode($data);

$stmt = $conn->prepare("
    INSERT INTO history (staff_id, event_type, event_date, data_snapshot)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("isss", $staff_id, $event_type, $event_date, $data_snapshot);
$stmt->execute();
header("Location: dayoffrequests.php");
$stmt->close();
