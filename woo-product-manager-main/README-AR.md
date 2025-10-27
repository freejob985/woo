# 🛍️ مدير منتجات WooCommerce

لوحة تحكم React.js حديثة واحترافية لإدارة منتجات WooCommerce عبر REST API.

![React](https://img.shields.io/badge/React-18.3.1-blue)
![TypeScript](https://img.shields.io/badge/TypeScript-5.x-blue)
![Vite](https://img.shields.io/badge/Vite-5.x-purple)

## 🎯 نظرة عامة

تطبيق ويب متكامل مبني بـ React.js و TypeScript لإدارة منتجات WooCommerce بشكل كامل من خارج WordPress. يوفر واجهة مستخدم حديثة وسريعة مع دعم كامل للـ CRUD operations.

## ✨ المميزات الرئيسية

### إدارة المنتجات
- ✅ **عرض المنتجات**: شبكة بطاقات متجاوبة وجميلة
- ✅ **إضافة منتجات**: إنشاء منتجات بسيطة ومتغيرة
- ✅ **تعديل المنتجات**: تحرير شامل عبر modals منظمة
- ✅ **حذف المنتجات**: حذف آمن مع تأكيد
- ✅ **البحث**: بحث فوري في المنتجات
- ✅ **الترقيم**: تصفح سهل للمنتجات الكثيرة

### تصميم بطاقة المنتج (5 صفوف)
كل بطاقة منتج تعرض:

**الصف 1: الصورة المصغرة | التصنيف | نوع المنتج**
- صورة المنتج الرئيسية
- أول تصنيف
- نوع المنتج (Simple/Variable)

**الصف 2: الاسم | التوفر | السعر**
- اسم المنتج
- حالة المخزون (متوفر/غير متوفر)
- السعر بالريال السعودي

**الصفوف 3-4: أزرار التحرير السريع**
- 🔹 **General/Variations**: الإعدادات العامة والصور
- 📦 **Inventory**: إدارة المخزون
- 🚚 **Shipping**: تفاصيل الشحن (الوزن والأبعاد)
- 🔗 **Linked Products**: المنتجات المرتبطة
- 📸 **Media**: الصور والمعرض
- 🎨 **Variations**: التنويعات (للمنتجات المتغيرة فقط)

**الصف 5: أزرار الإجراءات**
- 💾 **Save**: حفظ التعديلات
- ⚙️ **Settings**: إعدادات النشر (Draft/Published/Visibility/Categories/Delete)

### تجربة المستخدم
- 🌓 **الوضع الليلي/النهاري**: تبديل بين الثيمات
- 📱 **تصميم متجاوب**: يعمل على جميع الأجهزة
- 🎨 **واجهة عصرية**: مبنية بـ shadcn/ui
- ⚡ **أداء سريع**: محسّن بـ Vite
- 🔒 **Type Safety**: دعم TypeScript كامل

### التكامل مع API
- عمليات CRUD كاملة
- إدارة المنتجات الفيزيائية
- إدارة المنتجات المتغيرة (مقاسات، ألوان، الخ)
- رفع وإدارة الصور
- إدارة المخزون
- دعم التصنيفات والوسوم
- لوحة إحصائيات شاملة

## 🛠️ التقنيات المستخدمة

| التقنية | الإصدار | الاستخدام |
|---------|---------|-----------|
| React | 18.3.1 | إطار العمل الأساسي |
| TypeScript | 5.x | لغة البرمجة |
| Vite | 5.x | أداة البناء |
| Tailwind CSS | 3.x | التصميم |
| shadcn/ui | Latest | مكونات UI |
| Axios | Latest | HTTP Client |
| React Router | 6.x | التوجيه |
| Context API | - | إدارة الحالة |

## 📦 التثبيت والإعداد

### المتطلبات الأساسية

قبل البدء، تأكد من توفر:

1. ✅ **Node.js 16+** مع npm
2. ✅ **متجر WooCommerce** مع تفعيل REST API
3. ✅ **مفاتيح WooCommerce API** (Consumer Key & Secret)
4. ✅ **إضافة WordPress** المرفقة في `/api/`

### الخطوة 1️⃣: تثبيت إضافة WordPress (مهم جداً!)

**يجب تثبيت الإضافة أولاً لحل مشكلة CORS**

#### الطريقة الأولى: الرفع عبر لوحة WordPress

```bash
1. اضغط مجلد /api/ كملف .zip
2. في لوحة WordPress: الإضافات ← أضف جديد ← رفع إضافة
3. اختر الملف المضغوط
4. اضغط "التثبيت الآن" ثم "تفعيل"
```

#### الطريقة الثانية: الرفع عبر FTP

```bash
1. ارفع مجلد /api/ إلى /wp-content/plugins/
2. في لوحة WordPress: الإضافات
3. ابحث عن "WooCommerce Products API Manager"
4. اضغط "تفعيل"
```

#### التحقق من التثبيت

بعد التفعيل:
- ✅ ستظهر قائمة جديدة: **Products API** في لوحة WordPress
- ✅ الإضافة تضيف CORS headers تلقائياً
- ✅ API endpoints متوفرة على: `https://موقعك.com/wp-json/murjan-api/v1`

### الخطوة 2️⃣: الحصول على مفاتيح API

1. سجّل دخول إلى لوحة WordPress
2. اذهب إلى: **WooCommerce ← الإعدادات ← متقدم ← REST API**
3. اضغط **"إضافة مفتاح"**
4. املأ البيانات:
   - **الوصف**: "React Product Manager"
   - **المستخدم**: اختر مستخدم المدير
   - **الصلاحيات**: **قراءة/كتابة**
5. اضغط **"إنشاء مفتاح API"**
6. انسخ **Consumer Key** و **Consumer Secret**
7. ⚠️ **احفظ المفاتيح فوراً! لن تستطيع رؤيتها مرة أخرى**

### الخطوة 3️⃣: استنساخ المشروع

```bash
git clone <رابط_المشروع>
cd woo-product-manager-main
```

### الخطوة 4️⃣: تثبيت الحزم

```bash
npm install
```

### الخطوة 5️⃣: إعداد المتغيرات البيئية

أنشئ ملف `.env.local` في جذر المشروع:

```env
# رابط API الخاص بمتجرك
VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1

# مفتاح المستهلك
VITE_WOOCOMMERCE_CONSUMER_KEY=ck_1234567890abcdef

# سر المستهلك
VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_1234567890abcdef

# عدد المنتجات في الصفحة (اختياري)
VITE_ITEMS_PER_PAGE=12
```

📖 **دليل مفصل**: راجع [ENV-SETUP-GUIDE.md](./ENV-SETUP-GUIDE.md)

### الخطوة 6️⃣: تشغيل المشروع

```bash
npm run dev
```

التطبيق سيعمل على: `http://localhost:8080` 🚀

## 📂 هيكل المشروع

```
woo-product-manager-main/
├── src/
│   ├── components/
│   │   ├── Layout/
│   │   │   └── DashboardLayout.tsx      # التخطيط الرئيسي
│   │   ├── Products/
│   │   │   ├── ProductCard.tsx          # بطاقة المنتج
│   │   │   ├── ProductGrid.tsx          # شبكة المنتجات
│   │   │   ├── AddProductModal.tsx      # نافذة إضافة منتج
│   │   │   └── EditProductModal.tsx     # نافذة التعديل
│   │   └── ui/                          # مكونات shadcn/ui
│   ├── contexts/
│   │   ├── ProductContext.tsx           # إدارة حالة المنتجات
│   │   └── ThemeContext.tsx             # الوضع الليلي/النهاري
│   ├── services/
│   │   └── api.ts                       # WooCommerce API Client
│   ├── types/
│   │   └── product.ts                   # أنواع TypeScript
│   ├── pages/
│   │   ├── Index.tsx                    # صفحة المنتجات
│   │   ├── Settings.tsx                 # الإعدادات
│   │   └── Stats.tsx                    # الإحصائيات
│   └── App.tsx                          # المكون الرئيسي
├── api/                                 # إضافة WordPress (للرفع)
└── ENV-SETUP-GUIDE.md                   # دليل الإعداد
```

## 🎮 دليل الاستخدام

### إضافة منتج جديد

1. اضغط زر **"Add Product"** في الأعلى
2. اختر نوع المنتج:
   - **Simple Product**: منتج عادي بسعر واحد
   - **Variable Product**: منتج بتنويعات (مقاسات، ألوان، الخ)
3. املأ الحقول المطلوبة:
   - اسم المنتج ⭐
   - السعر الأساسي ⭐
   - اختياري: الوصف، SKU، التصنيفات، الصور
4. اضغط **"Create Product"**

### تعديل منتج

اضغط على أي من أزرار التحرير في بطاقة المنتج:

- **General**: تعديل الاسم، الوصف، السعر
- **Inventory**: إدارة الكميات والمخزون
- **Shipping**: تحديد الوزن والأبعاد
- **Media**: رفع وإدارة الصور
- **Variations**: للمنتجات المتغيرة فقط
- **Settings**: إعدادات النشر والحذف

### البحث عن منتجات

استخدم شريط البحث في الأعلى للبحث بالاسم أو SKU.

### تبديل الثيم

اضغط على زر الثيم في الشريط الجانبي للتبديل بين الوضع الليلي والنهاري.

## 🐛 حل المشاكل الشائعة

### مشكلة CORS

**الخطأ**: `Access to XMLHttpRequest has been blocked by CORS policy`

**الحل**:
1. ✅ تأكد من تثبيت وتفعيل إضافة **"WooCommerce Products API Manager"**
2. ✅ موقع الإضافة: `/api/woo-products-api.php`
3. ✅ الإضافة تضيف CORS headers تلقائياً
4. ✅ امسح ذاكرة التخزين المؤقت للمتصفح

الإضافة تضيف هذه الـ headers تلقائياً:
```php
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Credentials: true
Access-Control-Allow-Headers: Authorization, Content-Type
```

### مشكلة الاتصال بـ API

**الخطأ**: "API not configured"

**الحل**:
1. تحقق من ملف `.env.local` والمفاتيح صحيحة
2. تأكد من تفعيل WooCommerce REST API
3. تأكد من رابط المتجر صحيح ويستخدم HTTPS
4. الرابط يجب أن ينتهي بـ `/wp-json/murjan-api/v1`

**الخطأ**: 401 Unauthorized

**الحل**:
1. أعد إنشاء مفاتيح API في WooCommerce
2. تأكد من الصلاحيات "قراءة/كتابة"
3. تحقق من عدم وجود أخطاء إملائية في المفاتيح
4. تأكد من صلاحية `manage_woocommerce` للمستخدم

### مشاكل البناء

**الخطأ**: أخطاء TypeScript

**الحل**:
```bash
npm run build
```

إذا استمرت الأخطاء، احذف node_modules وأعد التثبيت:
```bash
rm -rf node_modules package-lock.json
npm install
```

## 🚀 النشر (Deployment)

### البناء للإنتاج

```bash
npm run build
```

الملفات الجاهزة ستكون في مجلد `dist/`

### النشر على Vercel

```bash
npm install -g vercel
vercel
```

أو:
1. ادفع الكود إلى GitHub
2. اربط المشروع بـ Vercel
3. أضف المتغيرات البيئية في لوحة Vercel

### النشر على Netlify

```bash
npm run build
npm install -g netlify-cli
netlify deploy --prod
```

أو:
1. اسحب مجلد `dist/` إلى Netlify
2. أضف المتغيرات البيئية

## 📊 لوحة الإحصائيات

اذهب إلى صفحة **Stats** لعرض:
- إجمالي عدد المنتجات
- حالة المخزون (متوفر/غير متوفر)
- تقسيم أنواع المنتجات
- معلومات المبيعات
- المتوسطات

## 🔐 أمان المفاتيح

### أفضل الممارسات

1. ✅ **لا تضع** `.env.local` في Git أبداً
2. ✅ `.env.local` موجود في `.gitignore` (تلقائياً)
3. ✅ استخدم مفاتيح مختلفة للتطوير والإنتاج
4. ✅ غيّر المفاتيح بشكل دوري
5. ✅ استخدم HTTPS في الإنتاج
6. ✅ حدد صلاحيات المفاتيح بما يلزم فقط

## 📝 المتغيرات البيئية

| المتغير | الوصف | مطلوب؟ |
|---------|--------|--------|
| `VITE_WOOCOMMERCE_API_URL` | رابط API الخاص بمتجرك | نعم ✅ |
| `VITE_WOOCOMMERCE_CONSUMER_KEY` | مفتاح المستهلك | نعم ✅ |
| `VITE_WOOCOMMERCE_CONSUMER_SECRET` | سر المستهلك | نعم ✅ |
| `VITE_ITEMS_PER_PAGE` | عدد المنتجات في الصفحة | لا (القيمة الافتراضية: 12) |

## 🎓 مصادر إضافية

- [توثيق WooCommerce REST API](https://woocommerce.github.io/woocommerce-rest-api-docs/)
- [توثيق React](https://react.dev)
- [توثيق Tailwind CSS](https://tailwindcss.com)
- [مكونات shadcn/ui](https://ui.shadcn.com)

## 💡 نصائح الأداء

1. استخدم الـ pagination لعدم تحميل جميع المنتجات
2. الصور يتم تحميلها بشكل lazy
3. State management محسّن بـ Context API
4. Build production محسّن بـ Vite

## 🤝 المساهمة

المساهمات مرحب بها! اتبع هذه الخطوات:

1. Fork المشروع
2. أنشئ فرع للميزة: `git checkout -b feature/amazing-feature`
3. Commit التغييرات: `git commit -m 'إضافة ميزة رائعة'`
4. ادفع للفرع: `git push origin feature/amazing-feature`
5. افتح Pull Request

## 📞 الدعم الفني

للمساعدة:
- افتح issue على GitHub
- راجع قسم حل المشاكل أعلاه
- اطّلع على توثيق WooCommerce API
- راجع ملف `ENV-SETUP-GUIDE.md`

## 📄 الترخيص

هذا المشروع مفتوح المصدر ومتاح تحت ترخيص MIT.

---

**بُني بـ ❤️ باستخدام React و TypeScript و WooCommerce REST API**

### 🌟 ميزات إضافية قادمة

- [ ] تصدير المنتجات CSV
- [ ] استيراد منتجات CSV
- [ ] تعديل جماعي للمنتجات
- [ ] نظام إشعارات متقدم
- [ ] دعم المنتجات الرقمية والقابلة للتحميل
- [ ] إدارة الكوبونات

---

**نسخة 1.0.0** | تم بناؤه بواسطة فريق مرجان

