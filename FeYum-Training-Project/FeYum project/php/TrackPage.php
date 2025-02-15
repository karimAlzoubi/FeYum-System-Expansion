<?php
session_start();
require '../backend/conn.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestNumber = trim($_POST['request_number']); // Get the request number

    // Validate the prefix
    $prefix = strtoupper(substr($requestNumber, 0, 1)); // Extract the first character
    $numericPart = substr($requestNumber, 1); // Extract the numeric part

    if (!in_array($prefix, ['M', 'J']) || !is_numeric($numericPart)) {
        $_SESSION['error'] = "رقم الطلب يجب أن يبدأ بـ 'M' أو 'J' متبوعًا برقم صحيح.";
        header("Location: TrackPage.php");
        exit();
    }

    try {
        if ($prefix === 'M') {
            // Query for communication requests
            $stmtCommunication = $pdo->prepare("SELECT * FROM communication WHERE message_id = :request_number");
            $stmtCommunication->bindParam(':request_number', $numericPart, PDO::PARAM_STR);
            $stmtCommunication->execute();
            $communicationRequest = $stmtCommunication->fetch(PDO::FETCH_ASSOC);

            if ($communicationRequest) {
                $_SESSION['request'] = $communicationRequest;
                header("Location: follow up communication request.php");
                exit();
            }
        } elseif ($prefix === 'J') {
            // Query for job applications
            $stmtApplication = $pdo->prepare("SELECT * FROM applications WHERE application_id = :request_number");
            $stmtApplication->bindParam(':request_number', $numericPart, PDO::PARAM_INT);
            $stmtApplication->execute();
            $jobApplication = $stmtApplication->fetch(PDO::FETCH_ASSOC);

            if ($jobApplication) {
                $_SESSION['application_id'] = $jobApplication['application_id'];
                header("Location: job-status.php");
                exit();
            }
        }

        // If no request is found
        $_SESSION['error'] = "رقم الطلب غير موجود. يرجى المحاولة مرة أخرى.";
        header("Location: TrackPage.php");
        exit();
    } catch (PDOException $e) {
        echo "Error fetching data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <!--  Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تتبع الطلب</title>
  <link rel="stylesheet" href="../css/styleTrackPage.css">
  <link rel="stylesheet" href="../css/dark-mode.css">
</head>
<body>
  <div class="track-container">
    <div class="logo">
      <img src="../assets/fim.png" alt="Logo">
    </div>
    <form id="trackForm" action="TrackPage.php" method="POST" class="track-form">
      <label for="request-number">رقم الطلب</label>
      <input type="text" id="request-number" name="request_number" placeholder="مثال: M321 أو J321" required>
      
      <div class="buttons">
        <button type="button" class="back-button" onclick="window.location.href='FirstPage.php'">رجوع</button>
        <button type="submit" class="track-button">تتبع</button>
      </div>
    </form>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="error-message">
          <p><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
      </div>
    <?php endif; ?>
  </div>
  <script src="../js/scriptTrackPage.js"></script>
  <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>