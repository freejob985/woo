<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل الإعداد السريع - Quick Setup Guide</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .header p { font-size: 1.2em; opacity: 0.9; }
        .content { padding: 40px; }
        .status-card {
            background: #f8f9fa;
            border-left: 5px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .status-card.warning {
            border-left-color: #ffc107;
            background: #fff3cd;
        }
        .status-card.error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        .step {
            background: #e8f4fd;
            border: 2px solid #0066cc;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
        }
        .step h3 {
            color: #0066cc;
            font-size: 1.5em;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .step-number {
            background: #0066cc;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        code {
            background: #2d3748;
            color: #68d391;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            direction: ltr;
            display: inline-block;
        }
        pre {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            direction: ltr;
            text-align: left;
            margin: 15px 0;
        }
        pre code { background: none; padding: 0; }
        .checklist {
            list-style: none;
            padding: 0;
        }
        .checklist li {
            padding: 12px;
            margin: 8px 0;
            background: white;
            border-radius: 5px;
            border-left: 3px solid #28a745;
            transition: all 0.3s;
        }
        .checklist li:hover { transform: translateX(-5px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .checklist li:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-left: 10px;
            font-size: 1.2em;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
            text-align: center;
        }
        .btn:hover { background: #218838; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .credentials {
            background: #fff3cd;
            border: 2px dashed #ffc107;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .credentials h4 {
            color: #856404;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        .cred-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: monospace;
            direction: ltr;
            text-align: left;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 3px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 دليل الإعداد السريع</h1>
            <p>Quick Setup Guide for WooCommerce Product Manager</p>
        </div>

        <div class="content">
            <div class="status-card">
                <h2>✅ تم إنشاء ملف .env.local بنجاح!</h2>
                <p>تم تكوين المشروع تلقائياً مع بيانات الاتصال الصحيحة.</p>
            </div>

            <div class="credentials">
                <h4>🔑 بيانات API المُستخدمة:</h4>
                <div class="cred-item">
                    <strong>API URL:</strong><br>
                    https://dev.murjan.sa/wp-json/murjan-api/v1
                </div>
                <div class="cred-item">
                    <strong>Consumer Key:</strong><br>
                    ck_2210fb8d333da5da151029715a85618a4b283a52
                </div>
                <div class="cred-item">
                    <strong>Consumer Secret:</strong><br>
                    cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
                </div>
            </div>

            <div class="status-card warning">
                <h3>⚠️ مشكلة CORS الحالية</h3>
                <p><strong>الخطأ:</strong> التطبيق لا يمكنه الاتصال بـ API بسبب إعدادات CORS</p>
                <code style="display: block; margin-top: 10px;">
                    Access-Control-Allow-Origin header is not present on the requested resource
                </code>
            </div>

            <div class="step">
                <h3><span class="step-number">1</span> تشغيل المشروع محلياً</h3>
                <p>افتح Terminal في مجلد المشروع ونفذ الأوامر:</p>
                <pre><code># تثبيت المكتبات (إذا لم تكن مثبتة)
npm install

# تشغيل السيرفر المحلي
npm run dev</code></pre>
                <p style="margin-top: 15px;">ثم افتح المتصفح على: <code>http://localhost:5173</code></p>
            </div>

            <div class="step">
                <h3><span class="step-number">2</span> حل مشكلة CORS على الخادم</h3>
                <p><strong>يجب رفع ملف cors-headers.php إلى الخادم:</strong></p>
                
                <ul class="checklist">
                    <li>افتح cPanel أو File Manager للاستضافة</li>
                    <li>انتقل إلى: <code>public_html/wp-content/</code></li>
                    <li>أنشئ مجلد <code>mu-plugins</code> إذا لم يكن موجوداً</li>
                    <li>ارفع ملف <code>api/cors-headers.php</code> إلى <code>wp-content/mu-plugins/</code></li>
                    <li>تأكد من الصلاحيات: 644</li>
                </ul>

                <div class="status-card warning" style="margin-top: 20px;">
                    <strong>📍 المسار النهائي يجب أن يكون:</strong><br>
                    <code>wp-content/mu-plugins/cors-headers.php</code>
                </div>
            </div>

            <div class="step">
                <h3><span class="step-number">3</span> رفع ملف .htaccess للـ API</h3>
                <ul class="checklist">
                    <li>انتقل إلى مجلد WordPress على الخادم</li>
                    <li>ابحث عن المسار: <code>wp-json/murjan-api/v1/</code></li>
                    <li>ارفع ملف <code>api/.htaccess</code> إلى هذا المجلد</li>
                    <li>تأكد من تفعيل "Show Hidden Files" لرؤية ملف .htaccess</li>
                </ul>
            </div>

            <div class="step">
                <h3><span class="step-number">4</span> التحقق من تفعيل Plugin</h3>
                <ul class="checklist">
                    <li>اذهب إلى: WordPress Admin > Plugins</li>
                    <li>تأكد من تفعيل: "WooCommerce Products API Manager"</li>
                    <li>تأكد من تفعيل: "WooCommerce"</li>
                    <li>يجب أن ترى "Products API" في القائمة الجانبية</li>
                </ul>
            </div>

            <div class="step">
                <h3><span class="step-number">5</span> اختبار الاتصال</h3>
                <p>افتح Console في المتصفح (F12) واكتب:</p>
                <pre><code>fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=1')
  .then(r => r.json())
  .then(d => console.log('✅ Success:', d))
  .catch(e => console.error('❌ Error:', e))</code></pre>
                
                <div class="status-card" style="margin-top: 20px;">
                    <strong>النتيجة المتوقعة:</strong> يجب أن ترى بيانات المنتجات بدون أخطاء CORS
                </div>
            </div>

            <div class="step">
                <h3><span class="step-number">6</span> بناء للإنتاج (Production Build)</h3>
                <p>عندما تريد نشر المشروع:</p>
                <pre><code># بناء المشروع
npm run build

# معاينة النسخة المبنية
npm run preview

# نشر على Vercel
npm install -g vercel
vercel --prod</code></pre>

                <div class="status-card warning" style="margin-top: 20px;">
                    <strong>⚠️ مهم للنشر على Vercel:</strong><br>
                    تأكد من إضافة متغيرات البيئة في Vercel Dashboard > Settings > Environment Variables
                </div>
            </div>

            <div class="status-card">
                <h3>📚 ملفات مرجعية للمساعدة:</h3>
                <ul class="checklist">
                    <li><strong>CORS-FIX-GUIDE.php:</strong> دليل شامل لحل مشاكل CORS (افتحه في المتصفح)</li>
                    <li><strong>env.example:</strong> مثال لملف البيئة</li>
                    <li><strong>.env.local:</strong> ملف البيئة الحالي (تم إنشاؤه تلقائياً)</li>
                    <li><strong>api/cors-headers.php:</strong> ملف CORS للرفع على الخادم</li>
                    <li><strong>api/.htaccess:</strong> إعدادات Apache للـ API</li>
                </ul>
            </div>

            <div class="status-card error">
                <h3>🔴 استكشاف الأخطاء الشائعة:</h3>
                
                <h4 style="margin-top: 15px;">1. لا تظهر المنتجات:</h4>
                <p>✓ تحقق من Console - هل هناك أخطاء CORS؟</p>
                <p>✓ تحقق من Network tab - ما هي حالة الطلب؟</p>
                <p>✓ تأكد من رفع cors-headers.php للخادم</p>

                <h4 style="margin-top: 15px;">2. خطأ 401 Unauthorized:</h4>
                <p>✓ تحقق من صحة Consumer Key و Secret</p>
                <p>✓ تأكد من أن مفاتيح API لها صلاحيات Read/Write</p>

                <h4 style="margin-top: 15px;">3. خطأ 404 Not Found:</h4>
                <p>✓ تأكد من تفعيل Plugin</p>
                <p>✓ جرب زيارة: https://dev.murjan.sa/wp-json/murjan-api/v1/products</p>

                <h4 style="margin-top: 15px;">4. الإحصائيات لا تظهر:</h4>
                <p>✓ يتم حساب الإحصائيات من قائمة المنتجات</p>
                <p>✓ تحقق من وجود منتجات في المتجر</p>
                <p>✓ افتح Console وابحث عن رسائل "📊 Stats"</p>
            </div>

            <div class="step">
                <h3>🎨 الميزات المتاحة في التطبيق:</h3>
                <ul class="checklist">
                    <li>عرض المنتجات في شبكة متجاوبة (Products Grid)</li>
                    <li>بطاقات منتجات تفاعلية مع جميع التفاصيل</li>
                    <li>إضافة منتجات جديدة (Simple & Variable)</li>
                    <li>تعديل المنتجات الموجودة</li>
                    <li>رفع وإدارة الصور</li>
                    <li>إدارة Variations للمنتجات المتغيرة</li>
                    <li>تحديث حالة المخزون</li>
                    <li>فلترة وبحث متقدم</li>
                    <li>Pagination للمنتجات</li>
                    <li>إحصائيات شاملة في Dashboard</li>
                    <li>Dark Mode</li>
                    <li>واجهة عربية/إنجليزية</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <h3>🎉 كل شيء جاهز!</h3>
            <p style="margin: 15px 0;">بمجرد رفع ملفات CORS، سيعمل التطبيق بشكل كامل</p>
            <p style="color: #666; font-size: 0.9em;">
                للحصول على دليل مفصل لحل CORS، افتح ملف:<br>
                <code>api/CORS-FIX-GUIDE.php</code> في المتصفح
            </p>
        </div>
    </div>
</body>
</html>

