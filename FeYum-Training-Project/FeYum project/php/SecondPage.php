<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>واجهة الوظائف</title>
  <link rel="stylesheet" href="../css/styleSecondPage.css">
  <link rel="stylesheet" href="../css/dark-mode.css">
      <!--  Bootstrap -->
    
    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- إضافة خط Tajawal -->
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  
</head>
<body>
  <div class="container">
    <!-- الشعار -->
    <div class="logo">
      <img src="../assets/fim.png" alt="شعار">
    </div>

    <!-- الخيارات -->
    <div class="options">
      <button class="option-btn" onclick="window.location.href='jobs.php'">الوظائف</button>
      <button class="option-btn" onclick="window.location.href='ID and Name.php'">التواصل</button>
    </div>

    <!-- زر الرجوع -->
    <button class="back-btn" onclick="window.location.href='FirstPage.php'">رجوع</button>
  </div>

  <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>
