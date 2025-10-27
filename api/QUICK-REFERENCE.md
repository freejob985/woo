# 📋 مرجع سريع - WooCommerce Products API

> **دليل مختصر لجميع الـ Endpoints**

---

## 🔗 الرابط الأساسي
```
https://dev.murjan.sa/wp-json/murjan-api/v1
```

## 🔐 المصادقة
```
Consumer Key: ck_2210fb8d333da5da151029715a85618a4b283a52
Consumer Secret: cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
```

---

## 🟣 All Products API - جميع المنتجات

| # | Method | Endpoint | الوصف | المعاملات الرئيسية |
|---|--------|----------|-------|---------------------|
| 1 | `GET` | `/products` | عرض جميع المنتجات | `page`, `per_page`, `type`, `featured`, `on_sale`, `category`, `orderby`, `order` |
| 2 | `GET` | `/products/search` | البحث | `s` (مطلوب), `page`, `per_page`, `type`, `orderby` |
| 3 | `GET` | `/products/{id}` | عرض منتج واحد | `id` في المسار |
| 4 | `GET` | `/products/stats` | الإحصائيات | بدون معاملات |

### مثال استخدام
```bash
# عرض المنتجات المخفضة
GET /products?on_sale=true&page=1&per_page=20

# البحث
GET /products/search?s=قميص&type=physical

# منتج واحد
GET /products/123
```

---

## 🔵 Physical Products API - المنتجات الفيزيائية

| # | Method | Endpoint | الوصف | نوع المحتوى |
|---|--------|----------|-------|-------------|
| 1 | `POST` | `/physical-products` | إضافة منتج | `multipart/form-data` |
| 2 | `POST` | `/physical-products/{id}` | تعديل منتج | `multipart/form-data` |
| 3 | `GET` | `/physical-products` | عرض الكل | - |
| 4 | `GET` | `/physical-products/{id}` | عرض واحد | - |
| 5 | `GET` | `/physical-products/search` | البحث | - |
| 6 | `DELETE` | `/physical-products/{id}` | حذف | - |
| 7 | `GET` | `/physical-products/stats` | إحصائيات | - |

### مثال إضافة منتج
```bash
curl -X POST "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products" \
  -u "ck_xxxxx:cs_xxxxx" \
  -F "name=قميص قطني" \
  -F "regular_price=200.00" \
  -F "sale_price=150.00" \
  -F "stock_quantity=50" \
  -F "weight=0.3" \
  -F "main_image=@shirt.jpg"
```

### الحقول الأساسية للإضافة

#### معلومات عامة
- `name` ✅ (مطلوب)
- `description`
- `short_description`
- `sku`

#### الأسعار
- `regular_price` ✅ (مطلوب)
- `sale_price`
- `date_on_sale_from`
- `date_on_sale_to`

#### المخزون
- `manage_stock` (true/false)
- `stock_quantity`
- `stock_status` (instock/outofstock/onbackorder)
- `backorders` (no/notify/yes)

#### الشحن
- `weight` (كجم)
- `length` (سم)
- `width` (سم)
- `height` (سم)

#### التصنيفات
- `categories` (مفصولة بفاصلة)
- `tags` (مفصولة بفاصلة)

#### الصور
- `main_image` (ملف)
- `gallery_images[]` (ملفات متعددة)

---

## 🟢 Variable Products API - المنتجات المتغيرة

| # | Method | Endpoint | الوصف | نوع المحتوى |
|---|--------|----------|-------|-------------|
| 1 | `POST` | `/variable-products` | إضافة منتج | `multipart/form-data` |
| 2 | `POST` | `/variable-products/{id}` | تعديل منتج | `multipart/form-data` |
| 3 | `GET` | `/variable-products` | عرض الكل | - |
| 4 | `GET` | `/variable-products/{id}` | عرض واحد | - |
| 5 | `GET` | `/variable-products/search` | البحث | - |
| 6 | `DELETE` | `/variable-products/{id}` | حذف | - |
| 7 | `GET` | `/variable-products/stats` | إحصائيات | - |

### مثال إضافة منتج متغير
```bash
curl -X POST "https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products" \
  -u "ck_xxxxx:cs_xxxxx" \
  -F "name=بنطلون جينز" \
  -F "sku=JEANS-001" \
  -F 'attributes=[{"name":"المقاس","options":["S","M","L"]},{"name":"اللون","options":["أزرق","أسود"]}]' \
  -F 'variations=[{"attributes":{"المقاس":"S","اللون":"أزرق"},"sku":"JEANS-S-BLUE","regular_price":"180.00","stock_quantity":15}]' \
  -F "main_image=@jeans.jpg"
```

### شكل JSON للـ Attributes
```json
[
  {
    "name": "المقاس",
    "options": ["S", "M", "L", "XL"]
  },
  {
    "name": "اللون",
    "options": ["أزرق", "أسود"]
  }
]
```

### شكل JSON للـ Variations
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
  }
]
```

---

## 🎯 معاملات الفلترة الشائعة

### للصفحات (Pagination)
```
?page=1&per_page=10
```

### حسب النوع
```
?type=all          # جميع الأنواع
?type=physical     # فيزيائية فقط
?type=variable     # متغيرة فقط
```

### حسب الحالة
```
?status=publish    # منشورة (افتراضي)
?status=draft      # مسودة
?status=any        # جميع الحالات
```

### المنتجات الخاصة
```
?featured=true     # المميزة
?on_sale=true      # المخفضة
```

### حسب التصنيف
```
?category=clothing        # slug التصنيف
```

### الترتيب
```
?orderby=date&order=DESC     # حسب التاريخ (الأحدث)
?orderby=price&order=ASC     # حسب السعر (الأقل)
?orderby=title&order=ASC     # حسب الاسم (أبجدي)
?orderby=popularity          # حسب الشعبية
?orderby=rating              # حسب التقييم
```

---

## 📊 أمثلة متقدمة

### 1. المنتجات الفيزيائية المخفضة مرتبة بالسعر الأقل
```bash
GET /products?type=physical&on_sale=true&orderby=price&order=ASC&page=1&per_page=20
```

### 2. المنتجات المتغيرة المميزة في تصنيف معين
```bash
GET /products?type=variable&featured=true&category=clothing&page=1
```

### 3. البحث في المنتجات الفيزيائية فقط
```bash
GET /products/search?s=هاتف&type=physical&page=1
```

### 4. أول 50 منتج (للتطبيقات)
```bash
GET /products?page=1&per_page=50&orderby=date&order=DESC
```

### 5. المنتجات في تصنيف معين مرتبة بالتقييم
```bash
GET /products?category=electronics&orderby=rating&order=DESC
```

---

## 🔍 البحث السريع

### في جميع المنتجات
```bash
GET /products/search?s=كلمة_البحث
```

### في الفيزيائية فقط
```bash
GET /physical-products/search?s=كلمة_البحث
```

### في المتغيرة فقط
```bash
GET /variable-products/search?s=كلمة_البحث
```

### بحث مع صفحات
```bash
GET /products/search?s=كلمة_البحث&page=1&per_page=20
```

---

## ❌ أكواد الأخطاء الشائعة

| الكود | الحالة | الوصف | الحل |
|------|--------|-------|------|
| `woo_api_auth_missing` | 401 | مفاتيح API غير موجودة | أضف Consumer Key & Secret |
| `woo_api_auth_invalid_key` | 401 | مفتاح خاطئ | تحقق من صحة المفاتيح |
| `woo_api_auth_insufficient_permissions` | 403 | صلاحيات غير كافية | استخدم مفتاح بصلاحيات `read_write` |
| `product_not_found` | 404 | منتج غير موجود | تحقق من رقم المنتج |
| `product_creation_failed` | 500 | فشل الإنشاء | تحقق من البيانات المرسلة |

---

## 📱 مثال باستخدام JavaScript

### Fetch API
```javascript
const username = 'ck_xxxxx';
const password = 'cs_xxxxx';
const credentials = btoa(`${username}:${password}`);

// عرض المنتجات
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=10', {
  headers: {
    'Authorization': `Basic ${credentials}`
  }
})
.then(response => response.json())
.then(data => console.log(data));

// إضافة منتج
const formData = new FormData();
formData.append('name', 'منتج جديد');
formData.append('regular_price', '100.00');
formData.append('stock_quantity', '20');

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

### Axios
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'https://dev.murjan.sa/wp-json/murjan-api/v1',
  auth: {
    username: 'ck_xxxxx',
    password: 'cs_xxxxx'
  }
});

// عرض المنتجات
api.get('/products', {
  params: {
    page: 1,
    per_page: 10,
    on_sale: true
  }
})
.then(response => console.log(response.data));

// إضافة منتج
const formData = new FormData();
formData.append('name', 'منتج جديد');
formData.append('regular_price', '100.00');

api.post('/physical-products', formData)
  .then(response => console.log(response.data));
```

---

## 🐍 مثال باستخدام Python

```python
import requests
from requests.auth import HTTPBasicAuth

# المصادقة
auth = HTTPBasicAuth(
    'ck_xxxxx',
    'cs_xxxxx'
)

base_url = 'https://dev.murjan.sa/wp-json/murjan-api/v1'

# عرض المنتجات
response = requests.get(
    f'{base_url}/products',
    auth=auth,
    params={
        'page': 1,
        'per_page': 10,
        'on_sale': True
    }
)
print(response.json())

# إضافة منتج
files = {
    'main_image': open('shirt.jpg', 'rb')
}
data = {
    'name': 'قميص جديد',
    'regular_price': '100.00',
    'stock_quantity': '20'
}

response = requests.post(
    f'{base_url}/physical-products',
    auth=auth,
    data=data,
    files=files
)
print(response.json())
```

---

## 🔄 شكل Response الأساسي

### نجاح (Success)
```json
{
  "success": true,
  "total": 10,
  "current_page": 1,
  "products": [...]
}
```

### خطأ (Error)
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

## 📖 روابط مفيدة

- [التوثيق الكامل](./API-COMPLETE-DOCUMENTATION.md) - شرح تفصيلي لكل endpoint
- [Postman Collection](./postman/) - مجموعة جاهزة للاختبار
- [README](./README.md) - دليل التثبيت

---

**آخر تحديث:** 27 يناير 2024  
**الإصدار:** 1.0.0

