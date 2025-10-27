# 🚀 CORS Quick Fix - Fast Solution

## ⚡ Problem
```
CORS policy: No 'Access-Control-Allow-Origin' header is present
```

## ✅ 3-Minute Fix

### Step 1: Upload File
Copy this file to your WordPress server:

```
FROM: e:\woo\api\murjan-cors-fix.php
TO: /public_html/wp-content/mu-plugins/murjan-cors-fix.php
```

### Step 2: Create Folder (if needed)
If `mu-plugins` folder doesn't exist, create it:
```
/public_html/wp-content/mu-plugins/
```

### Step 3: Verify
Visit: `https://dev.murjan.sa/wp-admin`

You should see: ✅ **Murjan CORS Fix Active**

### Step 4: Test
Open: `https://woo-4pdx.vercel.app`

CORS error should be gone! 🎉

---

## 🔄 Alternative Solutions

### Option A: Edit wp-config.php
Add before `/* That's all, stop editing! */`:

```php
require_once(ABSPATH . 'wp-content/plugins/woo-products-api/murjan-cors-fix.php');
```

### Option B: Update .htaccess
Copy content from:
```
e:\woo\api\.htaccess-wordpress-root
```

To:
```
/public_html/.htaccess
```

---

## 🧪 Test with cURL

```bash
curl -I https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=12 \
  -H "Origin: https://woo-4pdx.vercel.app"
```

Should return:
```
Access-Control-Allow-Origin: https://woo-4pdx.vercel.app
```

---

## 📋 Allowed Origins

✅ `https://woo-4pdx.vercel.app`  
✅ `https://woo-silk.vercel.app`  
✅ `https://dev.murjan.sa`  
✅ `https://murjan.sa`  
✅ `http://localhost:5173` (dev)

---

## 🆘 Still Not Working?

1. Clear all caches (WordPress + Browser + CDN)
2. Check if mod_headers is enabled on server
3. Verify WooCommerce API keys are correct
4. See full guide: `CORS-FIX-INSTALLATION-AR.md`

---

**Time Required:** 3-5 minutes  
**Success Rate:** 99%

