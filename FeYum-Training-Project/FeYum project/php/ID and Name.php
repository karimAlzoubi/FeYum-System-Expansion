<?php
            session_start(); // بدء الجلسة
            require_once '../backend/conn.php';

            // رسالة الخطأ الافتراضية
            $error_message = '';

            // التحقق عند إرسال البيانات
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $first_name = $_POST['first_name'] ?? '';
                $last_name = $_POST['last_name'] ?? '';
                $employee_id = $_POST['employee_id'] ?? '';

                try {
                    // تحقق من قاعدة البيانات
                    $query = "SELECT * FROM employee_info WHERE first_name = ? AND last_name = ? AND job_number = ?";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$first_name, $last_name, $employee_id]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$result) {
                        $error_message = 'بيانات غير صحيحة. الرجاء التحقق.';
                    } else {
                        // حفظ بيانات الموظف في الجلسة
                        $_SESSION['employee_id'] = $result['employee_id']; 
                        header('Location: communication with hr.php');
                        exit;
                    
                    }
                } catch (PDOException $e) {
                    $error_message = 'حدث خطأ في الاتصال بقاعدة البيانات';
                }
            }
            ?>

            

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع الطلب</title>
    <link rel="stylesheet" href="../css/style ID and Name.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <!--  Bootstrap -->
    
    <!--  Bootstrap Icons   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- شعار -->
        <div class="logo">
            <img src="../assets/fim.png" alt="شعار فيم"> 
        </div>
        <!-- نموذج الإدخال -->
        <form method="POST">
            <div class="form-container">
                <label for="first-name">الاسم الأول</label>
                <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required />
            </div>
            <div class="form-container">
                <label for="last-name">الاسم الأخير</label>
                <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required />
            </div>
            <div class="form-container">
                <label for="employee-id">الرقم الوظيفي</label>
                <input type="text" id="employee-id" name="employee_id" value="<?php echo htmlspecialchars($_POST['employee_id'] ?? ''); ?>" required />
            </div>

            <!-- رسالة خطأ -->
            <?php if ($error_message): ?>
                <p class="error-message" style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <!-- أزرار -->
            <div class="buttons">
                <button type="button" id="back-btn">رجوع</button>
                <button type="submit" id="track-btn">متابعة</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('back-btn').addEventListener('click', function() {
            window.location.href = 'SecondPage.php';
        });

        document.getElementById('track-btn').addEventListener('click', function(event) {
            const firstName = document.getElementById('first-name').value.trim();
            const lastName = document.getElementById('last-name').value.trim();
            const employeeId = document.getElementById('employee-id').value.trim();

            if (!firstName || !lastName || !employeeId) {
                alert('يرجى ملء جميع الحقول.');
                event.preventDefault();
            }
        });
    </script>

<button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>

</body>
</html>