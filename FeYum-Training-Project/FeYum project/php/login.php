<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل الدخول</title>
  <!-- CSS -->
  <link rel="stylesheet" href="../css/styleLogin.css">
  <link rel="stylesheet" href="../css/dark-mode.css">
    <!--  Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="login-container">
    <div class="logo">
        <img src="../assets/fim.png" alt="شعار فيم">
    </div>
    <form id="loginForm" action="../backend/login.php" method="POST" class="login-form">
      <?php
      // عرض رسالة الخطأ إذا وجدت
      if(isset($_SESSION['error'])) {
          echo '<div class="error-message" style="display: block;">' . $_SESSION['error'] . '</div>';
          unset($_SESSION['error']); // حذف رسالة الخطأ بعد عرضها
      }
      ?>
      
      <label for="email">اسم المستخدم</label>
      <input type="text" id="email" name="email" placeholder="demo@example.com" required>
      
      <label for="password">كلمة المرور</label>
      <input type="password" id="password" name="password" placeholder="demo123" required>
      
      <button type="submit" class="login-button">تسجيل الدخول</button>
    </form>
  </div>
  <script src="../js/script.js"></script>
  <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>