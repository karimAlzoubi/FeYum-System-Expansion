<?php
// Include the database connection file
require_once '../backend/conn.php';

// Check if 'document_id' is present in the GET parameters
if (!isset($_GET['document_id'])) {
    // Terminate the script and display an error message if 'document_id' is missing
    die("معرف المستند غير موجود.");
}

// Retrieve the 'document_id' from the GET parameters
$document_id = $_GET['document_id'];

try {
    // Prepare a SQL statement to select the file path of the document
    $stmt = $pdo->prepare("SELECT file_path FROM documents WHERE document_id = ?");
    // Execute the prepared statement with the provided 'document_id'
    $stmt->execute([$document_id]);
    // Fetch the document details as an associative array
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the document exists in the database
    if (!$document) {
        // Terminate the script and display an error message if the document is not found
        die("المستند غير موجود.");
    }

    // Retrieve the file path from the fetched document
    $file_path = $document['file_path'];

    // Check if the file exists on the server
    if (!file_exists($file_path)) {
        // Terminate the script and display an error message if the file is not found
        die("الملف غير موجود.");
    }

    // Set the content type header to PDF to display the file in the browser
    header('Content-Type: application/pdf');
    // Set the content disposition to inline to display the PDF within the browser
    header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
    // Read and output the file to the browser
    readfile($file_path);
} catch (PDOException $e) {
    // Terminate the script and display an error message if a database error occurs
    die("حدث خطأ: " . $e->getMessage());
}
?>
