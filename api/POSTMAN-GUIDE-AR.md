# 📮 دليل استخدام Postman Collection

دليل شامل لاستخدام مجموعة Postman لاختبار API المنتجات

---

## 📋 المحتويات

1. [ما هو Postman؟](#ما-هو-postman)
2. [تثبيت Postman](#تثبيت-postman)
3. [استيراد Collection](#استيراد-collection)
4. [إعداد Authentication](#إعداد-authentication)
5. [استخدام Endpoints](#استخدام-endpoints)
6. [شرح التنظيم](#شرح-التنظيم)
7. [البيانات التجريبية](#البيانات-التجريبية)
8. [حل المشاكل](#حل-المشاكل)

---


## 🎯 ما هو Postman؟

**Postman** هو أداة قوية لاختبار APIs تتيح لك:

- ✅ إرسال طلبات HTTP بسهولة
- ✅ تنظيم الطلبات في مجموعات
- ✅ حفظ البيانات التجريبية
- ✅ إجراء اختبارات تلقائية
- ✅ مشاركة المجموعات مع الفريق

---

## 💻 تثبيت Postman

### الطريقة 1: تطبيق سطح المكتب (موصى به)

1. اذهب إلى: https://www.postman.com/downloads/
2. حمّل النسخة المناسبة لنظامك:
   - **Windows:** Postman-win64-Setup.exe
   - **Mac:** Postman-osx.zip
   - **Linux:** Postman-linux-x64.tar.gz
3. ثبّت التطبيق
4. أنشئ حساب مجاني (اختياري)

### الطريقة 2: نسخة المتصفح

1. اذهب إلى: https://web.postman.co/
2. سجّل دخول أو أنشئ حساب
3. استخدم Postman مباشرة من المتصفح

---

## 📥 استيراد Collection

### الخطوات:

#### 1. افتح Postman

#### 2. اضغط على زر "Import"
- في الشريط العلوي الأيسر
- أو اضغط `Ctrl+O` (Windows/Linux)
- أو اضغط `Cmd+O` (Mac)

#### 3. اختر طريقة الاستيراد

**خيار A: استيراد الملف**
- اضغط "Upload Files"
- اختر الملف:
```
woo-products-importer/api/postman/WooCommerce-Products-API-Collection.postman_collection.json
```
- اضغط "Open"

**خيار B: السحب والإفلات**
- اسحب ملف JSON وأفلته في نافذة Import

#### 4. تأكيد الاستيراد
- راجع معاينة Collection
- اضغط "Import"

✅ **تم!** ستظهر Collection في الشريط الجانبي الأيسر

---

## 🔐 إعداد Authentication

بعد استيراد Collection، يجب إعداد مفاتيح API:

### الخطوات:

#### 1. افتح Collection Settings
- اضغط على اسم Collection: **"WooCommerce Products API - Murjan"**
- اضغط على أيقونة النقاط الثلاث `···`
- اختر **"Edit"**

#### 2. اذهب إلى تبويب Authorization
- في النافذة المنبثقة، اختر تبويب **"Authorization"**

#### 3. تأكد من الإعدادات التالية:
- **Type:** Basic Auth
- **Username:** أدخل Consumer Key الخاص بك
```
ck_2210fb8d333da5da151029715a85618a4b283a52
```
- **Password:** أدخل Consumer Secret الخاص بك
```
cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
```

#### 4. احفظ التغييرات
- اضغط **"Update"**

✅ **جاهز!** الآن جميع الطلبات في Collection ستستخدم هذه المفاتيح تلقائياً.

---

## 🔧 إعداد المتغيرات (Variables)

Collection تحتوي على متغيرات لتسهيل الاستخدام:

### المتغيرات الموجودة:

#### 1. base_url
```
https://dev.murjan.sa/wp-json/murjan-api/v1
```
- يُستخدم في جميع الطلبات
- **لتعديله:**
  - افتح Collection Settings
  - اذهب إلى تبويب "Variables"
  - عدّل قيمة `base_url`
  - احفظ

#### 2. physical_product_id
- يُحفظ تلقائياً بعد إنشاء منتج فيزيائي
- يُستخدم في طلبات التعديل والحذف

#### 3. variable_product_id
- يُحفظ تلقائياً بعد إنشاء منتج متغير
- يُستخدم في طلبات التعديل والحذف

---

## 🚀 استخدام Endpoints

### بنية Collection

```
📁 WooCommerce Products API - Murjan
│
├── 📁 🔵 Physical Products
│   ├── 📄 إضافة منتج فيزيائي جديد
│   ├── 📄 تعديل منتج فيزيائي
│   ├── 📄 عرض جميع المنتجات الفيزيائية
│   ├── 📄 عرض منتج فيزيائي واحد
│   ├── 📄 البحث عن منتجات فيزيائية
│   ├── 📄 حذف منتج فيزيائي
│   └── 📄 إحصائيات المنتجات الفيزيائية
│
├── 📁 🟢 Variable Products
│   ├── 📄 إضافة منتج متغير جديد
│   ├── 📄 تعديل منتج متغير
│   ├── 📄 عرض جميع المنتجات المتغيرة
│   ├── 📄 عرض منتج متغير واحد
│   ├── 📄 البحث عن منتجات متغيرة
│   ├── 📄 حذف منتج متغير
│   └── 📄 إحصائيات المنتجات المتغيرة
│
└── 📁 🔐 Authentication Test
    ├── 📄 اختبار المفاتيح - Valid
    └── 📄 اختبار المفاتيح - Invalid
```

---

## 📝 مثال عملي: إضافة منتج فيزيائي

### الخطوة 1: اختر الطلب
- افتح مجلد **"🔵 Physical Products"**
- اضغط على **"إضافة منتج فيزيائي جديد"**

### الخطوة 2: راجع البيانات
في تبويب **"Body"** ستجد بيانات جاهزة:

```json
{
    "name": "هاتف ذكي سامسونج جالاكسي",
    "description": "هاتف ذكي بشاشة AMOLED...",
    "short_description": "هاتف سامسونج الجديد...",
    "sku": "PHONE-SAM-001",
    "regular_price": 2999.00,
    "sale_price": 2499.00,
    "stock_quantity": 50,
    "stock_status": "instock",
    "weight": 0.195,
    "length": 16.2,
    "width": 7.5,
    "height": 0.9
}
```

### الخطوة 3: عدّل البيانات (اختياري)
- يمكنك تعديل أي قيمة حسب حاجتك
- البيانات الموجودة جاهزة للاختبار

### الخطوة 4: أرسل الطلب
- اضغط الزر الأزرق **"Send"**

### الخطوة 5: راجع الاستجابة
في الأسفل في قسم **"Response"** ستظهر النتيجة:

```json
{
    "success": true,
    "message": "Product created successfully.",
    "product_id": 123,
    "product": {
        "id": 123,
        "name": "هاتف ذكي سامسونج جالاكسي",
        ...
    }
}
```

✅ **تم بنجاح!** المنتج أُضيف إلى المتجر.

---

## 🎨 فهم واجهة Postman

### المناطق الرئيسية:

```
┌─────────────────────────────────────────────────────┐
│  Collections  │  Request Tabs                       │
│               │  ┌──────────────────────────────┐   │
│  📁 Collection│  │ GET/POST/PUT/DELETE          │   │
│  ├─ Request 1 │  │ URL: {{base_url}}/...        │   │
│  ├─ Request 2 │  └──────────────────────────────┘   │
│  └─ Request 3 │                                      │
│               │  Tabs: Params | Authorization |     │
│               │        Headers | Body | ...         │
│               │  ┌──────────────────────────────┐   │
│               │  │                              │   │
│               │  │  Request Body / Params       │   │
│               │  │                              │   │
│               │  └──────────────────────────────┘   │
│               │                                      │
│               │  [ Send Button ]                     │
│               │                                      │
│               │  ┌──────────────────────────────┐   │
│               │  │  Response                    │   │
│               │  │  Status: 200 OK              │   │
│               │  │  Time: 245ms                 │   │
│               │  │  ┌────────────────────────┐  │   │
│               │  │  │ JSON Response          │  │   │
│               │  │  │ { "success": true ... }│  │   │
│               │  │  └────────────────────────┘  │   │
│               │  └──────────────────────────────┘   │
└─────────────────────────────────────────────────────┘
```

---

## 🧪 الاختبارات التلقائية

بعض الطلبات تحتوي على اختبارات تلقائية:

### مثال: طلب إضافة منتج

في تبويب **"Tests"** ستجد:

```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Product created successfully", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.success).to.eql(true);
    pm.expect(jsonData.product_id).to.exist;
    
    // Save product ID for other requests
    pm.environment.set("physical_product_id", jsonData.product_id);
});
```

### ماذا تفعل؟
- ✅ تتحقق من نجاح الطلب (200 OK)
- ✅ تتحقق من وجود `success: true`
- ✅ تتحقق من وجود `product_id`
- ✅ تحفظ `product_id` للاستخدام في طلبات أخرى

### كيف تراها؟
بعد إرسال الطلب، اذهب إلى تبويب **"Test Results"** في الاستجابة:

```
✓ Status code is 200
✓ Product created successfully

Tests passed: 2/2
```

---

## 📊 البيانات التجريبية

### 1. منتجات فيزيائية

#### مثال 1: هاتف ذكي
```json
{
  "name": "هاتف ذكي سامسونج جالاكسي",
  "sku": "PHONE-SAM-001",
  "regular_price": 2999.00,
  "sale_price": 2499.00,
  "stock_quantity": 50
}
```

#### مثال 2: لابتوب
```json
{
  "name": "لابتوب ديل إنسبايرون",
  "sku": "LAPTOP-DELL-001",
  "regular_price": 4500.00,
  "stock_quantity": 20,
  "weight": 2.5
}
```

#### مثال 3: ساعة ذكية
```json
{
  "name": "ساعة أبل الذكية Series 8",
  "sku": "WATCH-APPLE-001",
  "regular_price": 1999.00,
  "sale_price": 1699.00,
  "stock_quantity": 75,
  "weight": 0.032
}
```

### 2. منتجات متغيرة

#### مثال 1: قميص
```json
{
  "name": "قميص رجالي كلاسيكي",
  "sku": "SHIRT-CLASS-001",
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
      "attributes": {"Color": "أبيض", "Size": "M"},
      "regular_price": 199.00,
      "stock_quantity": 30,
      "sku": "SHIRT-CLASS-001-WHITE-M"
    }
  ]
}
```

#### مثال 2: حذاء رياضي
```json
{
  "name": "حذاء رياضي نايكي",
  "sku": "SHOE-NIKE-001",
  "attributes": [
    {
      "name": "Size",
      "options": ["39", "40", "41", "42", "43", "44"]
    },
    {
      "name": "Color",
      "options": ["أسود", "أبيض", "رمادي"]
    }
  ]
}
```

---

## 🔄 سير العمل الموصى به

### 1. البدء بالاختبارات

```
الخطوة 1: اختبار المفاتيح
└─> 🔐 Authentication Test > اختبار المفاتيح - Valid
    
    ✅ نجح؟ انتقل للخطوة 2
    ❌ فشل؟ راجع إعداد Authentication
```

### 2. إضافة منتج فيزيائي

```
الخطوة 2: إضافة منتج
└─> 🔵 Physical Products > إضافة منتج فيزيائي جديد
    
    ✅ تم الحفظ في: physical_product_id
```

### 3. عرض المنتج المُضاف

```
الخطوة 3: عرض المنتج
└─> 🔵 Physical Products > عرض منتج فيزيائي واحد
    
    (يستخدم تلقائياً: {{physical_product_id}})
```

### 4. تعديل المنتج

```
الخطوة 4: تعديل
└─> 🔵 Physical Products > تعديل منتج فيزيائي
    
    عدّل البيانات في Body ثم أرسل
```

### 5. البحث والإحصائيات

```
الخطوة 5: استكشف
├─> البحث عن منتجات فيزيائية
├─> عرض جميع المنتجات الفيزيائية
└─> إحصائيات المنتجات الفيزيائية
```

### 6. كرر مع المنتجات المتغيرة

```
الخطوة 6: منتجات متغيرة
└─> 🟢 Variable Products
    
    نفس الخطوات السابقة
```

---

## ❗ حل المشاكل

### المشكلة 1: خطأ 401 Unauthorized

**الأعراض:**
```json
{
  "code": "woo_api_auth_missing",
  "message": "Authentication credentials are missing."
}
```

**الحل:**
1. راجع إعداد Authorization في Collection
2. تأكد من إدخال Consumer Key و Secret بشكل صحيح
3. تأكد من اختيار Type: Basic Auth

---

### المشكلة 2: المتغيرات لا تعمل

**الأعراض:**
- URL يظهر بـ `{{base_url}}` بدون استبدالها

**الحل:**
1. تأكد من استيراد Collection كاملة
2. راجع Variables في Collection Settings
3. تأكد من وجود قيمة `base_url`

---

### المشكلة 3: لا يمكن إرسال الطلب

**الأعراض:**
- زر Send غير نشط
- رسالة خطأ في Postman

**الحل:**
1. تحقق من اتصال الإنترنت
2. تأكد من أن URL صحيح
3. جرّب نسخ URL ولصقه في المتصفح
4. تحقق من إعدادات Proxy في Postman

---

### المشكلة 4: 404 Not Found

**الأعراض:**
```json
{
  "code": "rest_no_route",
  "message": "No route was found matching the URL and request method"
}
```

**الحل:**
1. تأكد من تفعيل الإضافة في WordPress
2. اذهب إلى: الإعدادات > الروابط الدائمة
3. اضغط "حفظ التغييرات" (بدون تعديل)
4. جرّب الطلب مرة أخرى

---

## 💡 نصائح احترافية

### 1. استخدم Environments
- أنشئ بيئات مختلفة (Development, Staging, Production)
- كل بيئة بمفاتيح API مختلفة

### 2. احفظ الاستجابات
- اضغط "Save Response" لحفظ استجابة مهمة
- مفيد للمراجعة والمقارنة

### 3. استخدم Console
- اضغط `View > Show Postman Console`
- شاهد تفاصيل الطلبات والاستجابات

### 4. أنشئ Examples
- بعد نجاح طلب، اضغط "Save as Example"
- مفيد لتوثيق الاستجابات المتوقعة

### 5. شارك مع الفريق
- Export Collection
- شارك ملف JSON مع الفريق
- أو استخدم Postman Team Workspace

---

## 📚 موارد إضافية

### تعلم المزيد عن Postman:
- 📖 [Postman Learning Center](https://learning.postman.com/)
- 🎥 [Postman YouTube Channel](https://www.youtube.com/c/Postman)
- 📝 [Postman Blog](https://blog.postman.com/)

### روابط مفيدة:
- 🔗 [API Documentation](API-DOCUMENTATION-AR.md)
- 🔗 [README](README-AR.md)
- 🔗 [WooCommerce REST API Docs](https://woocommerce.github.io/woocommerce-rest-api-docs/)

---

## ✅ قائمة مراجعة سريعة

قبل البدء، تأكد من:

- [ ] تثبيت Postman
- [ ] استيراد Collection
- [ ] إعداد Consumer Key في Authorization
- [ ] إعداد Consumer Secret في Authorization
- [ ] مراجعة base_url في Variables
- [ ] تفعيل الإضافة في WordPress
- [ ] اختبار Authentication

**جاهز للبدء؟ اذهب إلى:**
```
🔐 Authentication Test > اختبار المفاتيح - Valid
```

---

**استمتع باختبار API! 🚀**

للدعم والمساعدة، راجع [README-AR.md](README-AR.md)

