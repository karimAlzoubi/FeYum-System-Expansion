<?php
// استدعاء ملف الاتصال
require_once '../backend/conn.php';

// جلب بيانات الموظفين
try {
    $stmt = $pdo->prepare("SELECT * FROM employee_info");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $employeeCount = count($employees);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموظفين</title>
    <link rel="stylesheet" href="../css/stylejr.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
        <!-- الشريط الجانبي -->
        <aside class="sidebar">
            <!-- شعار فيم -->
            <div class="logo">
                <img src="../assets/fim.png" alt="شعار فيم"> 
            </div>

            <!-- القائمة المنسدلة للموارد البشرية -->
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

        <!-- منتصف الصفحة -->
        <main class="content">
            <header>
                <h1>الموظفين</h1>
                <div class="action-buttons">
                    <div class="action-buttons">
                        <button><i class="bi bi-filter"></i></button>
                        <button id="search-btn"><i class="bi bi-search"></i></button>
                        <input type="text" id="search-input" placeholder="بحث..." style="display: none;">
                        <button><i class="bi bi-pencil-square"></i></button>
            </header>

            <div class="messages">
                <h3>عدد الموظفين: <?php echo $employeeCount; ?></h3>
                
                <?php foreach($employees as $employee): ?>
                <div class="message-card">
                    <span class="icon">
                        <?php if(!empty($employee['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($employee['profile_image']); ?>" alt="صورة الموظف">
                        <?php endif; ?>
                    </span>
                    <div class="info">
                        <h4><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h4>
                        <p><?php echo htmlspecialchars($employee['job_title'] ?? 'غير محدد'); ?></p>
                    </div>
                    <button class="details-btn" onclick="window.location.href='Employee Info.php?employee_id=<?php echo $employee['employee_id']; ?>'">
                        <i class="bi bi-info-circle"></i> التفاصيل
                    </button>
                </div>
                <?php endforeach; ?>

                <?php if(empty($employees)): ?>
                    <p class="no-data">لا يوجد موظفين حالياً</p>
                <?php endif; ?>
            </div>
        </main>
    <script>
        // إظهار وإخفاء شريط البحث عند النقر على زر البحث
        document.getElementById("search-btn").addEventListener("click", function () {
            let searchInput = document.getElementById("search-input");

            if (searchInput.style.display === "none" || searchInput.style.display === "") {
                searchInput.style.display = "block";
                searchInput.focus();
            } else {
                searchInput.style.display = "none";
            }
        });

        // تنفيذ البحث عند الضغط على Enter
        document.getElementById("search-input").addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                searchMessages();
                this.style.display = "none";
            }
        });

        // دالة البحث عن الأسماء
        function searchMessages() {
            let searchQuery = document.getElementById("search-input").value.toLowerCase();
            let messages = document.querySelectorAll(".message-card");

            messages.forEach(function (msg) {
                let employeeName = msg.querySelector("h4").textContent.toLowerCase();
                if (employeeName.includes(searchQuery)) {
                    msg.style.display = "block";
                } else {
                    msg.style.display = "none";
                }
            });
        }

        // إخفاء شريط البحث عند النقر خارج الزر
        document.addEventListener("click", function (event) {
            let searchInput = document.getElementById("search-input");
            let searchBtn = document.getElementById("search-btn");

            if (event.target !== searchInput && event.target !== searchBtn) {
                searchInput.style.display = "none";
            }
        });
    </script>

    <style>
        .action-buttons button.active {
            background-color: rgb(74, 74, 72);
            color: black;
        }
        #search-input {
            padding: 5px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
            width: 150px;
        }
    </style>


<button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
        
</body>
</html>