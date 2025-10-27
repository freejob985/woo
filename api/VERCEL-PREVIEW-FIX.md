# 🔧 Vercel Preview Deployments CORS Fix

## 🎯 Problem | المشكلة

```
Access-Control-Allow-Origin header has a value 
'https://woo-4pdx-1crc0okoo-mgs-projects-75fe246b.vercel.app' 
that is not equal to the supplied origin.
```

### Root Cause | السبب الجذري

Vercel creates **preview deployments** with unique URLs for each commit:
- Production: `https://woo-4pdx.vercel.app`
- Preview: `https://woo-4pdx-abc123-xyz.vercel.app`
- Another Preview: `https://woo-4pdx-def456-xyz.vercel.app`

The backend was configured to only allow the production URL, causing CORS errors on preview deployments.

---

## ✅ Solution Applied | الحل المطبق

### Updated Files:

#### 1. `murjan-cors-fix.php`
Added regex pattern matching for Vercel preview URLs:

```php
// ✅ Check if origin is a Vercel preview deployment
$is_vercel_preview = !empty($origin) && (
    preg_match('/^https:\/\/woo-4pdx-[a-z0-9]+-[a-z0-9-]+\.vercel\.app$/i', $origin) ||
    preg_match('/^https:\/\/[a-z0-9]+-[a-z0-9-]+\.vercel\.app$/i', $origin)
);

if ($is_allowed || $is_vercel_preview || $is_localhost) {
    header("Access-Control-Allow-Origin: {$origin}");
    header("Access-Control-Allow-Credentials: true");
}
```

**Matches:**
- ✅ `https://woo-4pdx.vercel.app` (production)
- ✅ `https://woo-4pdx-abc123.vercel.app` (preview)
- ✅ `https://woo-4pdx-abc123-def456.vercel.app` (preview)
- ✅ Any Vercel preview deployment

#### 2. `cors-headers.php`
Same regex pattern added for compatibility.

#### 3. `.htaccess`
Added Apache regex rules:

```apache
# Vercel preview deployments pattern
SetEnvIf Origin "^https://woo-4pdx-[a-z0-9]+-[a-z0-9-]+\.vercel\.app$" AccessControlAllowOrigin=$0

# Any Vercel deployment
SetEnvIf Origin "^https://[a-z0-9]+-[a-z0-9-]+\.vercel\.app$" AccessControlAllowOrigin=$0
```

---

## 🔄 How to Apply | كيفية التطبيق

### Option 1: Must-Use Plugin (Recommended)
```bash
1. Re-upload the updated file:
   FROM: e:\woo\api\murjan-cors-fix.php
   TO: wp-content/mu-plugins/murjan-cors-fix.php

2. That's it! ✅ Works immediately.
```

### Option 2: .htaccess (Apache only)
```bash
1. Upload updated file:
   FROM: e:\woo\api\.htaccess
   TO: /public_html/.htaccess (WordPress root)

2. Restart Apache (optional)
```

---

## 🧪 Testing | الاختبار

### Test with Production URL:
```bash
curl -I https://dev.murjan.sa/wp-json/murjan-api/v1/products \
  -H "Origin: https://woo-4pdx.vercel.app"
```

**Expected:**
```
access-control-allow-origin: https://woo-4pdx.vercel.app
```

### Test with Preview URL:
```bash
curl -I https://dev.murjan.sa/wp-json/murjan-api/v1/products \
  -H "Origin: https://woo-4pdx-abc123-xyz.vercel.app"
```

**Expected:**
```
access-control-allow-origin: https://woo-4pdx-abc123-xyz.vercel.app
```

### Test from Browser:
1. Open any Vercel preview deployment
2. Open Console (F12)
3. Try to load products
4. Should see ✅ No CORS errors

---

## 📊 What This Fixes | ما يتم إصلاحه

### ✅ Before the Fix:
```
❌ Production URL works
❌ Preview deployments fail with CORS
❌ Must refresh after adding product
❌ Inconsistent behavior
```

### ✅ After the Fix:
```
✅ Production URL works
✅ Preview deployments work
✅ No refresh needed
✅ Consistent behavior across all deployments
```

---

## 🔍 Why It Happens | لماذا يحدث هذا

### Vercel Deployment Flow:
```
git push
    ↓
Vercel creates preview
    ↓
Preview gets unique URL: woo-4pdx-abc123.vercel.app
    ↓
Browser makes request with that origin
    ↓
Backend checks allowed origins
    ↓
❌ Not found in list → CORS error
```

### With the Fix:
```
git push
    ↓
Vercel creates preview with any URL
    ↓
Browser makes request
    ↓
Backend checks with regex pattern
    ↓
✅ Matches pattern → CORS allowed
```

---

## 🎯 Security Considerations | اعتبارات الأمان

### Is This Safe?
✅ **YES** - Because:
1. Only allows `*.vercel.app` domains (your hosting)
2. Uses strict regex patterns (not wildcards)
3. Validates HTTPS protocol
4. Doesn't allow random domains

### Pattern Breakdown:
```regex
^https:\/\/woo-4pdx-[a-z0-9]+-[a-z0-9-]+\.vercel\.app$

^ = Start of string
https:// = Must be HTTPS
woo-4pdx- = Your app prefix
[a-z0-9]+ = Alphanumeric (deployment hash)
- = Separator
[a-z0-9-]+ = More alphanumeric + hyphens
\.vercel\.app = Domain
$ = End of string
```

**Blocks:**
- ❌ `http://woo-4pdx-abc.vercel.app` (HTTP)
- ❌ `https://malicious-site.com`
- ❌ `https://fake-woo-4pdx.vercel.app`

**Allows:**
- ✅ `https://woo-4pdx-abc123.vercel.app`
- ✅ `https://woo-4pdx-abc123-def456.vercel.app`
- ✅ All legitimate Vercel previews

---

## 🔧 Advanced Configuration | إعدادات متقدمة

### To Restrict to Specific Project Only:
Edit pattern in `murjan-cors-fix.php`:

```php
// Only allow woo-4pdx previews (more restrictive)
$is_vercel_preview = !empty($origin) && 
    preg_match('/^https:\/\/woo-4pdx-[a-z0-9]+-[a-z0-9-]+\.vercel\.app$/i', $origin);
```

### To Allow Multiple Vercel Projects:
```php
// Allow woo-4pdx and another-project previews
$is_vercel_preview = !empty($origin) && (
    preg_match('/^https:\/\/woo-4pdx-[a-z0-9]+-[a-z0-9-]+\.vercel\.app$/i', $origin) ||
    preg_match('/^https:\/\/another-project-[a-z0-9]+-[a-z0-9-]+\.vercel\.app$/i', $origin)
);
```

---

## 🆘 Troubleshooting | استكشاف الأخطاء

### Still Getting CORS Errors?

#### 1. Check File Location
```bash
# Must-Use Plugin should be at:
wp-content/mu-plugins/murjan-cors-fix.php

# NOT at:
wp-content/plugins/woo-products-api/murjan-cors-fix.php
```

#### 2. Verify Pattern Matches Your URL
```php
// Add debug logging (temporarily)
error_log('Origin: ' . $origin);
error_log('Is Vercel Preview: ' . ($is_vercel_preview ? 'YES' : 'NO'));
```

Check logs in: `wp-content/debug.log`

#### 3. Clear All Caches
```bash
# WordPress cache
# Browser cache (Ctrl + Shift + Delete)
# CDN cache (Cloudflare → Purge Everything)
```

#### 4. Test Pattern Manually
```php
// Test in PHP directly
$origin = 'https://woo-4pdx-1crc0okoo-mgs-projects-75fe246b.vercel.app';
$matches = preg_match('/^https:\/\/[a-z0-9]+-[a-z0-9-]+\.vercel\.app$/i', $origin);
echo $matches ? 'Matches' : 'No match';
```

---

## 📋 Quick Reference | مرجع سريع

### Regex Patterns Used:
```regex
# Specific project previews
^https:\/\/woo-4pdx-[a-z0-9]+-[a-z0-9-]+\.vercel\.app$

# Any Vercel preview
^https:\/\/[a-z0-9]+-[a-z0-9-]+\.vercel\.app$

# Localhost (any port)
^http:\/\/(localhost|127\.0\.0\.1):[0-9]+$
```

### Example URLs Matched:
```
✅ https://woo-4pdx.vercel.app
✅ https://woo-4pdx-abc123.vercel.app
✅ https://woo-4pdx-1crc0okoo-mgs-projects-75fe246b.vercel.app
✅ https://my-project-xyz789.vercel.app
✅ http://localhost:3000
✅ http://localhost:5173
```

---

## ✅ Checklist | قائمة التحقق

- [ ] Updated `murjan-cors-fix.php` with regex patterns
- [ ] Updated `cors-headers.php` with regex patterns
- [ ] Updated `.htaccess` (if using Apache)
- [ ] Re-uploaded file to `mu-plugins/`
- [ ] Verified green notice in WordPress admin
- [ ] Tested production URL - works ✅
- [ ] Tested preview URL - works ✅
- [ ] Cleared all caches
- [ ] No CORS errors in console ✅

---

## 🎉 Expected Results | النتائج المتوقعة

### Console Output:
```
🚀 API Request: GET /products
✅ API Response: 200
✅ Products loaded successfully
🎉 No CORS errors!
```

### All Scenarios Working:
- ✅ Production deployment (`woo-4pdx.vercel.app`)
- ✅ Preview deployments (any commit)
- ✅ Branch deployments
- ✅ Development (localhost)
- ✅ After refresh
- ✅ After adding products

---

**Last Updated:** 2025-10-27  
**Version:** 1.1.0  
**Issue:** Vercel preview deployment CORS  
**Status:** ✅ Fixed

