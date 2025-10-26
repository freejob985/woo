# 🚀 Murjan WooCommerce Products API Manager

**نظام REST API متكامل لإدارة المنتجات الفيزيائية والمتغيرة في WooCommerce**

---

## ✨ المميزات الرئيسية

✅ **دعم كامل لرفع الصور** - عبر `multipart/form-data`  
✅ **جميع حقول المنتج** - أكثر من 30 حقل مع شرح تفصيلي  
✅ **Pagination والبحث** - في جميع قوائم المنتجات  
✅ **حماية كاملة 100%** - مفاتيح WooCommerce API  
✅ **Postman Collection جاهز** - مع أمثلة عملية كاملة  
✅ **توثيق شامل بالعربية** - أكثر من 1000 سطر توثيق  

---

## 📦 ما تم إنجازه

### 1. الإضافة (WordPress Plugin)

```
✅ woo-products-api.php - الملف الرئيسي
✅ class-authentication.php - نظام التوثيق والحماية
✅ class-physical-products-api.php - API المنتجات الفيزيائية (700+ سطر)
✅ class-variable-products-api.php - API المنتجات المتغيرة (550+ سطر)
```

### 2. الـ Endpoints (14 endpoint)

#### 🔵 المنتجات الفيزيائية (7 endpoints):
```
POST   /physical-products           - إضافة منتج (مع صور)
POST   /physical-products/{id}      - تعديل منتج (مع صور)
GET    /physical-products           - عرض الكل (pagination)
GET    /physical-products/{id}      - عرض منتج واحد
GET    /physical-products/search    - بحث (pagination)
DELETE /physical-products/{id}      - حذف منتج
GET    /physical-products/stats     - إحصائيات
```

#### 🟢 المنتجات المتغيرة (7 endpoints):
```
POST   /variable-products           - إضافة منتج متغير (مع صور وتنويعات)
POST   /variable-products/{id}      - تعديل منتج (مع صور)
GET    /variable-products           - عرض الكل (pagination)
GET    /variable-products/{id}      - عرض منتج واحد (مع جميع التنويعات)
GET    /variable-products/search    - بحث (pagination)
DELETE /variable-products/{id}      - حذف منتج (مع جميع التنويعات)
GET    /variable-products/stats     - إحصائيات تفصيلية
```

### 3. Postman Collection

```
✅ 16 طلب جاهز للاختبار
✅ شرح تفصيلي لكل حقل
✅ أمثلة كاملة بتعبئة الحقول
✅ دعم رفع الصور
✅ اختبارات تلقائية
✅ متغيرات ذكية
```

### 4. التوثيق

```
✅ COMPLETE-GUIDE-AR.md - دليل شامل (1000+ سطر)
✅ README.md - هذا الملف
✅ شرح مفصل في Postman لكل endpoint
```

---

## ⚡ البدء السريع (5 دقائق)

### 1. التثبيت

```bash
# ارفع مجلد api إلى:
wp-content/plugins/woo-products-importer/api/

# فعّل الإضافة من لوحة التحكم
WordPress > الإضافات > WooCommerce Products API Manager > تفعيل
```

### 2. المفاتيح

```
WooCommerce > Settings > Advanced > REST API > Add key

Consumer Key: ck_2210fb8d333da5da151029715a85618a4b283a52
Consumer Secret: cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
```

### 3. الاختبار

```bash
# استيراد Postman Collection
postman/Murjan-WooCommerce-API-Complete.postman_collection.json

# أو اختبار سريع بـ cURL:
curl -X GET \
  "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/stats" \
  -u "ck_xxx:cs_xxx"
```

---

## 📚 الحقول المدعومة

### المنتجات الفيزيائية (30+ حقل):

#### معلومات أساسية:
- `name`, `description`, `short_description`, `sku`

#### أسعار:
- `regular_price`, `sale_price`, `date_on_sale_from`, `date_on_sale_to`

#### مخزون:
- `manage_stock`, `stock_quantity`, `stock_status`, `backorders`, `sold_individually`, `low_stock_amount`

#### شحن:
- `weight`, `length`, `width`, `height`, `shipping_class`

#### ضرائب:
- `tax_status`, `tax_class`

#### تصنيف:
- `categories`, `tags`

#### صور:
- `main_image` (file)
- `gallery_images[]` (files متعددة)

#### إعدادات:
- `status`, `featured`, `catalog_visibility`, `reviews_allowed`, `purchase_note`, `external_url`

### المنتجات المتغيرة:

#### معلومات أساسية:
- `name`, `description`, `short_description`, `sku`, `status`, `featured`

#### خصائص (JSON):
```json
{
  "attributes": [
    {
      "name": "Color",
      "options": ["أبيض", "أزرق", "أسود"]
    }
  ]
}
```

#### تنويعات (JSON):
```json
{
  "variations": [
    {
      "attributes": {"Color": "أبيض", "Size": "M"},
      "regular_price": 199.00,
      "sale_price": 159.00,
      "stock_quantity": 30,
      "sku": "SHIRT-001-WHITE-M"
    }
  ]
}
```

#### صور:
- `main_image` (file)
- `gallery_images[]` (files)
- `variation_image_0`, `variation_image_1`, ... (لكل تنويعة)

---

## 🔍 مثال عملي: إضافة منتج فيزيائي كامل

### في Postman:

```
POST https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products

Authorization: Basic [base64(ck_xxx:cs_xxx)]

Body (form-data):
┌─────────────────────┬──────────────────────────────────────┐
│ Key                 │ Value                                │
├─────────────────────┼──────────────────────────────────────┤
│ name                │ هاتف سامسونج S23 Ultra              │
│ description         │ <p>هاتف ذكي بمواصفات عالية</p>      │
│ short_description   │ أحدث هواتف سامسونج                  │
│ sku                 │ SAMSUNG-S23U-512                     │
│ regular_price       │ 4999.00                              │
│ sale_price          │ 4299.00                              │
│ stock_quantity      │ 25                                   │
│ stock_status        │ instock                              │
│ weight              │ 0.228                                │
│ length              │ 16.3                                 │
│ width               │ 7.8                                  │
│ height              │ 0.89                                 │
│ categories          │ هواتف,إلكترونيات                    │
│ tags                │ 5G,ذكي,سامسونج                      │
│ status              │ publish                              │
│ featured            │ true                                 │
│ main_image          │ [اختر ملف صورة]                     │
│ gallery_images[]    │ [اختر عدة صور]                      │
└─────────────────────┴──────────────────────────────────────┘
```

### الاستجابة:

```json
{
  "success": true,
  "message": "Product created successfully.",
  "product_id": 123,
  "product": {
    "id": 123,
    "name": "هاتف سامسونج S23 Ultra",
    "price": "4299.00",
    "images": {
      "main_image": {
        "src": "https://dev.murjan.sa/.../image.jpg"
      }
    },
    ...
  }
}
```

---

## 🎯 مثال: إضافة منتج متغير

```
POST /variable-products

Body (form-data):
┌──────────────┬─────────────────────────────────────────────┐
│ Key          │ Value                                       │
├──────────────┼─────────────────────────────────────────────┤
│ name         │ قميص رجالي كلاسيكي                         │
│ description  │ <p>قميص قطني فاخر</p>                      │
│ sku          │ SHIRT-001                                   │
│ attributes   │ [                                           │
│              │   {"name":"Color",                          │
│              │    "options":["أبيض","أزرق","أسود"]},       │
│              │   {"name":"Size",                           │
│              │    "options":["S","M","L","XL"]}            │
│              │ ]                                           │
│ variations   │ [                                           │
│              │   {"attributes":{"Color":"أبيض","Size":"M"},│
│              │    "regular_price":199.00,                  │
│              │    "stock_quantity":30,                     │
│              │    "sku":"SHIRT-001-WHITE-M"},              │
│              │   {"attributes":{"Color":"أزرق","Size":"L"},│
│              │    "regular_price":199.00,                  │
│              │    "stock_quantity":20,                     │
│              │    "sku":"SHIRT-001-BLUE-L"}                │
│              │ ]                                           │
│ main_image   │ [ملف صورة]                                 │
└──────────────┴─────────────────────────────────────────────┘
```

---

## 🔄 Pagination والبحث

### عرض منتجات مع pagination:

```bash
# الصفحة الأولى (10 منتجات):
GET /physical-products?page=1&per_page=10

# الصفحة الثانية (20 منتج):
GET /physical-products?page=2&per_page=20

# ترتيب حسب السعر (الأرخص أولاً):
GET /physical-products?orderby=price&order=ASC

# الأكثر مبيعاً:
GET /physical-products?orderby=popularity&order=DESC
```

### البحث مع pagination:

```bash
# بحث بسيط:
GET /physical-products/search?s=هاتف

# بحث مع pagination:
GET /physical-products/search?s=هاتف&page=2&per_page=20
```

---

## 📊 الإحصائيات

```bash
# إحصائيات المنتجات الفيزيائية:
GET /physical-products/stats

# إحصائيات المنتجات المتغيرة:
GET /variable-products/stats
```

### الاستجابة:

```json
{
  "success": true,
  "statistics": {
    "total_products": 150,
    "in_stock": 120,
    "out_of_stock": 30,
    "total_value": "SAR 450,000",
    "total_stock_quantity": 5000,
    "average_price": "SAR 3,000"
  }
}
```

---

## 🔒 الأمان

### مستويات الحماية المطبقة:

```
✅ HTTPS - تشفير البيانات
✅ Authentication - التحقق من المفاتيح
✅ Authorization - التحقق من الصلاحيات
✅ Input Sanitization - تنظيف المدخلات
✅ SQL Injection Protection - Prepared Statements
✅ XSS Protection - تنظيف المخرجات
```

---

## 📁 هيكل الملفات

```
api/
├── woo-products-api.php                    # الملف الرئيسي
├── includes/
│   ├── class-authentication.php            # التوثيق
│   ├── class-physical-products-api.php     # API الفيزيائية
│   ├── class-variable-products-api.php     # API المتغيرة
│   └── index.php
├── postman/
│   ├── Murjan-WooCommerce-API-Complete.postman_collection.json
│   └── index.php
├── COMPLETE-GUIDE-AR.md                    # الدليل الشامل
├── README.md                               # هذا الملف
└── index.php
```

---

## 📖 التوثيق

### ملفات التوثيق:

1. **README.md** (هذا الملف) - ملخص سريع
2. **COMPLETE-GUIDE-AR.md** - دليل شامل 1000+ سطر
3. **Postman Collection** - أمثلة عملية مع شرح

---

## 🐛 حل المشاكل

### 401 Unauthorized
```
✅ تحقق من Consumer Key و Secret
✅ تأكد من نسخها بالكامل
```

### 403 Forbidden
```
✅ تأكد من صلاحيات Read/Write للمفاتيح
✅ المستخدم يجب أن يكون Administrator
```

### 404 Not Found
```
✅ الإعدادات > الروابط الدائمة > حفظ
```

### لا يمكن رفع الصور
```
✅ استخدم multipart/form-data
✅ تحقق من صلاحيات مجلد uploads
✅ تحقق من upload_max_filesize
```

---

## 🎉 الخلاصة

تم إنشاء نظام API متكامل يوفر:

- ✅ **14 Endpoint** جاهز ومختبر
- ✅ **دعم كامل للصور** عبر Form Data
- ✅ **30+ حقل** لكل منتج مع شرح
- ✅ **Pagination والبحث** في كل مكان
- ✅ **Postman Collection** جاهز للاستخدام
- ✅ **حماية 100%** بمعايير عالمية
- ✅ **توثيق شامل** بالعربية

---

## 📞 معلومات الاتصال

- 🌐 **الموقع**: https://dev.murjan.sa
- 🔑 **Consumer Key**: ck_2210fb8d333da5da151029715a85618a4b283a52
- 🔐 **Consumer Secret**: cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
- 📚 **التوثيق الكامل**: `COMPLETE-GUIDE-AR.md`

---

## ⭐ الإصدار

**Version:** 1.0.0  
**تاريخ الإنشاء:** يناير 2024  
**الحالة:** ✅ جاهز للإنتاج

---

**استمتع بالاستخدام! 🚀**

