# 🔒 CORS Error - Complete Solution Guide

## 🚨 Understanding the Error

When you see this error in your browser console:

```
Access to XMLHttpRequest at 'https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=12' 
from origin 'https://woo-silk.vercel.app' has been blocked by CORS policy: 
Response to preflight request doesn't pass access control check: 
No 'Access-Control-Allow-Origin' header is present on the requested resource.
```

## 🤔 What Does This Mean?

**CORS (Cross-Origin Resource Sharing)** is a security feature that prevents websites from making requests to different domains without permission.

In your case:
- **Frontend**: `https://woo-silk.vercel.app` (React app)
- **Backend**: `https://dev.murjan.sa` (WordPress API)
- **Problem**: They're on different domains, and WordPress doesn't allow the request by default

## ✅ The Solution

We've created a WordPress plugin that automatically handles CORS for you!

### Step 1: Install the WordPress Plugin

The plugin is located in `/api/woo-products-api.php` in this repository.

#### Option A: Upload via WordPress Admin (Recommended)

1. **Compress the plugin folder**
   - Navigate to the `/api/` folder
   - Right-click → Send to → Compressed (zipped) folder
   - Or use command line:
     ```bash
     cd /path/to/project
     zip -r woo-products-api.zip api/
     ```

2. **Upload to WordPress**
   - Log in to WordPress Admin
   - Go to: **Plugins → Add New → Upload Plugin**
   - Click **"Choose File"** and select the `.zip` file
   - Click **"Install Now"**
   - Click **"Activate Plugin"**

#### Option B: FTP Upload

1. Upload the entire `/api/` folder to:
   ```
   /wp-content/plugins/woo-products-api/
   ```

2. In WordPress Admin:
   - Go to **Plugins**
   - Find **"WooCommerce Products API Manager"**
   - Click **"Activate"**

### Step 2: Verify Installation

After activation, you should see:

1. ✅ A new menu item: **"Products API"** in WordPress Admin
2. ✅ A success message: "Plugin activated"
3. ✅ No errors in WordPress

### Step 3: Test the CORS Headers

Open your browser's developer tools and test the API:

```javascript
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=1')
  .then(response => {
    console.log('CORS Headers:', response.headers);
    return response.json();
  })
  .then(data => console.log('Success!', data))
  .catch(error => console.error('Error:', error));
```

You should see these headers in the response:
```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Credentials: true
Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With
```

## 🔧 How the Plugin Works

The plugin adds CORS headers to all REST API responses. Here's what it does:

```php
// Add CORS headers for external access
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-Requested-With');
        header('Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages');
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            status_header(200);
            exit();
        }
        
        return $value;
    });
}, 15);
```

### What Each Header Does

| Header | Purpose |
|--------|---------|
| `Access-Control-Allow-Origin: *` | Allows requests from any domain |
| `Access-Control-Allow-Methods` | Specifies allowed HTTP methods |
| `Access-Control-Allow-Credentials` | Allows cookies and authentication |
| `Access-Control-Allow-Headers` | Specifies allowed request headers |
| `Access-Control-Expose-Headers` | Makes pagination headers accessible |

## 🐛 Troubleshooting

### Error Still Appears After Installing Plugin

**Problem**: CORS error persists

**Solutions**:

1. **Clear browser cache**
   ```
   Chrome: Ctrl + Shift + Delete
   Firefox: Ctrl + Shift + Delete
   Safari: Cmd + Option + E
   ```

2. **Hard reload**
   ```
   Windows: Ctrl + Shift + R
   Mac: Cmd + Shift + R
   ```

3. **Verify plugin is active**
   - Go to WordPress Admin → Plugins
   - Check "WooCommerce Products API Manager" shows "Deactivate" (meaning it's active)

4. **Check for plugin conflicts**
   - Temporarily deactivate other security/CORS plugins
   - Some security plugins might override our headers

5. **Verify WordPress is serving the plugin**
   - Visit: `https://dev.murjan.sa/wp-admin/admin.php?page=woo-products-api`
   - You should see the API documentation page

### CORS Works in Browser but Not in React App

**Problem**: Direct browser requests work, but React app still gets CORS error

**Solutions**:

1. **Restart your React dev server**
   ```bash
   # Stop the server (Ctrl + C)
   npm run dev
   ```

2. **Check your API URL format**
   ```env
   # ❌ Wrong - missing /wp-json/murjan-api/v1
   VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa
   
   # ✅ Correct
   VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
   ```

3. **Verify axios configuration** in `src/services/api.ts`:
   ```typescript
   this.api = axios.create({
     baseURL: this.baseURL,
     auth: {
       username: this.consumerKey,
       password: this.consumerSecret,
     },
     headers: {
       'Content-Type': 'application/json',
     },
   });
   ```

### OPTIONS Request Fails (Preflight)

**Problem**: Browser sends OPTIONS request before actual request and it fails

**Solution**: The plugin handles this automatically. If it still fails:

1. **Check server configuration**
   - Some hosting providers block OPTIONS requests
   - Contact your host or add to `.htaccess`:
     ```apache
     <IfModule mod_rewrite.c>
         RewriteEngine On
         RewriteCond %{REQUEST_METHOD} OPTIONS
         RewriteRule ^(.*)$ $1 [R=200,L]
     </IfModule>
     ```

2. **Verify plugin priority**
   - The plugin runs at priority `15` to override default WordPress CORS
   - If another plugin runs later, it might override our headers

### CORS Works Locally but Not on Vercel/Netlify

**Problem**: Works on `localhost` but fails on production

**Solution**:

1. **Verify environment variables on hosting platform**
   - In Vercel: Settings → Environment Variables
   - In Netlify: Site settings → Build & deploy → Environment

2. **Check if the API URL is correct**
   - Must use HTTPS in production
   - Must match your WordPress site exactly

3. **Rebuild and redeploy**
   ```bash
   npm run build
   vercel --prod
   # or
   netlify deploy --prod
   ```

## 🔐 Security Considerations

### Current Setup (Development)

```php
Access-Control-Allow-Origin: *
```

This allows **any domain** to access your API. This is fine for:
- ✅ Development
- ✅ Testing
- ✅ Public APIs

### Production Recommendation

For production, you should restrict to specific domains:

**Edit** `/api/woo-products-api.php` and change:

```php
// Instead of:
header('Access-Control-Allow-Origin: *');

// Use:
$allowed_origins = array(
    'https://woo-silk.vercel.app',
    'https://your-production-domain.com'
);

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header('Access-Control-Allow-Origin: https://woo-silk.vercel.app');
}
```

## 📊 Testing CORS

### Test 1: Browser Console

```javascript
fetch('https://dev.murjan.sa/wp-json/murjan-api/v1/products?per_page=1', {
    headers: {
        'Authorization': 'Basic ' + btoa('your_key:your_secret')
    }
})
.then(r => r.json())
.then(d => console.log('✅ CORS working!', d))
.catch(e => console.error('❌ CORS failed:', e));
```

### Test 2: cURL Command

```bash
curl -i -H "Origin: https://woo-silk.vercel.app" \
  -H "Access-Control-Request-Method: GET" \
  -H "Access-Control-Request-Headers: Authorization" \
  -X OPTIONS \
  https://dev.murjan.sa/wp-json/murjan-api/v1/products
```

Expected response should include:
```
HTTP/1.1 200 OK
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
```

### Test 3: Postman

1. Create a new GET request: `https://dev.murjan.sa/wp-json/murjan-api/v1/products`
2. Add Basic Auth with your Consumer Key/Secret
3. Send the request
4. Check the **Headers** tab in the response
5. Verify CORS headers are present

## 📚 Additional Resources

- [MDN CORS Documentation](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)
- [WordPress REST API CORS](https://developer.wordpress.org/rest-api/using-the-rest-api/global-parameters/#cors)
- [Understanding Preflight Requests](https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request)

## 🆘 Still Need Help?

If you're still experiencing CORS issues:

1. ✅ Verify the plugin is installed and activated
2. ✅ Clear all caches (browser, WordPress, hosting)
3. ✅ Test with the browser console fetch command above
4. ✅ Check WordPress error logs
5. ✅ Contact your hosting provider (they might have security rules blocking CORS)

---

**CORS should now be working! 🎉**

If you followed all steps and it's still not working, open an issue on GitHub with:
- Screenshot of the error
- Browser console logs
- WordPress plugin list
- Hosting provider name

