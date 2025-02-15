<?php
session_start();
require_once '../backend/conn.php';

if (!isset($_SESSION['hr_id'])) {
    header("Location: login.php");
    exit();
}

$hr_id = $_SESSION['hr_id'];

try {
    // نقوم بجلب جميع الطلبات بدون تصفية
    $stmtApplications = $pdo->prepare("
        SELECT a.*, j.title as job_title, j.* 
        FROM applications a 
        LEFT JOIN jobs j ON a.job_id = j.job_id
        WHERE (a.status = 'Under Review' OR a.status IS NULL OR a.status = '')
    ");
    $stmtApplications->execute();
    $applications = $stmtApplications->fetchAll(PDO::FETCH_ASSOC);

    $resultData = [];

    if (!empty($applications)) {
        // نحضر أول وظيفة كمرجع للمقارنة
        $stmtJob = $pdo->prepare("SELECT * FROM jobs LIMIT 1");
        $stmtJob->execute();
        $referenceJob = $stmtJob->fetch(PDO::FETCH_ASSOC);

        $inputData = [
            "job" => $referenceJob,
            "applications" => $applications
        ];

        $inputFile = '../python/input_data.json';
        $outputFile = '../python/output_data.json';
        
        file_put_contents($inputFile, json_encode($inputData, JSON_UNESCAPED_UNICODE));

        $command = escapeshellcmd("python ../python/calculate_match.py $inputFile $outputFile");
        $output = shell_exec($command . " 2>&1");

        if ($output === null) {
            throw new Exception("Python script execution failed.");
        }

        if (!file_exists($outputFile)) {
            throw new Exception("No output file generated. Script output: " . $output);
        }

        $resultData = json_decode(file_get_contents($outputFile), true);
        if ($resultData === null) {
            throw new Exception("Invalid JSON output");
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات الوظائف</title>
    <link rel="stylesheet" href="../css/Jop_Request.css">
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
            <header>
                <h1>طلبات الوظائف</h1>
                <div class="action-buttons">
                    <button><i class="bi bi-pencil-square"></i></button>
                    <button id="search-btn"><i class="bi bi-search"></i></button>
                    <input type="text" id="search-input" placeholder="بحث..." style="display: none;">
                    <button><i class="bi bi-filter"></i></button>
                </div>
            </header>

            <div class="messages">
                <h3>عدد المتقدمين الموظفين: <?= count($resultData) ?></h3>
                
                <?php if (!empty($resultData)): ?>
                    <!-- الحل الأول: تعديل حلقة عرض البيانات -->
                    <?php foreach ($resultData as $applicant): ?>
                        <div class="message-card">
                            <span class="icon"></span>
                            <div class="percentage"><?= round($applicant['match_percentage']) ?>%</div>
                            <div class="info">
                                <h4><?= htmlspecialchars($applicant['full_name']) ?></h4>
                                <p><?= htmlspecialchars($applicant['job_title'] ?? 'غير محدد') ?></p>
                            </div>
                            <button class="details-btn" 
                                    onclick="window.location.href='applicantPage.php?application_id=<?= $applicant['application_id'] ?>'">
                                <i class="bi bi-info-circle"></i> التفاصيل
                            </button>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="message-card">
                        <p>لا توجد طلبات توظيف حالياً</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // زر الفلترة
            const filterBtn = document.querySelector(".bi-filter");
            let isDescending = true;

            // زر البحث
            const searchBtn = document.querySelector(".bi-search");

            // دالة تحديث ألوان النسب المئوية
            const updatePercentageColors = () => {
                document.querySelectorAll('.percentage').forEach(element => {
                    const matchValue = parseFloat(element.textContent);
                    if (matchValue >= 70) {
                        element.style.backgroundColor = '#28a745';
                        element.style.color = 'white';
                    } else if (matchValue >= 40) {
                        element.style.backgroundColor = '#ffc107';
                        element.style.color = 'black';
                    } else {
                        element.style.backgroundColor = '#dc3545';
                        element.style.color = 'white';
                    }
                });
            };

            // تطبيق الألوان عند تحميل الصفحة
            updatePercentageColors();

            // فلترة حسب النسبة المئوية
            filterBtn.addEventListener("click", function() {
                const container = document.querySelector(".messages");
                const headerElement = container.querySelector('h3'); // حفظ العنوان
                const cards = Array.from(container.querySelectorAll(".message-card"));

                cards.sort((a, b) => {
                    const percentA = parseFloat(a.querySelector(".percentage").textContent) || 0;
                    const percentB = parseFloat(b.querySelector(".percentage").textContent) || 0;

                    return isDescending ? percentA - percentB : percentB - percentA;
                });

                // إنشاء حاوية مؤقتة للمحتوى المرتب
                const tempContainer = document.createElement('div');

                // إضافة العنوان أولاً
                if (headerElement) {
                    tempContainer.appendChild(headerElement.cloneNode(true));
                }

                // إضافة البطاقات المرتبة
                cards.forEach(card => tempContainer.appendChild(card));

                // استبدال المحتوى القديم بالمحتوى الجديد المرتب
                container.innerHTML = tempContainer.innerHTML;

                isDescending = !isDescending;

                // تغيير أيقونة الترتيب
                filterBtn.innerHTML = isDescending ?
                    '<i class="bi bi-sort-down"></i>' :
                    '<i class="bi bi-sort-up"></i>';

                // تحديث الألوان بعد إعادة الترتيب
                updatePercentageColors();
            });

            // تفعيل القائمة المنسدلة
            document.querySelectorAll('.dropdown-content a').forEach(item => {
                item.addEventListener('click', (e) => {
                    document.querySelectorAll('.dropdown-content a').forEach(link =>
                        link.classList.remove('active'));
                    item.classList.add('active');
                });
            });
        });

        // إظهار وإخفاء شريط البحث عند النقر على زر البحث
        document.getElementById("search-btn").addEventListener("click", function() {
            let searchInput = document.getElementById("search-input");

            if (searchInput.style.display === "none" || searchInput.style.display === "") {
                searchInput.style.display = "block";
                searchInput.focus();
            } else {
                searchInput.style.display = "none";
            }
        });

        // تنفيذ البحث عند الضغط على Enter
        document.getElementById("search-input").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                searchMessages();
                this.style.display = "none";
            }
        });

        // دالة البحث عن الأسماء
        function searchMessages() {
            let searchQuery = document.getElementById("search-input").value.toLowerCase();
            let messages = document.querySelectorAll(".message-card");

            messages.forEach(function(msg) {
                let employeeName = msg.querySelector("h4").textContent.toLowerCase();
                if (employeeName.includes(searchQuery)) {
                    msg.style.display = "block";
                } else {
                    msg.style.display = "none";
                }
            });
        }

        // إخفاء شريط البحث عند النقر خارج الزر
        document.addEventListener("click", function(event) {
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