
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات التواصل</title>

    <!-- ربط الملفات -->
    <link rel="stylesheet" href="../css/style response copy.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!--  Bootstrap -->
</head>
<body>
    <div class="container">
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

        <!-- المحتوى الرئيسي -->
       
<main class="content">
    <header>
        <h1>تفاصيل الرسالة</h1>
    </header>
    <?php
         require_once  '../backend/data.php'; 
         ?>
       
    <?php if ($message): ?>
        <div class="card">
            <h1><?php echo htmlspecialchars($message['employee_name'] ?? ''); ?></h1>

            <div class="field">
                <label>العنوان:</label>
                <div class="value"><?php echo htmlspecialchars($message['send_title']); ?></div>
            </div>

            <div class="field">
                <label>الموضوع:</label>
                <div class="value"><?php echo htmlspecialchars($message['send_message_content']); ?></div>
            </div>

            <form action="../backend/submit_response.php?message_id=<?php echo $message_id; ?>" method="POST">

                <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>">

                <div class="field">
                    <label>الـــرد:</label>
                    <input type="text" 
                        name="respond_title" 
                        id="title" 
                        value="<?php echo !empty($message['respond_title']) ? htmlspecialchars($message['respond_title']) : ''; ?>"
                        placeholder="العنوان" 
                        required>
                </div>

                <div class="field">
                    <label>الموضوع:</label>
                    <textarea name="respond_message_content" 
                        id="subject" 
                        placeholder="الموضوع" 
                        required><?php echo !empty($message['respond_message_content']) ? htmlspecialchars($message['respond_message_content']) : ''; ?></textarea>
                    </div>

                <div class="buttons">
                    <button type="button" onclick="window.location.href='Communication requests.php'">تجاهل</button>
                    <button type="submit">رد</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p>الرسالة غير موجودة.</p>
    <?php endif; ?>
 </main>


        </main>
    </div>
    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>

