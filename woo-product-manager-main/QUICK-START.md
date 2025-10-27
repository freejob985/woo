# ⚡ Quick Start Guide - WooCommerce Product Manager

Get up and running in **under 10 minutes**! 🚀

## 📋 Before You Start - Checklist

- [ ] Node.js 16+ installed
- [ ] WooCommerce store with admin access
- [ ] 10 minutes of your time ⏱️

## 🎯 Step-by-Step Setup

### Step 1: Install WordPress Plugin (2 minutes)

**This is CRITICAL to avoid CORS errors!**

1. Navigate to the `/api/` folder in this repository
2. Compress it into a `.zip` file
3. In WordPress Admin:
   - Go to **Plugins → Add New → Upload Plugin**
   - Upload the `.zip` file
   - Click **"Install Now"** then **"Activate"**

✅ **Verify**: You should see "Products API" in WordPress menu

---

### Step 2: Generate API Keys (2 minutes)

1. In WordPress Admin, go to:
   ```
   WooCommerce → Settings → Advanced → REST API
   ```

2. Click **"Add Key"**

3. Fill in:
   - **Description**: `React Product Manager`
   - **User**: Your admin user
   - **Permissions**: `Read/Write`

4. Click **"Generate API Key"**

5. **COPY IMMEDIATELY** (you won't see them again!):
   - Consumer Key (starts with `ck_`)
   - Consumer Secret (starts with `cs_`)

✅ **Verify**: Keys should be ~40 characters long

---

### Step 3: Setup React App (3 minutes)

1. **Clone & Install**
   ```bash
   git clone <YOUR_REPO_URL>
   cd woo-product-manager-main
   npm install
   ```

2. **Create `.env.local`** in the root:
   ```env
   VITE_WOOCOMMERCE_API_URL=https://yoursite.com/wp-json/murjan-api/v1
   VITE_WOOCOMMERCE_CONSUMER_KEY=ck_your_key_here
   VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_your_secret_here
   VITE_ITEMS_PER_PAGE=12
   ```

   **Replace**:
   - `yoursite.com` with your actual domain
   - `ck_your_key_here` with your Consumer Key
   - `cs_your_secret_here` with your Consumer Secret

3. **Start the app**
   ```bash
   npm run dev
   ```

4. **Open your browser**
   ```
   http://localhost:8080
   ```

✅ **Verify**: You should see the product dashboard

---

## 🎉 You're Done!

If you see your products loading, congratulations! 🎊

## 🐛 Troubleshooting Quick Fixes

### CORS Error?

```
Access to XMLHttpRequest has been blocked by CORS policy
```

**Fix**: The WordPress plugin is not activated.
1. Go to WordPress Admin → Plugins
2. Find "WooCommerce Products API Manager"
3. Click "Activate"
4. Refresh your React app

---

### API Not Configured?

```
API not configured. Please configure your WooCommerce API credentials.
```

**Fix**: Check your `.env.local` file
1. Make sure it exists in the project root
2. Verify all 3 required variables are set
3. No spaces around `=` sign
4. API URL ends with `/wp-json/murjan-api/v1`
5. Restart the dev server: `npm run dev`

---

### 401 Unauthorized?

```
401 Unauthorized
```

**Fix**: Wrong API credentials
1. Go back to WooCommerce → Settings → Advanced → REST API
2. Delete the old key
3. Create a new key with **Read/Write** permissions
4. Update `.env.local` with new keys
5. Restart the dev server

---

### No Products Showing?

**Fix**: Make sure you have products in WooCommerce
1. Go to WooCommerce → Products
2. Check if you have at least one published product
3. If not, add a test product in WordPress
4. Refresh your React app

---

## 📚 Next Steps

Now that you're set up:

1. **Read the full docs**: Check [README.md](./README.md) for all features
2. **Explore the app**: Try adding, editing, and deleting products
3. **Customize**: Modify the UI to match your brand
4. **Deploy**: Follow the deployment guide in README.md

---

## 🆘 Still Having Issues?

1. Check [ENV-SETUP-GUIDE.md](./ENV-SETUP-GUIDE.md) for detailed configuration
2. Read the Troubleshooting section in [README.md](./README.md)
3. Open an issue on GitHub with:
   - Error message (screenshot)
   - Browser console logs
   - Your setup (Node version, OS)

---

## ✅ Quick Reference

### Environment File (.env.local)
```env
VITE_WOOCOMMERCE_API_URL=https://yoursite.com/wp-json/murjan-api/v1
VITE_WOOCOMMERCE_CONSUMER_KEY=ck_xxxxxxxxxxxxx
VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_xxxxxxxxxxxxx
VITE_ITEMS_PER_PAGE=12
```

### Development Commands
```bash
npm install          # Install dependencies
npm run dev          # Start dev server
npm run build        # Build for production
npm run preview      # Preview production build
```

### API Endpoints (Auto-configured)
```
GET    /products                  # Get all products
GET    /products/search           # Search products
GET    /products/{id}             # Get single product
POST   /physical-products         # Create physical product
POST   /variable-products         # Create variable product
POST   /physical-products/{id}    # Update physical product
POST   /variable-products/{id}    # Update variable product
DELETE /physical-products/{id}    # Delete physical product
DELETE /variable-products/{id}    # Delete variable product
GET    /products/stats            # Get statistics
```

---

**Ready to manage products like a pro! 💪**


