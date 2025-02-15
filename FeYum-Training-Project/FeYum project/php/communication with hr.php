<?php
session_start();
require_once '../backend/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تهيئة مصفوفة الاستجابة
    $response = ['success' => false];

    try {
        // استلام البيانات من النموذج
        $title = $_POST['title'] ?? '';
        $subject = $_POST['subject'] ?? '';

        // التحقق من وجود معرّف الموظف في الجلسة
        if (isset($_SESSION['employee_id'])) {
            $employee_id = $_SESSION['employee_id'];
        } else {
            $response['error'] = 'لا يوجد موظف مسجل في الجلسة.';
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response);
            exit;
        }

        // تحضير الاستعلام لإدخال البيانات
        $query = "INSERT INTO communication (send_title, send_message_content, employee_id) 
                  VALUES (:title, :subject, :employee_id)";
        $stmt = $pdo->prepare($query);

        // ربط القيم
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':subject', $subject);
        $stmt->bindValue(':employee_id', $employee_id);

        // تنفيذ الاستعلام
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message_id'] = $pdo->lastInsertId();
        } else {
            $response['error'] = 'حدث خطأ أثناء إرسال الرسالة';
        }
    } catch (PDOException $e) {
        $response['error'] = 'حدث خطأ في قاعدة البيانات: ' . $e->getMessage();
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
    exit;
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل مع الموارد البشرية</title>
    <link rel="stylesheet" href="../css/style communication with hr.css">
    <link rel="stylesheet" href="../css/box.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!-- Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <i class="bi bi-check-circle-fill success-icon"></i>
            <h3>تم إرسال رسالتك بنجاح!</h3>
            <p>رقم التتبع الخاص بك هو: <strong>M<span id="trackingNumber"></span></strong></p>
            <button onclick="window.location.href='FirstPage.php'" class="submit-btn">العودة للصفحة الرئيسية</button>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="../assets/fim.png" alt="شعار فيم">
            </div>
            <h6>تواصل مع الموارد البشرية</h6>
        </div>

        <form id="communicationForm" method="POST">
            <div class="form-group">
                <label for="title">العنوان</label>
                <input type="text" id="title" name="title" placeholder="العنوان" required>
            </div>
            <div class="form-group">
                <label for="subject">الموضوع</label>
                <textarea id="subject" name="subject" placeholder="الموضوع" required></textarea>
            </div>

            <div class="buttons">
                <button type="button" class="btn-back" id="back-btn">رجوع</button>
                <button type="submit">إرسال</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('communicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('trackingNumber').textContent = data.message_id;
                    document.getElementById('successModal').style.display = 'block';
                } else {
                    alert(data.error || 'حدث خطأ أثناء إرسال الرسالة');
                }
            })
            .catch(error => {
                alert('حدث خطأ أثناء إرسال الرسالة. الرجاء المحاولة مرة أخرى.');
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('successModal');
            if (event.target == modal) {
                window.location.href = 'FirstPage.php';
            }
        }

        // زر الرجوع
        document.getElementById('back-btn').addEventListener('click', function() {
            window.location.href = 'SecondPage.php';
        });
    </script>

<button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>

        
</body>
</html>