# 🔒 دليل الأمان والحماية

معلومات شاملة عن آليات الحماية والأمان في WooCommerce Products API Manager

---

## 📋 المحتويات

1. [نظرة عامة](#نظرة-عامة)
2. [آليات الحماية](#آليات-الحماية)
3. [التوثيق والتفويض](#التوثيق-والتفويض)
4. [أفضل الممارسات](#أفضل-الممارسات)
5. [معايير الأمان](#معايير-الأمان)
6. [التدقيق والمراقبة](#التدقيق-والمراقبة)

---

## 🛡️ نظرة عامة

تم تصميم API مع التركيز الأساسي على الأمان والحماية. جميع الروابط محمية ومشفرة.

### مستويات الحماية:

```
┌─────────────────────────────────────────┐
│  1️⃣ HTTPS Encryption                    │
│     └─ تشفير جميع البيانات المنقولة    │
├─────────────────────────────────────────┤
│  2️⃣ Authentication                      │
│     └─ التحقق من مفاتيح API            │
├─────────────────────────────────────────┤
│  3️⃣ Authorization                       │
│     └─ التحقق من الصلاحيات              │
├─────────────────────────────────────────┤
│  4️⃣ Input Validation                    │
│     └─ تنظيف وفحص جميع المدخلات        │
├─────────────────────────────────────────┤
│  5️⃣ Output Sanitization                 │
│     └─ تنظيف جميع المخرجات             │
└─────────────────────────────────────────┘
```

---

## 🔐 آليات الحماية

### 1. التشفير (Encryption)

#### HTTPS فقط

**مطلوب:** جميع طلبات API يجب أن تكون عبر HTTPS

```bash
✅ https://dev.murjan.sa/wp-json/murjan-api/v1/...
❌ http://dev.murjan.sa/wp-json/murjan-api/v1/...
```

**السبب:**
- حماية المفاتيح من الاعتراض
- تشفير البيانات المنقولة
- منع هجمات Man-in-the-Middle

**التنفيذ:**
```php
// يتم التحقق تلقائياً في WordPress
if (!is_ssl() && !is_local_environment()) {
    return new WP_Error('ssl_required', 'HTTPS is required');
}
```

---

### 2. التوثيق (Authentication)

#### WooCommerce API Keys

**الطريقة المستخدمة:** Basic Authentication

```
Authorization: Basic base64(consumer_key:consumer_secret)
```

**التحقق متعدد المراحل:**

```php
// المرحلة 1: التحقق من وجود المفاتيح
if (!$consumer_key || !$consumer_secret) {
    return WP_Error('auth_missing');
}

// المرحلة 2: البحث عن المفتاح في قاعدة البيانات
$key_data = get_api_key($consumer_key);
if (!$key_data) {
    return WP_Error('invalid_key');
}

// المرحلة 3: التحقق من Consumer Secret
if (!hash_equals($key_data->consumer_secret, $consumer_secret)) {
    return WP_Error('invalid_secret');
}
```

**حماية hash_equals:**
- يمنع Timing Attacks
- مقارنة آمنة للنصوص

---

### 3. التفويض (Authorization)

#### التحقق من الصلاحيات

```php
// صلاحيات API Key
if ($key_data->permissions !== 'read_write') {
    return WP_Error('insufficient_permissions');
}

// صلاحيات المستخدم
$user = get_user_by('id', $key_data->user_id);
if (!$user->has_cap('manage_woocommerce')) {
    return WP_Error('user_insufficient_permissions');
}
```

**الصلاحيات المطلوبة:**
- ✅ API Key: Read/Write
- ✅ User: manage_woocommerce capability

---

### 4. تنظيف المدخلات (Input Sanitization)

#### جميع المدخلات يتم تنظيفها

```php
// النصوص
$name = sanitize_text_field($input['name']);

// HTML
$description = wp_kses_post($input['description']);

// الأرقام
$price = floatval($input['price']);
$quantity = absint($input['quantity']);

// SKU
$sku = sanitize_text_field($input['sku']);
```

**الوظائف المستخدمة:**
- `sanitize_text_field()` - تنظيف النصوص
- `wp_kses_post()` - تنظيف HTML
- `floatval()` - تحويل إلى رقم عشري
- `absint()` - رقم صحيح موجب

---

### 5. حماية قاعدة البيانات

#### Prepared Statements

**جميع استعلامات SQL تستخدم Prepared Statements:**

```php
$wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys 
     WHERE consumer_key = %s",
    $consumer_key
);
```

**الفوائد:**
- ✅ حماية من SQL Injection
- ✅ معالجة آمنة للبيانات
- ✅ تشفير المعاملات

---

### 6. حماية من XSS

#### تنظيف المخرجات

```php
// عرض البيانات
echo esc_html($product->get_name());
echo esc_url($product->get_permalink());
echo esc_attr($product->get_sku());

// في JSON
wp_send_json(array(
    'name' => sanitize_text_field($name)
));
```

---

## 🔑 التوثيق والتفويض

### إنشاء مفاتيح API آمنة

#### ✅ الطريقة الصحيحة:

```
1. WooCommerce > Settings > Advanced > REST API
2. Add key
3. Description: واضحة ومحددة (مثل: "Mobile App v1.0")
4. User: حساب مخصص للـ API (ليس admin الرئيسي)
5. Permissions: Read/Write فقط ما تحتاجه
```

#### ❌ أخطاء شائعة:

```
❌ استخدام حساب admin الرئيسي
❌ صلاحيات غير محددة
❌ وصف غير واضح
❌ عدم تدوير المفاتيح بشكل دوري
```

---

### تخزين المفاتيح

#### ✅ الطريقة الآمنة:

```javascript
// في Node.js - استخدم متغيرات البيئة
require('dotenv').config();

const API_KEY = process.env.WOO_CONSUMER_KEY;
const API_SECRET = process.env.WOO_CONSUMER_SECRET;
```

```php
// في PHP - استخدم ملف config خارج public_html
define('WOO_CONSUMER_KEY', getenv('WOO_CONSUMER_KEY'));
define('WOO_CONSUMER_SECRET', getenv('WOO_CONSUMER_SECRET'));
```

```python
# في Python - استخدم متغيرات البيئة
import os
API_KEY = os.getenv('WOO_CONSUMER_KEY')
API_SECRET = os.getenv('WOO_CONSUMER_SECRET')
```

#### ❌ لا تفعل أبداً:

```javascript
// ❌ لا تحفظ المفاتيح في الكود مباشرة
const API_KEY = 'ck_123456789...';

// ❌ لا ترفعها على GitHub
git add config.js

// ❌ لا تضعها في JavaScript للمتصفح
<script>
    const apiKey = 'ck_123456789...';
</script>
```

---

## 🛡️ أفضل الممارسات

### 1. استخدام HTTPS دائماً

```bash
# تثبيت SSL Certificate
sudo certbot --nginx -d dev.murjan.sa
```

### 2. تدوير المفاتيح بانتظام

**الجدول الموصى به:**
- 🔄 كل 3 أشهر: للاستخدام العادي
- 🔄 كل شهر: للتطبيقات الحساسة
- 🔄 فوراً: في حالة الاشتباه بالاختراق

**الطريقة:**
```
1. أنشئ مفاتيح جديدة
2. حدّث التطبيقات لاستخدام المفاتيح الجديدة
3. اختبر جميع الوظائف
4. احذف المفاتيح القديمة
```

### 3. استخدام حسابات منفصلة

```
👤 admin - للإدارة اليومية
👤 api_user_mobile - لتطبيق الموبايل
👤 api_user_erp - لنظام ERP
👤 api_user_website - للموقع الخارجي
```

**الفوائد:**
- ✅ سهولة التتبع
- ✅ إلغاء وصول محدد
- ✅ تدقيق أفضل

### 4. تقييد الصلاحيات

```
✅ Read only - للعرض فقط
✅ Read/Write - للإضافة والتعديل
❌ لا تستخدم Write فقط
```

### 5. مراقبة الاستخدام

```php
// تسجيل محاولات الوصول
add_action('woocommerce_api_authentication_error', function($error) {
    error_log('API Auth Failed: ' . $error->get_error_message());
});

// تسجيل الطلبات الناجحة
add_action('rest_api_init', function() {
    error_log('API Request: ' . $_SERVER['REQUEST_URI']);
});
```

---

## 📊 معايير الأمان

### OWASP Top 10 Compliance

#### ✅ 1. Injection
- استخدام Prepared Statements
- تنظيف جميع المدخلات

#### ✅ 2. Broken Authentication
- WooCommerce API Keys قوية
- hash_equals للمقارنات

#### ✅ 3. Sensitive Data Exposure
- HTTPS إلزامي
- عدم عرض المفاتيح في الأخطاء

#### ✅ 4. XML External Entities (XXE)
- عدم استخدام XML
- JSON فقط

#### ✅ 5. Broken Access Control
- التحقق من صلاحيات المستخدم
- التحقق من صلاحيات API Key

#### ✅ 6. Security Misconfiguration
- إعدادات افتراضية آمنة
- عدم عرض معلومات النظام

#### ✅ 7. Cross-Site Scripting (XSS)
- تنظيف جميع المخرجات
- wp_kses_post للـ HTML

#### ✅ 8. Insecure Deserialization
- عدم استخدام unserialize
- JSON parsing آمن

#### ✅ 9. Using Components with Known Vulnerabilities
- تحديثات منتظمة
- استخدام WordPress و WooCommerce functions

#### ✅ 10. Insufficient Logging & Monitoring
- تسجيل محاولات الوصول
- تنبيهات للنشاطات المشبوهة

---

## 📈 التدقيق والمراقبة

### تفعيل Logging

```php
// في wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### مراقبة النشاطات

```php
// تسجيل API Requests
add_filter('rest_pre_dispatch', function($result, $server, $request) {
    $route = $request->get_route();
    $method = $request->get_method();
    
    error_log("API: $method $route");
    
    return $result;
}, 10, 3);
```

### تنبيهات الأمان

```php
// تنبيه عند فشل التوثيق المتكرر
$failed_attempts = get_transient('api_failed_attempts_' . $ip);
if ($failed_attempts > 5) {
    // إرسال بريد إلكتروني للمدير
    wp_mail(
        get_option('admin_email'),
        'API Security Alert',
        "Multiple failed authentication attempts from $ip"
    );
}
```

---

## 🚨 الاستجابة للحوادث

### في حالة اشتباه باختراق:

#### الخطوة 1: إيقاف الوصول فوراً
```
WooCommerce > Settings > Advanced > REST API
> احذف جميع المفاتيح المشبوهة
```

#### الخطوة 2: مراجعة السجلات
```bash
# راجع error_log
tail -f wp-content/debug.log

# ابحث عن نشاطات مشبوهة
grep "API" wp-content/debug.log
```

#### الخطوة 3: تغيير جميع المفاتيح
```
1. أنشئ مفاتيح جديدة
2. حدّث جميع التطبيقات
3. احذف المفاتيح القديمة
```

#### الخطوة 4: فحص المنتجات
```sql
-- تحقق من المنتجات المضافة مؤخراً
SELECT * FROM wp_posts 
WHERE post_type = 'product' 
AND post_date > DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY post_date DESC;
```

#### الخطوة 5: التوثيق والإبلاغ
```
1. سجّل تفاصيل الحادث
2. أبلغ الفريق
3. راجع وحسّن الإجراءات
```

---

## 📝 قائمة المراجعة الأمنية

### قبل الإطلاق:

- [ ] HTTPS مفعّل ويعمل
- [ ] SSL Certificate صالح
- [ ] مفاتيح API قوية
- [ ] صلاحيات محددة بدقة
- [ ] حسابات منفصلة للـ API
- [ ] Logging مفعّل
- [ ] Debug mode مطفأ في الإنتاج
- [ ] Firewall مفعّل
- [ ] نسخ احتياطية منتظمة
- [ ] خطة للاستجابة للحوادث

### مراجعة شهرية:

- [ ] فحص السجلات للنشاطات المشبوهة
- [ ] مراجعة المفاتيح النشطة
- [ ] حذف المفاتيح غير المستخدمة
- [ ] تحديث WordPress و WooCommerce
- [ ] فحص الإضافات للثغرات
- [ ] اختبار النسخ الاحتياطية
- [ ] مراجعة صلاحيات المستخدمين

---

## 🔗 موارد إضافية

### مصادر للتعلم:

- [OWASP REST Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/REST_Security_Cheat_Sheet.html)
- [WordPress Security White Paper](https://wordpress.org/about/security/)
- [WooCommerce Security Best Practices](https://woocommerce.com/document/security-best-practices/)

### أدوات فحص الأمان:

- [WPScan](https://wpscan.com/) - فحص ثغرات WordPress
- [Sucuri SiteCheck](https://sitecheck.sucuri.net/) - فحص الموقع
- [SSL Labs](https://www.ssllabs.com/ssltest/) - فحص SSL

---

## 📞 الإبلاغ عن ثغرات

إذا اكتشفت ثغرة أمنية:

📧 **Email:** security@murjan.sa

**يرجى تضمين:**
- وصف تفصيلي للثغرة
- خطوات إعادة إنتاج المشكلة
- التأثير المحتمل
- اقتراحات للحل (إن وُجدت)

**سنقوم بـ:**
- ✅ الرد خلال 24 ساعة
- ✅ تقييم الثغرة
- ✅ تطوير وإصدار تحديث
- ✅ إشعارك بالحل

---

**الأمان مسؤولية مشتركة. 🔒**

شكراً لمساعدتك في حماية النظام!

