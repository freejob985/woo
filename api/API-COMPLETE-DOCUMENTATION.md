# 📚 توثيق API شامل - WooCommerce Products API

> **التوثيق الكامل لجميع الـ Endpoints مع أمثلة JSON**

---

## 📋 جدول المحتويات

1. [معلومات أساسية](#معلومات-أساسية)
2. [المصادقة (Authentication)](#المصادقة-authentication)
3. [All Products API - جميع المنتجات](#all-products-api---جميع-المنتجات)
4. [Physical Products API - المنتجات الفيزيائية](#physical-products-api---المنتجات-الفيزيائية)
5. [Variable Products API - المنتجات المتغيرة](#variable-products-api---المنتجات-المتغيرة)
6. [أمثلة عملية](#أمثلة-عملية)
7. [أكواد الأخطاء](#أكواد-الأخطاء)

---

## 📌 معلومات أساسية

### الرابط الأساسي (Base URL)
```
https://dev.murjan.sa/wp-json/murjan-api/v1
```

### إصدار API
```
Version: 1.0.0
```

### تنسيق البيانات
- **Request:** `application/json` أو `multipart/form-data` (للصور)
- **Response:** `application/json`

### Encoding
- UTF-8

---

## 🔐 المصادقة (Authentication)

### نوع المصادقة
**Basic Authentication** باستخدام WooCommerce API Keys

### كيفية الحصول على API Keys

1. اذهب إلى لوحة تحكم WordPress
2. WooCommerce → Settings → Advanced → REST API
3. أنشئ مفتاح API جديد
4. احفظ `Consumer Key` و `Consumer Secret`

### طرق الاستخدام

#### الطريقة الأولى: Basic Auth Header
```http
Authorization: Basic base64(consumer_key:consumer_secret)
```

#### الطريقة الثانية: URL Parameters
```
?consumer_key=ck_xxxxx&consumer_secret=cs_xxxxx
```

### مثال باستخدام cURL
```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products" \
  -u "ck_2210fb8d333da5da151029715a85618a4b283a52:cs_7f1073e18d0af70d01c84692ce8c04609a722b5c"
```

### مثال باستخدام JavaScript (Fetch)
```javascript
const username = 'ck_2210fb8d333da5da151029715a85618a4b283a52';
const password = 'cs_7f1073e18d0af70d01c84692ce8c04609a722b5c';
const credentials = btoa(`${username}:${password}`);

fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products', {
  headers: {
    'Authorization': `Basic ${credentials}`
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## 🟣 All Products API - جميع المنتجات

> API موحد لعرض جميع المنتجات (الفيزيائية + المتغيرة) مع دعم كامل للفلترة والبحث

---

### 1️⃣ عرض جميع المنتجات (مع Pagination)

#### Endpoint
```
GET /products
```

#### المعاملات (Query Parameters)

| المعامل | النوع | القيمة الافتراضية | الوصف |
|---------|------|-------------------|-------|
| `page` | integer | 1 | رقم الصفحة |
| `per_page` | integer | 10 | عدد المنتجات في الصفحة |
| `type` | string | all | نوع المنتج: `all`, `physical`, `variable` |
| `status` | string | publish | حالة المنتج: `publish`, `draft`, `any` |
| `featured` | boolean | - | المنتجات المميزة فقط |
| `on_sale` | boolean | - | المنتجات المخفضة فقط |
| `category` | string | - | slug التصنيف |
| `orderby` | string | date | الترتيب: `date`, `title`, `price`, `popularity`, `rating` |
| `order` | string | DESC | اتجاه الترتيب: `ASC`, `DESC` |

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=10&type=all
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "total": 10,
  "total_products": 45,
  "total_pages": 5,
  "current_page": 1,
  "per_page": 10,
  "filters": {
    "type": "all",
    "status": "publish",
    "featured": null,
    "on_sale": null,
    "category": null
  },
  "products": [
    {
      "id": 123,
      "name": "قميص قطني رجالي",
      "slug": "cotton-shirt-men",
      "permalink": "https://dev.murjan.sa/product/cotton-shirt-men/",
      "type": "simple",
      "status": "publish",
      "featured": true,
      "catalog_visibility": "visible",
      "description": "قميص قطني عالي الجودة للرجال",
      "short_description": "قميص قطني مريح",
      "sku": "SHIRT-001",
      "price": "150.00",
      "price_html": "<span class=\"price\">150.00 ر.س</span>",
      "regular_price": "200.00",
      "sale_price": "150.00",
      "on_sale": true,
      "stock_status": "instock",
      "stock_quantity": 50,
      "manage_stock": true,
      "backorders": "no",
      "low_stock_amount": 5,
      "sold_individually": false,
      "reviews_allowed": true,
      "average_rating": "4.5",
      "rating_count": 12,
      "total_sales": 45,
      "is_physical": true,
      "weight": "0.3",
      "dimensions": {
        "length": "30",
        "width": "20",
        "height": "2"
      },
      "shipping_class": "",
      "categories": [
        {
          "id": 15,
          "name": "ملابس",
          "slug": "clothing"
        }
      ],
      "tags": [
        {
          "id": 22,
          "name": "قميص",
          "slug": "shirt"
        }
      ],
      "images": {
        "main_image": {
          "id": 456,
          "src": "https://dev.murjan.sa/wp-content/uploads/2024/shirt.jpg",
          "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-150x150.jpg",
          "alt": "قميص قطني"
        },
        "gallery": [
          {
            "id": 457,
            "src": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-2.jpg",
            "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-2-150x150.jpg",
            "alt": "قميص - منظر خلفي"
          }
        ]
      },
      "date_created": "2024-01-15 10:30:00",
      "date_modified": "2024-01-20 14:45:00"
    },
    {
      "id": 124,
      "name": "بنطلون جينز - مقاسات متعددة",
      "slug": "jeans-variable",
      "permalink": "https://dev.murjan.sa/product/jeans-variable/",
      "type": "variable",
      "status": "publish",
      "featured": false,
      "catalog_visibility": "visible",
      "description": "بنطلون جينز بمقاسات وألوان متعددة",
      "short_description": "جينز عصري",
      "sku": "JEANS-VAR-001",
      "price": "180.00",
      "price_html": "<span class=\"price\">180.00 ر.س - 220.00 ر.س</span>",
      "regular_price": "",
      "sale_price": "",
      "on_sale": false,
      "stock_status": "instock",
      "stock_quantity": null,
      "manage_stock": false,
      "backorders": "no",
      "low_stock_amount": null,
      "sold_individually": false,
      "reviews_allowed": true,
      "average_rating": "4.2",
      "rating_count": 8,
      "total_sales": 32,
      "is_physical": false,
      "attributes": [
        {
          "name": "المقاس",
          "options": ["S", "M", "L", "XL"],
          "visible": true,
          "variation": true
        },
        {
          "name": "اللون",
          "options": ["أزرق", "أسود"],
          "visible": true,
          "variation": true
        }
      ],
      "variations": [
        {
          "id": 125,
          "sku": "JEANS-S-BLUE",
          "price": "180.00",
          "regular_price": "180.00",
          "sale_price": "",
          "stock_status": "instock",
          "stock_quantity": 15,
          "attributes": {
            "المقاس": "S",
            "اللون": "أزرق"
          },
          "image": {
            "id": 460,
            "src": "https://dev.murjan.sa/wp-content/uploads/2024/jeans-s-blue.jpg",
            "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/jeans-s-blue-150x150.jpg"
          }
        },
        {
          "id": 126,
          "sku": "JEANS-M-BLUE",
          "price": "180.00",
          "regular_price": "180.00",
          "sale_price": "",
          "stock_status": "instock",
          "stock_quantity": 20,
          "attributes": {
            "المقاس": "M",
            "اللون": "أزرق"
          },
          "image": {
            "id": 461,
            "src": "https://dev.murjan.sa/wp-content/uploads/2024/jeans-m-blue.jpg",
            "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/jeans-m-blue-150x150.jpg"
          }
        }
      ],
      "variations_count": 8,
      "price_range": {
        "min": "180.00",
        "max": "220.00"
      },
      "categories": [
        {
          "id": 15,
          "name": "ملابس",
          "slug": "clothing"
        }
      ],
      "tags": [
        {
          "id": 23,
          "name": "جينز",
          "slug": "jeans"
        }
      ],
      "images": {
        "main_image": {
          "id": 458,
          "src": "https://dev.murjan.sa/wp-content/uploads/2024/jeans.jpg",
          "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/jeans-150x150.jpg",
          "alt": "بنطلون جينز"
        },
        "gallery": []
      },
      "date_created": "2024-01-18 09:15:00",
      "date_modified": "2024-01-22 11:20:00"
    }
  ]
}
```

---

### 2️⃣ البحث في جميع المنتجات

#### Endpoint
```
GET /products/search
```

#### المعاملات (Query Parameters)

| المعامل | النوع | مطلوب | الوصف |
|---------|------|-------|-------|
| `s` | string | ✅ نعم | كلمة البحث |
| `page` | integer | ❌ لا | رقم الصفحة (افتراضي: 1) |
| `per_page` | integer | ❌ لا | عدد النتائج (افتراضي: 10) |
| `type` | string | ❌ لا | نوع المنتج: `all`, `physical`, `variable` |
| `orderby` | string | ❌ لا | الترتيب: `relevance`, `date`, `title`, `price` |

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=قميص&page=1&per_page=10
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "search_term": "قميص",
  "total": 5,
  "total_results": 5,
  "total_pages": 1,
  "current_page": 1,
  "per_page": 10,
  "type_filter": "all",
  "products": [
    {
      "id": 123,
      "name": "قميص قطني رجالي",
      "slug": "cotton-shirt-men",
      "type": "simple",
      "price": "150.00",
      "stock_status": "instock",
      "images": {
        "main_image": {
          "id": 456,
          "src": "https://dev.murjan.sa/wp-content/uploads/2024/shirt.jpg",
          "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-150x150.jpg",
          "alt": "قميص قطني"
        },
        "gallery": []
      }
    }
  ]
}
```

---

### 3️⃣ عرض منتج واحد

#### Endpoint
```
GET /products/{id}
```

#### Path Parameters

| المعامل | النوع | الوصف |
|---------|------|-------|
| `id` | integer | رقم المنتج |

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/123
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "product": {
    "id": 123,
    "name": "قميص قطني رجالي",
    "slug": "cotton-shirt-men",
    "permalink": "https://dev.murjan.sa/product/cotton-shirt-men/",
    "type": "simple",
    "status": "publish",
    "featured": true,
    "catalog_visibility": "visible",
    "description": "قميص قطني عالي الجودة للرجال",
    "short_description": "قميص قطني مريح",
    "sku": "SHIRT-001",
    "price": "150.00",
    "regular_price": "200.00",
    "sale_price": "150.00",
    "on_sale": true,
    "stock_status": "instock",
    "stock_quantity": 50,
    "manage_stock": true,
    "images": {
      "main_image": {
        "id": 456,
        "src": "https://dev.murjan.sa/wp-content/uploads/2024/shirt.jpg",
        "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-150x150.jpg",
        "alt": "قميص قطني"
      },
      "gallery": []
    }
  }
}
```

#### مثال Response (خطأ - منتج غير موجود)

```json
{
  "code": "product_not_found",
  "message": "Product not found.",
  "data": {
    "status": 404
  }
}
```

---

### 4️⃣ إحصائيات شاملة

#### Endpoint
```
GET /products/stats
```

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/stats
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "statistics": {
    "products_overview": {
      "total_products": 150,
      "physical_products": 85,
      "variable_products": 65,
      "simple_products": 85,
      "total_variations": 320
    },
    "stock_status": {
      "in_stock": 130,
      "out_of_stock": 15,
      "on_backorder": 5,
      "low_stock": 12
    },
    "sales_info": {
      "on_sale": 35,
      "featured": 25,
      "total_sales": 2450,
      "average_sales_per_product": 16.33
    },
    "pricing": {
      "total_value": "45,750.00 ر.س",
      "total_value_raw": 45750.00,
      "average_price": "305.00 ر.س",
      "average_price_raw": 305.00
    },
    "categories": {
      "total_categories": 12
    }
  }
}
```

---

## 🔵 Physical Products API - المنتجات الفيزيائية

> API لإدارة المنتجات الفيزيائية (التي تحتاج شحن)

---

### 1️⃣ إضافة منتج فيزيائي جديد

#### Endpoint
```
POST /physical-products
```

#### Content-Type
```
multipart/form-data
```

#### Body Parameters

| الحقل | النوع | مطلوب | الوصف |
|------|------|-------|-------|
| **معلومات أساسية** ||||
| `name` | string | ✅ نعم | اسم المنتج |
| `description` | text | ❌ لا | الوصف الكامل (يدعم HTML) |
| `short_description` | text | ❌ لا | الوصف المختصر |
| `sku` | string | ❌ لا | رقم تعريفي فريد (SKU) |
| **الأسعار** ||||
| `regular_price` | decimal | ✅ نعم | السعر العادي |
| `sale_price` | decimal | ❌ لا | سعر التخفيض |
| `date_on_sale_from` | datetime | ❌ لا | تاريخ بداية التخفيض (YYYY-MM-DD) |
| `date_on_sale_to` | datetime | ❌ لا | تاريخ نهاية التخفيض (YYYY-MM-DD) |
| **المخزون** ||||
| `manage_stock` | boolean | ❌ لا | إدارة المخزون (true/false) |
| `stock_quantity` | integer | ❌ لا | كمية المخزون |
| `stock_status` | string | ❌ لا | حالة المخزون: `instock`, `outofstock`, `onbackorder` |
| `backorders` | string | ❌ لا | الطلب المسبق: `no`, `notify`, `yes` |
| `sold_individually` | boolean | ❌ لا | بيع قطعة واحدة فقط |
| `low_stock_amount` | integer | ❌ لا | حد تنبيه المخزون المنخفض |
| **الشحن** ||||
| `weight` | decimal | ❌ لا | الوزن (كيلوجرام) |
| `length` | decimal | ❌ لا | الطول (سنتيمتر) |
| `width` | decimal | ❌ لا | العرض (سنتيمتر) |
| `height` | decimal | ❌ لا | الارتفاع (سنتيمتر) |
| `shipping_class` | string | ❌ لا | فئة الشحن (slug) |
| **الضرائب** ||||
| `tax_status` | string | ❌ لا | حالة الضريبة: `taxable`, `shipping`, `none` |
| `tax_class` | string | ❌ لا | فئة الضريبة |
| **إعدادات إضافية** ||||
| `status` | string | ❌ لا | حالة المنتج: `publish`, `draft`, `pending` |
| `featured` | boolean | ❌ لا | منتج مميز |
| `catalog_visibility` | string | ❌ لا | الظهور: `visible`, `catalog`, `search`, `hidden` |
| `reviews_allowed` | boolean | ❌ لا | السماح بالمراجعات |
| `purchase_note` | text | ❌ لا | ملاحظة الشراء |
| **التصنيفات والوسوم** ||||
| `categories` | string/array | ❌ لا | التصنيفات (مفصولة بفاصلة أو array) |
| `tags` | string/array | ❌ لا | الوسوم (مفصولة بفاصلة أو array) |
| **الصور** ||||
| `main_image` | file | ❌ لا | الصورة الرئيسية |
| `gallery_images[]` | file[] | ❌ لا | صور المعرض (متعددة) |

#### مثال Request (cURL)

```bash
curl -X POST "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products" \
  -u "ck_xxxxx:cs_xxxxx" \
  -F "name=قميص قطني رجالي جديد" \
  -F "description=قميص قطني عالي الجودة مصنوع من القطن الخالص" \
  -F "short_description=قميص قطني مريح للاستخدام اليومي" \
  -F "sku=SHIRT-NEW-001" \
  -F "regular_price=200.00" \
  -F "sale_price=150.00" \
  -F "manage_stock=true" \
  -F "stock_quantity=50" \
  -F "stock_status=instock" \
  -F "weight=0.3" \
  -F "length=30" \
  -F "width=20" \
  -F "height=2" \
  -F "status=publish" \
  -F "featured=true" \
  -F "categories=clothing,mens-clothing" \
  -F "tags=shirt,cotton,casual" \
  -F "main_image=@/path/to/shirt-main.jpg" \
  -F "gallery_images[]=@/path/to/shirt-gallery-1.jpg" \
  -F "gallery_images[]=@/path/to/shirt-gallery-2.jpg"
```

#### مثال Request (JavaScript - FormData)

```javascript
const formData = new FormData();
formData.append('name', 'قميص قطني رجالي جديد');
formData.append('description', 'قميص قطني عالي الجودة');
formData.append('short_description', 'قميص قطني مريح');
formData.append('sku', 'SHIRT-NEW-001');
formData.append('regular_price', '200.00');
formData.append('sale_price', '150.00');
formData.append('manage_stock', 'true');
formData.append('stock_quantity', '50');
formData.append('stock_status', 'instock');
formData.append('weight', '0.3');
formData.append('length', '30');
formData.append('width', '20');
formData.append('height', '2');
formData.append('status', 'publish');
formData.append('featured', 'true');
formData.append('categories', 'clothing,mens-clothing');
formData.append('tags', 'shirt,cotton,casual');
formData.append('main_image', mainImageFile);
formData.append('gallery_images[]', galleryImage1);
formData.append('gallery_images[]', galleryImage2);

const username = 'ck_xxxxx';
const password = 'cs_xxxxx';
const credentials = btoa(`${username}:${password}`);

fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products', {
  method: 'POST',
  headers: {
    'Authorization': `Basic ${credentials}`
  },
  body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "message": "Product created successfully.",
  "product_id": 789,
  "product": {
    "id": 789,
    "name": "قميص قطني رجالي جديد",
    "slug": "cotton-shirt-new",
    "permalink": "https://dev.murjan.sa/product/cotton-shirt-new/",
    "type": "simple",
    "status": "publish",
    "featured": true,
    "catalog_visibility": "visible",
    "description": "قميص قطني عالي الجودة مصنوع من القطن الخالص",
    "short_description": "قميص قطني مريح للاستخدام اليومي",
    "sku": "SHIRT-NEW-001",
    "price": "150.00",
    "price_html": "<del>200.00 ر.س</del> <ins>150.00 ر.س</ins>",
    "regular_price": "200.00",
    "sale_price": "150.00",
    "on_sale": true,
    "stock_status": "instock",
    "stock_quantity": 50,
    "manage_stock": true,
    "backorders": "no",
    "sold_individually": false,
    "weight": "0.3",
    "dimensions": {
      "length": "30",
      "width": "20",
      "height": "2"
    },
    "shipping_class": "",
    "tax_status": "taxable",
    "tax_class": "",
    "reviews_allowed": true,
    "average_rating": "0",
    "rating_count": 0,
    "total_sales": 0,
    "purchase_note": "",
    "categories": [
      {
        "id": 15,
        "name": "ملابس",
        "slug": "clothing"
      },
      {
        "id": 22,
        "name": "ملابس رجالية",
        "slug": "mens-clothing"
      }
    ],
    "tags": [
      {
        "id": 30,
        "name": "قميص",
        "slug": "shirt"
      },
      {
        "id": 31,
        "name": "قطن",
        "slug": "cotton"
      },
      {
        "id": 32,
        "name": "كاجوال",
        "slug": "casual"
      }
    ],
    "images": {
      "main_image": {
        "id": 890,
        "src": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-main.jpg",
        "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-main-150x150.jpg",
        "alt": ""
      },
      "gallery": [
        {
          "id": 891,
          "src": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-gallery-1.jpg",
          "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-gallery-1-150x150.jpg",
          "alt": ""
        },
        {
          "id": 892,
          "src": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-gallery-2.jpg",
          "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/shirt-gallery-2-150x150.jpg",
          "alt": ""
        }
      ]
    },
    "date_created": "2024-01-27 10:30:00",
    "date_modified": "2024-01-27 10:30:00"
  }
}
```

---

### 2️⃣ تعديل منتج فيزيائي

#### Endpoint
```
POST /physical-products/{id}
```

#### Path Parameters

| المعامل | النوع | الوصف |
|---------|------|-------|
| `id` | integer | رقم المنتج المراد تعديله |

#### Content-Type
```
multipart/form-data
```

#### Body Parameters
نفس المعاملات في إضافة منتج جديد (جميعها اختيارية في حالة التعديل)

#### مثال Request

```bash
curl -X POST "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/789" \
  -u "ck_xxxxx:cs_xxxxx" \
  -F "name=قميص قطني رجالي - محدث" \
  -F "regular_price=250.00" \
  -F "sale_price=180.00" \
  -F "stock_quantity=75"
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "message": "Product updated successfully.",
  "product": {
    "id": 789,
    "name": "قميص قطني رجالي - محدث",
    "price": "180.00",
    "regular_price": "250.00",
    "sale_price": "180.00",
    "stock_quantity": 75,
    "date_modified": "2024-01-27 15:45:00"
  }
}
```

---

### 3️⃣ عرض جميع المنتجات الفيزيائية

#### Endpoint
```
GET /physical-products
```

#### Query Parameters

| المعامل | النوع | القيمة الافتراضية | الوصف |
|---------|------|-------------------|-------|
| `page` | integer | 1 | رقم الصفحة |
| `per_page` | integer | 10 | عدد المنتجات في الصفحة |
| `orderby` | string | date | الترتيب: `date`, `title`, `price`, `popularity`, `rating` |
| `order` | string | DESC | اتجاه الترتيب: `ASC`, `DESC` |

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products?page=1&per_page=10
```

#### مثال Response

```json
{
  "success": true,
  "total": 10,
  "total_products": 85,
  "total_pages": 9,
  "current_page": 1,
  "per_page": 10,
  "products": [
    {
      "id": 789,
      "name": "قميص قطني رجالي",
      "sku": "SHIRT-001",
      "price": "150.00",
      "stock_status": "instock",
      "stock_quantity": 50
    }
  ]
}
```

---

### 4️⃣ عرض منتج فيزيائي واحد

#### Endpoint
```
GET /physical-products/{id}
```

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/789
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "product": {
    "id": 789,
    "name": "قميص قطني رجالي",
    "type": "simple",
    "price": "150.00",
    "stock_status": "instock"
  }
}
```

---

### 5️⃣ البحث في المنتجات الفيزيائية

#### Endpoint
```
GET /physical-products/search
```

#### Query Parameters

| المعامل | النوع | مطلوب | الوصف |
|---------|------|-------|-------|
| `s` | string | ✅ نعم | كلمة البحث |
| `page` | integer | ❌ لا | رقم الصفحة |
| `per_page` | integer | ❌ لا | عدد النتائج |

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/search?s=قميص&page=1
```

#### مثال Response

```json
{
  "success": true,
  "search_term": "قميص",
  "total": 3,
  "total_pages": 1,
  "current_page": 1,
  "products": [
    {
      "id": 789,
      "name": "قميص قطني رجالي",
      "price": "150.00"
    }
  ]
}
```

---

### 6️⃣ حذف منتج فيزيائي

#### Endpoint
```
DELETE /physical-products/{id}
```

#### Path Parameters

| المعامل | النوع | الوصف |
|---------|------|-------|
| `id` | integer | رقم المنتج المراد حذفه |

#### مثال Request

```bash
curl -X DELETE "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/789" \
  -u "ck_xxxxx:cs_xxxxx"
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "message": "Product deleted successfully.",
  "deleted_product": {
    "id": 789,
    "name": "قميص قطني رجالي",
    "sku": "SHIRT-001"
  }
}
```

---

### 7️⃣ إحصائيات المنتجات الفيزيائية

#### Endpoint
```
GET /physical-products/stats
```

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/stats
```

#### مثال Response

```json
{
  "success": true,
  "statistics": {
    "total_products": 85,
    "in_stock": 70,
    "out_of_stock": 10,
    "on_backorder": 5,
    "total_value": "25,500.00 ر.س",
    "total_value_raw": 25500.00,
    "total_stock_quantity": 1250,
    "total_sales": 1450,
    "average_price": "300.00 ر.س",
    "average_price_raw": 300.00
  }
}
```

---

## 🟢 Variable Products API - المنتجات المتغيرة

> API لإدارة المنتجات المتغيرة (مثل المقاسات والألوان)

---

### 1️⃣ إضافة منتج متغير جديد

#### Endpoint
```
POST /variable-products
```

#### Content-Type
```
multipart/form-data
```

#### Body Parameters

| الحقل | النوع | مطلوب | الوصف |
|------|------|-------|-------|
| `name` | string | ✅ نعم | اسم المنتج |
| `description` | text | ❌ لا | الوصف الكامل |
| `short_description` | text | ❌ لا | الوصف المختصر |
| `sku` | string | ❌ لا | رقم تعريفي |
| `status` | string | ❌ لا | حالة المنتج: `publish`, `draft` |
| `featured` | boolean | ❌ لا | منتج مميز |
| `attributes` | JSON string | ✅ نعم | المواصفات (JSON) |
| `variations` | JSON string | ✅ نعم | الاختلافات (JSON) |
| `main_image` | file | ❌ لا | الصورة الرئيسية |
| `gallery_images[]` | file[] | ❌ لا | صور المعرض |

#### شكل JSON للـ Attributes

```json
[
  {
    "name": "المقاس",
    "options": ["S", "M", "L", "XL"]
  },
  {
    "name": "اللون",
    "options": ["أزرق", "أسود", "أبيض"]
  }
]
```

#### شكل JSON للـ Variations

```json
[
  {
    "attributes": {
      "المقاس": "S",
      "اللون": "أزرق"
    },
    "sku": "JEANS-S-BLUE",
    "regular_price": "180.00",
    "sale_price": "150.00",
    "stock_quantity": 15,
    "stock_status": "instock"
  },
  {
    "attributes": {
      "المقاس": "M",
      "اللون": "أزرق"
    },
    "sku": "JEANS-M-BLUE",
    "regular_price": "180.00",
    "sale_price": "",
    "stock_quantity": 20,
    "stock_status": "instock"
  },
  {
    "attributes": {
      "المقاس": "S",
      "اللون": "أسود"
    },
    "sku": "JEANS-S-BLACK",
    "regular_price": "180.00",
    "sale_price": "",
    "stock_quantity": 10,
    "stock_status": "instock"
  }
]
```

#### مثال Request (cURL)

```bash
curl -X POST "https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products" \
  -u "ck_xxxxx:cs_xxxxx" \
  -F "name=بنطلون جينز - مقاسات وألوان متعددة" \
  -F "description=بنطلون جينز عصري بمقاسات وألوان مختلفة" \
  -F "short_description=جينز عصري ومريح" \
  -F "sku=JEANS-VAR-001" \
  -F "status=publish" \
  -F "featured=true" \
  -F 'attributes=[{"name":"المقاس","options":["S","M","L","XL"]},{"name":"اللون","options":["أزرق","أسود","أبيض"]}]' \
  -F 'variations=[{"attributes":{"المقاس":"S","اللون":"أزرق"},"sku":"JEANS-S-BLUE","regular_price":"180.00","sale_price":"150.00","stock_quantity":15,"stock_status":"instock"},{"attributes":{"المقاس":"M","اللون":"أزرق"},"sku":"JEANS-M-BLUE","regular_price":"180.00","stock_quantity":20,"stock_status":"instock"}]' \
  -F "main_image=@/path/to/jeans-main.jpg"
```

#### مثال Request (JavaScript)

```javascript
const attributes = [
  {
    name: "المقاس",
    options: ["S", "M", "L", "XL"]
  },
  {
    name: "اللون",
    options: ["أزرق", "أسود", "أبيض"]
  }
];

const variations = [
  {
    attributes: {
      "المقاس": "S",
      "اللون": "أزرق"
    },
    sku: "JEANS-S-BLUE",
    regular_price: "180.00",
    sale_price: "150.00",
    stock_quantity: 15,
    stock_status: "instock"
  },
  {
    attributes: {
      "المقاس": "M",
      "اللون": "أزرق"
    },
    sku: "JEANS-M-BLUE",
    regular_price: "180.00",
    stock_quantity: 20,
    stock_status: "instock"
  }
];

const formData = new FormData();
formData.append('name', 'بنطلون جينز - مقاسات وألوان متعددة');
formData.append('description', 'بنطلون جينز عصري');
formData.append('short_description', 'جينز عصري ومريح');
formData.append('sku', 'JEANS-VAR-001');
formData.append('status', 'publish');
formData.append('featured', 'true');
formData.append('attributes', JSON.stringify(attributes));
formData.append('variations', JSON.stringify(variations));
formData.append('main_image', mainImageFile);

const username = 'ck_xxxxx';
const password = 'cs_xxxxx';
const credentials = btoa(`${username}:${password}`);

fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products', {
  method: 'POST',
  headers: {
    'Authorization': `Basic ${credentials}`
  },
  body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

#### مثال Response (نجاح)

```json
{
  "success": true,
  "message": "Variable product created successfully.",
  "product_id": 950,
  "product": {
    "id": 950,
    "name": "بنطلون جينز - مقاسات وألوان متعددة",
    "slug": "jeans-variable",
    "type": "variable",
    "status": "publish",
    "featured": true,
    "description": "بنطلون جينز عصري بمقاسات وألوان مختلفة",
    "short_description": "جينز عصري ومريح",
    "sku": "JEANS-VAR-001",
    "price": "150.00",
    "price_html": "150.00 ر.س - 180.00 ر.س",
    "attributes": [
      {
        "name": "المقاس",
        "options": ["S", "M", "L", "XL"],
        "visible": true,
        "variation": true
      },
      {
        "name": "اللون",
        "options": ["أزرق", "أسود", "أبيض"],
        "visible": true,
        "variation": true
      }
    ],
    "variations": [
      {
        "id": 951,
        "sku": "JEANS-S-BLUE",
        "price": "150.00",
        "regular_price": "180.00",
        "sale_price": "150.00",
        "stock_status": "instock",
        "stock_quantity": 15,
        "attributes": {
          "المقاس": "S",
          "اللون": "أزرق"
        },
        "image": ""
      },
      {
        "id": 952,
        "sku": "JEANS-M-BLUE",
        "price": "180.00",
        "regular_price": "180.00",
        "sale_price": "",
        "stock_status": "instock",
        "stock_quantity": 20,
        "attributes": {
          "المقاس": "M",
          "اللون": "أزرق"
        },
        "image": ""
      }
    ],
    "variations_count": 2,
    "images": {
      "main_image": {
        "id": 960,
        "src": "https://dev.murjan.sa/wp-content/uploads/2024/jeans-main.jpg",
        "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/jeans-main-150x150.jpg"
      },
      "gallery": []
    },
    "permalink": "https://dev.murjan.sa/product/jeans-variable/"
  }
}
```

---

### 2️⃣ تعديل منتج متغير

#### Endpoint
```
POST /variable-products/{id}
```

#### Path Parameters

| المعامل | النوع | الوصف |
|---------|------|-------|
| `id` | integer | رقم المنتج المراد تعديله |

#### Body Parameters
نفس المعاملات في إضافة منتج جديد (جميعها اختيارية)

#### مثال Request

```bash
curl -X POST "https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products/950" \
  -u "ck_xxxxx:cs_xxxxx" \
  -F "name=بنطلون جينز - محدث" \
  -F "featured=false"
```

#### مثال Response

```json
{
  "success": true,
  "message": "Variable product updated successfully.",
  "product": {
    "id": 950,
    "name": "بنطلون جينز - محدث",
    "featured": false
  }
}
```

---

### 3️⃣ عرض جميع المنتجات المتغيرة

#### Endpoint
```
GET /variable-products
```

#### Query Parameters

| المعامل | النوع | القيمة الافتراضية |
|---------|------|--------------------|
| `page` | integer | 1 |
| `per_page` | integer | 10 |

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products?page=1&per_page=10
```

#### مثال Response

```json
{
  "success": true,
  "total": 65,
  "total_pages": 7,
  "current_page": 1,
  "per_page": 10,
  "products": [
    {
      "id": 950,
      "name": "بنطلون جينز",
      "type": "variable",
      "variations_count": 8,
      "price": "150.00 - 220.00"
    }
  ]
}
```

---

### 4️⃣ عرض منتج متغير واحد

#### Endpoint
```
GET /variable-products/{id}
```

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products/950
```

#### مثال Response

```json
{
  "success": true,
  "product": {
    "id": 950,
    "name": "بنطلون جينز",
    "type": "variable",
    "attributes": [...],
    "variations": [...],
    "variations_count": 8
  }
}
```

---

### 5️⃣ البحث في المنتجات المتغيرة

#### Endpoint
```
GET /variable-products/search
```

#### Query Parameters

| المعامل | النوع | مطلوب |
|---------|------|-------|
| `s` | string | ✅ نعم |
| `page` | integer | ❌ لا |
| `per_page` | integer | ❌ لا |

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products/search?s=جينز
```

#### مثال Response

```json
{
  "success": true,
  "search_term": "جينز",
  "total": 5,
  "total_pages": 1,
  "current_page": 1,
  "products": [...]
}
```

---

### 6️⃣ حذف منتج متغير

#### Endpoint
```
DELETE /variable-products/{id}
```

#### مثال Request

```bash
curl -X DELETE "https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products/950" \
  -u "ck_xxxxx:cs_xxxxx"
```

#### مثال Response

```json
{
  "success": true,
  "message": "Variable product deleted successfully.",
  "deleted_product": {
    "id": 950,
    "name": "بنطلون جينز"
  }
}
```

---

### 7️⃣ إحصائيات المنتجات المتغيرة

#### Endpoint
```
GET /variable-products/stats
```

#### مثال Request

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products/stats
```

#### مثال Response

```json
{
  "success": true,
  "statistics": {
    "total_products": 65,
    "total_variations": 320,
    "average_variations_per_product": 4.92,
    "in_stock": 60,
    "out_of_stock": 5,
    "price_range": {
      "min": "50.00 ر.س",
      "max": "500.00 ر.س",
      "min_raw": 50.00,
      "max_raw": 500.00
    }
  }
}
```

---

## 🎯 أمثلة عملية

### مثال 1: عرض المنتجات المخفضة (On Sale)

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?on_sale=true&page=1&per_page=20
```

### مثال 2: البحث عن منتج معين

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=هاتف&type=physical
```

### مثال 3: فلترة حسب التصنيف

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?category=electronics&orderby=price&order=ASC
```

### مثال 4: المنتجات المميزة الفيزيائية

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?type=physical&featured=true&page=1
```

### مثال 5: إنشاء منتج بسيط بدون صور (باستخدام JSON)

**ملاحظة:** للمنتجات بدون صور، يمكنك استخدام JSON بدلاً من FormData

```bash
curl -X POST "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products" \
  -u "ck_xxxxx:cs_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "كتاب برمجة",
    "description": "كتاب تعليمي عن البرمجة",
    "sku": "BOOK-001",
    "regular_price": "99.00",
    "manage_stock": true,
    "stock_quantity": 100,
    "status": "publish"
  }'
```

---

## ❌ أكواد الأخطاء

### أخطاء المصادقة (Authentication Errors)

| الكود | الرسالة | الوصف |
|------|---------|-------|
| `woo_api_auth_missing` | Authentication credentials are missing | مفاتيح API غير موجودة |
| `woo_api_auth_invalid` | Invalid authentication method | طريقة مصادقة غير صحيحة |
| `woo_api_auth_invalid_key` | Invalid consumer key | مفتاح Consumer Key خاطئ |
| `woo_api_auth_invalid_secret` | Invalid consumer secret | مفتاح Consumer Secret خاطئ |
| `woo_api_auth_insufficient_permissions` | API key does not have sufficient permissions | صلاحيات غير كافية |
| `woo_api_auth_user_permissions` | User does not have sufficient permissions | المستخدم لا يملك الصلاحيات |

### أخطاء المنتجات (Product Errors)

| الكود | الرسالة | الحالة |
|------|---------|--------|
| `product_not_found` | Product not found | 404 |
| `product_creation_failed` | Failed to create product | 500 |
| `product_update_error` | Failed to update product | 500 |
| `product_deletion_failed` | Failed to delete product | 500 |
| `upload_error` | Image upload error | 500 |

### مثال Error Response

```json
{
  "code": "product_not_found",
  "message": "Product not found.",
  "data": {
    "status": 404
  }
}
```

---

## 📊 ملخص جميع الـ Endpoints

### All Products API 🟣

| Method | Endpoint | الوصف |
|--------|----------|-------|
| `GET` | `/products` | عرض جميع المنتجات |
| `GET` | `/products/search` | البحث في المنتجات |
| `GET` | `/products/{id}` | عرض منتج واحد |
| `GET` | `/products/stats` | إحصائيات شاملة |

### Physical Products API 🔵

| Method | Endpoint | الوصف |
|--------|----------|-------|
| `POST` | `/physical-products` | إضافة منتج فيزيائي |
| `POST` | `/physical-products/{id}` | تعديل منتج فيزيائي |
| `GET` | `/physical-products` | عرض جميع المنتجات الفيزيائية |
| `GET` | `/physical-products/{id}` | عرض منتج فيزيائي واحد |
| `GET` | `/physical-products/search` | البحث في المنتجات الفيزيائية |
| `DELETE` | `/physical-products/{id}` | حذف منتج فيزيائي |
| `GET` | `/physical-products/stats` | إحصائيات المنتجات الفيزيائية |

### Variable Products API 🟢

| Method | Endpoint | الوصف |
|--------|----------|-------|
| `POST` | `/variable-products` | إضافة منتج متغير |
| `POST` | `/variable-products/{id}` | تعديل منتج متغير |
| `GET` | `/variable-products` | عرض جميع المنتجات المتغيرة |
| `GET` | `/variable-products/{id}` | عرض منتج متغير واحد |
| `GET` | `/variable-products/search` | البحث في المنتجات المتغيرة |
| `DELETE` | `/variable-products/{id}` | حذف منتج متغير |
| `GET` | `/variable-products/stats` | إحصائيات المنتجات المتغيرة |

---

## 📞 الدعم والمساعدة

### معلومات الاتصال

- **الموقع:** https://dev.murjan.sa
- **API Base URL:** https://dev.murjan.sa/wp-json/murjan-api/v1
- **الإصدار:** 1.0.0

### الموارد المفيدة

- [Postman Collection](./postman/) - مجموعة Postman كاملة لتجربة جميع الـ Endpoints
- [README.md](./README.md) - دليل التثبيت والإعداد
- [CHANGELOG.md](./CHANGELOG-v1.1.0.md) - سجل التغييرات

---

## 📝 ملاحظات مهمة

### 1. معدل الطلبات (Rate Limiting)
- لا يوجد حد للطلبات حالياً، لكن يُنصح بعدم إرسال أكثر من 60 طلب في الدقيقة

### 2. حجم الصور
- الحد الأقصى لحجم الصورة: 5 MB
- الصيغ المدعومة: JPG, PNG, GIF, WebP

### 3. الترميز
- جميع البيانات يجب أن تكون بترميز UTF-8
- يدعم اللغة العربية بشكل كامل

### 4. HTTPS
- يجب استخدام HTTPS للاتصال الآمن
- Basic Authentication يتطلب HTTPS

### 5. الـ Cache
- بعض الاستعلامات قد تكون مخزنة مؤقتاً (cached)
- يمكن إضافة `?timestamp=` لتجاوز الـ cache

---

**آخر تحديث:** 27 يناير 2024  
**الإصدار:** 1.0.0  
**الفريق:** Murjan Development Team

---


