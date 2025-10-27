# 🟣 دليل استخدام All Products API

## نظرة عامة

تم إضافة **API جديد** لعرض جميع المنتجات (الفيزيائية والمتغيرة) في مكان واحد مع دعم كامل للصفحات، البحث، والفلترة المتقدمة.

### 🎯 الميزات الرئيسية

- ✅ عرض جميع المنتجات (فيزيائية + متغيرة) في استدعاء واحد
- ✅ تعدد صفحات (Pagination) محترف
- ✅ بحث متقدم مع تعدد الصفحات
- ✅ فلترة حسب النوع، الحالة، التصنيف
- ✅ دعم المنتجات المميزة والمخفضة
- ✅ إحصائيات شاملة

---

## 📡 الروابط المتاحة

### الرابط الأساسي
```
https://dev.murjan.sa/wp-json/murjan-api/v1/products
```

---

## 1️⃣ عرض جميع المنتجات مع الصفحات

### الرابط
```
GET /products
```

### المعاملات المتاحة

| المعامل | القيمة الافتراضية | الوصف |
|---------|-------------------|-------|
| `page` | 1 | رقم الصفحة الحالية |
| `per_page` | 10 | عدد المنتجات في كل صفحة |
| `type` | all | نوع المنتج: `all`, `physical`, `variable` |
| `status` | publish | حالة المنتج: `publish`, `draft`, `any` |
| `orderby` | date | الترتيب حسب: `date`, `title`, `price`, `popularity`, `rating` |
| `order` | DESC | اتجاه الترتيب: `ASC`, `DESC` |
| `featured` | - | `true` للمنتجات المميزة فقط |
| `on_sale` | - | `true` للمنتجات المخفضة فقط |
| `category` | - | فلترة حسب slug التصنيف |

### 📋 أمثلة الاستخدام

#### مثال 1: عرض جميع المنتجات (الصفحة الأولى)
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=10
```

#### مثال 2: عرض المنتجات الفيزيائية فقط
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?type=physical&page=1&per_page=20
```

#### مثال 3: عرض المنتجات المتغيرة فقط
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?type=variable&page=1
```

#### مثال 4: عرض المنتجات المميزة
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?featured=true
```

#### مثال 5: عرض المنتجات المخفضة
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?on_sale=true&page=1
```

#### مثال 6: فلترة حسب التصنيف
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?category=electronics&page=1
```

#### مثال 7: ترتيب حسب السعر
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?orderby=price&order=ASC
```

#### مثال 8: دمج عدة فلاتر
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?type=physical&featured=true&on_sale=true&orderby=price&order=DESC&page=1&per_page=15
```

### 📥 الاستجابة

```json
{
  "success": true,
  "total": 25,
  "total_products": 100,
  "total_pages": 10,
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
      "name": "منتج تجريبي",
      "slug": "test-product",
      "type": "simple",
      "is_physical": true,
      "price": "99.00",
      "regular_price": "120.00",
      "sale_price": "99.00",
      "on_sale": true,
      "stock_status": "instock",
      "stock_quantity": 50,
      "featured": true,
      "images": {
        "main_image": {
          "id": 456,
          "src": "https://dev.murjan.sa/wp-content/uploads/2024/01/image.jpg",
          "thumbnail": "https://dev.murjan.sa/wp-content/uploads/2024/01/image-150x150.jpg"
        },
        "gallery": []
      },
      "categories": [
        {
          "id": 15,
          "name": "إلكترونيات",
          "slug": "electronics"
        }
      ],
      "weight": "0.5",
      "dimensions": {
        "length": "10",
        "width": "5",
        "height": "3"
      }
    }
  ]
}
```

---

## 2️⃣ البحث في جميع المنتجات

### الرابط
```
GET /products/search
```

### المعاملات المطلوبة

| المعامل | الوصف |
|---------|-------|
| `s` | كلمة البحث (مطلوب) |
| `page` | رقم الصفحة (افتراضي: 1) |
| `per_page` | عدد النتائج (افتراضي: 10) |
| `type` | نوع المنتج: `all`, `physical`, `variable` |
| `orderby` | الترتيب: `relevance`, `date`, `title`, `price` |

### 📋 أمثلة الاستخدام

#### مثال 1: البحث البسيط
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=قميص
```

#### مثال 2: البحث في المنتجات الفيزيائية فقط
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=قميص&type=physical
```

#### مثال 3: البحث مع الصفحات
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=قميص&page=2&per_page=20
```

#### مثال 4: البحث مع الترتيب حسب السعر
```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=قميص&orderby=price
```

### 📥 الاستجابة

```json
{
  "success": true,
  "search_term": "قميص",
  "total": 15,
  "total_results": 45,
  "total_pages": 5,
  "current_page": 1,
  "per_page": 10,
  "type_filter": "all",
  "products": [
    {
      "id": 789,
      "name": "قميص رجالي كلاسيكي",
      "type": "variable",
      "price": "150.00",
      "variations_count": 12,
      "attributes": [
        {
          "name": "المقاس",
          "options": ["S", "M", "L", "XL"]
        },
        {
          "name": "اللون",
          "options": ["أبيض", "أزرق", "أسود"]
        }
      ]
    }
  ]
}
```

---

## 3️⃣ عرض منتج واحد (أي نوع)

### الرابط
```
GET /products/{id}
```

### 📋 مثال الاستخدام

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/123
```

### 📥 الاستجابة

```json
{
  "success": true,
  "product": {
    "id": 123,
    "name": "منتج تجريبي",
    "type": "simple",
    "is_physical": true,
    "description": "وصف كامل للمنتج...",
    "short_description": "وصف مختصر...",
    "sku": "PROD-123",
    "price": "99.00",
    "regular_price": "120.00",
    "sale_price": "99.00",
    "on_sale": true,
    "stock_status": "instock",
    "stock_quantity": 50,
    "manage_stock": true,
    "featured": true,
    "categories": [...],
    "tags": [...],
    "images": {...},
    "weight": "0.5",
    "dimensions": {...},
    "total_sales": 234,
    "average_rating": "4.5",
    "rating_count": 18
  }
}
```

---

## 4️⃣ الإحصائيات الشاملة

### الرابط
```
GET /products/stats
```

### 📋 مثال الاستخدام

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/stats
```

### 📥 الاستجابة

```json
{
  "success": true,
  "statistics": {
    "products_overview": {
      "total_products": 250,
      "physical_products": 150,
      "variable_products": 100,
      "simple_products": 150,
      "total_variations": 1200
    },
    "stock_status": {
      "in_stock": 200,
      "out_of_stock": 30,
      "on_backorder": 20,
      "low_stock": 15
    },
    "sales_info": {
      "on_sale": 75,
      "featured": 50,
      "total_sales": 12450,
      "average_sales_per_product": 49.8
    },
    "pricing": {
      "total_value": "SAR 125,000.00",
      "total_value_raw": 125000,
      "average_price": "SAR 500.00",
      "average_price_raw": 500
    },
    "categories": {
      "total_categories": 25
    }
  }
}
```

---

## 🔐 المصادقة (Authentication)

جميع الروابط تتطلب مصادقة باستخدام **WooCommerce API Keys**:

### طريقة 1: Basic Authentication

```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products" \
  -u "ck_XXXXXXXXXXXXX:cs_XXXXXXXXXXXXX"
```

### طريقة 2: Query Parameters

```bash
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products?consumer_key=ck_XXX&consumer_secret=cs_XXX
```

### طريقة 3: في Postman

1. اذهب إلى **Authorization**
2. اختر **Basic Auth**
3. ضع:
   - Username: `Consumer Key`
   - Password: `Consumer Secret`

---

## 🎨 أمثلة متقدمة

### مثال 1: تطبيق متجر مع صفحات

```javascript
// صفحة المنتجات الرئيسية
const fetchProducts = async (page = 1) => {
  const response = await fetch(
    `https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=${page}&per_page=20`,
    {
      headers: {
        'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
      }
    }
  );
  return await response.json();
};

// استخدام الدالة
const data = await fetchProducts(1);
console.log(`عرض ${data.products.length} من أصل ${data.total_products} منتج`);
console.log(`الصفحة ${data.current_page} من ${data.total_pages}`);
```

### مثال 2: البحث المباشر

```javascript
// بحث مع تأخير (debounce)
const searchProducts = async (searchTerm) => {
  const response = await fetch(
    `https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=${encodeURIComponent(searchTerm)}&per_page=10`,
    {
      headers: {
        'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
      }
    }
  );
  return await response.json();
};
```

### مثال 3: عرض المنتجات المخفضة

```javascript
const getSaleProducts = async () => {
  const response = await fetch(
    'https://dev.murjan.sa/wp-json/murjan-api/v1/products?on_sale=true&orderby=price&order=ASC',
    {
      headers: {
        'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
      }
    }
  );
  return await response.json();
};
```

### مثال 4: تطبيق React مع Pagination

```jsx
import React, { useState, useEffect } from 'react';

const ProductsList = () => {
  const [products, setProducts] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(0);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    loadProducts(currentPage);
  }, [currentPage]);

  const loadProducts = async (page) => {
    setLoading(true);
    try {
      const response = await fetch(
        `https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=${page}&per_page=12`,
        {
          headers: {
            'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
          }
        }
      );
      const data = await response.json();
      setProducts(data.products);
      setTotalPages(data.total_pages);
    } catch (error) {
      console.error('Error loading products:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      {loading ? (
        <p>جاري التحميل...</p>
      ) : (
        <>
          <div className="products-grid">
            {products.map(product => (
              <div key={product.id} className="product-card">
                <img src={product.images.main_image.thumbnail} alt={product.name} />
                <h3>{product.name}</h3>
                <p>{product.price_html}</p>
              </div>
            ))}
          </div>
          
          <div className="pagination">
            <button 
              onClick={() => setCurrentPage(prev => Math.max(1, prev - 1))}
              disabled={currentPage === 1}
            >
              السابق
            </button>
            <span>الصفحة {currentPage} من {totalPages}</span>
            <button 
              onClick={() => setCurrentPage(prev => Math.min(totalPages, prev + 1))}
              disabled={currentPage === totalPages}
            >
              التالي
            </button>
          </div>
        </>
      )}
    </div>
  );
};

export default ProductsList;
```

---

## 🚀 نصائح الأداء

### 1. استخدام Caching

```javascript
// تخزين مؤقت للنتائج
const cache = new Map();

const fetchProductsWithCache = async (page) => {
  const cacheKey = `products_page_${page}`;
  
  if (cache.has(cacheKey)) {
    return cache.get(cacheKey);
  }
  
  const data = await fetchProducts(page);
  cache.set(cacheKey, data);
  
  return data;
};
```

### 2. Lazy Loading للصفحات

```javascript
// تحميل تدريجي عند السكرول
const loadMoreProducts = async () => {
  const nextPage = currentPage + 1;
  const newProducts = await fetchProducts(nextPage);
  setProducts([...products, ...newProducts.products]);
  setCurrentPage(nextPage);
};
```

### 3. تحديد per_page بحكمة

- للصفحات الرئيسية: `per_page=12` أو `20`
- للبحث: `per_page=10`
- للموبايل: `per_page=8`

---

## ❌ معالجة الأخطاء

### أمثلة على الأخطاء المحتملة

```json
// 401: غير مصرح
{
  "code": "unauthorized",
  "message": "Invalid API credentials",
  "status": 401
}

// 404: المنتج غير موجود
{
  "code": "product_not_found",
  "message": "Product not found.",
  "status": 404
}

// 400: معاملات خاطئة
{
  "code": "rest_invalid_param",
  "message": "Invalid parameter(s): page",
  "status": 400
}
```

### معالجة الأخطاء في JavaScript

```javascript
const fetchProducts = async (page) => {
  try {
    const response = await fetch(`https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=${page}`);
    
    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'فشل تحميل المنتجات');
    }
    
    return await response.json();
  } catch (error) {
    console.error('خطأ:', error.message);
    return { success: false, products: [], error: error.message };
  }
};
```

---

## 📞 الدعم

لأي استفسارات أو مشاكل:
- 📧 البريد الإلكتروني: support@murjan.sa
- 🌐 الموقع: https://dev.murjan.sa
- 📖 التوثيق الكامل: راجع ملف `API-DOCUMENTATION-AR.md`

---

## 📝 ملاحظات مهمة

1. ✅ جميع الروابط تدعم HTTPS
2. ✅ الاستجابات بصيغة JSON
3. ✅ دعم كامل للغة العربية
4. ✅ التواريخ بصيغة: `Y-m-d H:i:s`
5. ✅ الأسعار بالعملة المحددة في WooCommerce

---

تم التحديث: 2024
الإصدار: 1.0.0

