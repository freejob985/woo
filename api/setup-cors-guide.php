<?php
/**
 * 🔧 دليل إعداد CORS الكامل
 * Complete CORS Setup Guide
 * 
 * اتبع هذه الخطوات لحل مشكلة CORS نهائياً
 * Follow these steps to fix CORS issues permanently
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل إعداد CORS - CORS Setup Guide</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #e74c3c;
            margin-top: 30px;
        }
        h3 {
            color: #27ae60;
        }
        .step {
            background: #ecf0f1;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            border-radius: 5px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        code {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .button {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 دليل إعداد CORS الكامل</h1>
        <p><strong>Complete CORS Setup Guide for WooCommerce Products API</strong></p>

        <div class="warning">
            <h3>⚠️ ما هي مشكلة CORS؟</h3>
            <p><strong>CORS (Cross-Origin Resource Sharing)</strong> هي آلية أمان في المتصفحات تمنع المواقع من الوصول إلى موارد من نطاق مختلف.</p>
            <p>عندما تحاول <code>https://woo-silk.vercel.app</code> الوصول إلى <code>https://dev.murjan.sa/wp-json</code>، يحظر المتصفح الطلب إلا إذا سمح الخادم بذلك.</p>
        </div>

        <h2>📋 الحلول المتاحة (اختر واحداً)</h2>

        <!-- الحل الأول -->
        <div class="step">
            <h3>✅ الحل 1: Must-Use Plugin (موصى به جداً)</h3>
            <p><strong>هذا هو أفضل حل لأنه يعمل تلقائياً ولا يتأثر بتحديثات القوالب</strong></p>
            
            <ol>
                <li>اذهب إلى مجلد WordPress الخاص بك</li>
                <li>افتح مجلد <code>wp-content</code></li>
                <li>إذا لم يكن موجوداً، أنشئ مجلد جديد اسمه <code>mu-plugins</code></li>
                <li>انسخ ملف <code>cors-headers.php</code> من:
                    <pre>wp-content/plugins/woo-products-api/cors-headers.php</pre>
                </li>
                <li>الصقه في:
                    <pre>wp-content/mu-plugins/cors-headers.php</pre>
                </li>
                <li>✅ تم! الملف سيعمل تلقائياً بدون تفعيل</li>
            </ol>

            <div class="success">
                <strong>✨ ميزات هذا الحل:</strong>
                <ul>
                    <li>✅ يعمل تلقائياً عند تحميل WordPress</li>
                    <li>✅ لا يتأثر بتحديثات القوالب أو الـ Plugins</li>
                    <li>✅ لا يحتاج إلى تفعيل يدوي</li>
                    <li>✅ أولوية تحميل عالية</li>
                </ul>
            </div>
        </div>

        <!-- الحل الثاني -->
        <div class="step">
            <h3>✅ الحل 2: تعديل wp-config.php</h3>
            <p><strong>إذا لم تستطع إنشاء mu-plugins</strong></p>
            
            <ol>
                <li>افتح ملف <code>wp-config.php</code> من جذر WordPress</li>
                <li>ابحث عن السطر:
                    <pre>require_once ABSPATH . 'wp-settings.php';</pre>
                </li>
                <li>أضف هذا السطر <strong>قبله مباشرة</strong>:
                    <pre>require_once ABSPATH . 'wp-content/plugins/woo-products-api/cors-headers.php';</pre>
                </li>
                <li>احفظ الملف</li>
            </ol>
        </div>

        <!-- الحل الثالث -->
        <div class="step">
            <h3>✅ الحل 3: تعديل .htaccess</h3>
            <p><strong>إذا كنت تستخدم Apache Server</strong></p>
            
            <ol>
                <li>افتح ملف <code>.htaccess</code> من جذر WordPress</li>
                <li>أضف هذا الكود في <strong>بداية الملف</strong>:</li>
            </ol>

            <pre><code>&lt;IfModule mod_headers.c&gt;
    # CORS Headers
    Header always set Access-Control-Allow-Origin "*"
    Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS, PATCH"
    Header always set Access-Control-Allow-Headers "Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin"
    Header always set Access-Control-Allow-Credentials "true"
    Header always set Access-Control-Max-Age "86400"
&lt;/IfModule&gt;

&lt;IfModule mod_rewrite.c&gt;
    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule ^(.*)$ $1 [R=200,L]
&lt;/IfModule&gt;</code></pre>
        </div>

        <h2>⚙️ إضافة النطاقات المسموح بها</h2>

        <div class="step">
            <h3>📝 في الباك إند (WordPress API)</h3>
            <p>افتح ملف <code>cors-headers.php</code> وعدّل قائمة <code>$allowed_origins</code>:</p>
            
            <pre><code>$allowed_origins = array(
    // Production Domains
    'https://woo-silk.vercel.app',
    'https://dev.murjan.sa',
    'https://murjan.sa',
    
    // Development Domains
    'http://localhost:5173',
    
    // 📝 أضف نطاقاتك هنا:
    'https://your-domain.com',
    'https://staging.your-domain.com',
);</code></pre>
        </div>

        <div class="step">
            <h3>📝 في الفرونت إند (React App)</h3>
            
            <p><strong>1. ملف .env:</strong></p>
            <pre><code>VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
VITE_WOOCOMMERCE_CONSUMER_KEY=your_consumer_key
VITE_WOOCOMMERCE_CONSUMER_SECRET=your_consumer_secret</code></pre>

            <p><strong>2. ملف vercel.json (للنشر على Vercel):</strong></p>
            <p>تم إنشاؤه تلقائياً في مجلد المشروع ✅</p>
        </div>

        <h2>🧪 اختبار CORS</h2>

        <div class="step">
            <h3>✅ طريقة الاختبار</h3>
            
            <ol>
                <li>افتح Developer Console في المتصفح (F12)</li>
                <li>اذهب إلى تبويب Console</li>
                <li>انسخ والصق هذا الكود:</li>
            </ol>

            <pre><code>fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=1')
  .then(response => response.json())
  .then(data => {
    console.log('✅ CORS Working!', data);
    console.log('Total Products:', data.total_products);
  })
  .catch(error => {
    console.error('❌ CORS Error:', error);
  });</code></pre>

            <div class="success">
                <strong>✅ النتيجة المتوقعة:</strong>
                <p>يجب أن ترى رسالة <code>✅ CORS Working!</code> مع بيانات المنتجات</p>
            </div>

            <div class="error">
                <strong>❌ إذا ظهر خطأ:</strong>
                <p>تحقق من:</p>
                <ul>
                    <li>تفعيل أحد الحلول أعلاه</li>
                    <li>مسح الكاش (Cache) من المتصفح</li>
                    <li>إعادة تشغيل Apache/Nginx</li>
                    <li>التحقق من Consumer Key & Secret</li>
                </ul>
            </div>
        </div>

        <h2>🔍 الأخطاء الشائعة وحلولها</h2>

        <div class="error">
            <h4>❌ خطأ 1: "No 'Access-Control-Allow-Origin' header"</h4>
            <p><strong>السبب:</strong> لم يتم تفعيل CORS على الخادم</p>
            <p><strong>الحل:</strong> اتبع أحد الحلول أعلاه (يفضل Must-Use Plugin)</p>
        </div>

        <div class="error">
            <h4>❌ خطأ 2: "Response to preflight request doesn't pass"</h4>
            <p><strong>السبب:</strong> الخادم لا يستجيب لطلبات OPTIONS</p>
            <p><strong>الحل:</strong> تأكد من إضافة كود معالجة OPTIONS في cors-headers.php</p>
        </div>

        <div class="error">
            <h4>❌ خطأ 3: "401 Unauthorized"</h4>
            <p><strong>السبب:</strong> مشكلة في مفاتيح API</p>
            <p><strong>الحل:</strong> تحقق من Consumer Key & Secret في ملف .env</p>
        </div>

        <div class="error">
            <h4>❌ خطأ 4: "ERR_NETWORK"</h4>
            <p><strong>السبب:</strong> مشكلة في الاتصال بالخادم أو CORS</p>
            <p><strong>الحل:</strong> 
                <ul>
                    <li>تحقق من اتصال الإنترنت</li>
                    <li>تأكد من أن الخادم يعمل</li>
                    <li>فعّل CORS بشكل صحيح</li>
                </ul>
            </p>
        </div>

        <h2>📞 الدعم الفني</h2>

        <div class="success">
            <p><strong>إذا واجهت مشاكل بعد اتباع جميع الخطوات:</strong></p>
            <ol>
                <li>تحقق من سجلات الأخطاء في WordPress: <code>wp-content/debug.log</code></li>
                <li>راجع Console في المتصفح للأخطاء المفصلة</li>
                <li>جرب تعطيل الـ Plugins الأخرى مؤقتاً</li>
                <li>تأكد من تفعيل WooCommerce</li>
                <li>راجع صلاحيات مفاتيح API في WooCommerce</li>
            </ol>
        </div>

        <h2>✨ نصائح للإنتاج (Production)</h2>

        <div class="warning">
            <ul>
                <li>🔒 استخدم HTTPS في الإنتاج (إلزامي)</li>
                <li>🔐 لا تستخدم <code>*</code> في Access-Control-Allow-Origin للإنتاج</li>
                <li>📝 حدد النطاقات المسموح بها بشكل دقيق</li>
                <li>🔄 فعّل SSL Certificate</li>
                <li>⚡ استخدم CDN لتحسين الأداء</li>
                <li>📊 راقب سجلات الأخطاء بانتظام</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #ecf0f1;">
            <p><strong>🚀 WooCommerce Products API Manager</strong></p>
            <p>Version 1.0.0 | Murjan Team</p>
        </div>
    </div>
</body>
</html>

