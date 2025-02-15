<?php
session_start();

if (!isset($_SESSION['request'])) {
    // Redirect back to the tracking page if no request details are available
    header("Location: ../html/TrackPage.php");
    exit();
}

$request = $_SESSION['request']; // Retrieve request details
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ربط الملفات -->
    <link rel="stylesheet" href="../css/Follow up communication request.css"> <!-- ربط ملف CSS الخاص بتنسيق الصفحة -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <!-- تضمين خط Tajawal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <!--  Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ربط مكتبة أيقونات Bootstrap -->
</head>

<body>
    <div class="container">

        <!-- المحتوى الرئيسي -->
        <main class="content">
            <!-- عنوان الصفحة -->
            <div class="card">
                <div align="center">
                    <img src="../assets/fim.png" alt="شعار فيم">
                </div>

                <!-- تفاصيل الطلب -->
                <div class="field">
                    <label>العنوان:</label>
                    <div class="value"><?php echo htmlspecialchars($request['send_title'] ?? ''); ?></div>
                </div>

                <div class="field">
                    <label>الموضوع:</label>
                    <div class="value"><?php echo htmlspecialchars($request['send_message_content'] ?? ''); ?></div>
                </div>

                <!-- قسم رد مسؤول الموارد البشرية -->
                <div align="center">
                    <h3>رد موظف الموارد البشرية </h3>
                </div>

                <div class="field">
                    <div class="value"><?php echo htmlspecialchars($request['respond_title'] ?? ''); ?></div>
                </div>

                <div class="field">
                    <div class="value"><?php echo htmlspecialchars($request['respond_message_content'] ?? ''); ?></div>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="buttons">
                    <button type="button" onclick="window.location.href='TrackPage.php'">رجوع</button> <!-- زر الرجوع -->
                </div>
            </div>
        </main>
    </div>

    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>

</body>
</html>