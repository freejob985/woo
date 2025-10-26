# 📚 دليل API الكامل

دليل شامل لجميع endpoints المتاحة في WooCommerce Products API Manager

---

## 📋 جدول المحتويات

- [معلومات عامة](#معلومات-عامة)
- [التوثيق](#التوثيق)
- [المنتجات الفيزيائية](#المنتجات-الفيزيائية)
- [المنتجات المتغيرة](#المنتجات-المتغيرة)
- [رموز الاستجابة](#رموز-الاستجابة)
- [معالجة الأخطاء](#معالجة-الأخطاء)

---

## 🌐 معلومات عامة

### Base URL
```
https://dev.murjan.sa/wp-json/murjan-api/v1
```

### Content Type
جميع الطلبات والاستجابات بصيغة JSON:
```
Content-Type: application/json
```

### Rate Limiting
لا يوجد حد حالياً، لكن يُنصح بعدم إرسال أكثر من 100 طلب في الدقيقة.

---

## 🔐 التوثيق

### طريقة Basic Authentication

أضف header التالي لجميع الطلبات:

```
Authorization: Basic base64(consumer_key:consumer_secret)
```

### مثال في cURL:
```bash
curl -X GET \
  'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products' \
  -u 'ck_YOUR_KEY:cs_YOUR_SECRET'
```

### مثال في JavaScript:
```javascript
const credentials = btoa('ck_YOUR_KEY:cs_YOUR_SECRET');

fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products', {
  headers: {
    'Authorization': `Basic ${credentials}`,
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

### مثال في PHP:
```php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products');
curl_setopt($ch, CURLOPT_USERPWD, 'ck_YOUR_KEY:cs_YOUR_SECRET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
```

---

## 🔵 المنتجات الفيزيائية

### 1. إضافة منتج فيزيائي

**Endpoint:** `POST /physical-products`

**Request Body:**
```json
{
  "name": "اسم المنتج",
  "description": "الوصف الكامل للمنتج",
  "short_description": "وصف مختصر",
  "sku": "PROD-001",
  "regular_price": 299.00,
  "sale_price": 249.00,
  "stock_quantity": 100,
  "stock_status": "instock",
  "weight": 0.5,
  "length": 10,
  "width": 5,
  "height": 3
}
```

**الحقول:**

| الحقل | النوع | مطلوب | الوصف |
|------|------|-------|-------|
| name | string | ✅ | اسم المنتج |
| description | string | ❌ | الوصف الكامل (يدعم HTML) |
| short_description | string | ❌ | وصف مختصر |
| sku | string | ❌ | رمز SKU فريد |
| regular_price | float | ❌ | السعر العادي |
| sale_price | float | ❌ | سعر التخفيض |
| stock_quantity | integer | ❌ | كمية المخزون |
| stock_status | string | ❌ | حالة المخزون: `instock`, `outofstock` |
| weight | float | ❌ | الوزن (كجم) |
| length | float | ❌ | الطول (سم) |
| width | float | ❌ | العرض (سم) |
| height | float | ❌ | الارتفاع (سم) |

**Response (Success):**
```json
{
  "success": true,
  "message": "Product created successfully.",
  "product_id": 123,
  "product": {
    "id": 123,
    "name": "اسم المنتج",
    "slug": "product-name",
    "type": "simple",
    "status": "publish",
    "sku": "PROD-001",
    "price": "249.00",
    "regular_price": "299.00",
    "sale_price": "249.00",
    "stock_status": "instock",
    "stock_quantity": 100,
    "weight": "0.5",
    "dimensions": {
      "length": "10",
      "width": "5",
      "height": "3"
    }
  }
}
```

---

### 2. تعديل منتج فيزيائي

**Endpoint:** `PUT /physical-products/{id}`

**Parameters:**
- `{id}`: رقم المنتج (Product ID)

**Request Body:**
```json
{
  "name": "اسم محدث",
  "regular_price": 350.00,
  "stock_quantity": 150
}
```

**ملاحظة:** أرسل فقط الحقول التي تريد تعديلها.

**Response:**
```json
{
  "success": true,
  "message": "Product updated successfully.",
  "product": { /* بيانات المنتج المحدثة */ }
}
```

---

### 3. عرض جميع المنتجات الفيزيائية

**Endpoint:** `GET /physical-products`

**Query Parameters:**

| المعامل | الافتراضي | الوصف |
|---------|-----------|-------|
| page | 1 | رقم الصفحة |
| per_page | 10 | عدد المنتجات في الصفحة |

**مثال:**
```
GET /physical-products?page=2&per_page=20
```

**Response:**
```json
{
  "success": true,
  "total": 150,
  "total_pages": 8,
  "current_page": 2,
  "per_page": 20,
  "products": [
    {
      "id": 123,
      "name": "منتج 1",
      /* ... */
    },
    /* المزيد من المنتجات */
  ]
}
```

---

### 4. عرض منتج فيزيائي واحد

**Endpoint:** `GET /physical-products/{id}`

**Parameters:**
- `{id}`: رقم المنتج

**Response:**
```json
{
  "success": true,
  "product": {
    "id": 123,
    "name": "اسم المنتج",
    "slug": "product-name",
    "type": "simple",
    "status": "publish",
    "description": "الوصف الكامل",
    "short_description": "وصف مختصر",
    "sku": "PROD-001",
    "price": "249.00",
    "regular_price": "299.00",
    "sale_price": "249.00",
    "stock_status": "instock",
    "stock_quantity": 100,
    "manage_stock": true,
    "weight": "0.5",
    "dimensions": {
      "length": "10",
      "width": "5",
      "height": "3"
    },
    "image": "https://example.com/image.jpg",
    "permalink": "https://example.com/product/product-name"
  }
}
```

---

### 5. البحث عن منتجات فيزيائية

**Endpoint:** `GET /physical-products/search`

**Query Parameters:**

| المعامل | مطلوب | الوصف |
|---------|-------|-------|
| s | ✅ | كلمة البحث |

**مثال:**
```
GET /physical-products/search?s=هاتف
```

**Response:**
```json
{
  "success": true,
  "search_term": "هاتف",
  "total": 5,
  "products": [
    /* نتائج البحث */
  ]
}
```

---

### 6. حذف منتج فيزيائي

**Endpoint:** `DELETE /physical-products/{id}`

**Parameters:**
- `{id}`: رقم المنتج

**Response:**
```json
{
  "success": true,
  "message": "Product deleted successfully.",
  "deleted_product": {
    /* بيانات المنتج المحذوف */
  }
}
```

---

### 7. إحصائيات المنتجات الفيزيائية

**Endpoint:** `GET /physical-products/stats`

**Response:**
```json
{
  "success": true,
  "statistics": {
    "total_products": 150,
    "in_stock": 120,
    "out_of_stock": 30,
    "total_value": "SAR 45,000",
    "total_value_raw": 45000.00,
    "total_stock_quantity": 5000,
    "average_price": "SAR 300",
    "average_price_raw": 300.00
  }
}
```

---

## 🟢 المنتجات المتغيرة

### 1. إضافة منتج متغير

**Endpoint:** `POST /variable-products`

**Request Body:**
```json
{
  "name": "قميص رجالي",
  "description": "قميص قطني فاخر",
  "short_description": "قميص بألوان ومقاسات متعددة",
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
      "sale_price": 159.00,
      "stock_quantity": 30,
      "stock_status": "instock",
      "sku": "SHIRT-001-WHITE-M"
    },
    {
      "attributes": {
        "Color": "أزرق",
        "Size": "L"
      },
      "regular_price": 199.00,
      "stock_quantity": 20,
      "stock_status": "instock",
      "sku": "SHIRT-001-BLUE-L"
    }
  ]
}
```

**الحقول:**

| الحقل | النوع | مطلوب | الوصف |
|------|------|-------|-------|
| name | string | ✅ | اسم المنتج |
| description | string | ❌ | الوصف الكامل |
| short_description | string | ❌ | وصف مختصر |
| sku | string | ❌ | SKU المنتج الأساسي |
| attributes | array | ❌ | مصفوفة الخصائص |
| variations | array | ❌ | مصفوفة التنويعات |

**هيكل Attributes:**
```json
{
  "name": "اسم الخاصية",
  "options": ["خيار 1", "خيار 2", "خيار 3"]
}
```

**هيكل Variations:**
```json
{
  "attributes": {
    "AttributeName": "Value"
  },
  "regular_price": 199.00,
  "sale_price": 159.00,
  "stock_quantity": 30,
  "stock_status": "instock",
  "sku": "UNIQUE-SKU"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Variable product created successfully.",
  "product_id": 456,
  "product": {
    "id": 456,
    "name": "قميص رجالي",
    "type": "variable",
    "attributes": [
      {
        "name": "Color",
        "options": ["أبيض", "أزرق", "أسود"],
        "visible": true,
        "variation": true
      }
    ],
    "variations": [
      {
        "id": 457,
        "sku": "SHIRT-001-WHITE-M",
        "price": "159.00",
        "attributes": {
          "Color": "أبيض",
          "Size": "M"
        },
        "stock_quantity": 30
      }
    ],
    "variations_count": 2
  }
}
```

---

### 2. تعديل منتج متغير

**Endpoint:** `PUT /variable-products/{id}`

**Request Body:**
```json
{
  "name": "اسم محدث",
  "description": "وصف محدث",
  "attributes": [
    {
      "name": "Color",
      "options": ["أبيض", "أزرق", "أسود", "أخضر"]
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Variable product updated successfully.",
  "product": { /* بيانات المنتج المحدثة */ }
}
```

---

### 3. عرض جميع المنتجات المتغيرة

**Endpoint:** `GET /variable-products`

**Query Parameters:**
- `page`: رقم الصفحة (default: 1)
- `per_page`: عدد المنتجات (default: 10)

**Response:**
```json
{
  "success": true,
  "total": 50,
  "total_pages": 5,
  "current_page": 1,
  "per_page": 10,
  "products": [
    {
      "id": 456,
      "name": "منتج متغير",
      "type": "variable",
      "attributes": [ /* ... */ ],
      "variations": [ /* ... */ ],
      "variations_count": 12
    }
  ]
}
```

---

### 4. عرض منتج متغير واحد

**Endpoint:** `GET /variable-products/{id}`

**Response:**
```json
{
  "success": true,
  "product": {
    "id": 456,
    "name": "قميص رجالي",
    "slug": "mens-shirt",
    "type": "variable",
    "status": "publish",
    "description": "وصف كامل",
    "short_description": "وصف مختصر",
    "sku": "SHIRT-001",
    "price": "159.00",
    "price_html": "<span>159 SAR - 199 SAR</span>",
    "attributes": [
      {
        "name": "Color",
        "options": ["أبيض", "أزرق", "أسود"],
        "visible": true,
        "variation": true
      },
      {
        "name": "Size",
        "options": ["S", "M", "L", "XL"],
        "visible": true,
        "variation": true
      }
    ],
    "variations": [
      {
        "id": 457,
        "sku": "SHIRT-001-WHITE-M",
        "price": "159.00",
        "regular_price": "199.00",
        "sale_price": "159.00",
        "stock_status": "instock",
        "stock_quantity": 30,
        "attributes": {
          "Color": "أبيض",
          "Size": "M"
        },
        "image": "https://example.com/variation-image.jpg"
      }
    ],
    "variations_count": 12,
    "image": "https://example.com/product-image.jpg",
    "permalink": "https://example.com/product/mens-shirt"
  }
}
```

---

### 5. البحث عن منتجات متغيرة

**Endpoint:** `GET /variable-products/search`

**Query Parameters:**
- `s`: كلمة البحث (مطلوب)

**Response:**
```json
{
  "success": true,
  "search_term": "قميص",
  "total": 3,
  "products": [ /* نتائج البحث */ ]
}
```

---

### 6. حذف منتج متغير

**Endpoint:** `DELETE /variable-products/{id}`

**Response:**
```json
{
  "success": true,
  "message": "Variable product deleted successfully.",
  "deleted_product": { /* بيانات المنتج المحذوف */ }
}
```

**ملاحظة:** سيتم حذف جميع التنويعات تلقائياً.

---

### 7. إحصائيات المنتجات المتغيرة

**Endpoint:** `GET /variable-products/stats`

**Response:**
```json
{
  "success": true,
  "statistics": {
    "total_products": 50,
    "total_variations": 600,
    "average_variations_per_product": 12,
    "in_stock": 45,
    "out_of_stock": 5,
    "price_range": {
      "min": "SAR 99",
      "max": "SAR 599",
      "min_raw": 99.00,
      "max_raw": 599.00
    }
  }
}
```

---

## 📊 رموز الاستجابة (Status Codes)

| الرمز | المعنى | الوصف |
|------|--------|-------|
| 200 | OK | الطلب نجح |
| 201 | Created | تم إنشاء المورد بنجاح |
| 400 | Bad Request | البيانات المرسلة غير صحيحة |
| 401 | Unauthorized | فشل التوثيق |
| 403 | Forbidden | لا توجد صلاحيات كافية |
| 404 | Not Found | المورد غير موجود |
| 500 | Internal Server Error | خطأ في الخادم |

---

## ⚠️ معالجة الأخطاء

### هيكل رسالة الخطأ

```json
{
  "code": "error_code",
  "message": "وصف الخطأ",
  "data": {
    "status": 401
  }
}
```

### أمثلة على الأخطاء

#### 1. خطأ في التوثيق
```json
{
  "code": "woo_api_auth_missing",
  "message": "Authentication credentials are missing.",
  "data": {
    "status": 401
  }
}
```

#### 2. صلاحيات غير كافية
```json
{
  "code": "woo_api_auth_insufficient_permissions",
  "message": "API key does not have sufficient permissions.",
  "data": {
    "status": 403
  }
}
```

#### 3. منتج غير موجود
```json
{
  "code": "product_not_found",
  "message": "Physical product not found.",
  "data": {
    "status": 404
  }
}
```

#### 4. فشل الإنشاء
```json
{
  "code": "product_creation_failed",
  "message": "Failed to create product.",
  "data": {
    "status": 500
  }
}
```

---

## 💡 نصائح للاستخدام

### 1. Pagination
عند جلب قوائم كبيرة، استخدم pagination:
```
GET /physical-products?page=1&per_page=50
```

### 2. البحث الفعال
استخدم كلمات بحث محددة للحصول على نتائج أفضل:
```
GET /physical-products/search?s=هاتف+سامسونج
```

### 3. تحديث جزئي
عند التعديل، أرسل فقط الحقول المراد تعديلها:
```json
PUT /physical-products/123
{
  "stock_quantity": 200
}
```

### 4. معالجة الأخطاء
دائماً تحقق من `success` في الاستجابة:
```javascript
if (response.success) {
  // نجح الطلب
} else {
  // فشل الطلب
  console.error(response.message);
}
```

---

## 🔄 أمثلة كاملة للتكامل

### مثال: تطبيق React

```javascript
import axios from 'axios';

const API_URL = 'https://dev.murjan.sa/wp-json/murjan-api/v1';
const AUTH = {
  username: 'ck_YOUR_KEY',
  password: 'cs_YOUR_SECRET'
};

// جلب المنتجات
async function getProducts() {
  try {
    const response = await axios.get(`${API_URL}/physical-products`, {
      auth: AUTH,
      params: {
        page: 1,
        per_page: 20
      }
    });
    return response.data.products;
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}

// إضافة منتج
async function createProduct(productData) {
  try {
    const response = await axios.post(
      `${API_URL}/physical-products`,
      productData,
      { auth: AUTH }
    );
    return response.data;
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}
```

### مثال: تطبيق Python

```python
import requests
from requests.auth import HTTPBasicAuth

API_URL = 'https://dev.murjan.sa/wp-json/murjan-api/v1'
AUTH = HTTPBasicAuth('ck_YOUR_KEY', 'cs_YOUR_SECRET')

# جلب المنتجات
def get_products():
    response = requests.get(
        f'{API_URL}/physical-products',
        auth=AUTH,
        params={'page': 1, 'per_page': 20}
    )
    return response.json()

# إضافة منتج
def create_product(product_data):
    response = requests.post(
        f'{API_URL}/physical-products',
        auth=AUTH,
        json=product_data
    )
    return response.json()
```

---

**تم بحمد الله ✨**

للمزيد من المعلومات، راجع ملف [README-AR.md](README-AR.md)

