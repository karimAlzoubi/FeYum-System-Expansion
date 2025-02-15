<?php
require_once '../backend/conn.php';

// Get the job ID from the URL
$job_id = $_GET['id'] ?? null;

if (!$job_id) {
    header("Location: jobs.php?error=NoJobID");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare CV file upload
        $cv_file = $_FILES['cv'] ?? null;
        $cv_path = '';
        
        if ($cv_file && $cv_file['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/cvs/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($cv_file['name'], PATHINFO_EXTENSION);
            $cv_path = $upload_dir . uniqid() . '.' . $file_extension;
            
            move_uploaded_file($cv_file['tmp_name'], $cv_path);

            // Insert document details into the 'documents' table
            $stmt = $pdo->prepare("INSERT INTO documents (file_path, type, uploaded_at) VALUES (:file_path, :type, NOW())");
            $stmt->execute([':file_path' => $cv_path, ':type' => $cv_file['type']]);

            // Get the document ID (cv_document_id)
            $cv_document_id = $pdo->lastInsertId();
        }

        // Insert application into database
        $stmt = $pdo->prepare("
            INSERT INTO applications (
                job_id, full_name, email, mobile_number, experience_summary, Id_number,
                nationality, languages, skills, education_summary, linkedin_profile, cv_document_id, 
                application_date, status
            ) VALUES (
                :job_id, :full_name, :email, :mobile_number, :experience_summary, :Id_number,
                :nationality, :languages, :skills, :education_summary, :linkedin_profile, :cv_document_id,
                NOW(), 'Pending'
            )
        ");

        $stmt->execute([
            ':job_id' => $job_id,
            ':full_name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':mobile_number' => $_POST['phone'],
            'Id_number' => $_POST['Id_number'],
            'nationality' => $_POST['nationality'],
            ':experience_summary' => $_POST['experience'],
            ':languages' => $_POST['languages'],
            ':skills' => $_POST['skills'],
            ':education_summary' => $_POST['qualification'],
            ':linkedin_profile' => $_POST['linkedin'],
            ':cv_document_id' => $cv_document_id ?? null // Use the document ID here
        ]);

        // Get the application ID
        $application_id = $pdo->lastInsertId();
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'application_id' => $application_id]);
        exit();

    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => "حدث خطأ أثناء تقديم الطلب. الرجاء المحاولة مرة أخرى."]);
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب توظيف</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/submitApplication.css">
    <link rel="stylesheet" href="../css/box.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
</head>
<body>
    <!-- Modal -->
    <div id="successModal" class="modal">
    <div class="modal-content">
        <i class="bi bi-check-circle-fill success-icon"></i>
        <h3>تم إرسال رسالتك بنجاح!</h3>
        <p>رقم التتبع الخاص بك هو: <strong>J<span id="trackingNumber"></span></strong></p>
        <button onclick="window.location.href='FirstPage.php'" class="submit-btn">العودة للصفحة الرئيسية</button>
    </div>
</div>

    <main class="main-content">
        <button class="close-btn" onclick="window.location.href='jobs.php'">&times;</button>
        <header>
            <h1>طلب توظيف</h1>
        </header>

        <form id="applicationForm" class="job-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">الاسم كامل</label>
                <input type="text" id="name" name="name" placeholder="الاسم كامل" required>
            </div>
            <div class="form-group">
    <label for="nationality">الجنسية</label>
    <select id="nationality" name="nationality" required>
        <option value="">اختر الجنسية</option>
        <option value="السعودية">السعودية</option>
        <option value="مصر">مصر</option>
        <option value="الإمارات">الإمارات</option>
        <option value="الأردن">الأردن</option>
        <option value="لبنان">لبنان</option>
        <option value="فلسطين">فلسطين</option>
        <option value="الكويت">الكويت</option>
        <option value="عمان">عمان</option>
        <option value="البحرين">البحرين</option>
        <option value="قطر">قطر</option>
        <option value="اليمن">اليمن</option>
        <option value="العراق">العراق</option>
        <option value="سوريا">سوريا</option>
        <option value="الجزائر">الجزائر</option>
        <option value="تونس">تونس</option>
        <option value="المغرب">المغرب</option>
        <option value="ليبيا">ليبيا</option>
        <option value="السودان">السودان</option>
        <option value="موريشيوس">موريشيوس</option>
        <option value="جيبوتي">جيبوتي</option>
        <option value="الصومال">الصومال</option>
        <!-- يمكنك إضافة المزيد من الخيارات هنا -->
    </select>
</div>
<div class="form-group">
            <label for="id_number">رقم الهوية او الاقامة</label>
            <input type="text" id="id_number" name="Id_number" placeholder="رقم الهوية" required>
        </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" placeholder="البريد الإلكتروني" required>
            </div>
            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" placeholder="رقم الهاتف" required>
            </div>
            <div class="form-group">
                <label for="qualification">المؤهل العلمي</label>
                <input type="text" id="qualification" name="qualification" placeholder="المؤهل العلمي" required>
            </div>
            <div class="form-group">
                <label for="experience">الخبرات</label>
                <input type="text" id="experience" name="experience" placeholder="الخبرات" required>
            </div>
            <div class="form-group">
                <label for="languages">اللغات</label>
                <input type="text" id="languages" name="languages" placeholder="اللغات" required>
            </div>
            <div class="form-group">
                <label for="skills">المهارات</label>
                <input type="text" id="skills" name="skills" placeholder="المهارات" required>
            </div>
            <div class="form-group">
                <label for="linkedin">صفحة لينكد إن</label>
                <input type="text" id="linkedin" name="linkedin" placeholder="رابط صفحة LinkedIn">
            </div>
            <div class="form-group">
                <label class="field-label">السيرة الذاتية</label>
                <div class="cv-upload">
                    <input type="file" name="cv" accept=".pdf,.doc,.docx">
                </div>
                
            </div>
            <button type="submit" class="submit-btn">رفع الطلب</button>
        </form>
    </main>

    <script>
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('trackingNumber').textContent = data.application_id;
                    document.getElementById('successModal').style.display = 'block';
                } else {
                    alert(data.error || 'حدث خطأ أثناء تقديم الطلب');
                }
            })
            .catch(error => {
                alert('حدث خطأ أثناء تقديم الطلب. الرجاء المحاولة مرة أخرى.');
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('successModal');
            if (event.target == modal) {
                window.location.href = 'FirstPage.php';
            }
        }
    </script>
    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>