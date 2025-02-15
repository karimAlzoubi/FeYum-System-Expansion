<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تفاصيل الموظف</title>
  <!--  Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!--  Bootstrap Icons   -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- إضافة الخط Tajawal -->
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <!-- css   -->
  <link rel="stylesheet" href="../css/EmployeInfo1.css">
  <link rel="stylesheet" href="../css/dark-mode.css">
</head>
<body>
    <!-- الشريط الجانبي -->
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

    <!-- المحتوى الرئيسي -->
    <main class="main-content">
        <?php
        include '../backend/data.php';

        if (!isset($_SESSION['email'])) {
            echo "<div class='alert alert-warning'>يرجى تسجيل الدخول لعرض البيانات.</div>";
            exit();
        }

        $email = $_SESSION['email'];
        $employee = getEmployeeData($email);

        if (!$employee) {
            echo "<div class='alert alert-info'>لا توجد بيانات متاحة للموظف.</div>";
            exit();
        }
        ?>

        <header>
            <div class="employee-header">
                <h1>الموظف <?php echo isset($employee['first_name']) && isset($employee['last_name']) ? 
                    htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) : 'غير محدد'; ?></h1>
            </div>
        </header>

        <form class="employee-form">
            <div class="form-group">
                <label for="first-name">الاسم الأول</label>
                <input type="text" id="first-name" 
                    value="<?php echo isset($employee['first_name']) ? htmlspecialchars($employee['first_name']) : ''; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="last-name">الاسم الأخير</label>
                <input type="text" id="last-name" 
                    value="<?php echo isset($employee['last_name']) ? htmlspecialchars($employee['last_name']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="nationality">الجنسية</label>
                <input type="text" id="nationality" 
                    value="<?php echo isset($employee['nationality']) ? htmlspecialchars($employee['nationality']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" 
                    value="<?php echo isset($employee['work_email']) ? htmlspecialchars($employee['work_email']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="salary">الراتب</label>
                <input type="text" id="salary" 
                    value="<?php echo isset($employee['salary']) ? htmlspecialchars($employee['salary']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="mobile">رقم الجوال</label>
                <input type="text" id="mobile" 
                    value="<?php echo isset($employee['work_mobile']) ? htmlspecialchars($employee['work_mobile']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="employment">المسمى الوظيفي</label>
                <input type="text" id="employment" 
                    value="<?php echo isset($employee['job_title']) ? htmlspecialchars($employee['job_title']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="id-number">رقم الهوية</label>
                <input type="text" id="id-number" 
                    value="<?php echo isset($employee['Id_number']) ? htmlspecialchars($employee['Id_number']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="branch">الفرع</label>
                <input type="text" id="branch" 
                    value="<?php echo isset($employee['branch']) ? htmlspecialchars($employee['branch']) : ''; ?>" readonly>
            </div>
        </form>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>
</html>