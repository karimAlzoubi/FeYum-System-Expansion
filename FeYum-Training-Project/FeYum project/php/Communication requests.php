<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات التواصل</title>

    <!--  CSS   ربط-->
    <link rel="stylesheet" href="../css/stylejr.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    
    <!-- للخط -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    
    <!--  Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="containgyjher">
    
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
     
    <!-- الرسائل -->
     <!-- Main content -->
     <main class="content">
            <header>
                <?php require_once '../backend/data.php'; ?>
                <h1>طلبات التواصل</h1>
                <p>عدد الرسائل التي لم يتم الرد عليها: <span><?php echo $messageCount; ?></span></p>

                <div class="action-buttons">
                    <button id="filter-btn"><i class="bi bi-filter"></i></button>
                    <button id="search-btn"><i class="bi bi-search"></i></button>
                    <input type="text" id="search-input" placeholder="ابحث عن اسم..." style="display: none;" />
                    <button><i class="bi bi-pencil-square"></i></button>
                </div>
            </header>

            <!-- الرسائل -->
            <div class="messages">
                <h3>اليوم</h3>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message-card" data-status="<?php echo $message['respond_message_content'] ? 'responded' : 'not_responded'; ?>">           
                            <span class="icon">
                                <i class="bi bi-envelope"></i>
                </span>
                <div class="info">
                    <h4><?php echo htmlspecialchars($message['employee_name'] ?? ''); ?></h4>
                    <p><?php echo htmlspecialchars(date('h:i A', strtotime($message['created_at'] ?? '')) . ' - ' . ($message['respond_message_content'] ? 'تم الرد عليه' : 'لم يتم الرد عليه')); ?></p>
                </div>
                <a href="response.php?message_id=<?php echo $message['message_id']; ?>">
                    <button class="details-btn">
                        <i class="bi bi-info-circle"></i> التفاصيل
                    </button>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا توجد رسائل جديدة.</p>
    <?php endif; ?>
</div>
</div>
</main>
</div>    

<button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
<script>
        // تفعيل الفلتر لإظهار الرسائل غير المستجابة فقط
        let filterState = 0; // 0 = جميع الرسائل، 1 = لم يتم الرد، 2 = تم الرد

        document.getElementById("filter-btn").addEventListener("click", function () {
            var messages = document.querySelectorAll(".message-card");

            if (filterState === 0) {
                // إظهار فقط الرسائل التي لم يتم الرد عليها
                messages.forEach(msg => {
                    if (msg.getAttribute("data-status") === "responded") {
                        msg.style.display = "none";
                    } else {
                        msg.style.display = "block";
                    }
                });
                this.classList.add("active");
                this.innerHTML = '<i class="bi bi-filter"></i> '; // تحديث نص الزر
                filterState = 1;
            } else if (filterState === 1) {
                // إظهار فقط الرسائل التي تم الرد عليها
                messages.forEach(msg => {
                    if (msg.getAttribute("data-status") === "not_responded") {
                        msg.style.display = "none";
                    } else {
                        msg.style.display = "block";
                    }
                });
                this.classList.add("active");
                this.innerHTML = '<i class="bi bi-filter"></i>'; // تحديث نص الزر
                filterState = 2;
            } else {
                // إظهار جميع الرسائل
                messages.forEach(msg => msg.style.display = "block");
                this.classList.remove("active");
                this.innerHTML = '<i class="bi bi-filter"></i>'; // إعادة نص الزر الافتراضي
                filterState = 0;
            }
        });

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
</body>
</html>