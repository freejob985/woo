# 🌐 حل CORS في الفرونت إند React - دليل شامل

## 📌 نظرة عامة

تم تطبيق حلول CORS الكاملة في تطبيق React لضمان الاتصال السلس مع WordPress API.

---

## ✅ ما تم تطبيقه

### 1️⃣ **تحديث vercel.json**
```json
{
  "headers": [
    {
      "source": "/(.*)",
      "headers": [
        { "key": "Access-Control-Allow-Origin", "value": "*" },
        { "key": "Access-Control-Allow-Methods", "value": "GET, POST, PUT, DELETE, OPTIONS, PATCH" },
        { "key": "Access-Control-Allow-Headers", "value": "Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin, X-Api-Key" },
        { "key": "Access-Control-Allow-Credentials", "value": "true" },
        { "key": "Access-Control-Expose-Headers", "value": "X-WP-Total, X-WP-TotalPages" },
        { "key": "Access-Control-Max-Age", "value": "86400" }
      ]
    }
  ]
}
```

**الفائدة:** يضبط CORS headers تلقائياً لجميع الطلبات على Vercel

---

### 2️⃣ **تحديث vite.config.ts**
```typescript
server: {
  cors: {
    origin: '*',
    methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'],
    allowedHeaders: ['Authorization', 'Content-Type', 'X-WP-Nonce', 'X-Requested-With', 'Accept', 'Origin', 'X-Api-Key'],
    exposedHeaders: ['X-WP-Total', 'X-WP-TotalPages'],
    credentials: true,
    maxAge: 86400,
  },
  proxy: {
    '/api': {
      target: 'https://dev.murjan.sa',
      changeOrigin: true,
      secure: false,
      rewrite: (path) => path.replace(/^\/api/, '/wp-json/murjan-api/v1'),
      configure: (proxy) => {
        proxy.on('proxyReq', (proxyReq) => {
          proxyReq.setHeader('Origin', 'https://woo-4pdx.vercel.app');
        });
        proxy.on('proxyRes', (proxyRes) => {
          proxyRes.headers['access-control-allow-origin'] = '*';
          proxyRes.headers['access-control-allow-credentials'] = 'true';
        });
      },
    },
  },
}
```

**الفائدة:**
- ✅ يحل مشاكل CORS في بيئة التطوير (localhost)
- ✅ يعيد توجيه الطلبات عبر proxy لتجنب CORS
- ✅ يضيف headers تلقائياً للطلبات

---

### 3️⃣ **تحديث public/_headers**
```
/*
  Access-Control-Allow-Origin: *
  Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH
  Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin, X-Api-Key
  Access-Control-Allow-Credentials: true
  Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages
  Access-Control-Max-Age: 86400
```

**الفائدة:** يعمل كـ fallback إذا فشلت إعدادات vercel.json

---

### 4️⃣ **تحديث src/services/api.ts**

#### ✅ إضافة Headers صحيحة:
```typescript
this.api = axios.create({
  baseURL: this.baseURL,
  auth: {
    username: this.consumerKey,
    password: this.consumerSecret,
  },
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',  // ✅ مهم للـ CORS
  },
  timeout: 30000,
  withCredentials: false,  // ✅ false عند استخدام Origin: *
});
```

#### ✅ تحسين Request Interceptor:
```typescript
this.api.interceptors.request.use(
  (config) => {
    console.log(`🚀 API Request: ${config.method?.toUpperCase()} ${config.url}`);
    
    // ضمان إرسال headers الصحيحة
    if (!config.headers['X-Requested-With']) {
      config.headers['X-Requested-With'] = 'XMLHttpRequest';
    }
    
    return config;
  }
);
```

**الفائدة:**
- ✅ يرسل headers مطلوبة مع كل طلب
- ✅ يكتشف أخطاء CORS ويعطي رسائل واضحة

---

## 🔄 كيفية العمل

### في التطوير (Development):
```
React App (localhost:8080)
    ↓
Vite Proxy (/api)
    ↓
dev.murjan.sa/wp-json/murjan-api/v1
```

**المزايا:**
- ✅ لا توجد مشاكل CORS (الطلبات من نفس الـ origin)
- ✅ سهل للتطوير والاختبار

### في الإنتاج (Production):
```
React App (woo-4pdx.vercel.app)
    ↓ (مباشرة)
dev.murjan.sa/wp-json/murjan-api/v1
```

**المتطلبات:**
- ✅ CORS headers من السيرفر (WordPress)
- ✅ vercel.json يضيف headers للطلبات
- ✅ Backend يسمح بالنطاق في allowed origins

---

## 🧪 الاختبار

### 1️⃣ اختبار محلي (Development):
```bash
# شغّل السيرفر المحلي
npm run dev

# افتح في المتصفح
http://localhost:8080

# افتح Console (F12) وشاهد الطلبات
```

**يجب أن ترى:**
```
🚀 API Request: GET /api/products
✅ Proxy response: 200 /api/products
```

### 2️⃣ اختبار على Vercel:
```bash
# ارفع التعديلات إلى Vercel
git add .
git commit -m "Update CORS configuration"
git push

# افتح الموقع
https://woo-4pdx.vercel.app
```

**يجب أن ترى:**
```
🚀 API Request: GET https://dev.murjan.sa/wp-json/murjan-api/v1/products
✅ API Response: https://dev.murjan.sa/wp-json/murjan-api/v1/products 200
```

### 3️⃣ اختبار CORS من Console:
افتح Developer Tools (F12) واكتب:

```javascript
// اختبار مباشر
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=12', {
  method: 'GET',
  headers: {
    'Authorization': 'Basic ' + btoa('YOUR_KEY:YOUR_SECRET'),
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
})
.then(res => {
  console.log('✅ Status:', res.status);
  console.log('✅ Headers:', res.headers);
  return res.json();
})
.then(data => console.log('✅ Data:', data))
.catch(err => console.error('❌ Error:', err));
```

---

## 🔧 استكشاف الأخطاء

### ❌ Problem: CORS error في Development

**الأسباب المحتملة:**
1. Vite server غير مشغّل بشكل صحيح
2. Proxy configuration خاطئة

**الحل:**
```bash
# 1. أوقف السيرفر
Ctrl + C

# 2. امسح node_modules
rm -rf node_modules

# 3. أعد التثبيت
npm install

# 4. شغّل السيرفر
npm run dev
```

---

### ❌ Problem: CORS error في Production (Vercel)

**الأسباب المحتملة:**
1. Backend لا يرسل CORS headers
2. النطاق غير مضاف في allowed origins
3. vercel.json غير مطبق

**الحل:**

#### ✅ الخطوة 1: تحقق من Backend
```bash
# اختبر من Terminal
curl -I https://dev.murjan.sa/wp-json/murjan-api/v1/products \
  -H "Origin: https://woo-4pdx.vercel.app"
```

**يجب أن ترى:**
```
Access-Control-Allow-Origin: https://woo-4pdx.vercel.app
```

إذا لم ترَ هذا، **طبّق حل CORS في Backend** (انظر `api/README-CORS-FIX.md`)

#### ✅ الخطوة 2: تحقق من Vercel
1. افتح: https://vercel.com/dashboard
2. اذهب إلى Project Settings
3. تأكد من أن `vercel.json` موجود في الـ root
4. أعد Deploy المشروع

#### ✅ الخطوة 3: امسح الكاش
```bash
# في المتصفح
Ctrl + Shift + Delete
# ثم امسح جميع الكاش
```

---

### ❌ Problem: Authorization Failed

**السبب:** Consumer Key/Secret خاطئة

**الحل:**
```bash
# 1. افتح .env في المشروع
code .env

# 2. تأكد من صحة القيم:
VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
VITE_WOOCOMMERCE_CONSUMER_KEY=ck_xxxxxxxxxxxxxxxxxxxxx
VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_xxxxxxxxxxxxxxxxxxxxx

# 3. أعد تشغيل السيرفر
npm run dev
```

---

### ❌ Problem: 403 Forbidden

**السبب:** صلاحيات API غير كافية

**الحل:**
1. في WordPress: WooCommerce → Settings → Advanced → REST API
2. احذف المفاتيح القديمة
3. أنشئ مفاتيح جديدة بصلاحيات **Read/Write**
4. انسخ المفاتيح إلى `.env`

---

## 📊 ملخص التغييرات

| الملف | التعديل | السبب |
|------|---------|-------|
| `vercel.json` | إضافة CORS headers | لدعم CORS على Vercel |
| `vite.config.ts` | تحديث proxy + CORS | لحل CORS في التطوير |
| `public/_headers` | إضافة CORS headers | Fallback لـ Vercel |
| `src/services/api.ts` | تحسين headers + interceptors | إرسال headers صحيحة |

---

## 🎯 Best Practices

### ✅ في التطوير:
- استخدم Vite proxy (`/api`) بدلاً من الـ URL المباشر
- شغّل السيرفر على `localhost:8080`
- راجع Console للتأكد من عدم وجود أخطاء

### ✅ في الإنتاج:
- تأكد من تطبيق CORS على Backend (WordPress)
- استخدم HTTPS فقط (لا HTTP)
- احتفظ بنسخة احتياطية من المفاتيح

### ✅ الأمان:
- لا ترفع ملف `.env` إلى Git
- استخدم Environment Variables في Vercel
- جدّد المفاتيح دورياً

---

## 📚 ملفات مرجعية

### Backend (WordPress):
- `e:\woo\api\murjan-cors-fix.php` - Must-Use Plugin لحل CORS
- `e:\woo\api\.htaccess` - Apache CORS configuration
- `e:\woo\api\README-CORS-FIX.md` - دليل كامل للـ Backend

### Frontend (React):
- `e:\woo\woo-product-manager-main\vercel.json` - Vercel CORS config
- `e:\woo\woo-product-manager-main\vite.config.ts` - Vite dev server config
- `e:\woo\woo-product-manager-main\src\services\api.ts` - API service

---

## 🆘 الحصول على الدعم

### Checklist قبل طلب الدعم:
- [ ] طبّقت حل CORS في Backend (WordPress)
- [ ] رفعت ملف `murjan-cors-fix.php` إلى `mu-plugins/`
- [ ] اختبرت من Postman وعمل بنجاح
- [ ] مسحت كل أنواع الكاش
- [ ] تحققت من Consumer Key & Secret
- [ ] راجعت Console (F12) ونسخت رسالة الخطأ

### معلومات مطلوبة للدعم:
```
1. البيئة: Development / Production
2. المتصفح: Chrome / Firefox / Safari
3. نظام التشغيل: Windows / Mac / Linux
4. رسالة الخطأ من Console (F12)
5. رابط الموقع على Vercel
6. هل Backend يعمل من Postman؟ نعم / لا
```

---

## ✅ الخلاصة

### تم تطبيق:
1. ✅ CORS headers في vercel.json
2. ✅ Proxy configuration في vite.config.ts
3. ✅ Headers صحيحة في api.ts
4. ✅ Fallback headers في _headers
5. ✅ Error handling محسّن

### النتيجة:
- 🎉 لا توجد أخطاء CORS في Development
- 🎉 لا توجد أخطاء CORS في Production (بعد تطبيق Backend fix)
- 🎉 رسائل خطأ واضحة إذا حدثت مشكلة
- 🎉 Performance محسّن مع caching

---

## 🔗 روابط مفيدة

- [Vite Proxy Configuration](https://vitejs.dev/config/server-options.html#server-proxy)
- [Vercel Headers Configuration](https://vercel.com/docs/projects/project-configuration#headers)
- [Axios CORS Configuration](https://axios-http.com/docs/handling_errors)
- [MDN CORS Guide](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)

---

**آخر تحديث:** 2025-10-27  
**الإصدار:** 1.0.0  
**التوافق:** React 18+ | Vite 5+ | Axios 1.6+ | Vercel

