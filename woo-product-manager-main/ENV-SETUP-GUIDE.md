# 🔐 Environment Configuration Guide

## Quick Setup

Create a `.env.local` file in the root of the project and add the following:

```env
# WooCommerce API Configuration
VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
VITE_WOOCOMMERCE_CONSUMER_KEY=your_consumer_key_here
VITE_WOOCOMMERCE_CONSUMER_SECRET=your_consumer_secret_here

# Pagination Settings (optional)
VITE_ITEMS_PER_PAGE=12
```

## 📝 How to Get Your API Credentials

1. **Log in to your WordPress Admin Dashboard**

2. **Navigate to WooCommerce REST API Settings**
   - Go to: `WooCommerce` → `Settings` → `Advanced` → `REST API`

3. **Create New API Key**
   - Click "Add Key" or "Create an API Key"

4. **Fill in the API Key Form:**
   - **Description**: `WooCommerce Products Manager`
   - **User**: Select your admin user
   - **Permissions**: `Read/Write`

5. **Generate the Key**
   - Click "Generate API Key"
   - **⚠️ IMPORTANT**: Copy both keys immediately! You won't be able to see them again.

6. **Copy to .env.local**
   - Copy the `Consumer Key` to `VITE_WOOCOMMERCE_CONSUMER_KEY`
   - Copy the `Consumer Secret` to `VITE_WOOCOMMERCE_CONSUMER_SECRET`

## 🌐 API URL Format

Your API URL should follow this format:

```
https://yourdomain.com/wp-json/murjan-api/v1
```

**Examples:**
- Production: `https://store.example.com/wp-json/murjan-api/v1`
- Development: `https://dev.murjan.sa/wp-json/murjan-api/v1`
- Local: `http://localhost/wordpress/wp-json/murjan-api/v1`

## 🔒 CORS Setup (IMPORTANT!)

To avoid CORS errors, make sure:

1. ✅ The **"WooCommerce Products API Manager"** plugin is installed and activated on your WordPress site
2. ✅ Plugin location: `/api/woo-products-api.php`
3. ✅ The plugin automatically handles CORS headers - no manual configuration needed!

## 🛠️ Troubleshooting

### CORS Errors

```
Access to XMLHttpRequest has been blocked by CORS policy
```

**Solutions:**
1. Verify the plugin is activated in WordPress
2. Clear your browser cache
3. Make sure HTTPS is enabled if using SSL
4. Check that the API URL ends with `/wp-json/murjan-api/v1`

### 401 Unauthorized Errors

```
401 Unauthorized
```

**Solutions:**
1. Double-check your Consumer Key and Secret
2. Verify the API user has proper permissions (manage_woocommerce)
3. Make sure the keys haven't expired or been revoked
4. Check if your WordPress user has WooCommerce admin rights

### Products Don't Load

**Solutions:**
1. Test the API directly in your browser or Postman:
   ```
   GET https://yourdomain.com/wp-json/murjan-api/v1/products?per_page=12
   ```
2. Check WordPress error logs
3. Verify WooCommerce is installed and activated
4. Make sure you have at least one product in WooCommerce

## 📦 Example .env.local File

```env
# Production Configuration
VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
VITE_WOOCOMMERCE_CONSUMER_KEY=ck_1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p7q8r9s0t
VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_9z8y7x6w5v4u3t2s1r0q9p8o7n6m5l4k3j2i1h0g
VITE_ITEMS_PER_PAGE=12
```

## 🔐 Security Best Practices

1. ✅ **Never** commit `.env.local` to version control
2. ✅ Add `.env.local` to `.gitignore` (already done)
3. ✅ Use different API keys for development and production
4. ✅ Rotate your API keys regularly
5. ✅ Use HTTPS in production
6. ✅ Limit API key permissions to what's needed

## 🚀 Ready to Go!

Once you've configured your `.env.local` file:

```bash
# Install dependencies
npm install

# Start the development server
npm run dev
```

Your app should now connect to your WooCommerce store! 🎉

