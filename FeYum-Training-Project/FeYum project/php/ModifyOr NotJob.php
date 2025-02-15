<?php
// استدعاء ملف الاتصال
require_once '../backend/conn.php';

// التحقق من وجود معرف الوظيفة في URL
if (!isset($_GET['job_id'])) {
    header("Location: Job raise.php");
    exit();
}

$job_id = $_GET['job_id'];

// جلب تفاصيل الوظيفة
try {
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = ?");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        header("Location: Job raise.php");
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

// معالجة تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
      $sql = "UPDATE jobs SET 
              title = ?,
              description = ?,
              salary = ?,
              location = ?,
              deadline = ?,
              status = ?,
              max_applications = ?,
              posted_by = ?,
              education = ?,
              skills = ?,
              experience = ?,
              languages = ?
              WHERE job_id = ?";
              
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
          $_POST['title'],
          $_POST['description'],
          $_POST['salary'],
          $_POST['location'],
          $_POST['deadline'],
          $_POST['status'],
          $_POST['max_applications'],
          $_POST['posted_by'],
          $_POST['education'],
          $_POST['skills'],
          $_POST['experience'],
          $_POST['languages'],
          $job_id
      ]);
      
      $success_message = "تم تحديث الوظيفة بنجاح";
  } catch(PDOException $e) {
      $error_message = "حدث خطأ أثناء التحديث: " . $e->getMessage();
  }
}
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل وظيفة</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/ModifyJob.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
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

    <div class="container">
        <main class="main-content">
            <header>
                <h1>تفاصيل الوظيفة</h1>
            </header>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form class="job-form" method="POST">
                <div class="form-group">
                    <label for="title">العنوان</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($job['title'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="description">الوصف</label>
                    <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($job['description'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="salary">الراتب</label>
                    <input type="text" id="salary" name="salary" value="<?php echo htmlspecialchars($job['salary'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="location">الموقع</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($job['location'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="deadline">آخر موعد</label>
                    <input type="date" id="deadline" name="deadline" value="<?php echo htmlspecialchars($job['deadline'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="status">الحالة</label>
                    <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($job['status'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="max_applications">أقصى عدد للطلبات</label>
                    <input type="number" id="max_applications" name="max_applications" value="<?php echo htmlspecialchars($job['max_applications'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="posted_by">تم النشر بواسطة</label>
                    <input type="number" id="posted_by" name="posted_by" value="<?php echo htmlspecialchars($job['posted_by'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="education">المؤهل العلمي</label>
                    <input type="text" id="education" name="education" value="<?php echo htmlspecialchars($job['education'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="skills">المهارات</label>
                    <input type="text" id="skills" name="skills" value="<?php echo htmlspecialchars($job['skills'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="experience">الخبرات</label>
                    <input type="text" id="experience" name="experience" value="<?php echo htmlspecialchars($job['experience'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="languages">اللغات</label>
                    <input type="text" id="languages" name="languages" value="<?php echo htmlspecialchars($job['languages'] ?? ''); ?>">
                </div>

                <div class="buttons">
                    <button type="submit" class="btn-back">حفظ التعديلات</button>
                </div>
                <div class="buttons">
                    <button type="button" class="btn-back" onclick="window.location.href='Job raise.php'">رجوع</button>
                </div>
            </form>
        </main>
    </div>
    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>