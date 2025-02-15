<?php
// Start session and include database connection
session_start();
require '../backend/conn.php';

// Check if the application ID is provided via GET or SESSION
$application_id = $_GET['application_id'] ?? $_SESSION['application_id'] ?? null;

// Fetch application details from the database
$application = null;
if ($application_id) {
    try {
        // Modified query to get job title from jobs table
        $stmt = $pdo->prepare("
            SELECT 
                a.full_name, 
                a.application_id, 
                a.status, 
                j.title AS job_title 
            FROM applications a
            JOIN jobs j ON a.job_id = j.job_id
            WHERE a.application_id = :application_id
        ");
        $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);
        $stmt->execute();
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no application is found, show an error message
        if (!$application) {
            $error_message = "لم يتم العثور على الطلب.";
        }
    } catch (PDOException $e) {
        $error_message = "خطأ في جلب البيانات: " . $e->getMessage();
    }
} else {
    $error_message = "رقم الطلب مفقود.";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حالة الطلب</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/job-status.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Main Card -->
        <div class="status-card">
            <!-- Logo -->
            <div class="logo-container text-center">
                <img src="../assets/fim.png" alt="شعار فيم" class="logo mb-4">
            </div>

            <!-- Dynamic Job Title -->
            <h1 class="page-title text-center">طلب توظيف <?php echo isset($application['job_title']) ? htmlspecialchars($application['job_title']) : 'وظيفة'; ?></h1>

            <!-- Content -->
            <div class="card-content mt-4">
                <?php if (isset($error_message)) { ?>
                    <!-- Error Message -->
                    <div class="error-message text-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
                <?php } elseif ($application) { ?>
                    <!-- Name Field -->
                    <div class="info-item d-flex justify-content-between mb-2">
                        <div class="label">الاسم الكامل</div>
                        <div class="value"><?php echo htmlspecialchars($application['full_name']); ?></div>
                    </div>

                    <!-- Tracking Number -->
                    <div class="info-item d-flex justify-content-between mb-2">
                        <div class="label">رقم التتبع</div>
                        <div class="value">J<?php echo htmlspecialchars($application['application_id']); ?></div>
                    </div>

                    <!-- Status -->
                    <div class="status-text text-center mt-3 text-<?php echo ($application['status'] === 'Rejected' ? 'danger' : ($application['status'] === 'Accepted' ? 'success' : 'warning')); ?>">
                        <?php
                        if ($application['status'] === 'Rejected') {
                            echo 'تم رفض طلبك';
                        } elseif ($application['status'] === 'Accepted') {
                            echo 'تم قبول طلبك';
                        } else {
                            echo 'قيد المراجعة';
                        }
                        ?>
                    </div>
                <?php } ?>

                <!-- Back Button -->
                <div class="button-container text-center mt-4">
                    <button class="btn btn-danger btn-lg" onclick="window.location.href='FirstPage.php'">رجوع</button>
                </div>
            </div>
        </div>
    </div>

    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>

</body>
</html>