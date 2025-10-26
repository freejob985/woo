# 📚 الدليل الشامل - Murjan WooCommerce Products API

## 🎯 نظرة عامة

تم إنشاء نظام REST API متكامل لإدارة المنتجات الفيزيائية والمتغيرة في WooCommerce مع:

✅ **دعم كامل لرفع الصور** عبر Form Data  
✅ **جميع حقول المنتج** مع شرح تفصيلي  
✅ **البحث والصفحات** (Pagination)  
✅ **حماية كاملة** بمفاتيح WooCommerce API  
✅ **Postman Collection جاهز** مع أمثلة عملية  

---

## 📦 محتويات المشروع

```
api/
├── woo-products-api.php                    # الملف الرئيسي
├── includes/
│   ├── class-authentication.php            # نظام التوثيق
│   ├── class-physical-products-api.php     # API المنتجات الفيزيائية
│   ├── class-variable-products-api.php     # API المنتجات المتغيرة
│   └── index.php                           # حماية المجلد
├── postman/
│   ├── Murjan-WooCommerce-API-Complete.postman_collection.json
│   └── index.php
├── COMPLETE-GUIDE-AR.md                    # هذا الملف
└── index.php
```

---

## 🚀 التثبيت السريع

### 1. رفع الملفات

```bash
# ارفع مجلد api إلى:
wp-content/plugins/woo-products-importer/api/
```

### 2. تفعيل الإضافة

1. اذهب إلى: **الإضافات** > **الإضافات المثبتة**
2. ابحث عن: **WooCommerce Products API Manager**
3. اضغط: **تفعيل**

### 3. إنشاء مفاتيح API

1. **WooCommerce** > **الإعدادات** > **متقدم** > **REST API**
2. اضغط: **Add key**
3. املأ:
   - **Description**: Murjan API
   - **User**: اختر Administrator
   - **Permissions**: **Read/Write** ✅
4. احفظ المفاتيح:
   ```
   Consumer Key: ck_2210fb8d333da5da151029715a85618a4b283a52
   Consumer Secret: cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
   ```

---

## 🌐 الرابط الأساسي

```
https://dev.murjan.sa/wp-json/murjan-api/v1
```

---

## 🔵 المنتجات الفيزيائية (Physical Products)

### جميع الـ Endpoints

| Method | Endpoint | الوصف |
|--------|----------|-------|
| POST | `/physical-products` | إضافة منتج |
| POST | `/physical-products/{id}` | تعديل منتج |
| GET | `/physical-products` | عرض الكل مع pagination |
| GET | `/physical-products/{id}` | عرض منتج واحد |
| GET | `/physical-products/search` | بحث مع pagination |
| DELETE | `/physical-products/{id}` | حذف منتج |
| GET | `/physical-products/stats` | إحصائيات |

---

### 1️⃣ إضافة منتج فيزيائي

#### Method: `POST`
#### Endpoint: `/physical-products`
#### Content-Type: `multipart/form-data` ⚠️ مهم

#### الحقول المتاحة:

##### 📝 معلومات أساسية

| الحقل | النوع | مطلوب | الوصف | مثال |
|------|------|-------|-------|------|
| `name` | text | ✅ | اسم المنتج | "هاتف سامسونج S23 Ultra" |
| `description` | text | ❌ | الوصف الكامل (يدعم HTML) | "&lt;p&gt;وصف كامل&lt;/p&gt;" |
| `short_description` | text | ❌ | وصف مختصر | "هاتف بمواصفات عالية" |
| `sku` | text | ❌ | رقم تعريفي فريد | "PHONE-SAM-001" |

##### 💰 الأسعار

| الحقل | النوع | الوصف | مثال |
|------|------|-------|------|
| `regular_price` | number | السعر العادي | "4999.00" |
| `sale_price` | number | سعر التخفيض | "4299.00" |
| `date_on_sale_from` | date | تاريخ بداية التخفيض | "2024-01-01" |
| `date_on_sale_to` | date | تاريخ نهاية التخفيض | "2024-12-31" |

##### 📦 المخزون

| الحقل | النوع | القيم | الوصف |
|------|------|-------|-------|
| `manage_stock` | boolean | true/false | تفعيل إدارة المخزون |
| `stock_quantity` | number | 0-999999 | كمية المخزون |
| `stock_status` | text | instock, outofstock, onbackorder | حالة المخزون |
| `backorders` | text | no, notify, yes | السماح بالطلب المسبق |
| `sold_individually` | boolean | true/false | بيع قطعة واحدة فقط |
| `low_stock_amount` | number | 5 | تنبيه عند انخفاض المخزون |

##### 📏 الشحن

| الحقل | النوع | الوصف | مثال |
|------|------|-------|------|
| `weight` | number | الوزن (كجم) | "0.228" |
| `length` | number | الطول (سم) | "16.3" |
| `width` | number | العرض (سم) | "7.8" |
| `height` | number | الارتفاع (سم) | "0.89" |
| `shipping_class` | text | فئة الشحن | "heavy" |

##### 💵 الضرائب

| الحقل | القيم | الوصف |
|------|-------|-------|
| `tax_status` | taxable, shipping, none | حالة الضريبة |
| `tax_class` | standard, reduced-rate, zero-rate | فئة الضريبة |

##### 🗂️ التصنيف

| الحقل | النوع | الوصف | مثال |
|------|------|-------|------|
| `categories` | text | تصنيفات مفصولة بفاصلة | "هواتف,إلكترونيات,سامسونج" |
| `tags` | text | وسوم مفصولة بفاصلة | "5G,كاميرا,ذكي" |

##### 🖼️ الصور

| الحقل | النوع | الوصف |
|------|------|-------|
| `main_image` | file | الصورة الرئيسية (JPG/PNG) |
| `gallery_images[]` | file[] | صور المعرض (متعددة) |

##### ⚙️ إعدادات إضافية

| الحقل | القيم | الوصف |
|------|-------|-------|
| `status` | publish, draft, pending | حالة النشر |
| `featured` | true/false | منتج مميز |
| `catalog_visibility` | visible, catalog, search, hidden | الظهور |
| `reviews_allowed` | true/false | السماح بالمراجعات |
| `purchase_note` | text | ملاحظة تظهر بعد الشراء |
| `external_url` | url | رابط خارجي للمنتج |
| `button_text` | text | نص زر الشراء | "اشتر الآن" |

#### مثال كامل في Postman:

```
POST https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products

Headers:
Authorization: Basic [base64(ck_xxx:cs_xxx)]

Body (Form Data):
name = هاتف ذكي سامسونج جالاكسي S23 Ultra
description = <h3>مواصفات فريدة</h3><p>هاتف ذكي بشاشة...</p>
short_description = أحدث هواتف سامسونج الرائدة
sku = SAMSUNG-S23U-512-BLACK
regular_price = 4999.00
sale_price = 4299.00
stock_quantity = 25
stock_status = instock
manage_stock = true
weight = 0.228
length = 16.3
width = 7.8
height = 0.89
categories = الهواتف الذكية,إلكترونيات
tags = 5G,كاميرا عالية,شاشة كبيرة
status = publish
featured = true
catalog_visibility = visible
tax_status = taxable
reviews_allowed = true
purchase_note = شكراً لشرائك! ستصلك رسالة تأكيد قريباً
main_image = [اختر ملف صورة]
gallery_images[] = [اختر ملفات متعددة]
```

#### الاستجابة:

```json
{
  "success": true,
  "message": "Product created successfully.",
  "product_id": 123,
  "product": {
    "id": 123,
    "name": "هاتف ذكي سامسونج جالاكسي S23 Ultra",
    "sku": "SAMSUNG-S23U-512-BLACK",
    "price": "4299.00",
    "regular_price": "4999.00",
    "sale_price": "4299.00",
    "stock_status": "instock",
    "stock_quantity": 25,
    "weight": "0.228",
    "dimensions": {
      "length": "16.3",
      "width": "7.8",
      "height": "0.89"
    },
    "categories": [
      {
        "id": 15,
        "name": "الهواتف الذكية",
        "slug": "smartphones"
      }
    ],
    "tags": [
      {
        "id": 25,
        "name": "5G",
        "slug": "5g"
      }
    ],
    "images": {
      "main_image": {
        "id": 456,
        "src": "https://dev.murjan.sa/wp-content/uploads/2024/01/phone.jpg",
        "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/01/phone-150x150.jpg"
      },
      "gallery": [
        {
          "id": 457,
          "src": "https://dev.murjan.sa/wp-content/uploads/2024/01/phone-2.jpg"
        }
      ]
    },
    "permalink": "https://dev.murjan.sa/product/samsung-s23-ultra/"
  }
}
```

---

### 2️⃣ تعديل منتج فيزيائي

#### Method: `POST`
#### Endpoint: `/physical-products/{id}`

**ملاحظة:** أرسل فقط الحقول التي تريد تعديلها

#### مثال:

```
POST /physical-products/123

Body (Form Data):
name = هاتف سامسونج S23 Ultra - محدث
regular_price = 4599.00
stock_quantity = 50
main_image = [صورة جديدة]
```

---

### 3️⃣ عرض جميع المنتجات - مع Pagination

#### Method: `GET`
#### Endpoint: `/physical-products`

#### Parameters:

| Parameter | الافتراضي | الوصف | مثال |
|-----------|-----------|-------|------|
| `page` | 1 | رقم الصفحة | `page=2` |
| `per_page` | 10 | عدد المنتجات (أقصى: 100) | `per_page=20` |
| `orderby` | date | الترتيب حسب: date, title, price, popularity, rating | `orderby=price` |
| `order` | DESC | الاتجاه: ASC, DESC | `order=ASC` |

#### أمثلة:

```bash
# الصفحة الأولى (10 منتجات):
GET /physical-products?page=1&per_page=10

# الصفحة الثانية (20 منتج):
GET /physical-products?page=2&per_page=20

# الأرخص أولاً:
GET /physical-products?orderby=price&order=ASC

# الأحدث أولاً:
GET /physical-products?orderby=date&order=DESC

# الأكثر مبيعاً:
GET /physical-products?orderby=popularity&order=DESC
```

#### الاستجابة:

```json
{
  "success": true,
  "total": 45,
  "total_products": 45,
  "total_pages": 5,
  "current_page": 1,
  "per_page": 10,
  "products": [
    {
      "id": 123,
      "name": "منتج 1",
      ...
    }
  ]
}
```

---

### 4️⃣ عرض منتج واحد

#### Method: `GET`
#### Endpoint: `/physical-products/{id}`

```bash
GET /physical-products/123
```

---

### 5️⃣ البحث في المنتجات - مع Pagination

#### Method: `GET`
#### Endpoint: `/physical-products/search`

#### Parameters:

| Parameter | مطلوب | الوصف |
|-----------|-------|-------|
| `s` | ✅ | كلمة البحث |
| `page` | ❌ | رقم الصفحة (افتراضي: 1) |
| `per_page` | ❌ | عدد النتائج (افتراضي: 10) |

#### أمثلة:

```bash
# بحث بسيط:
GET /physical-products/search?s=هاتف

# بحث مع pagination:
GET /physical-products/search?s=هاتف&page=2&per_page=20

# بحث بـ SKU:
GET /physical-products/search?s=PHONE-001
```

#### يبحث في:
- ✅ اسم المنتج
- ✅ الوصف الكامل
- ✅ الوصف المختصر
- ✅ رقم SKU

---

### 6️⃣ حذف منتج

#### Method: `DELETE`
#### Endpoint: `/physical-products/{id}`

```bash
DELETE /physical-products/123
```

⚠️ **تحذير:** حذف نهائي لا يمكن التراجع عنه

---

### 7️⃣ الإحصائيات

#### Method: `GET`
#### Endpoint: `/physical-products/stats`

```bash
GET /physical-products/stats
```

#### الاستجابة:

```json
{
  "success": true,
  "statistics": {
    "total_products": 150,
    "in_stock": 120,
    "out_of_stock": 25,
    "on_backorder": 5,
    "total_value": "SAR 450,000",
    "total_value_raw": 450000.00,
    "total_stock_quantity": 5000,
    "total_sales": 1250,
    "average_price": "SAR 3,000",
    "average_price_raw": 3000.00
  }
}
```

---

## 🟢 المنتجات المتغيرة (Variable Products)

### جميع الـ Endpoints

| Method | Endpoint | الوصف |
|--------|----------|-------|
| POST | `/variable-products` | إضافة منتج متغير |
| POST | `/variable-products/{id}` | تعديل منتج |
| GET | `/variable-products` | عرض الكل مع pagination |
| GET | `/variable-products/{id}` | عرض منتج واحد |
| GET | `/variable-products/search` | بحث مع pagination |
| DELETE | `/variable-products/{id}` | حذف منتج |
| GET | `/variable-products/stats` | إحصائيات |

---

### 1️⃣ إضافة منتج متغير

#### Method: `POST`
#### Endpoint: `/variable-products`
#### Content-Type: `multipart/form-data`

#### المكونات الأساسية:

##### 1. معلومات المنتج الأساسية

| الحقل | مثال |
|------|------|
| name | "قميص رجالي كلاسيكي" |
| description | "&lt;p&gt;قميص قطني فاخر&lt;/p&gt;" |
| short_description | "قميص بألوان متعددة" |
| sku | "SHIRT-CLASSIC-001" |
| status | "publish" |
| featured | "true" |

##### 2. الخصائص (Attributes) - JSON

تحديد أنواع الخيارات المتاحة:

```json
[
  {
    "name": "Color",
    "options": ["أبيض", "أزرق", "أسود", "رمادي"]
  },
  {
    "name": "Size",
    "options": ["S", "M", "L", "XL", "XXL"]
  }
]
```

##### 3. التنويعات (Variations) - JSON

كل تركيبة من الخصائص:

```json
[
  {
    "attributes": {"Color": "أبيض", "Size": "M"},
    "regular_price": 199.00,
    "sale_price": 159.00,
    "stock_quantity": 30,
    "stock_status": "instock",
    "sku": "SHIRT-CLASSIC-001-WHITE-M"
  },
  {
    "attributes": {"Color": "أبيض", "Size": "L"},
    "regular_price": 199.00,
    "sale_price": 159.00,
    "stock_quantity": 25,
    "stock_status": "instock",
    "sku": "SHIRT-CLASSIC-001-WHITE-L"
  }
]
```

##### حقول التنويعة:

| الحقل | مطلوب | الوصف |
|------|-------|-------|
| attributes | ✅ | {اسم_الخاصية: القيمة} |
| regular_price | ❌ | السعر العادي |
| sale_price | ❌ | سعر التخفيض |
| stock_quantity | ❌ | الكمية |
| stock_status | ❌ | instock/outofstock |
| sku | ❌ | SKU فريد (موصى به) |

##### 4. الصور

| الحقل | الوصف |
|------|-------|
| main_image | الصورة الرئيسية |
| gallery_images[] | صور المعرض (متعددة) |
| variation_image_0 | صورة التنويعة الأولى |
| variation_image_1 | صورة التنويعة الثانية |
| ... | وهكذا لكل تنويعة |

#### مثال كامل:

```
POST /variable-products

Body (Form Data):
name = قميص رجالي كلاسيكي
description = <p>قميص قطني فاخر...</p>
short_description = قميص بألوان ومقاسات متعددة
sku = SHIRT-CLASSIC-001
status = publish
featured = true

attributes = [
  {"name": "Color", "options": ["أبيض", "أزرق", "أسود"]},
  {"name": "Size", "options": ["S", "M", "L", "XL"]}
]

variations = [
  {
    "attributes": {"Color": "أبيض", "Size": "M"},
    "regular_price": 199.00,
    "sale_price": 159.00,
    "stock_quantity": 30,
    "stock_status": "instock",
    "sku": "SHIRT-001-WHITE-M"
  },
  {
    "attributes": {"Color": "أزرق", "Size": "L"},
    "regular_price": 199.00,
    "stock_quantity": 20,
    "stock_status": "instock",
    "sku": "SHIRT-001-BLUE-L"
  }
]

main_image = [ملف صورة]
gallery_images[] = [ملفات متعددة]
variation_image_0 = [صورة للتنويعة الأولى]
variation_image_1 = [صورة للتنويعة الثانية]
```

---

### 2️⃣ باقي Endpoints المنتجات المتغيرة

جميع endpoints الأخرى (تعديل، عرض، بحث، حذف، إحصائيات) تعمل بنفس طريقة المنتجات الفيزيائية.

---

## 📮 استخدام Postman

### 1. استيراد Collection

1. افتح Postman
2. اضغط **Import**
3. اختر الملف:
   ```
   api/postman/Murjan-WooCommerce-API-Complete.postman_collection.json
   ```

### 2. إعداد Authentication

Collection مُعد مسبقاً بالمفاتيح:
- Consumer Key: `ck_2210fb8d333da5da151029715a85618a4b283a52`
- Consumer Secret: `cs_7f1073e18d0af70d01c84692ce8c04609a722b5c`

### 3. البدء بالاختبار

#### اختبار التوثيق أولاً:
```
🔐 Authentication Tests > اختبار المفاتيح - صحيحة
```

#### إضافة منتج فيزيائي:
```
🔵 Physical Products > إضافة منتج فيزيائي - مع صور
```

#### إضافة منتج متغير:
```
🟢 Variable Products > إضافة منتج متغير - مع صور
```

---

## 🔒 الأمان والحماية

### مستويات الحماية:

1. ✅ **HTTPS** - تشفير البيانات
2. ✅ **Authentication** - التحقق من المفاتيح
3. ✅ **Authorization** - التحقق من الصلاحيات
4. ✅ **Input Sanitization** - تنظيف المدخلات
5. ✅ **SQL Injection Protection** - Prepared Statements
6. ✅ **XSS Protection** - تنظيف المخرجات

### أفضل الممارسات:

```
✅ استخدم HTTPS دائماً
✅ لا تشارك المفاتيح في أماكن عامة
✅ قم بتدوير المفاتيح بشكل دوري
✅ استخدم مفاتيح منفصلة لكل تطبيق
✅ راقب استخدام API بانتظام
```

---

## 🐛 حل المشاكل

### المشكلة: 401 Unauthorized

**الحل:**
```
1. تحقق من Consumer Key و Secret
2. تأكد من نسخها بالكامل
3. جرّب إنشاء مفاتيح جديدة
```

### المشكلة: 403 Forbidden

**الحل:**
```
1. تأكد من اختيار Read/Write عند إنشاء المفاتيح
2. تحقق من أن المستخدم Administrator
```

### المشكلة: 404 Not Found

**الحل:**
```
1. اذهب إلى: الإعدادات > الروابط الدائمة
2. اضغط: حفظ التغييرات
3. جرّب مرة أخرى
```

### المشكلة: لا يمكن رفع الصور

**الحل:**
```
1. تأكد من استخدام multipart/form-data
2. تحقق من صلاحيات مجلد wp-content/uploads
3. تأكد من حجم الملف (أقل من upload_max_filesize)
```

---

## 📊 أمثلة عملية

### مثال 1: إضافة 10 منتجات عبر Script

```javascript
const API_URL = 'https://dev.murjan.sa/wp-json/murjan-api/v1';
const AUTH = {
  username: 'ck_2210fb8d333da5da151029715a85618a4b283a52',
  password: 'cs_7f1073e18d0af70d01c84692ce8c04609a722b5c'
};

async function addProduct(name, price) {
  const formData = new FormData();
  formData.append('name', name);
  formData.append('regular_price', price);
  formData.append('stock_quantity', 50);
  formData.append('status', 'publish');
  
  const response = await fetch(`${API_URL}/physical-products`, {
    method: 'POST',
    headers: {
      'Authorization': 'Basic ' + btoa(`${AUTH.username}:${AUTH.password}`)
    },
    body: formData
  });
  
  return response.json();
}

// إضافة 10 منتجات
for (let i = 1; i <= 10; i++) {
  const result = await addProduct(`منتج رقم ${i}`, 99 + i);
  console.log(`تم إضافة منتج ${i}: ID ${result.product_id}`);
}
```

### مثال 2: البحث وتحديث الأسعار

```javascript
async function updatePrices(searchTerm, newPrice) {
  // البحث عن المنتجات
  const searchResponse = await fetch(
    `${API_URL}/physical-products/search?s=${searchTerm}`,
    {
      headers: {
        'Authorization': 'Basic ' + btoa(`${AUTH.username}:${AUTH.password}`)
      }
    }
  );
  
  const searchData = await searchResponse.json();
  
  // تحديث كل منتج
  for (const product of searchData.products) {
    const formData = new FormData();
    formData.append('regular_price', newPrice);
    
    await fetch(`${API_URL}/physical-products/${product.id}`, {
      method: 'POST',
      headers: {
        'Authorization': 'Basic ' + btoa(`${AUTH.username}:${AUTH.password}`)
      },
      body: formData
    });
    
    console.log(`تم تحديث سعر: ${product.name}`);
  }
}

// تحديث أسعار جميع الهواتف
updatePrices('هاتف', 2999.00);
```

---

## ✅ قائمة التحقق

### قبل البدء:

- [ ] WordPress 5.8+ مثبت
- [ ] WooCommerce 5.0+ مفعّل
- [ ] الإضافة مفعّلة
- [ ] مفاتيح API تم إنشاؤها
- [ ] Postman مثبت (اختياري)

### الاختبار:

- [ ] اختبار التوثيق نجح
- [ ] إضافة منتج فيزيائي نجح
- [ ] رفع صورة نجح
- [ ] إضافة منتج متغير نجح
- [ ] البحث يعمل
- [ ] Pagination يعمل

---

## 📞 الدعم

### معلومات الاتصال:

- 🌐 **الموقع**: https://dev.murjan.sa
- 📧 **Email**: support@murjan.sa
- 📚 **التوثيق**: راجع هذا الملف

### الموارد:

- 📖 **API Documentation**: راجع Postman Collection
- 🔒 **Security**: تحقق من SECURITY-AR.md
- 🚀 **Quick Start**: راجع QUICK-START-AR.md

---

## 🎯 الخلاصة

تم إنشاء نظام API متكامل يوفر:

✅ **14 Endpoint** جاهز  
✅ **دعم كامل للصور** عبر Form Data  
✅ **جميع حقول المنتج** مع شرح  
✅ **Pagination والبحث** في كل مكان  
✅ **Postman Collection** جاهز  
✅ **حماية كاملة** 100%  
✅ **توثيق شامل** بالعربية  

**استمتع بالاستخدام! 🚀**

---

**آخر تحديث:** يناير 2024  
**الإصدار:** 1.0.0  
**الحالة:** ✅ جاهز للإنتاج

