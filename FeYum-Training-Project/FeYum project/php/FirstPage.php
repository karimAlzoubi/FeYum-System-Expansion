
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>واجهة المستخدم</title>
    <!--  CSS   ربط-->
    <link rel="stylesheet" href="../css/firstPage.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    
    <!-- للخط -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    
    <!--  Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bodyFirstPage">
    <div class="logoFirstPage">
        <img src="../assets/fim.png" alt="شعار فيم"> 
    </div>
    <div class="cardsFirstPage">
        <div class="cardFirstPage">
            <h3>تسجيل دخول المنشآت</h3>
            <button onclick="window.location.href='login.php'">دخول</button>
        </div>
        <div class="cardFirstPage">
            <h3>تتبع الطلب</h3>
            <button onclick="window.location.href='TrackPage.php'">دخول</button>
        </div>
        <div class="cardFirstPage">
            <h3>رفع طلب</h3>
            <button onclick="window.location.href='SecondPage.php'">دخول</button>
        </div>
    </div>    
    </div>

    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
        
</body>
</html>
