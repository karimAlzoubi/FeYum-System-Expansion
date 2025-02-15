<?php
// استدعاء ملف الاتصال
require_once '../backend/conn.php';

// التحقق من وجود معرف الطلب في URL
if (!isset($_GET['application_id'])) {
    header("Location: Jobs Request.php");
    exit();
}

$application_id = $_GET['application_id'];

// جلب معلومات المتقدم مع معلومات الوظيفة
try {
    $stmt = $pdo->prepare("
        SELECT a.*, j.title as job_title, j.salary, j.location, d.document_id as cv_document_id 
        FROM applications a 
        LEFT JOIN jobs j ON a.job_id = j.job_id 
        LEFT JOIN documents d ON a.cv_document_id = d.document_id
        WHERE a.application_id = ?");
    $stmt->execute([$application_id]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$applicant) {
        header("Location: Jobs Request.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        $status = $_POST['action'] === 'accept' ? 'Accepted' : 'Rejected';
        $pdo->beginTransaction();

        if ($status === 'Rejected') {
            // تحديث حالة الطلب إلى مرفوض
            $updateStmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
            $updateStmt->execute([$status, $application_id]);

            // تحديث max_applications في جدول الوظائف
            $updateStmt = $pdo->prepare("UPDATE jobs SET max_applications = max_applications - 1 WHERE job_id = ?");
            $updateStmt->execute([$applicant['job_id']]);
        
        } else {
            // توليد job_number بشكل تقليدي: E + 5 أرقام عشوائية
            $job_number = 'E' . rand(10000, 99999);
            
            // استخدام location كـ branch
            $branch = $applicant['location'];

            // فك الاسم الكامل
            $fullNameParts = explode(' ', $applicant['full_name'], 2);
            $firstName = $fullNameParts[0] ?? '';
            $lastName = $fullNameParts[1] ?? '';

            // إدخال معلومات الموظف الجديد في جدول employee_info
            $insertStmt = $pdo->prepare("
                INSERT INTO employee_info (first_name, last_name, Id_number, nationality, job_title, job_number, work_mobile, salary, work_email, branch)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
        
            // تنفيذ الاستعلام
            $insertStmt->execute([
                $firstName,
                $lastName,
                $applicant['Id_number'],
                $applicant['nationality'],
                $applicant['job_title'],
                $job_number,
                $applicant['mobile_number'],
                $applicant['salary'],
                $applicant['email'],
                $branch
            ]);

            // تحديث حالة الطلب إلى مقبول
            $updateStmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
            $updateStmt->execute([$status, $application_id]);
            
            // تحديث max_applications في جدول الوظائف
            $updateStmt = $pdo->prepare("UPDATE jobs SET max_applications = max_applications - 1 WHERE job_id = ?");
            $updateStmt->execute([$applicant['job_id']]);
        }
        
        $pdo->commit();
        header("Location: Jobs Request.php");
        exit();
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "حدث خطأ أثناء معالجة الطلب: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link
    href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap">
    <!--  Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/applicantPage.css">
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

    <div class="content">
        <h1 class="page-title">المتقدم <?php echo htmlspecialchars($applicant['full_name']); ?></h1>

        <div class="card">
    <h6 class="card-title">معلومات المتقدم</h6>
    <div class="fields-grid">
    <div class="field">
<label class="field-label">الاسم الكامل:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['full_name']) ? htmlspecialchars($applicant['full_name']) : ''; ?>" readonly>
</div>


<div class="field">
    <label class="field-label">الجنسية :</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['nationality']) ? htmlspecialchars($applicant['nationality']) : ''; ?>" readonly>
</div>


<div class="field">
    <label class="field-label"> رقم الهوية او الاقامة:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['Id_number']) ? htmlspecialchars($applicant['Id_number']) : ''; ?>" readonly>

</div>


<div class="field">
    <label class="field-label">رقم الهاتف:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['mobile_number']) ? htmlspecialchars($applicant['mobile_number']) : ''; ?>" readonly>
</div>

<div class="field">
    <label class="field-label">البريد الإلكتروني:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['email']) ? htmlspecialchars($applicant['email']) : ''; ?>" readonly>
</div>

<div class="field">
    <label class="field-label">المؤهل العلمي:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['education_summary']) ? htmlspecialchars($applicant['education_summary']) : ''; ?>" readonly>
</div>

<div class="field">
    <label class="field-label">الخبرات:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['experience_summary']) ? htmlspecialchars($applicant['experience_summary']) : ''; ?>" readonly>
</div>

<div class="field">
    <label class="field-label">اللغات:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['languages']) ? htmlspecialchars($applicant['languages']) : ''; ?>" readonly>
</div>

<div class="field">
    <label class="field-label">المهارات:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['skills']) ? htmlspecialchars($applicant['skills']) : ''; ?>" readonly>
</div>

<div class="field">
    <label class="field-label">صفحة لينكد إن:</label>
    <input type="text" class="field-input" value="<?php echo !empty($applicant['linkedin_profile']) ? htmlspecialchars($applicant['linkedin_profile']) : ''; ?>" readonly>
</div>  


<!--معلومات السيرة الذاتية  -->
<?php if (!empty($applicant['cv_document_id'])): ?>
                <div class="field">
                    <label class="field-label">السيرة الذاتية:</label>
                    <div class="file-section">
                        <input type="text" class="field-input" value="السيرة الذاتية.pdf" readonly>
                        <a href="download_cv.php?document_id=<?php echo htmlspecialchars($applicant['cv_document_id']); ?>" target="_blank" class="view-file-btn">عرض</a>
                    </div>
                </div>
            <?php endif; ?>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="card-buttons">
        <button type="submit" name="action" value="reject" class="btn reject-btn">رفض</button>
        <button type="submit" name="action" value="accept" class="btn accept-btn">قبول</button>
    </form>
</div>
        <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
    </body>
</html>