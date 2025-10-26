=== WooCommerce Products API Manager ===
Contributors: Murjan Team
Tags: woocommerce, rest api, products, api, integration
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

REST API متكامل لإدارة المنتجات الفيزيائية والمتغيرة في WooCommerce مع حماية كاملة وتوثيق شامل.

== Description ==

WooCommerce Products API Manager توفر REST API كامل لإدارة المنتجات في متجرك الإلكتروني.

= المميزات الرئيسية =

* **إدارة المنتجات الفيزيائية**: إضافة، تعديل، عرض، بحث، وحذف المنتجات الفيزيائية
* **إدارة المنتجات المتغيرة**: إدارة كاملة للمنتجات المتغيرة مع تنويعاتها
* **حماية كاملة**: توثيق عبر WooCommerce API Keys مع التحقق من الصلاحيات
* **إحصائيات تفصيلية**: تقارير شاملة عن المنتجات والمخزون
* **Postman Collection**: ملف جاهز لاختبار جميع الروابط
* **توثيق شامل**: دليل كامل باللغة العربية

= حالات الاستخدام =

* ربط تطبيقات الموبايل بالمتجر
* مزامنة المنتجات بين مواقع متعددة
* التكامل مع أنظمة ERP وإدارة المخزون
* إنشاء لوحات تحكم مخصصة
* أتمتة عمليات إضافة وتحديث المنتجات

= API Endpoints =

**المنتجات الفيزيائية:**
* POST /physical-products - إضافة منتج
* PUT /physical-products/{id} - تعديل منتج
* GET /physical-products - عرض جميع المنتجات
* GET /physical-products/{id} - عرض منتج واحد
* GET /physical-products/search - البحث
* DELETE /physical-products/{id} - حذف منتج
* GET /physical-products/stats - الإحصائيات

**المنتجات المتغيرة:**
* POST /variable-products - إضافة منتج متغير
* PUT /variable-products/{id} - تعديل منتج
* GET /variable-products - عرض جميع المنتجات
* GET /variable-products/{id} - عرض منتج واحد
* GET /variable-products/search - البحث
* DELETE /variable-products/{id} - حذف منتج
* GET /variable-products/stats - الإحصائيات

= الأمان =

* توثيق إلزامي لجميع الروابط
* استخدام WooCommerce API Keys
* التحقق من صلاحيات المستخدم
* حماية من SQL Injection و XSS
* تنظيف جميع المدخلات

== Installation ==

= التثبيت التلقائي =

1. اذهب إلى: الإضافات > أضف جديد
2. ابحث عن: WooCommerce Products API Manager
3. اضغط: تثبيت الآن
4. فعّل الإضافة

= التثبيت اليدوي =

1. حمّل ملف الإضافة
2. ارفعه إلى: /wp-content/plugins/woo-products-importer/api/
3. فعّل الإضافة من لوحة التحكم

= بعد التثبيت =

1. اذهب إلى: WooCommerce > الإعدادات > متقدم > REST API
2. أنشئ مفاتيح API جديدة بصلاحيات Read/Write
3. احفظ Consumer Key و Consumer Secret
4. استخدمها للتوثيق في طلبات API

== Frequently Asked Questions ==

= هل أحتاج إلى WooCommerce؟ =

نعم، هذه الإضافة تتطلب WooCommerce 5.0 أو أحدث.

= كيف أحصل على مفاتيح API؟ =

1. WooCommerce > الإعدادات > متقدم > REST API
2. Add key
3. اختر صلاحيات Read/Write
4. احفظ المفاتيح

= أين الرابط الأساسي للـ API؟ =

https://YOUR-DOMAIN.com/wp-json/murjan-api/v1

= كيف أختبر الـ API؟ =

استخدم Postman Collection المرفق مع الإضافة في مجلد: api/postman/

= هل API محمي؟ =

نعم، جميع الروابط محمية وتتطلب مفاتيح WooCommerce API للوصول.

= أين التوثيق الكامل؟ =

راجع الملفات التالية في مجلد الإضافة:
* README-AR.md - دليل شامل
* API-DOCUMENTATION-AR.md - توثيق API
* POSTMAN-GUIDE-AR.md - دليل Postman

== Screenshots ==

1. لوحة تحكم الإضافة - عرض جميع Endpoints
2. صفحة إعدادات API
3. Postman Collection - اختبار المنتجات الفيزيائية
4. Postman Collection - اختبار المنتجات المتغيرة
5. إحصائيات المنتجات

== Changelog ==

= 1.0.0 - 2024-01-26 =
* الإصدار الأول
* إدارة كاملة للمنتجات الفيزيائية
* إدارة كاملة للمنتجات المتغيرة
* حماية بمفاتيح WooCommerce API
* إحصائيات تفصيلية
* Postman Collection كامل
* توثيق شامل باللغة العربية

== Upgrade Notice ==

= 1.0.0 =
الإصدار الأول من الإضافة.

== Additional Info ==

**Base URL:** https://dev.murjan.sa/wp-json/murjan-api/v1

**Authentication:** Basic Auth مع WooCommerce API Keys

**Documentation:** متوفر بالكامل باللغة العربية

**Support:** support@murjan.sa

**License:** GPLv2 or later

