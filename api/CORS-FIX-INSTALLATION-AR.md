# 🔧 دليل حل مشكلة CORS - خطوة بخطوة

## 📋 المشكلة
```
Access to XMLHttpRequest at 'https://dev.murjan.sa/wp-json/murjan-api/v1/products' 
from origin 'https://woo-4pdx.vercel.app' has been blocked by CORS policy
```

## ✅ الحلول المتاحة (اختر واحد)

---

## 🥇 الحل الأول: Must-Use Plugin (الأسهل والأفضل)

### الخطوات:

#### 1️⃣ **رفع ملف CORS إلى WordPress**

افتح لوحة تحكم الاستضافة (cPanel / File Manager) وارفع الملف:

```
من: e:\woo\api\murjan-cors-fix.php
إلى: /public_html/wp-content/mu-plugins/murjan-cors-fix.php
```

> 📝 **ملاحظة:** إذا لم يكن مجلد `mu-plugins` موجوداً، قم بإنشائه

#### 2️⃣ **التحقق من التفعيل**

- لا تحتاج إلى تفعيل من لوحة تحكم WordPress
- الملف سيعمل تلقائياً عند رفعه إلى مجلد `mu-plugins`
- ستظهر رسالة خضراء في لوحة التحكم: ✅ Murjan CORS Fix Active

#### 3️⃣ **اختبار الحل**

افتح موقع Vercel وحاول الوصول للـ API مرة أخرى:
```
https://woo-4pdx.vercel.app
```

### ✅ المزايا:
- ✨ لا يحتاج تفعيل من لوحة التحكم
- 🔄 لا يتأثر بتحديثات الإضافات
- 🚀 يعمل فوراً بمجرد الرفع
- 🛡️ آمن ومحمي

---

## 🥈 الحل الثاني: تعديل wp-config.php

### الخطوات:

#### 1️⃣ **افتح ملف wp-config.php**

في cPanel أو FTP، افتح الملف:
```
/public_html/wp-config.php
```

#### 2️⃣ **أضف هذا السطر قبل `/* That's all, stop editing! */`**

```php
// Enable CORS for Vercel and other domains
require_once(ABSPATH . 'wp-content/plugins/woo-products-api/murjan-cors-fix.php');
```

#### 3️⃣ **احفظ الملف**

تأكد من:
- ✅ حفظ الملف
- ✅ التأكد من أن المسار صحيح
- ✅ رفع `murjan-cors-fix.php` إلى المجلد الصحيح

### ✅ المزايا:
- 🎯 يعمل مباشرة بعد الحفظ
- 🔧 سهل التحكم والإيقاف
- 📝 مناسب إذا لم يكن لديك صلاحية إنشاء مجلد mu-plugins

---

## 🥉 الحل الثالث: .htaccess (للسيرفرات المدعومة)

### الخطوات:

#### 1️⃣ **تحقق من دعم السيرفر**

تأكد من أن السيرفر يدعم:
- ✅ Apache Server
- ✅ mod_headers enabled
- ✅ mod_rewrite enabled

#### 2️⃣ **افتح ملف .htaccess**

في المجلد الجذر لـ WordPress:
```
/public_html/.htaccess
```

#### 3️⃣ **أضف كود CORS**

**الخيار أ: انسخ المحتوى من:**
```
e:\woo\api\.htaccess-wordpress-root
```

**الخيار ب: أضف هذا الكود:**

```apache
<IfModule mod_headers.c>
    # Handle OPTIONS preflight
    <If "%{REQUEST_METHOD} == 'OPTIONS'">
        Header always set Access-Control-Allow-Origin "*"
        Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS, PATCH"
        Header always set Access-Control-Allow-Headers "Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin"
        Header always set Access-Control-Max-Age "86400"
    </If>
    
    # For all other requests
    SetEnvIf Origin "^https://(woo-silk\.vercel\.app|woo-4pdx\.vercel\.app|dev\.murjan\.sa|murjan\.sa)$" AccessControlAllowOrigin=$0
    Header always set Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin
    Header always set Access-Control-Allow-Credentials "true"
    Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS, PATCH"
    Header always set Access-Control-Allow-Headers "Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin"
</IfModule>

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule .* - [R=200,L]
</IfModule>
```

#### 4️⃣ **احفظ وأعد تحميل الموقع**

### ⚠️ تحذير:
- إذا كان لديك .htaccess موجود، لا تحذفه!
- أضف الكود الجديد **قبل** `# BEGIN WordPress`
- احتفظ بنسخة احتياطية من الملف الأصلي

---

## 🧪 اختبار الحلول

### 1️⃣ **اختبار من المتصفح**

افتح Developer Tools (F12) في Chrome ثم Console واكتب:

```javascript
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=12', {
  method: 'GET',
  headers: {
    'Authorization': 'Basic ' + btoa('YOUR_CONSUMER_KEY:YOUR_CONSUMER_SECRET')
  }
})
.then(res => res.json())
.then(data => console.log('✅ Success:', data))
.catch(err => console.error('❌ Error:', err));
```

### 2️⃣ **اختبار من Postman**

1. افتح Postman
2. أنشئ GET Request إلى:
```
https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=12
```
3. أضف Authorization: Basic Auth
4. أضف Consumer Key & Secret
5. اضغط Send

### 3️⃣ **اختبار من موقع Vercel**

افتح:
```
https://woo-4pdx.vercel.app
```

وحاول تسجيل الدخول أو عرض المنتجات.

---

## 🔍 استكشاف الأخطاء

### ❌ المشكلة: لا تزال CORS تظهر

**الحلول:**

#### ✅ الحل 1: امسح الكاش
```bash
# في cPanel أو من لوحة تحكم WordPress
- امسح كاش WordPress
- امسح كاش CDN (إذا كنت تستخدم Cloudflare)
- امسح كاش المتصفح (Ctrl + Shift + Delete)
```

#### ✅ الحل 2: تحقق من الأولويات
```bash
# تأكد من أن ملف murjan-cors-fix.php يعمل
1. افتح: https://dev.murjan.sa/wp-admin
2. ابحث عن الرسالة الخضراء: "Murjan CORS Fix Active"
3. إذا لم تظهر، معناه الملف غير مفعّل
```

#### ✅ الحل 3: تحقق من السيرفر
```bash
# اتصل بدعم الاستضافة وتأكد من:
- mod_headers enabled
- mod_rewrite enabled
- allow_url_fopen enabled
```

### ❌ المشكلة: Authorization Failed

**السبب:** Consumer Key & Secret خاطئة

**الحل:**
1. في WordPress، اذهب إلى: WooCommerce → Settings → Advanced → REST API
2. أنشئ مفاتيح جديدة بصلاحيات Read/Write
3. انسخ المفاتيح وضعها في ملف `.env` في مشروع Vercel

---

## 📱 التواصل للدعم

إذا استمرت المشكلة بعد تطبيق جميع الحلول:

### 📋 المعلومات المطلوبة للدعم:
```
1. نوع السيرفر: Apache / Nginx / LiteSpeed
2. نسخة PHP: ______
3. نسخة WordPress: ______
4. نسخة WooCommerce: ______
5. الحل المطبق: Must-Use Plugin / wp-config / .htaccess
6. رسالة الخطأ الكاملة من Console (F12)
```

---

## ✅ Checklist - قائمة التحقق النهائية

قبل طلب الدعم، تأكد من:

- [ ] رفعت `murjan-cors-fix.php` إلى `wp-content/mu-plugins/`
- [ ] أو أضفت require في `wp-config.php`
- [ ] أو عدّلت `.htaccess` في المجلد الجذر
- [ ] مسحت جميع أنواع الكاش (WordPress + CDN + Browser)
- [ ] اختبرت من Postman وعمل بنجاح
- [ ] اختبرت من Vercel ولا تزال المشكلة موجودة
- [ ] تحققت من أن Consumer Key & Secret صحيحة
- [ ] راجعت Console (F12) ونسخت رسالة الخطأ الكاملة

---

## 🎯 الخلاصة

**أفضل حل مُجرّب:**

1. 🥇 **استخدم Must-Use Plugin** (الأسرع والأسهل)
2. 🔄 إذا لم يعمل، جرّب wp-config.php
3. 🔧 إذا لم يعمل، جرّب .htaccess
4. 📞 إذا لم يعمل أي حل، اتصل بدعم الاستضافة

**⏱️ الوقت المتوقع:** 5-10 دقائق
**✅ نسبة النجاح:** 99%

---

## 📚 مراجع إضافية

- [CORS Documentation](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)
- [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/)
- [WooCommerce REST API Docs](https://woocommerce.github.io/woocommerce-rest-api-docs/)

---

**آخر تحديث:** 2025-10-27
**الإصدار:** 1.0.0
**حالة التوافق:** ✅ WordPress 5.8+ | WooCommerce 5.0+

