<?php
session_start();
require '../backend/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM hr WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION['hr_id'] = $user['hr_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['loggedin'] = true;

            header("Location: ../php/hr dashboard.php");
            exit();
        } else {
            // بدلاً من الانتقال إلى صفحة بيضاء، نقوم بإرجاع إلى صفحة تسجيل الدخول مع رسالة خطأ
            $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة";
            header("Location: ../php/Login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "حدث خطأ في الاتصال بقاعدة البيانات. حاول مرة أخرى لاحقًا";
        header("Location: ../php/Login.php");
        exit();
    }
}
?>