 <?php
// כבה הודעות שגיאה לגולשים (בפיתוח אפשר להדליק)
ini_set('display_errors', 0);

// אימות טוקן – רק אם הגדרת ב-ext.ini
if (($_POST['token'] ?? '') !== 'MY_SECRET_TOKEN') {
    header('HTTP/1.1 403 Forbidden'); exit;
}

// פרמטרים מהקריאה של ימות
$caller     = $_POST['ApiPhone']     ?? '';
$extension  = ltrim($_POST['ApiExtension'] ?? '', '/'); // לדוג' "/2" => "2"

// מיפוי: לכל שלוחה איזה ספרה/קוד להוסיף בסוף המספר
$suffixMap = [
  '1'  => '1',
  '2'  => '2',
  '6' => '6',
  // ברירת מחדל אם לא מוגדר
  '*'  => '0',
];

// בחירת הקוד הרלוונטי
$suffix = $suffixMap[$extension] ?? $suffixMap['*'];

// למי מחייגים בפועל (המספר שלך או כמה מספרים עם נקודה באמצע)
$routingNumbers = '0525634454';

// שורת ה-routing: רק חובה לתת למי לחייג
$fields = [
  $routingNumbers,   // routing_to_phone
  '', '', '', '',    // שדות שלא נוגעים בהם
  'no01',            // routing_your_id: ברירת מחדל – מספר המתקשר
];

// החזרת תשובה לימות
header('Content-Type: text/plain; charset=utf-8');

// ניתוב רגיל
echo 'routing=' . implode(',', $fields) . "\n";

// הוספת הקוד לסוף מספר המחייג (עד 6 ספרות, ספרות בלבד)
echo 'routing_your_id_add=' . preg_replace('/\D/', '', $suffix) . "\n";