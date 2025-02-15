<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Include the database connection
require_once '../backend/conn.php';

// Initialize variables with default values
$jobsCount = $applicantsCount = $respondedCount = $notRespondedCount = 0;

try {
    // Fetch number of jobs
    $stmt = $pdo->query("SELECT COUNT(*) FROM jobs");
    $jobsCount = $stmt->fetchColumn() ?: 0;

    // Fetch number of applicants
    $stmt = $pdo->query("SELECT COUNT(*) FROM applications");
    $applicantsCount = $stmt->fetchColumn() ?: 0;

    // Fetch number of responded requests
    $stmt = $pdo->query("SELECT COUNT(*) FROM communication WHERE respond_message_content IS NOT NULL");
    $respondedCount = $stmt->fetchColumn() ?: 0;

    // Fetch number of not responded requests
    $stmt = $pdo->query("SELECT COUNT(*) FROM communication WHERE respond_message_content IS NULL");
    $notRespondedCount = $stmt->fetchColumn() ?: 0;

} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ربط ملف CSS الخاص بالتصميم -->
    <link rel="stylesheet" href="../css/hr dashboard.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <!-- تضمين خط Tajawal من Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    
    <!-- تضمين مكتبة Bootstrap لتنسيق التصميم -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- تضمين مكتبة أيقونات Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <aside class="sidebar">
    <div class="logo">
      <img src="../assets/fim.png" alt="شعار فيم">
    </div>
    <div class="dropdown">
      <!-- زر عرض القائمة -->
      <button class="dropdown-btn">الموارد البشرية</button>
      <div class="dropdown-content">
          <!-- روابط القائمة -->
          <a href="personal info.php">
              <i class="bi bi-person-circle"></i> البيانات الشخصية
          </a>
          <a href="Communication requests.php">
              <i class="bi bi-envelope"></i> طلبات التواصل
          </a>
          <a href="Employee Dashboard.php">
              <i class="bi bi-people"></i> الموظفين
          </a>
          <a href="Job raise.php">
              <i class="bi bi-upload"></i>  الوظائف
          </a>
          <a href="Jobs Request.php">
              <i class="bi bi-file-earmark-text"></i> طلبات التوظيف
          </a>
      </div>
  </div>
</aside>

        <main class="content">
            <div class="welcome-card">
                <img src="../assets/fim.png" alt="شعار فيم">
                <h2>مرحبًا بك في منصة فيم</h2>
                <p>منصة متخصصة لإدارة الموارد البشرية بفعالية وسهولة.</p>
            </div>
            <div class="cards-container">
                <div class="card">
                    <span class="icon bi bi-briefcase"></span>
                    <h3>عدد الوظائف</h3>
                    <p><?php echo $jobsCount; ?></p>
                </div>
                <div class="card">
                    <span class="icon bi bi-person-lines-fill"></span>
                    <h3>عدد المتقدمين</h3>
                    <p><?php echo $applicantsCount; ?></p>
                </div>
                <div class="card">
                    <span class="icon bi bi-check-circle"></span>
                    <h3>تم الرد عليه</h3>
                    <p><?php echo $respondedCount; ?></p>
                </div>
                <div class="card">
                    <span class="icon bi bi-x-circle"></span>
                    <h3>لم يتم الرد عليه</h3>
                    <p><?php echo $notRespondedCount; ?></p>
                </div>
            </div>
        </main>
    
    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>

</body>
</html>