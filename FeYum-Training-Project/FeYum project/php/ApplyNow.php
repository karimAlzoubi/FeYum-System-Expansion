<?php
require_once '../backend/conn.php'; // Include database connection

// Get the job ID from the URL
$job_id = $_GET['id'] ?? null;

if ($job_id) {
    try {
        // Fetch job details based on the job ID
        $stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = :job_id");
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->execute();
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        // If job not found, redirect back with an error
        if (!$job) {
            header("Location: jobs.php?error=JobNotFound");
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    // Redirect back if no job ID is provided
    header("Location: jobs.php?error=NoJobID");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($job['title']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet"> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/ApplyNow.css">
  <link rel="stylesheet" href="../css/dark-mode.css">
</head>
<body>
  <main class="main-content">
    <button class="close-btn" onclick="window.location.href='jobs.php'">&times;</button>
    <header>
      <h1><?php echo htmlspecialchars($job['title']); ?></h1>
      <h3><?php echo htmlspecialchars($job['title']); ?></h3>

      <!-- Description -->
      <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>

      <!-- Location -->
      <p><strong>الموقع:</strong> <?php echo htmlspecialchars($job['location'] ?? 'غير محدد'); ?></p>

      <!-- Salary -->
      <p><strong>الراتب:</strong> 
        <?php echo ($job['salary'] !== NULL) ? htmlspecialchars($job['salary']) . ' ريال سعودي' : 'غير محدد'; ?>
      </p>

      <!-- Skills -->
      <p><strong>المهارات المطلوبة:</strong></p>
      <ul>
          <?php
          if (!empty($job['skills'])) {
              $skills = explode(',', $job['skills']);
              foreach ($skills as $skill) {
                  echo "<li>" . htmlspecialchars(trim($skill)) . "</li>";
              }
          } else {
              echo "<li>غير محددة</li>";
          }
          ?>
      </ul>

      <!-- Education -->
      <?php if ($job['education'] !== NULL): ?>
        <p><strong>المؤهلات الدراسية:</strong> <?php echo nl2br(htmlspecialchars($job['education'])); ?></p>
      <?php endif; ?>

      <!-- Experience -->
      <?php if ($job['experience'] !== NULL): ?>
        <p><strong>الخبرات المطلوبة:</strong> <?php echo nl2br(htmlspecialchars($job['experience'])); ?></p>
      <?php endif; ?>

      <!-- Deadline -->
      <?php if ($job['deadline'] !== NULL): ?>
        <p><strong>الموعد النهائي:</strong> <?php echo htmlspecialchars($job['deadline']); ?></p>
      <?php endif; ?>

    </header>

    <button class="submit-btn" onclick="window.location.href='submitApplication.php?id=<?php echo $job['job_id']; ?>'">قدم الآن</button>
  </main>
  <button id="darkModeToggle">
        <i id="darkModeIcon" class="bi bi-moon"></i>
        </button>
        <script src="../js/dark-mode.js"></script>
</body>

</html>