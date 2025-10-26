# 🚀 WooCommerce Products API Manager

نظام REST API متكامل لإدارة المنتجات الفيزيائية والمتغيرة في WooCommerce مع حماية كاملة وتوثيق شامل.

## 📋 المحتويات

- [نظرة عامة](#نظرة-عامة)
- [المتطلبات](#المتطلبات)
- [التثبيت](#التثبيت)
- [الإعداد](#الإعداد)
- [استخدام API](#استخدام-api)
- [الأمثلة](#الأمثلة)
- [Postman Collection](#postman-collection)

---

## 🎯 نظرة عامة

هذه الإضافة توفر REST API كامل لإدارة:

### 🔵 المنتجات الفيزيائية (Physical Products)
- إضافة منتج جديد
- تعديل منتج موجود
- عرض جميع المنتجات
- عرض منتج واحد
- البحث في المنتجات
- حذف منتج
- عرض الإحصائيات

### 🟢 المنتجات المتغيرة (Variable Products)
- إضافة منتج متغير مع تنويعاته
- تعديل منتج متغير
- عرض جميع المنتجات المتغيرة
- عرض منتج متغير واحد
- البحث في المنتجات
- حذف منتج متغير
- عرض الإحصائيات التفصيلية

### 🔒 الحماية والأمان
- توثيق عبر WooCommerce API Keys
- التحقق من صلاحيات المستخدم
- حماية جميع الروابط
- دعم Basic Authentication

---

## 💻 المتطلبات

- **WordPress:** 5.8 أو أحدث
- **WooCommerce:** 5.0 أو أحدث
- **PHP:** 7.4 أو أحدث
- **صلاحيات:** manage_woocommerce

---

## 📦 التثبيت

### الطريقة 1: رفع الملفات يدوياً

1. ارفع مجلد `api` إلى:
```
wp-content/plugins/woo-products-importer/api/
```

2. افتح لوحة تحكم WordPress

3. اذهب إلى: **الإضافات** > **الإضافات المثبتة**

4. فعّل إضافة: **WooCommerce Products API Manager**

### الطريقة 2: عبر FTP

1. اتصل بالموقع عبر FTP

2. انسخ مجلد `api` إلى:
```
/public_html/wp-content/plugins/woo-products-importer/api/
```

3. فعّل الإضافة من لوحة التحكم

---

## ⚙️ الإعداد

### 1. إنشاء مفاتيح API

1. اذهب إلى: **WooCommerce** > **الإعدادات** > **متقدم** > **REST API**

2. اضغط على: **Add key**

3. املأ البيانات:
   - **Description:** Murjan Products API
   - **User:** اختر مستخدم له صلاحية Administrator
   - **Permissions:** Read/Write

4. اضغط: **Generate API key**

5. احفظ المفاتيح:
   - **Consumer key:** `ck_xxxxxxxxxxxxxxx`
   - **Consumer secret:** `cs_xxxxxxxxxxxxxxx`

⚠️ **مهم:** احتفظ بالمفاتيح في مكان آمن!

### 2. اختبار الاتصال

استخدم Postman أو cURL للاختبار:

```bash
curl -X GET \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/stats' \
  -u 'ck_xxx:cs_xxx'
```

إذا حصلت على استجابة JSON، فالإعداد ناجح! ✅

---

## 🔌 استخدام API

### الرابط الأساسي

```
https://dev.murjan.sa/wp-json/murjan-api/v1
```

### التوثيق (Authentication)

جميع الطلبات تحتاج إلى توثيق. استخدم إحدى الطرق:

#### 1. Basic Authentication (موصى به)

```bash
curl -X GET \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products' \
  -u 'ck_YOUR_KEY:cs_YOUR_SECRET'
```

#### 2. Authorization Header

```bash
curl -X GET \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products' \
  -H 'Authorization: Basic BASE64_ENCODED_CREDENTIALS'
```

حيث `BASE64_ENCODED_CREDENTIALS` = `base64_encode(consumer_key:consumer_secret)`

---

## 📚 الأمثلة

### مثال 1: إضافة منتج فيزيائي

```bash
curl -X POST \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products' \
  -u 'ck_xxx:cs_xxx' \
  -H 'Content-Type: application/json' \
  -d '{
    "name": "هاتف ذكي سامسونج",
    "description": "هاتف ذكي بمواصفات عالية",
    "short_description": "سامسونج الجديد",
    "sku": "PHONE-001",
    "regular_price": 2999.00,
    "sale_price": 2499.00,
    "stock_quantity": 50,
    "weight": 0.195,
    "length": 16.2,
    "width": 7.5,
    "height": 0.9
  }'
```

### مثال 2: إضافة منتج متغير

```bash
curl -X POST \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products' \
  -u 'ck_xxx:cs_xxx' \
  -H 'Content-Type: application/json' \
  -d '{
    "name": "قميص رجالي",
    "description": "قميص قطني فاخر",
    "sku": "SHIRT-001",
    "attributes": [
      {
        "name": "Color",
        "options": ["أبيض", "أزرق", "أسود"]
      },
      {
        "name": "Size",
        "options": ["S", "M", "L", "XL"]
      }
    ],
    "variations": [
      {
        "attributes": {
          "Color": "أبيض",
          "Size": "M"
        },
        "regular_price": 199.00,
        "stock_quantity": 30,
        "sku": "SHIRT-001-WHITE-M"
      }
    ]
  }'
```

### مثال 3: البحث عن منتج

```bash
curl -X GET \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/search?s=هاتف' \
  -u 'ck_xxx:cs_xxx'
```

### مثال 4: الحصول على الإحصائيات

```bash
curl -X GET \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/stats' \
  -u 'ck_xxx:cs_xxx'
```

---

## 📮 Postman Collection

### استيراد المجموعة

1. افتح Postman

2. اضغط: **Import**

3. اختر الملف:
```
api/postman/WooCommerce-Products-API-Collection.postman_collection.json
```

4. سيتم استيراد جميع الـ Endpoints مع:
   - ✅ أمثلة كاملة للبيانات
   - ✅ شرح مفصل لكل endpoint
   - ✅ اختبارات تلقائية
   - ✅ متغيرات للتسهيل

### إعداد Postman

1. بعد الاستيراد، اذهب إلى: **Variables** (في Collection)

2. تأكد من:
   - `base_url`: `https://dev.murjan.sa/wp-json/murjan-api/v1`

3. اذهب إلى: **Authorization** (في Collection)

4. املأ:
   - **Type:** Basic Auth
   - **Username:** مفتاح Consumer Key الخاص بك
   - **Password:** مفتاح Consumer Secret الخاص بك

5. احفظ التغييرات

### استخدام Postman

الآن يمكنك:
- ✅ اختبار جميع الـ Endpoints
- ✅ تعديل البيانات حسب حاجتك
- ✅ رؤية الاستجابات الفعلية
- ✅ حفظ النتائج للمراجعة

---

## 📖 قائمة Endpoints الكاملة

### المنتجات الفيزيائية

| Method | Endpoint | الوصف |
|--------|----------|-------|
| POST | `/physical-products` | إضافة منتج جديد |
| PUT | `/physical-products/{id}` | تعديل منتج |
| GET | `/physical-products` | عرض جميع المنتجات |
| GET | `/physical-products/{id}` | عرض منتج واحد |
| GET | `/physical-products/search?s=query` | البحث |
| DELETE | `/physical-products/{id}` | حذف منتج |
| GET | `/physical-products/stats` | الإحصائيات |

### المنتجات المتغيرة

| Method | Endpoint | الوصف |
|--------|----------|-------|
| POST | `/variable-products` | إضافة منتج متغير |
| PUT | `/variable-products/{id}` | تعديل منتج |
| GET | `/variable-products` | عرض جميع المنتجات |
| GET | `/variable-products/{id}` | عرض منتج واحد |
| GET | `/variable-products/search?s=query` | البحث |
| DELETE | `/variable-products/{id}` | حذف منتج |
| GET | `/variable-products/stats` | الإحصائيات |

---

## 🔒 معلومات الأمان

### الحماية المطبقة:

1. ✅ **التوثيق الإلزامي:** جميع الروابط محمية
2. ✅ **التحقق من المفاتيح:** مقارنة آمنة للمفاتيح
3. ✅ **فحص الصلاحيات:** التحقق من manage_woocommerce
4. ✅ **تنظيف البيانات:** جميع المدخلات يتم تنظيفها
5. ✅ **حماية SQL Injection:** استخدام Prepared Statements
6. ✅ **حماية XSS:** تنظيف المحتوى HTML

### أفضل الممارسات:

- 🔐 استخدم HTTPS دائماً
- 🔐 لا تشارك المفاتيح في الأماكن العامة
- 🔐 قم بتجديد المفاتيح بشكل دوري
- 🔐 استخدم مفاتيح منفصلة لكل تطبيق
- 🔐 راقب استخدام API بانتظام

---

## 🎯 حالات الاستخدام

### 1. تطبيقات الموبايل
استخدم API لعرض وإدارة المنتجات في تطبيق iOS أو Android

### 2. المواقع الخارجية
قم بمزامنة المنتجات بين موقعك ومواقع أخرى

### 3. أنظمة ERP
ربط WooCommerce مع نظام إدارة المخزون

### 4. لوحات التحكم المخصصة
إنشاء لوحة تحكم مخصصة لإدارة المنتجات

### 5. الأتمتة
أتمتة عمليات إضافة وتحديث المنتجات

---

## 🐛 حل المشاكل

### المشكلة: 401 Unauthorized

**الحل:**
- تأكد من صحة Consumer Key و Consumer Secret
- تأكد من استخدام Basic Authentication بشكل صحيح
- تحقق من أن المفاتيح نشطة وغير محذوفة

### المشكلة: 403 Forbidden

**الحل:**
- تأكد من أن المفاتيح لها صلاحيات Read/Write
- تحقق من أن المستخدم المرتبط بالمفاتيح له صلاحية manage_woocommerce

### المشكلة: 404 Not Found

**الحل:**
- تأكد من تفعيل الإضافة
- تحقق من Permalinks: اذهب إلى **الإعدادات** > **الروابط الدائمة** واضغط حفظ

### المشكلة: 500 Internal Server Error

**الحل:**
- تفعيل WP_DEBUG لرؤية الخطأ بالتفصيل
- تحقق من سجلات الأخطاء (Error Logs)
- تأكد من تثبيت WooCommerce بشكل صحيح

---

## 📞 الدعم

في حالة وجود أي مشاكل أو استفسارات:

- 📧 Email: support@murjan.sa
- 🌐 Website: https://dev.murjan.sa
- 📱 Documentation: راجع ملفات التوثيق المرفقة

---

## 📄 الترخيص

هذه الإضافة مرخصة تحت GPL v2 أو أحدث

---

## 🙏 الشكر

تم تطوير هذه الإضافة بواسطة فريق Murjan لتسهيل إدارة المنتجات في WooCommerce عبر REST API.

---

**نتمنى لك تجربة ممتازة! 🚀**

