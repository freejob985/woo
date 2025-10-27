<?php
/**
 * CORS Setup Instructions
 * Follow these steps to fix CORS issues permanently
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/*
 * ============================================
 * CORS FIX - COMPLETE SETUP GUIDE
 * ============================================
 * 
 * مشكلة CORS تحدث عندما يحاول تطبيق من نطاق مختلف (مثل Vercel) الوصول إلى API الخاص بك.
 * 
 * الحلول المتاحة (اختر واحدة):
 * 
 * ============================================
 * الطريقة 1: تفعيل CORS من خلال Must-Use Plugin (موصى بها)
 * ============================================
 * 
 * 1. انسخ ملف cors-headers.php
 * 2. ارفعه إلى: wp-content/mu-plugins/cors-headers.php
 * 3. إذا لم يكن مجلد mu-plugins موجودًا، أنشئه
 * 4. سيتم تفعيل CORS تلقائيًا
 * 
 * ============================================
 * الطريقة 2: إضافة الكود إلى wp-config.php
 * ============================================
 * 
 * افتح ملف wp-config.php وأضف هذا السطر قبل require_once(ABSPATH . 'wp-settings.php');
 * 
 * require_once(ABSPATH . 'wp-content/plugins/woo-products-api/cors-headers.php');
 * 
 * ============================================
 * الطريقة 3: إضافة الكود إلى functions.php
 * ============================================
 * 
 * افتح ملف functions.php في قالبك وأضف:
 */

// OPTION 1: Add CORS headers to all REST API requests
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        
        if (!empty($origin)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin');
            header('Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Max-Age: 86400');
            status_header(200);
            exit();
        }
        
        return $value;
    });
}, 15);

/*
 * ============================================
 * الطريقة 4: تعديل .htaccess في جذر WordPress
 * ============================================
 * 
 * افتح ملف .htaccess في جذر WordPress وأضف:
 * 
 * <IfModule mod_headers.c>
 *     SetEnvIf Origin "^(https?://(?:.+\.)?(?:vercel\.app|murjan\.sa|localhost(?::\d+)?))$" ORIGIN_ALLOWED=$0
 *     Header always set Access-Control-Allow-Origin "%{ORIGIN_ALLOWED}e" env=ORIGIN_ALLOWED
 *     Header always set Access-Control-Allow-Origin "*"
 *     Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS, PATCH"
 *     Header always set Access-Control-Allow-Headers "Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin"
 *     Header always set Access-Control-Max-Age "86400"
 * </IfModule>
 * 
 * <IfModule mod_rewrite.c>
 *     RewriteEngine On
 *     RewriteCond %{REQUEST_METHOD} OPTIONS
 *     RewriteRule ^(.*)$ $1 [R=200,L]
 * </IfModule>
 * 
 * ============================================
 * التحقق من أن CORS يعمل
 * ============================================
 * 
 * 1. افتح Console في المتصفح (F12)
 * 2. جرب هذا الكود:
 */

?>
<script>
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=1')
  .then(response => response.json())
  .then(data => console.log('✅ CORS Working!', data))
  .catch(error => console.error('❌ CORS Error:', error));
</script>
<?php

/*
 * 3. إذا رأيت البيانات بدون أخطاء، فإن CORS يعمل بشكل صحيح
 * 4. إذا ظهر خطأ CORS، جرب الحلول الأخرى
 * 
 * ============================================
 * الأخطاء الشائعة وحلولها
 * ============================================
 * 
 * خطأ 1: "No 'Access-Control-Allow-Origin' header is present"
 * الحل: تأكد من تفعيل أحد الحلول أعلاه
 * 
 * خطأ 2: "Response to preflight request doesn't pass access control check"
 * الحل: تأكد من أن الخادم يستجيب لطلبات OPTIONS
 * 
 * خطأ 3: "Credentials flag is true, but Access-Control-Allow-Credentials is not"
 * الحل: أضف header('Access-Control-Allow-Credentials: true');
 * 
 * ============================================
 * معلومات إضافية
 * ============================================
 * 
 * - للإنتاج: استخدم قائمة محددة من النطاقات المسموح بها
 * - للتطوير: يمكنك استخدام '*' للسماح لجميع النطاقات
 * - تأكد من تفعيل SSL (HTTPS) في الإنتاج
 * - راجع سجلات الأخطاء في WordPress للمزيد من التفاصيل
 * 
 * ============================================
 * الدعم
 * ============================================
 * 
 * إذا واجهت مشاكل:
 * 1. تحقق من سجلات الأخطاء في WordPress
 * 2. تحقق من Console في المتصفح
 * 3. جرب تعطيل Plugins الأخرى مؤقتًا
 * 4. تأكد من أن WooCommerce مُفعّل
 * 5. تأكد من صلاحيات المفاتيح API
 */

