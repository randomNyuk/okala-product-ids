<?php
set_time_limit(0);
ob_start();
ob_implicit_flush(true);

$storeIds = file('store-ids.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$categories = ["refreshments", "dairy-products", "groceries", "home-hygiene", "Beverages", "spices", "canned-ready-food", "cosmetics-hygiene", "proteins", "breakfast-goods", "home-stuff", "baby-mother-care", "fruits-vegetables", "nuts", "multiples"];

$baseUrl = "https://apigateway.okala.com/api/Search/v1/Product/Search";
$headers = [
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0",
    "Accept: application/json, text/plain, */*",
    "Accept-Language: en-US,en;q=0.5",
    "Accept-Encoding: gzip, deflate, br, zstd",
    "Referer: https://www.okala.com/",
    "X-Correlation-Id: 03fb6d3d-88de-469b-8f96-17adf2e3bb52",
    "session-id: 3c1618e9-6cc0-4a3b-9431-8d6b0e9f302c",
    "idfa: null",
    "metrix_user_id: null",
    "ui-version: 2.0",
    "source: okala",
    "Origin: https://www.okala.com",
    "Sec-Fetch-Dest: empty",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Site: same-site",
    "Authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjEzRjRFNUExQ0NG..."
];

foreach ($storeIds as $storeId) {
    $storeId = trim($storeId);
    foreach ($categories as $category) {
        $url = $baseUrl . "?StoreIds=$storeId&CategorySlugs=$category&HasQuantity=true&Page=1&Take=10000&excludeShoppingCard=true";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo "❌ خطای cURL برای فروشگاه $storeId و دسته $category: " . curl_error($ch) . "<br>\n";
        } elseif ($httpCode !== 200) {
            echo "❌ پاسخ نامعتبر ($httpCode) از API برای فروشگاه $storeId و دسته $category<br>\n";
        } else {
            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "❌ خطای در تجزیه JSON برای فروشگاه $storeId و دسته $category: " . json_last_error_msg() . "<br>\n";
            } elseif (!empty($data['entities']) && is_array($data['entities'])) {
                $ids = array_column($data['entities'], 'id');
                $line = implode(" ", array_map(fn($id) => "$storeId:$id", $ids)) . " ";
                file_put_contents("product-ids.txt", $line, FILE_APPEND);
                echo "✅ افزوده شد: " . count($ids) . " شناسه از فروشگاه $storeId - دسته $category<br>\n";
            } else {
                echo "⚠️ هیچ محصولی پیدا نشد برای فروشگاه $storeId - دسته $category<br>\n";
            }
        }

        curl_close($ch);
        ob_flush();
        flush();
        usleep(500000);
    }
}
