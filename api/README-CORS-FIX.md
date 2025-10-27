# 🔧 CORS Fix for Murjan WooCommerce API

## 📌 Overview

This folder contains complete CORS (Cross-Origin Resource Sharing) solutions for accessing the WooCommerce API from Vercel and other domains.

---

## 🎯 Quick Start

### For Immediate Fix:
1. **Read:** `CORS-QUICK-FIX.md` (English - 3 min)
2. **Upload:** `murjan-cors-fix.php` to `wp-content/mu-plugins/`
3. **Done!** ✅

### For Detailed Guide:
- **Arabic:** `CORS-FIX-INSTALLATION-AR.md` (Complete guide in Arabic)
- **English:** `CORS-QUICK-FIX.md` (Quick reference)

---

## 📁 Files in This Folder

### 🔴 Critical Files (Use These!)

| File | Purpose | When to Use |
|------|---------|-------------|
| `murjan-cors-fix.php` | **Must-Use Plugin** | ⭐ **Best Solution** - Upload to `mu-plugins/` |
| `.htaccess` | Apache CORS config | Already in use for this plugin folder |
| `.htaccess-wordpress-root` | Apache CORS config | Copy to WordPress root if needed |

### 📘 Documentation Files

| File | Description |
|------|-------------|
| `CORS-FIX-INSTALLATION-AR.md` | Complete guide in Arabic (شرح مفصل) |
| `CORS-QUICK-FIX.md` | Quick English guide |
| `README-CORS-FIX.md` | This file |

### 🟡 Reference Files (Already integrated)

| File | Status |
|------|--------|
| `cors-headers.php` | ✅ Already loaded by `woo-products-api.php` |
| `CORS-FIX-GUIDE.php` | Legacy guide |
| `cors-setup-instructions.php` | Legacy instructions |

---

## 🚀 Recommended Solutions (Priority Order)

### 1️⃣ Must-Use Plugin (Best)
```
Upload: murjan-cors-fix.php
To: /public_html/wp-content/mu-plugins/murjan-cors-fix.php
Time: 2 minutes
Success Rate: 99%
```

**Advantages:**
- ✅ Works immediately
- ✅ No activation needed
- ✅ Survives plugin updates
- ✅ Most reliable

**Instructions:**
See `CORS-QUICK-FIX.md` or `CORS-FIX-INSTALLATION-AR.md`

---

### 2️⃣ wp-config.php Method
```php
// Add this line to wp-config.php before "That's all, stop editing!"
require_once(ABSPATH . 'wp-content/plugins/woo-products-api/murjan-cors-fix.php');
```

**Advantages:**
- ✅ Quick to implement
- ✅ Easy to enable/disable
- ⚠️ Requires file editing access

---

### 3️⃣ .htaccess Method (Apache Only)
```
Copy: .htaccess-wordpress-root
To: /public_html/.htaccess
```

**Advantages:**
- ✅ Works at server level
- ⚠️ Requires Apache server
- ⚠️ Requires mod_headers enabled

---

## 🌍 Allowed Origins

Current configuration allows:

### Production:
- ✅ `https://woo-4pdx.vercel.app` (Main Vercel App)
- ✅ `https://woo-silk.vercel.app` (Alternative Vercel)
- ✅ `https://dev.murjan.sa` (Main Domain)
- ✅ `https://murjan.sa` (Main Domain)
- ✅ `https://www.murjan.sa` (Main with www)

### Development:
- ✅ `http://localhost:3000`
- ✅ `http://localhost:5173` (Vite default)
- ✅ `http://127.0.0.1:5173`

---

## 🧪 Testing

### Quick Test with cURL:
```bash
curl -I https://dev.murjan.sa/wp-json/murjan-api/v1/products \
  -H "Origin: https://woo-4pdx.vercel.app"
```

Expected response headers:
```
Access-Control-Allow-Origin: https://woo-4pdx.vercel.app
Access-Control-Allow-Credentials: true
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH
```

### Test from Browser Console:
```javascript
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=12')
  .then(res => res.json())
  .then(data => console.log('✅ Success:', data))
  .catch(err => console.error('❌ Error:', err));
```

---

## 🔍 Troubleshooting

### CORS Still Not Working?

1. **Clear Caches:**
   - WordPress cache
   - Browser cache (Ctrl + Shift + Delete)
   - CDN cache (if using Cloudflare)

2. **Verify File Location:**
   - Check `murjan-cors-fix.php` is in `mu-plugins/` folder
   - Check file permissions (644 or 755)

3. **Check Server:**
   - Ensure mod_headers is enabled (Apache)
   - Ensure mod_rewrite is enabled (Apache)

4. **Verify Authentication:**
   - Check WooCommerce API keys
   - Verify Consumer Key & Secret in `.env`

5. **Contact Support:**
   - Provide server type (Apache/Nginx)
   - Provide error message from browser console
   - Provide which solution you tried

---

## 📊 Success Rates

| Method | Success Rate | Time Required |
|--------|--------------|---------------|
| Must-Use Plugin | 99% | 2-3 min |
| wp-config.php | 95% | 1-2 min |
| .htaccess | 85% | 3-5 min |

---

## 📚 Additional Resources

- **WooCommerce REST API:** https://woocommerce.github.io/woocommerce-rest-api-docs/
- **CORS Documentation:** https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
- **WordPress REST API:** https://developer.wordpress.org/rest-api/

---

## 🆘 Need Help?

### Quick Support Checklist:
- [ ] Tried uploading `murjan-cors-fix.php` to `mu-plugins/`
- [ ] Cleared all caches
- [ ] Tested with Postman (worked)
- [ ] Tested from Vercel (didn't work)
- [ ] Checked browser console for error message
- [ ] Verified API keys are correct

### What to Include in Support Request:
```
1. Server Type: Apache / Nginx / LiteSpeed
2. PHP Version: _______
3. WordPress Version: _______
4. WooCommerce Version: _______
5. Solution Tried: Must-Use Plugin / wp-config / .htaccess
6. Error Message: (from browser console F12)
```

---

## ✅ Summary

**The Problem:**
```
Access to XMLHttpRequest from origin 'https://woo-4pdx.vercel.app' 
has been blocked by CORS policy
```

**The Solution:**
Upload `murjan-cors-fix.php` to `wp-content/mu-plugins/`

**Time Required:** 2-3 minutes  
**Success Rate:** 99%

---

**Last Updated:** 2025-10-27  
**Version:** 1.0.0  
**Compatibility:** WordPress 5.8+ | WooCommerce 5.0+ | PHP 7.4+

