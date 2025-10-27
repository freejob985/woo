# 🎉 ميزة جديدة: All Products API

## ✨ ما الجديد؟

تم إضافة **API جديد وموحد** لعرض جميع المنتجات (الفيزيائية والمتغيرة) في مكان واحد!

### 🚀 الميزات

- ✅ **روت واحد لجميع المنتجات**: `/products`
- ✅ **تعدد صفحات احترافي**: دعم كامل للـ pagination
- ✅ **بحث متقدم**: مع دعم الصفحات
- ✅ **فلترة ذكية**: حسب النوع، الحالة، التصنيف، المميزة، المخفضة
- ✅ **إحصائيات شاملة**: جميع البيانات في مكان واحد

---

## 📡 الروابط الجديدة

### 1. عرض جميع المنتجات
```
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products
```

**المعاملات المدعومة:**
- `page` - رقم الصفحة
- `per_page` - عدد المنتجات لكل صفحة
- `type` - النوع: `all`, `physical`, `variable`
- `orderby` - الترتيب: `date`, `title`, `price`
- `featured` - المميزة فقط: `true`
- `on_sale` - المخفضة فقط: `true`
- `category` - حسب التصنيف

### 2. البحث في جميع المنتجات
```
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=كلمة_البحث
```

### 3. عرض منتج واحد
```
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/{id}
```

### 4. الإحصائيات الشاملة
```
GET https://dev.murjan.sa/wp-json/murjan-api/v1/products/stats
```

---

## 📋 أمثلة سريعة

### مثال 1: الصفحة الأولى
```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=10" \
  -u "ck_XXX:cs_XXX"
```

### مثال 2: المنتجات الفيزيائية فقط
```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products?type=physical" \
  -u "ck_XXX:cs_XXX"
```

### مثال 3: المنتجات المخفضة
```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products?on_sale=true" \
  -u "ck_XXX:cs_XXX"
```

### مثال 4: البحث مع الصفحات
```bash
curl -X GET "https://dev.murjan.sa/wp-json/murjan-api/v1/products/search?s=قميص&page=1&per_page=10" \
  -u "ck_XXX:cs_XXX"
```

---

## 📦 الملفات الجديدة

1. **`includes/class-all-products-api.php`** - الكلاس الرئيسي
2. **`ALL-PRODUCTS-API-GUIDE-AR.md`** - الدليل الكامل بالعربي
3. **`postman/All-Products-API.postman_collection.json`** - مجموعة Postman جديدة

---

## 📥 استيراد Postman Collection

1. افتح Postman
2. اضغط **Import**
3. اختر الملف: `api/postman/All-Products-API.postman_collection.json`
4. ستجد جميع الأمثلة جاهزة للاستخدام!

---

## 🎯 حالات الاستخدام

### للمتاجر الإلكترونية
```javascript
// صفحة المنتجات الرئيسية
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?page=1&per_page=20')
```

### للتطبيقات
```javascript
// تحميل جميع المنتجات
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=50')
```

### للعروض الخاصة
```javascript
// المنتجات المخفضة + المميزة
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?featured=true&on_sale=true')
```

---

## 🔒 المصادقة

استخدم نفس مفاتيح WooCommerce API:
- **Consumer Key**: `ck_2210fb8d333da5da151029715a85618a4b283a52`
- **Consumer Secret**: `cs_7f1073e18d0af70d01c84692ce8c04609a722b5c`

---

## 📖 المزيد من التفاصيل

راجع الدليل الكامل: **`ALL-PRODUCTS-API-GUIDE-AR.md`**

---

## 🎊 ملاحظة

الـ APIs القديمة لا تزال تعمل:
- `/physical-products` - للمنتجات الفيزيائية
- `/variable-products` - للمنتجات المتغيرة

الآن لديك خيار استخدام `/products` للحصول على الكل معاً! 🚀

