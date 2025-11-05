<?php
// ✅ إعداد ترويسات عامة لتفادي مشاكل CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS");
header("Access-Control-Allow-Headers: *");

// 🧠 إعداد الرابط الأساسي للبث
$base = "http://top102856-4k.org:80/live/abderahimkangoo201/pk6o5g2s9o/";
$m3u8 = "1.m3u8";

// ✅ في حالة طلب مقطع (ts أو js)
if (isset($_GET["seg"]) && $_GET["seg"] != "") {
    $seg = basename($_GET["seg"]);
    $url = $base . $seg;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
    curl_setopt($ch, CURLOPT_REFERER, $base);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$data) {
        http_response_code(404);
        echo "Segment not found or blocked.";
        exit;
    }

    // ✅ تحديد نوع المحتوى حسب الامتداد
    $ext = strtolower(pathinfo($seg, PATHINFO_EXTENSION));
    if ($ext === "ts" || $ext === "js") {
        header("Content-Type: video/mp2t");
    } elseif ($ext === "mp4") {
        header("Content-Type: video/mp4");
    } else {
        header("Content-Type: application/octet-stream");
    }

    echo $data;
    exit;
}

// ✅ تحميل ملف m3u8 الرئيسي
$ch = curl_init($base . $m3u8);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
curl_setopt($ch, CURLOPT_REFERER, $base);
$m3u8_data = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$m3u8_data) {
    http_response_code(404);
    echo "M3U8 not found or blocked.";
    exit;
}

// ✅ تعديل الروابط داخل ملف m3u8 لتشير إلى البروكسي نفسه
$proxyBase = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?seg=";
$m3u8_data = preg_replace('#http://top102856-4k\.org(:80)?/live/abderahimkangoo201/pk6o5g2s9o/#', $proxyBase, $m3u8_data);

header("Content-Type: application/vnd.apple.mpegurl");
echo $m3u8_data;
?>
