<?php
require_once '../backend/conn.php';

// Query to fetch active jobs with less applicants than max_applications
$stmt = $pdo->query("
    SELECT j.* 
    FROM jobs j
    LEFT JOIN (SELECT job_id, COUNT(*) AS applicant_count 
               FROM applications
               GROUP BY job_id) a
    ON j.job_id = a.job_id
    WHERE j.status = 'Active' AND (a.applicant_count < j.max_applications OR a.applicant_count IS NULL)
");
$jobs = $stmt->fetchAll();
$jobCount = count($jobs);
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جميع الوظائف المعلنة</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/Jobs style.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <main class="container">
        <!-- الهيدر -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div class="action-buttons d-flex gap-2">
                <button class="filter-btn">
                    <i class="bi bi-filter"></i>
                </button>
                <button class="search-btn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <div class="text-end">
                <h1>جميع الوظائف المعلنة</h1>
                <h3 class="text-muted">عدد الوظائف (<?php echo $jobCount; ?>)</h3>
            </div>
        </header>

        <section class="employees">
            <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <img src="../assets/fim.png" alt="صورة الشركة">
                <div class="info">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                </div>
                <button class="details-btn" onclick="window.location.href='ApplyNow.php?id=<?php echo $job['job_id']; ?>'">التفاصيل</button>
            </div>
            <?php endforeach; ?>
        </section>

        <button class="back-btn" onclick="window.location.href='SecondPage.php'">
            رجوع
        </button>

        <div class="footer-logo">
            <img src="../assets/fim.png" alt="شعار فيم">
        </div>
    </main>
    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>
