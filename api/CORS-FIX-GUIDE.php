<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * دليل إصلاح مشكلة CORS - CORS Fix Guide
 * ═══════════════════════════════════════════════════════════════
 * 
 * هذا الملف يحتوي على تعليمات كاملة لحل مشكلة CORS
 * This file contains complete instructions to fix CORS issues
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل إصلاح CORS - CORS Fix Guide</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
            line-height: 1.8;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 4px solid #3498db;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        h2 {
            color: #2980b9;
            background: #ecf0f1;
            padding: 15px;
            border-left: 5px solid #3498db;
            margin-top: 30px;
        }
        h3 {
            color: #16a085;
            margin-top: 25px;
        }
        .step {
            background: #e8f8f5;
            border: 2px solid #1abc9c;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .success {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        code {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            direction: ltr;
            display: inline-block;
        }
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            direction: ltr;
            text-align: left;
        }
        pre code {
            background: none;
            padding: 0;
        }
        .checklist {
            list-style: none;
            padding-right: 0;
        }
        .checklist li {
            padding: 10px;
            margin: 10px 0;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .checklist li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-left: 10px;
        }
        .file-path {
            background: #6c757d;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 دليل إصلاح مشكلة CORS - CORS Fix Complete Guide</h1>

        <div class="error">
            <h3>❌ المشكلة - The Problem</h3>
            <p><strong>الخطأ:</strong></p>
            <code>Access to XMLHttpRequest at 'https://dev.murjan.sa/wp-json/murjan-api/v1/products' from origin 'https://woo-silk.vercel.app' has been blocked by CORS policy</code>
            
            <p style="margin-top: 15px;"><strong>السبب:</strong> الخادم لا يسمح للتطبيق الأمامي بالوصول إلى API بسبب إعدادات CORS غير صحيحة</p>
        </div>

        <div class="success">
            <h3>✅ الحل - The Solution</h3>
            <p>اتبع الخطوات التالية بالترتيب لحل المشكلة نهائياً:</p>
        </div>

        <h2>📋 الخطوة 1: رفع ملفات CORS إلى الخادم</h2>
        
        <div class="step">
            <h3>أ) رفع ملف cors-headers.php</h3>
            <p><strong>المسار المطلوب:</strong></p>
            <div class="file-path">wp-content/mu-plugins/cors-headers.php</div>
            
            <p><strong>الطريقة:</strong></p>
            <ul class="checklist">
                <li>افتح لوحة تحكم الاستضافة (cPanel أو أي لوحة أخرى)</li>
                <li>اذهب إلى File Manager</li>
                <li>انتقل إلى: <code>public_html/wp-content/</code></li>
                <li>أنشئ مجلد جديد اسمه <code>mu-plugins</code> إذا لم يكن موجوداً</li>
                <li>ادخل إلى مجلد <code>mu-plugins</code></li>
                <li>ارفع ملف <code>cors-headers.php</code> من مجلد <code>api/</code></li>
            </ul>

            <div class="warning">
                <strong>⚠️ مهم جداً:</strong> تأكد من أن ملف cors-headers.php يحتوي على نطاقك في قائمة allowed_origins:
                <pre><code>$allowed_origins = array(
    'https://woo-silk.vercel.app',
    'https://dev.murjan.sa',
    'http://localhost:5173',
    // أضف نطاقاتك هنا
);</code></pre>
            </div>
        </div>

        <div class="step">
            <h3>ب) رفع ملف .htaccess للـ API</h3>
            <p><strong>المسار المطلوب:</strong></p>
            <div class="file-path">public_html/wp-json/murjan-api/v1/.htaccess</div>
            
            <p><strong>الطريقة:</strong></p>
            <ul class="checklist">
                <li>انتقل إلى: <code>public_html/wp-json/murjan-api/v1/</code></li>
                <li>ارفع ملف <code>.htaccess</code> من مجلد <code>api/</code></li>
                <li>أو أنشئ ملف <code>.htaccess</code> جديد وألصق فيه المحتوى من الملف الموجود</li>
            </ul>

            <div class="warning">
                <strong>⚠️ ملاحظة:</strong> بعض خوادم Apache لا تظهر ملفات .htaccess بشكل افتراضي. تأكد من تفعيل "Show Hidden Files" في File Manager
            </div>
        </div>

        <h2>📋 الخطوة 2: تفعيل Plugin</h2>
        
        <div class="step">
            <h3>تفعيل WooCommerce Products API Manager</h3>
            <ul class="checklist">
                <li>اذهب إلى WordPress Admin</li>
                <li>اختر: Plugins > Installed Plugins</li>
                <li>ابحث عن: "WooCommerce Products API Manager"</li>
                <li>اضغط "Activate" إذا لم يكن مفعلاً</li>
                <li>تحقق من ظهور "Products API" في القائمة الجانبية</li>
            </ul>
        </div>

        <h2>📋 الخطوة 3: إعداد مفاتيح API</h2>
        
        <div class="step">
            <h3>إنشاء WooCommerce API Keys</h3>
            <ul class="checklist">
                <li>اذهب إلى: WooCommerce > Settings</li>
                <li>اختر تبويب: Advanced</li>
                <li>اختر: REST API</li>
                <li>اضغط "Add Key"</li>
                <li>املأ البيانات:
                    <ul>
                        <li>Description: Product Manager App</li>
                        <li>User: اختر مستخدم Admin</li>
                        <li>Permissions: Read/Write</li>
                    </ul>
                </li>
                <li>اضغط "Generate API Key"</li>
                <li>انسخ Consumer Key و Consumer Secret</li>
            </ul>

            <div class="success">
                <strong>✅ المفاتيح الحالية (للمرجع):</strong>
                <pre><code>Consumer Key: ck_2210fb8d333da5da151029715a85618a4b283a52
Consumer Secret: cs_7f1073e18d0af70d01c84692ce8c04609a722b5c</code></pre>
            </div>
        </div>

        <h2>📋 الخطوة 4: إعداد التطبيق الأمامي (React)</h2>
        
        <div class="step">
            <h3>تحديث ملف .env.local</h3>
            <p>أنشئ أو عدّل ملف <code>.env.local</code> في مجلد المشروع:</p>
            <pre><code>VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
VITE_WOOCOMMERCE_CONSUMER_KEY=ck_2210fb8d333da5da151029715a85618a4b283a52
VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
VITE_ITEMS_PER_PAGE=12</code></pre>

            <div class="warning">
                <strong>⚠️ مهم:</strong> 
                <ul>
                    <li>ملف .env.local يجب أن يكون في جذر مجلد woo-product-manager-main</li>
                    <li>تأكد من أن .gitignore يحتوي على .env.local لحماية المفاتيح</li>
                    <li>بعد تحديث .env.local، أعد تشغيل سيرفر التطوير</li>
                </ul>
            </div>
        </div>

        <h2>📋 الخطوة 5: اختبار الاتصال</h2>
        
        <div class="step">
            <h3>أ) اختبار من المتصفح</h3>
            <p>افتح Console في المتصفح (F12) واكتب:</p>
            <pre><code>fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=1', {
    headers: {
        'Authorization': 'Basic ' + btoa('ck_2210fb8d333da5da151029715a85618a4b283a52:cs_7f1073e18d0af70d01c84692ce8c04609a722b5c')
    }
})
.then(r => r.json())
.then(console.log)
.catch(console.error)</code></pre>

            <p><strong>النتيجة المتوقعة:</strong> يجب أن ترى قائمة المنتجات بدون أخطاء CORS</p>
        </div>

        <div class="step">
            <h3>ب) اختبار التطبيق</h3>
            <ul class="checklist">
                <li>شغّل التطبيق: <code>npm run dev</code></li>
                <li>افتح المتصفح على: <code>http://localhost:5173</code></li>
                <li>يجب أن ترى المنتجات تظهر تلقائياً</li>
                <li>تحقق من Console - يجب ألا يكون هناك أخطاء CORS</li>
            </ul>
        </div>

        <h2>🔍 استكشاف الأخطاء - Troubleshooting</h2>

        <div class="warning">
            <h3>إذا استمرت مشكلة CORS:</h3>
            <ol>
                <li><strong>تحقق من أن cors-headers.php موجود في المسار الصحيح:</strong>
                    <code>wp-content/mu-plugins/cors-headers.php</code>
                </li>
                <li><strong>تحقق من صلاحيات الملفات:</strong>
                    <ul>
                        <li>cors-headers.php: 644</li>
                        <li>.htaccess: 644</li>
                    </ul>
                </li>
                <li><strong>تحقق من أن الخادم يدعم mod_headers:</strong>
                    <p>في cPanel > Select PHP Version > Extensions > تأكد من تفعيل mod_headers</p>
                </li>
                <li><strong>امسح الكاش:</strong>
                    <ul>
                        <li>كاش المتصفح (Ctrl + Shift + Delete)</li>
                        <li>كاش WordPress (إذا كنت تستخدم plugin للكاش)</li>
                        <li>كاش CDN (مثل Cloudflare)</li>
                    </ul>
                </li>
                <li><strong>تحقق من لوجات الخادم:</strong>
                    <p>في cPanel > Errors > راجع error_log للبحث عن أخطاء CORS</p>
                </li>
            </ol>
        </div>

        <div class="error">
            <h3>الأخطاء الشائعة وحلولها:</h3>
            
            <h4>1. "No 'Access-Control-Allow-Origin' header"</h4>
            <p><strong>السبب:</strong> ملف cors-headers.php غير محمّل أو في مكان خاطئ</p>
            <p><strong>الحل:</strong> تأكد من رفع الملف إلى mu-plugins</p>

            <h4>2. "Method not allowed"</h4>
            <p><strong>السبب:</strong> ملف .htaccess لا يسمح بـ OPTIONS requests</p>
            <p><strong>الحل:</strong> تأكد من رفع ملف .htaccess الصحيح</p>

            <h4>3. "401 Unauthorized"</h4>
            <p><strong>السبب:</strong> مفاتيح API خاطئة أو منتهية</p>
            <p><strong>الحل:</strong> أعد إنشاء مفاتيح API من WooCommerce Settings</p>

            <h4>4. "Plugin requires WooCommerce"</h4>
            <p><strong>السبب:</strong> WooCommerce غير مفعل</p>
            <p><strong>الحل:</strong> فعّل WooCommerce من Plugins</p>
        </div>

        <div class="success">
            <h2>✅ قائمة التحقق النهائية - Final Checklist</h2>
            <ul class="checklist">
                <li>ملف cors-headers.php موجود في wp-content/mu-plugins/</li>
                <li>ملف .htaccess موجود في wp-json/murjan-api/v1/</li>
                <li>Plugin "WooCommerce Products API Manager" مفعّل</li>
                <li>WooCommerce مثبت ومفعّل</li>
                <li>مفاتيح API منشأة بصلاحيات Read/Write</li>
                <li>ملف .env.local محدّث بالمفاتيح الصحيحة</li>
                <li>النطاق woo-silk.vercel.app موجود في قائمة allowed_origins</li>
                <li>تم مسح جميع الكاش</li>
                <li>التطبيق يعمل بدون أخطاء CORS</li>
            </ul>
        </div>

        <h2>📞 الدعم الفني - Support</h2>
        <div class="step">
            <p>إذا واجهت أي مشاكل بعد اتباع جميع الخطوات:</p>
            <ul>
                <li>تحقق من ملف error_log في الخادم</li>
                <li>راجع Console في المتصفح</li>
                <li>استخدم Network tab لرؤية تفاصيل الطلبات</li>
                <li>جرّب اختبار API من Postman أولاً</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 50px; padding: 20px; background: #2c3e50; color: white; border-radius: 8px;">
            <h3>🎉 بالتوفيق! Good Luck!</h3>
            <p>بعد اتباع هذه الخطوات، يجب أن يعمل التطبيق بشكل كامل بدون مشاكل CORS</p>
        </div>
    </div>
</body>
</html>

