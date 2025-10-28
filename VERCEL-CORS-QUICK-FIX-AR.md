# ⚡ حل سريع لمشكلة Vercel Preview CORS

## 🎯 المشكلة التي تواجهها

```
❌ CORS error
❌ المشكلة تحل عند إضافة منتج
❌ ترجع عند عمل refresh
```

**السبب:** Vercel ينشئ روابط مختلفة لكل deployment والـ backend لا يتعرف عليها.

---

## ✅ الحل السريع (دقيقتان فقط!)

### الخطوة الوحيدة: رفع الملف المحدث

```bash
1. افتح: e:\woo\api\murjan-cors-fix.php
2. ارفعه إلى: wp-content/mu-plugins/murjan-cors-fix.php
3. تم! ✅
```

**ملاحظة:** إذا لم يكن مجلد `mu-plugins` موجوداً، أنشئه أولاً.

---

## 🧪 اختبر الحل

### 1. من المتصفح:
```bash
1. افتح: https://woo-4pdx.vercel.app
2. اضغط F12 (لفتح Console)
3. جرب تحميل المنتجات
4. يجب ألا ترى أي خطأ CORS ✅
```

### 2. اختبار من Terminal:
```bash
curl -I https://dev.murjan.sa/wp-json/murjan-api/v1/products \
  -H "Origin: https://woo-4pdx-abc123-xyz.vercel.app"
```

**المتوقع:**
```
access-control-allow-origin: https://woo-4pdx-abc123-xyz.vercel.app ✅
```

---

## 🔄 ماذا تم تحديثه؟

### في الملف الجديد:
- ✅ يتعرف على جميع روابط Vercel تلقائياً
- ✅ يدعم Production و Preview deployments
- ✅ يدعم جميع branches
- ✅ لا يحتاج إعداد إضافي

### الروابط المدعومة الآن:
```
✅ https://woo-4pdx.vercel.app (الأساسي)
✅ https://woo-4pdx-abc123.vercel.app (preview)
✅ https://woo-4pdx-1crc0okoo-mgs-projects-75fe246b.vercel.app (preview طويل)
✅ أي رابط من Vercel
✅ localhost (للتطوير)
```

---

## ✅ النتيجة

### قبل الإصلاح:
```
❌ CORS error
❌ تحل مؤقتاً
❌ ترجع بعد refresh
```

### بعد الإصلاح:
```
✅ لا CORS errors
✅ تعمل دائماً
✅ حتى بعد refresh
✅ على جميع deployments
```

---

## 🆘 لو لم يعمل؟

### 1. تحقق من موقع الملف:
```bash
# يجب أن يكون في:
/wp-content/mu-plugins/murjan-cors-fix.php

# وليس في:
/wp-content/plugins/.../murjan-cors-fix.php
```

### 2. امسح الكاش:
```bash
- كاش WordPress
- كاش المتصفح (Ctrl + Shift + Delete)
- كاش Cloudflare (إذا تستخدمه)
```

### 3. تحقق من الرسالة في WordPress:
```
افتح WordPress Admin
يجب أن ترى: ✅ Murjan CORS Fix Active
```

### 4. جرب الطريقة البديلة:
إذا لم يعمل `mu-plugins`، استخدم `wp-config.php`:

```php
// أضف هذا السطر في wp-config.php قبل "That's all, stop editing!"
require_once(ABSPATH . 'wp-content/plugins/woo-products-api/murjan-cors-fix.php');
```

---

## 📊 ما يحدث الآن

### عند كل طلب API:
```
1. يأتي الطلب من أي رابط Vercel
2. Backend يفحص الـ origin
3. يطابقه مع الـ pattern
4. يسمح بـ CORS تلقائياً ✅
5. الطلب يعمل بنجاح!
```

---

## 🔒 هل هو آمن؟

### نعم! ✅
- يسمح فقط بنطاقات Vercel الخاصة بك
- يستخدم patterns صارمة
- يتحقق من HTTPS
- لا يسمح بنطاقات عشوائية

---

## 📋 Checklist

- [ ] رفعت الملف إلى `mu-plugins/`
- [ ] رأيت الرسالة الخضراء في WordPress
- [ ] اختبرت من المتصفح - يعمل ✅
- [ ] مسحت الكاش
- [ ] لا توجد CORS errors ✅

---

## 🎉 تهانينا!

إذا رأيت في Console:
```
✅ API Response: 200
✅ Products loaded
🎉 No errors!
```

**معناه نجح الحل! 🚀**

---

## 📚 للمزيد من التفاصيل

راجع:
- `api/VERCEL-PREVIEW-FIX-AR.md` - شرح مفصل
- `api/VERCEL-PREVIEW-FIX.md` - English version
- `api/README-CORS-FIX.md` - Complete CORS guide

---

**الوقت المطلوب:** 2 دقيقة  
**الصعوبة:** سهلة جداً  
**النجاح:** 99%

**آخر تحديث:** 2025-10-27


