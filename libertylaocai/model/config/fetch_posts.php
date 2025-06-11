<?php
require_once 'connect.php'; // Giả sử file này chứa kết nối database ($conn)

header('Content-Type: application/json');

$type = isset($_GET['type']) ? $_GET['type'] : '';
$active = isset($_GET['active']) ? (int)$_GET['active'] : 1;
$language = isset($_GET['language']) ? (int)$_GET['language'] : 1;

if (empty($type)) {
    echo json_encode(['success' => false, 'message' => 'Loại bài viết không hợp lệ']);
    exit;
}

$table = '';
$image_table = '';
$content_table = '';
$date_field = 'create_at';
switch ($type) {
    case 'news':
        $table = 'tintuc';
        $image_table = 'anhtintuc';
        $content_table = 'tintuc_ngonngu';
        break;
    case 'offer':
        $table = 'uudai';
        $image_table = 'anhuudai';
        $content_table = 'uudai_ngonngu';
        $date_field = 'created_at';
        break;
    case 'event':
        $table = 'sukiendatochuc';
        $image_table = 'anhsukiendatochuc';
        $content_table = 'sukiendatochuc_ngonngu';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Loại bài viết không hợp lệ']);
        exit;
}

$sql = "SELECT t.id, t.{$date_field} AS date, a.image, tn.title, tn.content
        FROM {$table} t
        JOIN {$image_table} a ON t.id = a.id_{$table}
        JOIN {$content_table} tn ON t.id = tn.id_{$table}
        WHERE a.is_primary = 1 AND tn.id_ngonngu = ? AND t.active = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $language, $active);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$posts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'content' => $row['content'],
        'image' => $row['image'],
        'date' => $row['date']
    ];
}

echo json_encode(['success' => true, 'posts' => $posts]);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>