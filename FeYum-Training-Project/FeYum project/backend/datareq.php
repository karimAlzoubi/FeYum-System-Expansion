<?php
// في بداية الملف الرئيسي
require_once 'conn.php';

// استعلام لفلترة البيانات
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter === 'responded') {
    $query = "SELECT c.*, e.first_name, e.last_name 
              FROM communication c
              LEFT JOIN employee_info e ON c.employee_id = e.employee_id
              WHERE c.respond_message_content IS NOT NULL";
} elseif ($filter === 'unresponded') {
    $query = "SELECT c.*, e.first_name, e.last_name 
              FROM communication c
              LEFT JOIN employee_info e ON c.employee_id = e.employee_id
              WHERE c.respond_message_content IS NULL";
} elseif ($filter === 'alphabetical') {
    $query = "SELECT c.*, e.first_name, e.last_name 
              FROM communication c
              LEFT JOIN employee_info e ON c.employee_id = e.employee_id
              ORDER BY e.first_name, e.last_name";
} else {
    $query = "SELECT c.*, e.first_name, e.last_name 
              FROM communication c
              LEFT JOIN employee_info e ON c.employee_id = e.employee_id
              ORDER BY c.created_at DESC";
}

try {
    $stmt = $pdo->query($query);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $messageCount = count($messages);
} catch (PDOException $e) {
    die("Error executing query: " . $e->getMessage());
}
?>

<!-- في جزء عرض البيانات -->
<div class="messages">
    <h3>اليوم</h3>
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="message-card">
                <span class="icon">
                    <i class="bi bi-envelope"></i>
                </span>
                <div class="info">
                    <h4><?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?></h4>
                    <p><?php echo htmlspecialchars(date('h:i A', strtotime($message['created_at'])) . ' - ' . 
                        ($message['respond_message_content'] ? 'تم الرد عليه' : 'لم يتم الرد عليه')); ?></p>
                </div>
                <a href="response.php?message_id=<?php echo $message['message_id']; ?>">
                    <button class="details-btn">
                        <i class="bi bi-info-circle"></i> التفاصيل
                    </button>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا توجد رسائل.</p>
    <?php endif; ?>
</div>