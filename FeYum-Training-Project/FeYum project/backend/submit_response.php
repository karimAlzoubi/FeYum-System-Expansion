<?php
session_start();
require '../backend/conn.php'; // الاتصال بقاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استخدام PDO بدلاً من mysql_real_escape_string
    $message_id = isset($_POST['message_id']) ? $_POST['message_id'] : '';
    $respond_title = isset($_POST['respond_title']) ? $_POST['respond_title'] : '';
    $respond_message_content = isset($_POST['respond_message_content']) ? $_POST['respond_message_content'] : '';

    // تحقق من وجود المدخلات
    if ($message_id && $respond_title && $respond_message_content) {
        try {
            // استرجاع القيم من الرسالة الأصلية
            $stmt = $pdo->prepare("SELECT * FROM communication WHERE message_id = :message_id");
            $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
            $stmt->execute();
            $originalMessage = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($originalMessage) {
                // تحديث السجل الموجود بإضافة الرد
                $sql = "UPDATE communication SET 
                        respond_message_content = :respond_message_content,
                        respond_title = :respond_title,
                        created_at = NOW() 
                        WHERE message_id = :message_id";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':respond_message_content', $respond_message_content, PDO::PARAM_STR);
                $stmt->bindParam(':respond_title', $respond_title, PDO::PARAM_STR);
                $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);

                // تنفيذ الاستعلام
                $stmt->execute();

                // التوجيه بعد النجاح
                header("Location: ../php/Communication requests.php");
                exit();
            } else {
                echo "الرسالة غير موجودة.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "يرجى ملء جميع الحقول.";
    }
}
?>
