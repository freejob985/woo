# 🟣 All Products API - دليل كامل

<div dir="rtl">

## 📌 نظرة عامة

**API جديد وقوي** تم تطويره خصيصاً لعرض جميع المنتجات (الفيزيائية والمتغيرة) في WooCommerce من خلال روت واحد موحد مع دعم كامل للصفحات، البحث، والفلترة المتقدمة.

---

## 🚀 الميزات الرئيسية

| الميزة | الوصف | الحالة |
|--------|-------|--------|
| 🔄 **Unified API** | روت واحد لجميع المنتجات | ✅ |
| 📄 **Pagination** | تعدد صفحات احترافي | ✅ |
| 🔍 **Advanced Search** | بحث مع صفحات وفلترة | ✅ |
| 🎯 **Smart Filtering** | فلترة متقدمة متعددة | ✅ |
| 📊 **Statistics** | إحصائيات شاملة | ✅ |
| 🖼️ **Complete Data** | جميع الحقول والصور | ✅ |
| 🔐 **Secure** | مصادقة WooCommerce | ✅ |
| 📖 **Documented** | توثيق كامل بالعربي | ✅ |

---

## 📡 الروابط (Endpoints)

### 1. عرض جميع المنتجات
```
GET /wp-json/murjan-api/v1/products
```
- دعم كامل للصفحات (pagination)
- فلترة حسب النوع، الحالة، التصنيف
- المنتجات المميزة والمخفضة
- ترتيب ديناميكي

### 2. البحث في المنتجات
```
GET /wp-json/murjan-api/v1/products/search
```
- بحث في جميع المنتجات
- دعم الصفحات
- ترتيب النتائج
- فلترة حسب النوع

### 3. عرض منتج واحد
```
GET /wp-json/murjan-api/v1/products/{id}
```
- تفاصيل كاملة
- يدعم جميع أنواع المنتجات

### 4. الإحصائيات الشاملة
```
GET /wp-json/murjan-api/v1/products/stats
```
- نظرة عامة على المتجر
- حالة المخزون
- المبيعات والأسعار

---

## 📦 الملفات المتوفرة

### الملفات التقنية
```
📁 api/
├── 📄 includes/
│   └── class-all-products-api.php          # الكلاس الرئيسي
├── 📄 woo-products-api.php                 # تم تحديثه
```

### ملفات التوثيق (7 ملفات)
```
📁 api/
├── 📖 ALL-PRODUCTS-API-GUIDE-AR.md         # الدليل الشامل (500+ سطر)
├── 🆕 NEW-ALL-PRODUCTS-API.md              # ملخص الميزات الجديدة
├── 🚀 START-HERE-PRODUCTS-API.md           # دليل البداية السريعة
├── 📝 CHANGELOG-v1.1.0.md                  # سجل التحديثات التفصيلي
├── 📋 SUMMARY-AR.md                        # الملخص العام
├── 📘 README-ALL-PRODUCTS-API.md           # هذا الملف
```

### Postman Collection
```
📁 api/postman/
└── All-Products-API.postman_collection.json  # 30+ مثال جاهز
```

---

## ⚡ البداية السريعة

### 1. الاختبار الفوري
```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=10" \
  -u "ck_2210fb8d333da5da151029715a85618a4b283a52:cs_7f1073e18d0af70d01c84692ce8c04609a722b5c"
```

### 2. استيراد Postman Collection
1. افتح Postman
2. Import → Choose Files
3. اختر: `api/postman/All-Products-API.postman_collection.json`
4. جرّب الأمثلة الـ 30+ جاهزة!

### 3. مثال JavaScript
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

## 🎯 أمثلة استخدام شائعة

### 1. الصفحة الأولى (10 منتجات)
```
GET /products?page=1&per_page=10
```

### 2. المنتجات الفيزيائية فقط
```
GET /products?type=physical&page=1
```

### 3. المنتجات المخفضة مرتبة بالسعر
```
GET /products?on_sale=true&orderby=price&order=ASC
```

### 4. البحث عن كلمة
```
GET /products/search?s=قميص&page=1&per_page=10
```

### 5. المنتجات المميزة في تصنيف معين
```
GET /products?featured=true&category=electronics
```

### 6. إحصائيات المتجر الكاملة
```
GET /products/stats
```

---

## 📖 المعاملات المتاحة (Query Parameters)

### معاملات الصفحات
| المعامل | القيمة الافتراضية | الوصف |
|---------|-------------------|-------|
| `page` | 1 | رقم الصفحة الحالية |
| `per_page` | 10 | عدد المنتجات في الصفحة |

### معاملات الفلترة
| المعامل | القيم | الوصف |
|---------|------|-------|
| `type` | all, physical, variable | نوع المنتج |
| `status` | publish, draft, any | حالة المنتج |
| `featured` | true | المنتجات المميزة فقط |
| `on_sale` | true | المنتجات المخفضة فقط |
| `category` | slug | فلترة حسب التصنيف |

### معاملات الترتيب
| المعامل | القيم | الوصف |
|---------|------|-------|
| `orderby` | date, title, price, popularity, rating | حسب |
| `order` | ASC, DESC | اتجاه الترتيب |

---

## 📊 هيكل الاستجابة

### استجابة عرض المنتجات
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
    "featured": null,
    "on_sale": null
  },
  "products": [
    {
      "id": 123,
      "name": "منتج تجريبي",
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
        "main_image": {...},
        "gallery": [...]
      },
      "categories": [...],
      "tags": [...],
      "weight": "0.5",
      "dimensions": {...}
    }
  ]
}
```

---

## 🔐 المصادقة (Authentication)

استخدم مفاتيح WooCommerce API:

### في cURL
```bash
-u "ck_XXXXX:cs_XXXXX"
```

### في JavaScript
```javascript
headers: {
  'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
}
```

### في Postman
```
Authorization → Basic Auth
Username: Consumer Key
Password: Consumer Secret
```

---

## 🎓 أمثلة كود كاملة

### React - قائمة المنتجات مع Pagination
```jsx
import { useState, useEffect } from 'react';

function ProductsList() {
  const [products, setProducts] = useState([]);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(0);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    loadProducts(page);
  }, [page]);

  const loadProducts = async (pageNum) => {
    setLoading(true);
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
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
          
          <Pagination
            current={page}
            total={totalPages}
            onChange={setPage}
          />
        </>
      )}
    </div>
  );
}
```

### React - البحث المباشر
```jsx
import { useState, useEffect } from 'react';

function SearchProducts() {
  const [searchTerm, setSearchTerm] = useState('');
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (searchTerm.length < 2) {
      setResults([]);
      return;
    }

    const timer = setTimeout(() => {
      searchProducts(searchTerm);
    }, 500); // debounce

    return () => clearTimeout(timer);
  }, [searchTerm]);

  const searchProducts = async (term) => {
    setLoading(true);
    try {
      const response = await fetch(
        `https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=${encodeURIComponent(term)}`,
        {
          headers: {
            'Authorization': 'Basic ' + btoa('ck_XXX:cs_XXX')
          }
        }
      );
      const data = await response.json();
      setResults(data.products);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <input
        type="text"
        value={searchTerm}
        onChange={(e) => setSearchTerm(e.target.value)}
        placeholder="ابحث عن منتج..."
      />
      
      {loading && <p>جاري البحث...</p>}
      
      <div className="search-results">
        {results.map(product => (
          <SearchResultItem key={product.id} product={product} />
        ))}
      </div>
    </div>
  );
}
```

---

## 📚 أين تبدأ؟

### للمبتدئين
1. ✅ اقرأ: **`START-HERE-PRODUCTS-API.md`**
2. ✅ استورد: **Postman Collection**
3. ✅ جرّب الأمثلة الجاهزة

### للمطورين
1. ✅ راجع: **`ALL-PRODUCTS-API-GUIDE-AR.md`**
2. ✅ استخدم أمثلة الكود الكاملة
3. ✅ ابدأ التطوير مباشرة

### للإشراف الفني
1. ✅ راجع: **`CHANGELOG-v1.1.0.md`**
2. ✅ افحص: **`SUMMARY-AR.md`**
3. ✅ راجع الكود: **`class-all-products-api.php`**

---

## 🔄 الفرق بين الـ APIs

| الميزة | APIs القديمة | All Products API (جديد) |
|--------|--------------|------------------------|
| الروت | `/physical-products`<br>`/variable-products` | `/products` |
| عرض الكل معاً | ❌ (استدعاءان منفصلان) | ✅ (استدعاء واحد) |
| الفلترة المتقدمة | محدودة | ✅ شاملة |
| البحث | منفصل لكل نوع | ✅ موحد للكل |
| الإحصائيات | منفصلة | ✅ شاملة |
| التوثيق | جيد | ✅ ممتاز |

> **ملاحظة:** الـ APIs القديمة لا تزال تعمل بشكل كامل ولن تتأثر.

---

## 🎯 حالات الاستخدام الحقيقية

### 1. متجر إلكتروني
- صفحة المنتجات الرئيسية مع pagination
- صفحة البحث الديناميكية
- صفحات التصنيفات
- صفحة العروض الخاصة والتخفيضات

### 2. تطبيق موبايل
- عرض المنتجات بالصفحات (Lazy Loading)
- بحث فوري (Live Search)
- فلترة متقدمة
- عرض الإحصائيات في Dashboard

### 3. لوحة تحكم إدارية
- نظرة عامة على المخزون
- إحصائيات المبيعات
- المنتجات الأكثر مبيعاً
- تتبع المخزون المنخفض

---

## 🚀 نصائح الأداء

### 1. استخدم per_page المناسب
- **الصفحة الرئيسية:** 12-20 منتج
- **البحث:** 10 منتجات
- **الموبايل:** 8 منتجات

### 2. Lazy Loading
```javascript
// تحميل تدريجي عند السكرول
const loadMore = async () => {
  const nextPage = currentPage + 1;
  const moreProducts = await fetchProducts(nextPage);
  setProducts([...products, ...moreProducts.products]);
  setCurrentPage(nextPage);
};
```

### 3. Caching
```javascript
// تخزين مؤقت للصفحات
const cache = new Map();

const fetchWithCache = async (page) => {
  const key = `products_${page}`;
  if (cache.has(key)) return cache.get(key);
  
  const data = await fetchProducts(page);
  cache.set(key, data);
  return data;
};
```

---

## ❓ أسئلة شائعة (FAQ)

### س: هل يمكن دمج عدة فلاتر معاً؟
**ج:** نعم! مثال:
```
?type=physical&featured=true&on_sale=true&orderby=price&order=ASC
```

### س: كم العدد الأقصى لـ per_page؟
**ج:** يُنصح بـ 50 منتج كحد أقصى لضمان الأداء.

### س: هل يدعم RTL (Right-to-Left)؟
**ج:** نعم، جميع النصوص تدعم العربية والـ RTL.

### س: ماذا عن الـ APIs القديمة؟
**ج:** لا تزال تعمل بشكل كامل ولن تتأثر.

### س: هل يحتاج لإعدادات خاصة؟
**ج:** لا، جاهز للعمل فوراً بعد رفع الملفات.

---

## 🛠️ المتطلبات التقنية

- ✅ WordPress 5.8+
- ✅ WooCommerce 5.0+
- ✅ PHP 7.4+
- ✅ مفاتيح WooCommerce API

---

## 📞 الدعم والمساعدة

### الاتصال
- 📧 **Email:** support@murjan.sa
- 🌐 **Website:** https://dev.murjan.sa

### الملفات التوثيقية
- 📖 الدليل الشامل: `ALL-PRODUCTS-API-GUIDE-AR.md`
- 🚀 البداية السريعة: `START-HERE-PRODUCTS-API.md`
- 📝 سجل التحديثات: `CHANGELOG-v1.1.0.md`
- 📋 الملخص: `SUMMARY-AR.md`

### Postman
- 📦 Collection: `postman/All-Products-API.postman_collection.json`
- 30+ مثال جاهز للاستخدام

---

## 🎉 الخلاصة

تم تطوير **All Products API** ليكون:
- ✅ **موحد** - روت واحد لجميع المنتجات
- ✅ **قوي** - فلترة وبحث وترتيب متقدم
- ✅ **سريع** - محسّن للأداء
- ✅ **موثق** - توثيق كامل بالعربي
- ✅ **سهل** - أمثلة جاهزة وPostman Collection
- ✅ **آمن** - مصادقة WooCommerce

---

## 📅 معلومات الإصدار

- **الإصدار:** 1.1.0
- **تاريخ الإصدار:** 26 أكتوبر 2024
- **الفريق:** Murjan Development Team
- **الترخيص:** proprietary

---

## ✅ قائمة التحقق

- [ ] قرأت README (هذا الملف)
- [ ] قرأت START-HERE-PRODUCTS-API.md
- [ ] استوردت Postman Collection
- [ ] جربت الأمثلة في Postman
- [ ] راجعت الدليل الكامل
- [ ] نفذت مثال كود
- [ ] جاهز للإنتاج! 🚀

---

<div align="center">

**🎊 استمتع بالتطوير مع All Products API! 🎊**

---

صُنع بـ ❤️ بواسطة Murjan Development Team

</div>

</div>

