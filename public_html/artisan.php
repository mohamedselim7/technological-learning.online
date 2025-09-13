<?php
// artisan.php on technological-learning.online

$commands = [
    'config:clear'   => 'مسح كاش الإعدادات',
    'config:cache'   => 'إعادة كاش الإعدادات',
    'route:clear'    => 'مسح كاش الروتس',
    'view:clear'     => 'مسح كاش الفيوز',
    'cache:clear'    => 'مسح كل الكاش',
    'storage:link'   => 'عمل لينك للستوريج',
];

$artisan = __DIR__ . '/artisan';
$output = '';

if (isset($_POST['cmd'])) {
    $cmd = $_POST['cmd'];
    $output = shell_exec("php $artisan $cmd 2>&1");
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم Artisan</title>
    <style>
        body { font-family: Tahoma, sans-serif; background:#f9f9f9; padding:30px; }
        h2 { color:#333; }
        form { margin:10px 0; }
        button { padding:10px 20px; margin:5px; cursor:pointer; border:none; border-radius:6px; background:#007bff; color:white; }
        pre { background:#222; color:#0f0; padding:15px; border-radius:8px; }
    </style>
</head>
<body>
    <h2>تشغيل أوامر Artisan (technological-learning.online)</h2>

    <?php foreach ($commands as $cmd => $label): ?>
        <form method="post">
            <input type="hidden" name="cmd" value="<?= $cmd ?>">
            <button type="submit"><?= $label ?></button>
        </form>
    <?php endforeach; ?>

    <?php if ($output): ?>
        <h3>النتيجة:</h3>
        <pre><?= htmlspecialchars($output) ?></pre>
    <?php endif; ?>
</body>
</html>
