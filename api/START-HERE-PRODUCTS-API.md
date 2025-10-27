# 🚀 البداية السريعة - All Products API

## 📌 مقدمة سريعة

تم إضافة **API جديد** يسمح لك بعرض جميع المنتجات (الفيزيائية والمتغيرة) في مكان واحد مع دعم كامل للصفحات والبحث والفلترة!

---

## ⚡ الاستخدام السريع

### الرابط الأساسي
```
https://dev.murjan.sa/wp-json/murjan-api/v1/products
```

### مثال بسيط (cURL)
```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=10" \
  -u "ck_2210fb8d333da5da151029715a85618a4b283a52:cs_7f1073e18d0af70d01c84692ce8c04609a722b5c"
```

### مثال JavaScript
```javascript
const response = await fetch(
  'https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=10',
  {
    headers: {
      'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
    }
  }
);
const data = await response.json();
console.log(data.products);
```

---

## 🎯 الروابط الأساسية الأربعة

| # | الرابط | الوصف | مثال |
|---|--------|-------|------|
| 1 | `GET /products` | عرض المنتجات مع الصفحات | `?page=1&per_page=10` |
| 2 | `GET /products/search` | البحث في المنتجات | `?s=قميص&page=1` |
| 3 | `GET /products/{id}` | عرض منتج واحد | `/products/123` |
| 4 | `GET /products/stats` | الإحصائيات الشاملة | لا يحتاج معاملات |

---

## 🔥 أمثلة شائعة

### 1. عرض الصفحة الأولى
```
GET /products?page=1&per_page=10
```

### 2. عرض المنتجات الفيزيائية فقط
```
GET /products?type=physical&page=1
```

### 3. عرض المنتجات المخفضة
```
GET /products?on_sale=true
```

### 4. البحث عن كلمة
```
GET /products/search?s=هاتف&page=1&per_page=10
```

### 5. الترتيب حسب السعر (الأقل)
```
GET /products?orderby=price&order=ASC
```

---

## 📦 استيراد Postman Collection

1. افتح Postman
2. اضغط **Import**
3. اختر: `api/postman/All-Products-API.postman_collection.json`
4. ستجد 30+ مثال جاهز! 🎉

---

## 🎓 المعاملات المتاحة

### معاملات الصفحات
- `page` - رقم الصفحة (افتراضي: 1)
- `per_page` - عدد المنتجات (افتراضي: 10)

### معاملات الفلترة
- `type` - النوع: `all`, `physical`, `variable`
- `featured` - المميزة: `true`
- `on_sale` - المخفضة: `true`
- `category` - التصنيف: `electronics`
- `status` - الحالة: `publish`, `draft`, `any`

### معاملات الترتيب
- `orderby` - حسب: `date`, `title`, `price`, `popularity`, `rating`
- `order` - الاتجاه: `ASC`, `DESC`

---

## 📖 التوثيق الكامل

للحصول على التوثيق الشامل، راجع:

### 📘 الأدلة
- **`ALL-PRODUCTS-API-GUIDE-AR.md`** - الدليل الكامل (200+ سطر)
- **`NEW-ALL-PRODUCTS-API.md`** - ملخص الميزات الجديدة
- **`CHANGELOG-v1.1.0.md`** - سجل التحديثات التفصيلي

### 📦 ملفات Postman
- **`postman/All-Products-API.postman_collection.json`** - جميع الأمثلة

### 💻 الكود
- **`includes/class-all-products-api.php`** - الكلاس الرئيسي

---

## 🔐 المصادقة

استخدم مفاتيح WooCommerce API:

```bash
Username: ck_2210fb8d333da5da151029715a85618a4b283a52
Password: cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
```

### في Postman:
1. Authorization → Basic Auth
2. Username: Consumer Key
3. Password: Consumer Secret

---

## ✅ مثال كامل مع React

```jsx
import { useState, useEffect } from 'react';

function ProductsList() {
  const [products, setProducts] = useState([]);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(0);

  useEffect(() => {
    fetchProducts(page);
  }, [page]);

  const fetchProducts = async (pageNum) => {
    try {
      const response = await fetch(
        `https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=${pageNum}&per_page=12`,
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
      console.error('Error:', error);
    }
  };

  return (
    <div>
      <div className="products-grid">
        {products.map(product => (
          <div key={product.id} className="product-card">
            <img src={product.images.main_image.src} alt={product.name} />
            <h3>{product.name}</h3>
            <p>{product.price_html}</p>
            <span>{product.type === 'variable' ? 'متغير' : 'فيزيائي'}</span>
          </div>
        ))}
      </div>
      
      <div className="pagination">
        <button 
          onClick={() => setPage(p => Math.max(1, p - 1))}
          disabled={page === 1}
        >
          السابق
        </button>
        <span>الصفحة {page} من {totalPages}</span>
        <button 
          onClick={() => setPage(p => Math.min(totalPages, p + 1))}
          disabled={page === totalPages}
        >
          التالي
        </button>
      </div>
    </div>
  );
}

export default ProductsList;
```

---

## 🎨 مثال Search Component

```jsx
import { useState } from 'react';

function SearchProducts() {
  const [searchTerm, setSearchTerm] = useState('');
  const [results, setResults] = useState([]);

  const handleSearch = async (term) => {
    if (term.length < 2) return;
    
    const response = await fetch(
      `https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=${encodeURIComponent(term)}&per_page=10`,
      {
        headers: {
          'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
        }
      }
    );
    const data = await response.json();
    setResults(data.products);
  };

  return (
    <div>
      <input
        type="text"
        value={searchTerm}
        onChange={(e) => {
          setSearchTerm(e.target.value);
          handleSearch(e.target.value);
        }}
        placeholder="ابحث عن منتج..."
      />
      
      <div className="search-results">
        {results.map(product => (
          <div key={product.id}>
            <h4>{product.name}</h4>
            <p>{product.price}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
```

---

## 🚀 نصائح الأداء

### 1. استخدم per_page المناسب
```javascript
// للصفحة الرئيسية
?per_page=20

// للموبايل
?per_page=8

// للبحث
?per_page=10
```

### 2. Lazy Loading
```javascript
// تحميل المزيد عند السكرول
const loadMore = async () => {
  const nextPage = currentPage + 1;
  const moreProducts = await fetchProducts(nextPage);
  setProducts([...products, ...moreProducts.products]);
};
```

### 3. Caching
```javascript
// تخزين مؤقت للصفحات
const cache = new Map();

const fetchWithCache = async (page) => {
  if (cache.has(page)) {
    return cache.get(page);
  }
  const data = await fetchProducts(page);
  cache.set(page, data);
  return data;
};
```

---

## ❓ أسئلة شائعة

### س: هل يمكن دمج عدة فلاتر؟
نعم! مثال:
```
?type=physical&featured=true&on_sale=true&orderby=price&order=ASC
```

### س: ما الفرق بين هذا API والـ APIs القديمة؟
الـ APIs القديمة `/physical-products` و `/variable-products` لا تزال تعمل.  
الـ API الجديد `/products` يجمعهما في مكان واحد مع ميزات أكثر.

### س: هل يدعم RTL (Right-to-Left)؟
نعم، جميع النصوص والبيانات تدعم اللغة العربية والـ RTL.

### س: كيف أحصل على إحصائيات المتجر؟
```
GET /products/stats
```

---

## 📞 الدعم

- 📧 **Email:** support@murjan.sa
- 🌐 **Website:** https://dev.murjan.sa
- 📖 **Docs:** راجع `ALL-PRODUCTS-API-GUIDE-AR.md`

---

## 🎉 خطوات البداية

1. ✅ استورد Postman Collection
2. ✅ جرب الأمثلة الجاهزة
3. ✅ راجع التوثيق الكامل
4. ✅ ابدأ التطوير!

---

**تحديث:** 26 أكتوبر 2024  
**الإصدار:** 1.1.0  
**الفريق:** Murjan Development Team

---

## 🔗 روابط سريعة

- 📘 [الدليل الكامل](ALL-PRODUCTS-API-GUIDE-AR.md)
- 🆕 [ملخص الميزات](NEW-ALL-PRODUCTS-API.md)
- 📝 [سجل التحديثات](CHANGELOG-v1.1.0.md)
- 📦 [Postman Collection](postman/All-Products-API.postman_collection.json)

---

**🚀 استمتع بالتطوير!**

