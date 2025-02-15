<?php
// استدعاء ملف الاتصال
require_once '../backend/conn.php';

// استعلام لجلب جميع الوظائف مع عدد المتقدمين الحاليين
try {
    $stmt = $pdo->prepare("
        SELECT j.*, 
               (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.job_id) AS current_applicants
        FROM jobs j
    ");
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $jobCount = count($jobs); // عدد الوظائف الكلي
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    die(); // توقف التنفيذ في حالة وجود خطأ
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الوظائف</title>
    <link rel="stylesheet" href="../css/stylejr.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
        <aside class="sidebar">
            <!-- شعار فيم -->
            <div class="logo">
                <img src="../assets/fim.png" alt="شعار فيم"> 
            </div>

            <!-- القائمة المنسدلة للموارد البشرية -->
            <div class="dropdown">
                <button class="dropdown-btn">الموارد البشرية</button>
                <div class="dropdown-content">
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
            <header>
                <h1>الوظائف</h1>
                <div class="action-buttons">
                    <button><i class="bi bi-plus" onclick="window.location.href='UpgradeJob.php'"></i></button>
                    <button><i class="bi bi-filter"></i></button>
                    <button id="search-btn"><i class="bi bi-search"></i></button>
                    <input type="text" id="search-input" placeholder="بحث..." style="display: none;">
                    <button><i class="bi bi-pencil-square"></i></button>
                </div>
            </header>

            <div class="messages">
            <h3>عدد الوظائف المرفوعة: <?php echo $jobCount; ?></h3>

                
                <?php if (!empty($jobs)): ?>
                    <?php foreach($jobs as $job): ?>
                        <?php
                            $currentApplicants = $job['current_applicants'];
                            $maxApplications = $job['max_applications'];
                            $remainingSlots = $maxApplications - $currentApplicants;
                        ?>
                        <div class="message-card">
                            <span class="icon"></span>
                            <div class="info">
                                <h4><?php echo htmlspecialchars($job['title'] ?? 'عنوان غير متوفر'); ?></h4>
                                <p><?php echo htmlspecialchars($job['location'] ?? 'الموقع غير متوفر'); ?></p>
                                <p>عدد المتقدمين الحاليين: <?php echo $currentApplicants; ?></p>
                                <p>المقاعد الشاغرة  : <?php echo $remainingSlots; ?></p>
                                </div>
                            <button class="details-btn" onclick="window.location.href='ModifyOr NotJob.php?job_id=<?php echo htmlspecialchars($job['job_id'] ?? ''); ?>'">
                                <i class="bi bi-info-circle"></i> التفاصيل
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>لا توجد وظائف متاحة حالياً</p>
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