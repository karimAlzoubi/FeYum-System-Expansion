<?php
// استيراد ملف الاتصال
require_once '../backend/conn.php';

$message = ''; // متغير لتخزين رسائل النجاح أو الخطأ

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // استلام البيانات من النموذج
        $title = $_POST['title'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $salary = $_POST['salary'];
        $deadline = $_POST['deadline'];
        $status = $_POST['status'];
        $posted_by = 1; // يمكن تغييره حسب نظام المستخدمين لديك
        $max_applications = $_POST['max_applications'];
        $education = $_POST['education'];
        $experience = $_POST['experience'];
        $skills = $_POST['skills'];
        $languages = $_POST['languages'];

        // إعداد استعلام SQL
        $sql = "INSERT INTO jobs (
            title, description, location, salary, 
            deadline, status, posted_by, max_applications,
            education, experience, skills, languages
        ) VALUES (
            :title, :description, :location, :salary,
            :deadline, :status, :posted_by, :max_applications,
            :education, :experience, :skills, :languages
        )";

        // تحضير وتنفيذ الاستعلام
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':location' => $location,
            ':salary' => $salary,
            ':deadline' => $deadline,
            ':status' => $status,
            ':posted_by' => $posted_by,
            ':max_applications' => $max_applications,
            ':education' => $education,
            ':experience' => $experience,
            ':skills' => $skills,
            ':languages' => $languages
        ]);

        $message = '<div class="alert alert-success">تم إضافة الوظيفة بنجاح</div>';
    } catch(PDOException $e) {
        $message = '<div class="alert alert-danger">حدث خطأ: ' . $e->getMessage() . '</div>';
    }
}
?>




<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع وظيفة</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styleUpgradeJob copy.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
</head>
<body>
    <div class="container">
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

        <main class="main-content">
            <header>
                <h1>رفع وظيفة</h1>
            </header>
            
            <!-- عرض رسالة النجاح أو الخطأ -->
            <?php if(!empty($message)) echo $message; ?>
            
            <form class="job-form" method="POST">
                <div class="form-group">
                    <label for="title">العنوان</label>
                    <input type="text" id="title" name="title" placeholder="العنوان" required>
                </div>

                <div class="form-group">
                    <label for="description">الوصف</label>
                    <textarea id="description" name="description" placeholder="الوصف" required></textarea>
                </div>

                <div class="form-group">
                    <label for="salary">الراتب</label>
                    <input type="text" id="salary" name="salary" placeholder="الراتب" required>
                </div>

                <div class="form-group">
                    <label for="location">الموقع</label>
                    <input type="text" id="location" name="location" placeholder="الموقع" required>
                </div>

                <div class="form-group">
                    <label for="deadline">آخر موعد</label>
                    <input type="date" id="deadline" name="deadline" required>
                </div>

                <div class="form-group">
                    <label for="status">الحالة</label>
                    <select id="status" name="status" required>
                        <option value="active">نشط</option>
                        <option value="pending">قيد المراجعة</option>
                        <option value="closed">مغلق</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="max-applicants">أقصى عدد للطلبات</label>
                    <input type="number" id="max-applicants" name="max_applications" placeholder="أقصى عدد للطلبات" required>
                </div>

                <div class="form-group">
                    <label for="qualifications">المؤهل العلمي</label>
                    <input type="text" id="qualifications" name="education" placeholder="المؤهل العلمي" required>
                </div>

                <div class="form-group">
                    <label for="skills">المهارات</label>
                    <textarea id="skills" name="skills" placeholder="المهارات" required></textarea>
                </div>

                <div class="form-group">
                    <label for="experience">الخبرات</label>
                    <textarea id="experience" name="experience" placeholder="الخبرات" required></textarea>
                </div>

                <div class="form-group">
                    <label for="languages">اللغات</label>
                    <input type="text" id="languages" name="languages" placeholder="اللغات" required>
                </div>

                <button type="submit" class="submit-btn">رفع</button>
            </form>
        </main>
    </div>
    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>