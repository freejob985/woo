# ⚡ دليل البداية السريعة

ابدأ باستخدام WooCommerce Products API في 5 دقائق!

---

## 🎯 الهدف

تمكينك من إرسال أول طلب API بنجاح خلال 5 دقائق.

---

## ✅ المتطلبات

- [ ] WordPress مثبت ويعمل
- [ ] WooCommerce مفعّل
- [ ] صلاحيات Administrator
- [ ] Postman مثبت (أو أي أداة API)

---

## 🚀 الخطوات السريعة

### الخطوة 1: تفعيل الإضافة (دقيقة واحدة)

1. ارفع مجلد `api` إلى:
```
wp-content/plugins/woo-products-importer/api/
```

2. اذهب إلى: **الإضافات** > **الإضافات المثبتة**

3. فعّل: **WooCommerce Products API Manager**

✅ **تم!**

---

### الخطوة 2: إنشاء مفاتيح API (دقيقتان)

1. اذهب إلى: **WooCommerce** > **الإعدادات**

2. تبويب: **متقدم** > **REST API**

3. اضغط: **Add key**

4. املأ النموذج:
   ```
   Description: Murjan API
   User: [اختر مستخدم Administrator]
   Permissions: Read/Write
   ```

5. اضغط: **Generate API key**

6. **احفظ المفاتيح في مكان آمن:**
   ```
   Consumer key: ck_xxxxxxxxxxxxxxx
   Consumer secret: cs_xxxxxxxxxxxxxxx
   ```

⚠️ **مهم:** لن تظهر المفاتيح مرة أخرى!

✅ **جاهز!**

---

### الخطوة 3: اختبار API (دقيقتان)

#### باستخدام cURL:

افتح Terminal أو CMD واكتب:

```bash
curl -X GET \
  "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/stats" \
  -u "ck_YOUR_KEY:cs_YOUR_SECRET"
```

**استبدل:**
- `dev.murjan.sa` برابط موقعك
- `ck_YOUR_KEY` بالـ Consumer Key
- `cs_YOUR_SECRET` بالـ Consumer Secret

#### باستخدام المتصفح:

**الطريقة الآمنة:** استخدم Postman (راجع الخطوة 4)

**للاختبار السريع فقط:**
```
https://ck_KEY:cs_SECRET@dev.murjan.sa/wp-json/murjan-api/v1/physical-products/stats
```

⚠️ **تحذير:** لا تستخدم هذه الطريقة في الإنتاج!

#### النتيجة المتوقعة:

```json
{
  "success": true,
  "statistics": {
    "total_products": 0,
    "in_stock": 0,
    "out_of_stock": 0,
    "total_value": "SAR 0",
    "total_stock_quantity": 0,
    "average_price": "SAR 0"
  }
}
```

✅ **رائع! API يعمل بنجاح!**

---

### الخطوة 4: استخدام Postman (اختياري - دقيقة واحدة)

1. افتح Postman

2. Import > Upload Files

3. اختر:
```
api/postman/WooCommerce-Products-API-Collection.postman_collection.json
```

4. في Collection Settings > Authorization:
   - Username: مفتاح Consumer Key
   - Password: مفتاح Consumer Secret

5. جرّب أي طلب من القائمة

✅ **ممتاز!**

---

## 🎓 الخطوات التالية

### 1. أضف منتجك الأول

#### باستخدام cURL:

```bash
curl -X POST \
  "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products" \
  -u "ck_YOUR_KEY:cs_YOUR_SECRET" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "منتجي الأول",
    "regular_price": 99.00,
    "stock_quantity": 50
  }'
```

#### باستخدام Postman:

1. افتح: **Physical Products** > **إضافة منتج فيزيائي جديد**
2. عدّل البيانات في Body حسب حاجتك
3. اضغط: **Send**

**النتيجة:**
```json
{
  "success": true,
  "message": "Product created successfully.",
  "product_id": 123,
  "product": { ... }
}
```

🎉 **مبروك! أول منتج تم إضافته عبر API!**

---

### 2. اعرض المنتج في المتجر

زر موقعك على:
```
https://dev.murjan.sa/shop/
```

ستجد المنتج الجديد يظهر! ✨

---

### 3. جرّب العمليات الأخرى

الآن يمكنك:

✅ **تعديل المنتج:**
```bash
curl -X PUT \
  "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/123" \
  -u "ck_YOUR_KEY:cs_YOUR_SECRET" \
  -H "Content-Type: application/json" \
  -d '{"stock_quantity": 100}'
```

✅ **البحث عن منتج:**
```bash
curl -X GET \
  "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products/search?s=منتجي" \
  -u "ck_YOUR_KEY:cs_YOUR_SECRET"
```

✅ **عرض جميع المنتجات:**
```bash
curl -X GET \
  "https://dev.murjan.sa/wp-json/murjan-api/v1/physical-products" \
  -u "ck_YOUR_KEY:cs_YOUR_SECRET"
```

---

## 📝 مثال سريع: إضافة منتج متغير

```bash
curl -X POST \
  "https://dev.murjan.sa/wp-json/murjan-api/v1/variable-products" \
  -u "ck_YOUR_KEY:cs_YOUR_SECRET" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "تيشيرت رياضي",
    "attributes": [
      {
        "name": "Size",
        "options": ["S", "M", "L", "XL"]
      },
      {
        "name": "Color",
        "options": ["أبيض", "أسود"]
      }
    ],
    "variations": [
      {
        "attributes": {"Size": "M", "Color": "أبيض"},
        "regular_price": 79.00,
        "stock_quantity": 50
      },
      {
        "attributes": {"Size": "L", "Color": "أسود"},
        "regular_price": 79.00,
        "stock_quantity": 40
      }
    ]
  }'
```

---

## 🧩 دمج مع تطبيقك

### JavaScript / Node.js

```javascript
const axios = require('axios');

const api = axios.create({
  baseURL: 'https://dev.murjan.sa/wp-json/murjan-api/v1',
  auth: {
    username: 'ck_YOUR_KEY',
    password: 'cs_YOUR_SECRET'
  }
});

// جلب المنتجات
async function getProducts() {
  const response = await api.get('/physical-products');
  console.log(response.data.products);
}

// إضافة منتج
async function addProduct() {
  const response = await api.post('/physical-products', {
    name: 'منتج جديد',
    regular_price: 199.00,
    stock_quantity: 30
  });
  console.log('تم إضافة المنتج:', response.data.product_id);
}

getProducts();
```

### PHP

```php
<?php
$api_url = 'https://dev.murjan.sa/wp-json/murjan-api/v1';
$consumer_key = 'ck_YOUR_KEY';
$consumer_secret = 'cs_YOUR_SECRET';

function makeRequest($endpoint, $method = 'GET', $data = null) {
    global $api_url, $consumer_key, $consumer_secret;
    
    $ch = curl_init($api_url . $endpoint);
    curl_setopt($ch, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// جلب المنتجات
$products = makeRequest('/physical-products');
print_r($products);

// إضافة منتج
$new_product = makeRequest('/physical-products', 'POST', [
    'name' => 'منتج جديد',
    'regular_price' => 199.00,
    'stock_quantity' => 30
]);
echo "Product ID: " . $new_product['product_id'];
?>
```

### Python

```python
import requests
from requests.auth import HTTPBasicAuth

API_URL = 'https://dev.murjan.sa/wp-json/murjan-api/v1'
AUTH = HTTPBasicAuth('ck_YOUR_KEY', 'cs_YOUR_SECRET')

# جلب المنتجات
response = requests.get(f'{API_URL}/physical-products', auth=AUTH)
products = response.json()
print(products)

# إضافة منتج
new_product = {
    'name': 'منتج جديد',
    'regular_price': 199.00,
    'stock_quantity': 30
}
response = requests.post(f'{API_URL}/physical-products', json=new_product, auth=AUTH)
print('Product ID:', response.json()['product_id'])
```

---

## 🔧 حل المشاكل السريعة

### ❌ خطأ 401 - Unauthorized

**السبب:** مفاتيح API خاطئة

**الحل:**
1. تحقق من Consumer Key و Secret
2. تأكد من نسخها بالكامل (بدون مسافات)
3. جرّب إنشاء مفاتيح جديدة

---

### ❌ خطأ 403 - Forbidden

**السبب:** صلاحيات غير كافية

**الحل:**
1. تأكد من اختيار Read/Write عند إنشاء المفاتيح
2. تأكد من أن المستخدم له صلاحية Administrator

---

### ❌ خطأ 404 - Not Found

**السبب:** الروابط الدائمة غير مفعلة

**الحل:**
1. اذهب إلى: **الإعدادات** > **الروابط الدائمة**
2. اضغط: **حفظ التغييرات**
3. جرّب مرة أخرى

---

### ❌ خطأ 500 - Server Error

**السبب:** خطأ في الإضافة أو البيانات

**الحل:**
1. فعّل WP_DEBUG في wp-config.php
2. راجع ملف الأخطاء (error_log)
3. تأكد من تثبيت WooCommerce بشكل صحيح

---

## 📚 المزيد من المعلومات

للتوثيق الكامل، راجع:

- 📖 [README-AR.md](README-AR.md) - دليل شامل
- 📖 [API-DOCUMENTATION-AR.md](API-DOCUMENTATION-AR.md) - توثيق API
- 📖 [POSTMAN-GUIDE-AR.md](POSTMAN-GUIDE-AR.md) - دليل Postman

---

## 🎯 قائمة مراجعة النجاح

- [ ] الإضافة مفعّلة ✅
- [ ] مفاتيح API تم إنشاؤها ✅
- [ ] أول اختبار نجح (stats) ✅
- [ ] أول منتج تم إضافته ✅
- [ ] المنتج يظهر في المتجر ✅
- [ ] Postman Collection مستورد ✅

**إذا أكملت جميع الخطوات: مبروك! 🎉**

أنت الآن جاهز لاستخدام API بالكامل!

---

## 💬 الدعم

في حالة وجود أي مشاكل:

- 📧 Email: support@murjan.sa
- 🌐 Website: https://dev.murjan.sa
- 📚 Documentation: راجع ملفات التوثيق

---

**ابدأ الآن واستمتع! 🚀**

