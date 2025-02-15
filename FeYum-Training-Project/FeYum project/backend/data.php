<?php
session_start();
require '../backend/conn.php'; // الاتصال بقاعدة البيانات

function getEmployeeData($email) {
    global $pdo;

    try {
        // جلب بيانات الموظف باستخدام البريد الإلكتروني
        $stmt = $pdo->prepare("SELECT * FROM employee_info WHERE work_email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // إعادة بيانات الموظف كصف
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // التعامل مع أي أخطاء في قاعدة البيانات
        echo "Error: " . $e->getMessage();
        return null;
    }
}


function getMessageCount() {
    global $pdo;

    try {
        // استعلام لحساب الرسائل غير المُجابة فقط
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM communication WHERE respond_message_content IS NULL");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

// استعلام للحصول على الرسائل
function getMessages() {
    global $pdo;

    try {
        // استعلام للحصول على الرسائل مع اسم الموظف
        $stmt = $pdo->prepare("
             SELECT c.*, CONCAT(e.first_name, ' ', e.last_name) AS employee_name 
    FROM communication c
    LEFT JOIN employee_info e ON c.employee_id = e.employee_id
    WHERE c.send_message_content IS NOT NULL 
    ORDER BY c.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// جلب البيانات
$messageCount = getMessageCount();
$messages = getMessages();


function getMessageById($message_id) {
    global $pdo; // الوصول إلى كائن الاتصال بقاعدة البيانات

    // تحقق من أن معرف الرسالة صالح
   
    try {
        // إعداد الاستعلام للبحث عن الرسالة بناءً على معرف الرسالة
        $stmt = $pdo->prepare("
         SELECT c.*, 
       CONCAT(e.first_name, ' ', e.last_name) AS employee_name 
FROM communication c
LEFT JOIN employee_info e ON c.employee_id = e.employee_id
WHERE c.send_message_content IS NOT NULL 
  AND (:message_id IS NULL OR c.message_id = :message_id) -- شرط البحث باستخدام معرف الرسالة
ORDER BY c.created_at DESC");
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // جلب البيانات كـ Array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // التعامل مع الأخطاء في حالة وجودها
        return "Error: " . $e->getMessage();
    }
}

// الحصول على معرف الرسالة من الـ GET
$message_id = $_GET['message_id'] ?? null;

// استدعاء الدالة لجلب تفاصيل الرسالة
$message = getMessageById($message_id);

if (is_array($message)) {
    // إذا تم العثور على الرسالة
    // يمكنك الآن استخدام محتوى الرسالة داخل $message
    // على سبيل المثال:
    // echo $message['employee_name'];
} else {
    // إذا كانت هناك مشكلة أو لم يتم العثور على الرسالة
    echo $message;
}


function fetchEmployees() {
    global $pdo;

    try {
        // استعلام لجلب بيانات الموظفين المطلوبة
        $sql = "SELECT employee_id, first_name, last_name, job_title FROM employee_info";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $employees;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}


?>



