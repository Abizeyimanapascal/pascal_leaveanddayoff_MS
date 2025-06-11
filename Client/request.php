<?php
session_start();
require_once '../server/config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['staff_id'])) {
    // Redirect to login page
    header("Location: index.php#loginsection");
    exit();
}
$day_off_result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['requestdayoffbtn'])) {
    $staff_id = $_SESSION['staff_id'];
    $day_off_reason = $_POST['day_off_reason'];
    $day_off_date = $_POST['day_off_date'];

    // Calculate the week number of the requested date
    $requested_week = date("o-W", strtotime($day_off_date)); // e.g., 2025-23 (year-week)

    // Check if the user already requested a day off in that week
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM day_off_requests 
        WHERE staff_id = ? 
        AND YEARWEEK(day_off_date, 1) = YEARWEEK(?, 1)
        AND decision !='Rejected'
    ");
    $stmt->bind_param("is", $staff_id, $day_off_date);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $day_off_result = "failed";
    } else {
        // Insert the day off request
        $stmt = $conn->prepare("
            INSERT INTO day_off_requests (staff_id, day_off_reason, day_off_date, decision) 
            VALUES (?, ?, ?, 'Pending')
        ");
        $stmt->bind_param("iss", $staff_id, $day_off_reason, $day_off_date);

        if ($stmt->execute()) {
            $day_off_result = "success";

            // After inserting a day off request:
            $data = [
                'request_date' => date("Y-m-d H:i:s"),
                'event_date' => $day_off_date,
                'days_requested' => '1',
                'reason' => $day_off_reason,
                'request_type' => 'DAY OFF',
                'tense' => 'is now',
                'decision' => 'Pending',
                'staff_id' => $staff_id
            ];

            $historystmt = $conn->prepare("
             INSERT INTO history (staff_id, event_type, event_date, data_snapshot)
             VALUES (?, 'day_off_req', ?, ?)
            ");
            $json_data = json_encode($data);
            $historystmt->bind_param("iss", $staff_id, $day_off_date, $json_data);
            $historystmt->execute();
            $historystmt->close();
        } else {
            $day_off_result = "<p style='color:red;'>Error submitting your request: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

?>
<?php if (!empty($day_off_result)) { ?>
    <script>
        window.onload = function() {
            window.location.hash = 'nav-dayoff-tab';
        };
    </script>
<?php } ?>

<?php
if (isset($_POST['requestleavebtn'])) {
    $staff_id = $_SESSION['staff_id']; // From session after login
    $start_date = $_POST['leavefrom'];
    $end_date = $_POST['leaveto'];
    $reason = $_POST['leavereason'];

    $leave_result = "";
    // Validate dates
    if (strtotime($start_date) > strtotime($end_date)) {
        $leave_result = "date_error";
    } else {
        // Calculate days requested
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $days_requested = $interval->days + 1; // Inclusive

        // Fetch total leave days taken so far
        $stmt = $conn->prepare("
    SELECT COALESCE(SUM(days_requested), 0) AS total_taken 
    FROM leave_requests 
    WHERE staff_id = ? 
    AND `decision` = 'Approved'
    AND YEAR(start_date) = YEAR(CURDATE())
          ");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $stmt->bind_result($total_leave_taken);
        $stmt->fetch();
        $stmt->close();

        // Handle case where no leave has been taken yet
        if (is_null($total_leave_taken)) {
            $total_leave_taken = 0;
        }

        $annual_leave_entitlement = 18;
        $leave_balance = $annual_leave_entitlement - $total_leave_taken;

        if ($days_requested <= $leave_balance) {

            // Check if the user already requested a leave in that year
            $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM leave_requests 
        WHERE staff_id = ? 
        AND YEAR(start_date) = YEAR(CURDATE())
        AND decision ='Pending'
    ");
            $stmt->bind_param("i", $staff_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $leave_result = "pending";
            } else {



                // Insert the leave request
                $stmt = $conn->prepare("INSERT INTO leave_requests (staff_id, start_date, end_date, leave_reason, days_requested, decision) VALUES (?, ?, ?, ?, ?, 'Pending')");
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("isssi", $staff_id, $start_date, $end_date, $reason, $days_requested);

                if ($stmt->execute()) {
                    $leave_result = "success";

                    // After inserting a day off request:
                    $event_date = " From " . $start_date . " To " . $end_date;
                    $data = [
                        'request_date' => date("Y-m-d H:i:s"),
                        'event_date' => $event_date,
                        'days_requested' => $days_requested,
                        'reason' => $reason,
                        'request_type' => 'LEAVE',
                        'tense' => 'is now',
                        'decision' => 'Pending',
                        'staff_id' => $staff_id
                    ];

                    $historystmt = $conn->prepare("
             INSERT INTO history (staff_id, event_type, event_date, data_snapshot)
             VALUES (?, 'leave_req', ?, ?)
            ");
                    $json_data = json_encode($data);
                    $historystmt->bind_param("iss", $staff_id, $event_date, $json_data);
                    $historystmt->execute();
                    $historystmt->close();
                } else {
                    $leave_result = "error";
                }
            }
        } else {
            $leave_result = "failed";
        }
    }
}

$staff_id = $_SESSION['staff_id'];
$current_week = date("Y-m-d");
$stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM day_off_requests 
        WHERE staff_id = ?
        AND `decision` = 'Approved' 
        AND YEARWEEK(day_off_date, 1) = YEARWEEK(?, 1)
    ");
$stmt->bind_param("is", $staff_id, $current_week);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
$remaining_day_off = 1 - $count;


$stmt1 = $conn->prepare("
    SELECT COALESCE(SUM(days_requested), 0) AS total_taken 
    FROM leave_requests 
    WHERE staff_id = ? 
    AND `decision` = 'Approved'
    AND YEAR(start_date) = YEAR(CURDATE())
    ");
$stmt1->bind_param("i", $staff_id);
$stmt1->execute();
$stmt1->bind_result($total_leave_taken);
$stmt1->fetch();
$stmt1->close();
$remaining_leave = 18 - $total_leave_taken;

$sql = $conn->query("
        SELECT * 
        FROM staff
        WHERE staff_id = $staff_id 
    ");
$row = $sql->fetch_assoc();
$user_name = $row['first_name'] . " " . $row['other_name'];
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="css/assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>UTAB</title>
    <link rel="icon" type="image/png" href="img/utab_logo.png">


    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/footers/">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="css/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/carousel1.css">
    <link rel="stylesheet" href="css/checkout.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Moment.js -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

    <!-- Daterangepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Include SweetAlert2 from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }

        .snav-link {
            color: #2ea3f2;
            font-weight: bold;
            font-family: 'Poppins', Helvetica, Arial, Lucida, sans-serif;
            text-decoration: none;

        }

        .p-color {
            background-color: #007A12;
            color: white;
        }
    </style>


    <!-- Custom styles for this template -->

</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path
                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path
                d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
        </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button"
            aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#circle-half"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto"
                    aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>


    <header data-bs-theme="">
        <div class="text-center bg-light fixed-top row text-muted shadow" style="transition:transform 3s ease-in;border-bottom:4px solid #2ea3f2">
            <div class="col-8">
                <nav class="navbar navbar-expand-md">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="account.php"><img src="img/logo.png" width="100px" height="30px"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse col-12 justify-content-left" id="navbarCollapse">
                            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
                                <li class="nav-item">
                                    <a class="snav-link ms-5 text-dark" href="request.php">REQUEST</a>
                                </li>
                                <li class="nav-item">
                                    <a class="snav-link ms-5" href="status.php">STATUS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

            </div>
            <div class="col-1 dropdown pt-3">
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle" />
                </a>
                <ul class="dropdown-menu text-small">
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="../Server/staff_logout.php"><b>Sing Out</b></a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 py-3 text-start"><b><?php echo $user_name; ?></b></div>
    </header>
    <br><br><br><br><br>
    <main class="text-muted">

        <!-- Marketing messaging and featurettes
  ================================================== -->
        <!-- Wrap the rest of the page in another container to center all the content. -->


        <div class="container marketing">
            <a name="loginsection"></a>

            <!-- Navigation Tabs -->
            <nav>
                <div class="nav nav-tabs mb-3 justify-content-center text-muted" id="nav-tab" role="tablist" style="font-weight: bold">
                    <button class="nav-link active" id="nav-dayoff-tab" data-bs-toggle="tab" data-bs-target="#nav-dayoff"
                        type="button" role="tab" aria-controls="nav-dayoff" aria-selected="true">REQUEST FOR DAY OFF</button>
                    <button class="nav-link" id="nav-leave-tab" data-bs-toggle="tab" data-bs-target="#nav-leave"
                        type="button" role="tab" aria-controls="nav-leave" aria-selected="false">REQUEST FOR LEAVE</button>
                </div>
            </nav>

            <!-- Tab Content -->
            <div class="tab-content row" id="nav-tabContent">
                <div class="col-md-1"></div>
                <div class="col-md-4 text-muted alert">
                    <iframe src="https://calendar.google.com/calendar/embed?src=your_calendar_id&ctz=Your_Time_Zone"
                        style="border: 0"
                        width="100%"
                        height="100%"
                        frameborder="0"
                        scrolling="no">
                    </iframe>
                </div>

                <!-- DAY OFF Tab -->
                <div class="tab-pane fade show active col-md-6" id="nav-dayoff" role="tabpanel" aria-labelledby="nav-dayoff-tab">
                    <form class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <label for="dayoffReason" class="form-label">Reason</label>
                                <textarea id="dayoffReason" name="day_off_reason" class="form-control" required></textarea>
                                <div class="invalid-feedback">
                                    Please enter the reason for your day off.
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="dayoffDate" class="form-label">Week Day</label>
                                <input type="date" id="dayoffDate" name="day_off_date" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please select a date.
                                </div>
                            </div>
                        </div>
                        <br>
                        <input type="submit" name="requestdayoffbtn" value="REQUEST" class="w-100 btn btn-primary btn-lg">
                    </form>
                    <script>
                        <?php if ($day_off_result === "success"): ?>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Your day off request has been submitted for approval.'
                            }).then(() => {
                                window.location.href = 'request.php';
                            });
                        <?php elseif ($day_off_result === "failed"): ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: 'You already requested a day off this week. Either Approved or Not yet! Check your status ...'
                            }).then(() => {
                                window.location.href = 'request.php';
                            });
                        <?php endif; ?>
                    </script>
                </div>

                <!-- LEAVE Tab -->
                <div class="tab-pane fade col-md-6" id="nav-leave" role="tabpanel" aria-labelledby="nav-leave-tab">
                    <form class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <label for="leaveReason" class="form-label">Reason</label>
                                <textarea id="leaveReason" name="leavereason" class="form-control" required></textarea>
                                <div class="invalid-feedback">
                                    Please enter the reason for your leave.
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="leaveTo" class="form-label">Start date</label>
                                <input type="date" class="form-control" id="" name="leavefrom" default="" />
                                <div class="invalid-feedback">
                                    Please select the end date.
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="leaveTo" class="form-label">End date</label>
                                <input type="date" class="form-control" id="" name="leaveto" default="" />
                                <div class="invalid-feedback">
                                    Please select the end date.
                                </div>
                            </div>
                        </div>
                        <br>
                        <input type="submit" name="requestleavebtn" value="REQUEST" class="w-100 btn btn-primary btn-lg">

                    </form>
                    <script>
                        <?php if ($leave_result === "date_error") { ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Date error!',
                                text: 'Start date cannot be after end date.'
                            }).then(() => {
                                window.location.href = 'request.php';
                            });
                        <?php } else if ($leave_result === "pending") { ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Penging leave!',
                                text: 'You olready have a pending leave. Check your status, or contact your HR'
                            }).then(() => {
                                window.location.href = 'request.php';
                            });
                        <?php } else if ($leave_result === "error") { ?>
                        <?php } else if ($leave_result === "success") { ?>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Leave request submitted successfully.'
                            }).then(() => {
                                window.location.href = 'request.php';
                            });
                        <?php } else if ($leave_result === "error") { ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!!',
                                text: 'Error submitting request. Please try again.'
                            }).then(() => {
                                window.location.href = 'request.php';
                            });
                        <?php } else if ($leave_result === "failed") { ?>
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Insufficient leave balance. You have only <?php echo $leave_balance; ?> day(s) left'
                            }).then(() => {
                                window.location.href = 'request.php';
                            });
                        <?php } ?>
                    </script>

                </div>
            </div>


        </div>



        </div><!-- /.container -->
    </main>

    <!-- FOOTER -->
    <br><br>
    <hr>
    <footer class="p-5  bg-dark text-light">
        <br><br>
        <div class="row">
            <div class="col-sm-4 mb-3">
                <span class="text-warning h4">...</span><br><br>
                <a href="ttps://utab.ac.rw/" style="text-decoration: none;">UTAB Website</a>
            </div>

            <div class="col-sm-2 mb-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="index.php?search" class="nav-link p-0">Home</a></li>
                    <li class="nav-item mb-2"><a href="aboutus.php" class="nav-link p-0">About-us</a></li>
                    <li class="nav-item mb-2"><a href="services.php" class="nav-link p-0">Services</a></li>
                </ul>
            </div>


            <div class="col-sm-2 mb-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="index.php?search" class="nav-link p-0">Home</a></li>
                    <li class="nav-item mb-2"><a href="aboutus.php" class="nav-link p-0">About-us</a></li>
                    <li class="nav-item mb-2"><a href="services.php" class="nav-link p-0">Services</a></li>
                </ul>
            </div>

            <div class="col-md-2 ">
                <img src="img/logo.png" height="80px" width="" alt="" style="margin-left:15%;"> <br>
            </div>
        </div>

        <div class="">
            <p class="text-center">&copy; 2025 UTAB. All right reserved.</p>
        </div>
        <br><br>
    </footer>

    <script src="css/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/checkout.js"></script>

    <script>
        $('#leave-dates').daterangepicker();


        const dateInput = document.getElementById('dayoffDate');

        // Get today's date
        const today = new Date();

        // Calculate Sunday of the current week
        const dayOfWeek = today.getDay(); // Sunday = 0, Monday = 1, etc.
        const daysUntilSunday = 7 - dayOfWeek; // days remaining until Sunday

        const sunday = new Date(today);
        sunday.setDate(today.getDate() + daysUntilSunday);

        // Format to YYYY-MM-DD
        const formatDate = (date) => {
            return date.toISOString().split('T')[0];
        };

        // Set the min and max attributes
        dateInput.min = formatDate(today);
        dateInput.max = formatDate(sunday);
    </script>


</body>

</html>