<?php
session_start();

if (isset($_POST['language_id'])) {
    $languageId = (int)$_POST['language_id'];
    // Chỉ cho phép id_ngonngu = 1 hoặc 2
    if (in_array($languageId, [1, 2])) {
        $_SESSION['language_id'] = $languageId;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid language ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No language ID provided']);
}
