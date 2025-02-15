<?php
// Start the session before any output
session_start();
require_once '../backend/conn.php';

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if employee_id is set and not empty
if (!isset($_GET['employee_id']) || empty($_GET['employee_id'])) {
    $_SESSION['error'] = "لم يتم تحديد الموظف.";
    header("Location: Employee Dashboard.php");
    exit();
}

$employee_id = $_GET['employee_id'];

// Fetch employee data
try {
    $stmt = $pdo->prepare("SELECT * FROM employee_info WHERE employee_id = ?");
    $stmt->execute([$employee_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        $_SESSION['error'] = "الموظف غير موجود.";
        header("Location: Employee Dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Handle update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Sanitize and validate input data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $nationality = trim($_POST['nationality'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $salary = trim($_POST['salary'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $job_number = trim($_POST['job_number'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    $id_number = trim($_POST['id_number'] ?? '');
    $branch = trim($_POST['branch'] ?? ''); // New line for branch

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($branch)) { // Updated validation
        $_SESSION['error'] = "الاسم الأول، الاسم الأخير، البريد الإلكتروني، والفرع مطلوبين.";
        $_SESSION['open_modal'] = true;
    } else {
        try {
            $update_stmt = $pdo->prepare("
                UPDATE employee_info SET
                    first_name = ?,
                    last_name = ?,
                    nationality = ?,
                    work_email = ?,
                    salary = ?,
                    work_mobile = ?,
                    job_number = ?,
                    job_title = ?,
                    Id_number = ?,
                    branch = ?
                WHERE employee_id = ?
            ");

            $update_stmt->execute([
                $first_name,
                $last_name,
                $nationality,
                $email,
                $salary,
                $mobile,
                $job_number,
                $job_title,
                $id_number,
                $branch,        // New parameter for branch
                $employee_id
            ]);

            // Redirect with success message
            header("Location: Employee Info.php?employee_id=" . urlencode($employee_id) . "&success=1");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "خطأ في تحديث بيانات الموظف: " . $e->getMessage();
            $_SESSION['open_modal'] = true;
        }
    }
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_employee'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    try {
        $delete_stmt = $pdo->prepare("DELETE FROM employee_info WHERE employee_id = ?");
        $delete_stmt->execute([$employee_id]);
        header("Location: Employee Dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting employee: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تفاصيل الموظف</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/EmployeInfo1.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <style>
        /* Ensure input fields have the default text cursor */
        .employee-form input,
        .employee-form select,
        .employee-form textarea {
            cursor: text;
        }

        /* Override any unwanted cursor styles in the modal */
        #editEmployeeModal .form-control {
            cursor: text !important;
            pointer-events: auto !important;
        }

        .btn-save {
            background-color: #B10000 !important;
            border-color: #B10000 !important;
            color: #fff !important;
        }

        /* Optional: Hover and Focus States for Save Button */
        .btn-save:hover,
        .btn-save:focus {
            background-color: #a00000 !important;
            border-color: #a00000 !important;
            color: #fff !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Fim Logo -->
            <div class="logo">
                <img src="../assets/fim.png" alt="شعار فيم" class="img-fluid">
            </div>

            <!-- HR Dropdown Menu -->

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
                        <i class="bi bi-upload"></i> الوظائف
                    </a>
                    <a href="Jobs Request.php">
                        <i class="bi bi-file-earmark-text"></i> طلبات التوظيف
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <div class="employee-header">
                    <h1>الموظف <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h1>
                </div>
            </header>

            <!-- Success Message -->
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    تم تحديث بيانات الموظف بنجاح.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="action-buttons d-flex mb-3">

                <!-- Delete Button Form -->
                <form method="POST" action="Employee Info.php?employee_id=<?php echo urlencode($employee_id); ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟ هذا الإجراء غير قابل للإلغاء.');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit" name="delete_employee" class="btn btn-danger me-2">
                        <i class="bi bi-trash"></i> حذف
                    </button>
                </form>

                <!-- Edit Button to Open Modal -->
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                    <i class="bi bi-pencil-square"></i> تعديل
                </button>
            </div>

            <!-- Employee Details Form (Read-Only) -->
            <form class="employee-form">
                <div class="form-group mb-3">
                    <label for="first-name" class="form-label">الاسم الأول</label>
                    <input type="text" id="first-name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="last-name" class="form-label">الاسم الأخير</label>
                    <input type="text" id="last-name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="nationality" class="form-label">الجنسية</label>
                    <input type="text" id="nationality" value="<?php echo htmlspecialchars($employee['nationality'] ?? ''); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($employee['work_email'] ?? ''); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="salary" class="form-label">الراتب</label>
                    <input type="text" id="salary" value="<?php echo htmlspecialchars($employee['salary'] ?? ''); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="mobile" class="form-label">رقم الجوال</label>
                    <input type="text" id="mobile" value="<?php echo htmlspecialchars($employee['work_mobile'] ?? ''); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="job_number" class="form-label">الرقم الوظيفي</label>
                    <input type="text" id="job_number" value="<?php echo htmlspecialchars($employee['job_number'] ?? ''); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="job_title" class="form-label">المسمى الوظيفي</label>
                    <input type="text" id="job_title" value="<?php echo htmlspecialchars($employee['job_title'] ?? ''); ?>" readonly class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="id_number" class="form-label">رقم الهوية</label>
                    <input type="text" id="id_number" value="<?php echo htmlspecialchars($employee['Id_number'] ?? ''); ?>" readonly class="form-control">
                </div>

                <!-- New Branch Field -->
                <div class="form-group mb-3">
                    <label for="branch" class="form-label">الفرع</label>
                    <input type="text" id="branch" value="<?php echo htmlspecialchars($employee['branch'] ?? ''); ?>" readonly class="form-control">
                </div>
            </form>
        </main>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="Employee Info.php?employee_id=<?php echo urlencode($employee_id); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">تعديل بيانات الموظف</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <input type="hidden" name="update_employee" value="1">

                        <!-- Form fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit-first-name" class="form-label">الاسم الأول</label>
                                <input type="text" class="form-control" id="edit-first-name" name="first_name" value="<?php echo htmlspecialchars($employee['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-last-name" class="form-label">الاسم الأخير</label>
                                <input type="text" class="form-control" id="edit-last-name" name="last_name" value="<?php echo htmlspecialchars($employee['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-nationality" class="form-label">الجنسية</label>
                            <input type="text" class="form-control" id="edit-nationality" name="nationality" value="<?php echo htmlspecialchars($employee['nationality'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="edit-email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="edit-email" name="email" value="<?php echo htmlspecialchars($employee['work_email'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit-salary" class="form-label">الراتب</label>
                            <input type="text" class="form-control" id="edit-salary" name="salary" value="<?php echo htmlspecialchars($employee['salary'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="edit-mobile" class="form-label">رقم الجوال</label>
                            <input type="text" class="form-control" id="edit-mobile" name="mobile" value="<?php echo htmlspecialchars($employee['work_mobile'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="edit-job-number" class="form-label">الرقم الوظيفي</label>
                            <input type="text" class="form-control" id="edit-job-number" name="job_number" value="<?php echo htmlspecialchars($employee['job_number'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="edit-job-title" class="form-label">المسمى الوظيفي</label>
                            <input type="text" class="form-control" id="edit-job-title" name="job_title" value="<?php echo htmlspecialchars($employee['job_title'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="edit-id-number" class="form-label">رقم الهوية</label>
                            <input type="text" class="form-control" id="edit-id-number" name="id_number" value="<?php echo htmlspecialchars($employee['Id_number'] ?? ''); ?>">
                        </div>

                        <!-- New Branch Field -->
                        <div class="mb-3">
                            <label for="edit-branch" class="form-label">الفرع</label>
                            <input type="text" class="form-control" id="edit-branch" name="branch" value="<?php echo htmlspecialchars($employee['branch'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary btn-save">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript (if any) -->
    <script src="../js/employee-delete.js"></script>

    <!-- Auto-open modal if there was an error during update -->
    <?php if (isset($_SESSION['open_modal']) && $_SESSION['open_modal']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
                editModal.show();
            });
        </script>
        <?php unset($_SESSION['open_modal']); ?>
    <?php endif; ?>

    <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
        
</body>
</html>