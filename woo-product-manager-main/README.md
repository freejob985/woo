# WooCommerce Product Manager

A modern, professional React.js dashboard for managing WooCommerce products via REST API.

![WooCommerce Product Manager](https://img.shields.io/badge/React-18.3.1-blue)
![TypeScript](https://img.shields.io/badge/TypeScript-5.x-blue)
![Vite](https://img.shields.io/badge/Vite-5.x-purple)

## 🚀 Features

### Product Management
- ✅ **Product Grid View**: Display products in beautiful, responsive cards
- ✅ **Add Products**: Create both Simple and Variable products
- ✅ **Edit Products**: Modify product details through organized modals
- ✅ **Delete Products**: Remove products with confirmation
- ✅ **Search**: Find products quickly with real-time search
- ✅ **Pagination**: Navigate through large product catalogs

### Product Details (5-Row Card Design)
Each product card displays:
1. **Row 1**: Thumbnail | Category | Product Type (Simple/Variable)
2. **Row 2**: Product Name | Availability | Price
3. **Rows 3-4**: Quick access buttons to edit sections:
   - General / Variations
   - Inventory
   - Shipping
   - Linked Products
   - Media
4. **Row 5**: Save button & Publishing settings

### User Experience
- 🌓 **Dark/Light Mode**: Toggle between themes
- 📱 **Responsive Design**: Works on all devices
- 🎨 **Modern UI**: Built with shadcn/ui components
- ⚡ **Fast Performance**: Optimized with Vite
- 🔒 **Type Safety**: Full TypeScript support

### API Integration
- Full CRUD operations (Create, Read, Update, Delete)
- Physical product management
- Variable product management (sizes, colors, etc.)
- Image upload and management
- Stock management
- Category and tag support
- Statistics dashboard

## 🛠️ Tech Stack

- **Framework**: React 18.3.1
- **Language**: TypeScript
- **Build Tool**: Vite
- **UI Library**: shadcn/ui + Tailwind CSS
- **State Management**: Context API
- **HTTP Client**: Axios
- **Form Handling**: React Hook Form
- **Routing**: React Router v6

## 📦 Installation

### Prerequisites
- Node.js 16+ and npm installed
- A WooCommerce store with REST API enabled
- WooCommerce API credentials (Consumer Key & Secret)
- **WooCommerce Products API Manager** plugin installed on WordPress (included in `/api/`)

### Quick Start

1. **Clone the repository**
```bash
git clone <YOUR_GIT_URL>
cd woocommerce-product-manager
```

2. **Install dependencies**
```bash
npm install
```

3. **Configure environment variables**

Create a `.env.local` file in the root directory:
```env
VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
VITE_WOOCOMMERCE_CONSUMER_KEY=ck_xxxxxxxxxxxxx
VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_xxxxxxxxxxxxx
VITE_ITEMS_PER_PAGE=12
```

📖 **Detailed setup guide**: See [ENV-SETUP-GUIDE.md](./ENV-SETUP-GUIDE.md)

4. **Start development server**
```bash
npm run dev
```

The app will be available at `http://localhost:8080`

## 🔌 Installing the WordPress Plugin (REQUIRED)

Before using this app, you **MUST** install the WooCommerce Products API Manager plugin on your WordPress site:

### Option 1: Upload via WordPress Admin

1. Locate the plugin folder: `/api/` in this repository
2. Compress the `/api/` folder into a `.zip` file
3. In WordPress Admin, go to: **Plugins → Add New → Upload Plugin**
4. Upload the `.zip` file
5. Click **"Install Now"** then **"Activate"**

### Option 2: FTP Upload

1. Upload the entire `/api/` folder to `/wp-content/plugins/` on your server
2. In WordPress Admin, go to **Plugins**
3. Find **"WooCommerce Products API Manager"**
4. Click **"Activate"**

### Verify Installation

After activation:
1. You should see a new menu item: **Products API** in WordPress Admin
2. The plugin automatically handles CORS headers
3. Your custom API endpoints will be available at: `https://yoursite.com/wp-json/murjan-api/v1`

## 🔑 Getting WooCommerce API Credentials

1. Log in to your WordPress admin dashboard
2. Navigate to: **WooCommerce → Settings → Advanced → REST API**
3. Click **"Add Key"**
4. Fill in the details:
   - Description: "React Product Manager"
   - User: Select your admin user
   - Permissions: **Read/Write**
5. Click **"Generate API Key"**
6. Copy the **Consumer Key** and **Consumer Secret**
7. Add them to your `.env.local` file (see ENV-SETUP-GUIDE.md)

## 📁 Project Structure

```
src/
├── components/
│   ├── Layout/
│   │   └── DashboardLayout.tsx    # Main layout with sidebar
│   ├── Products/
│   │   ├── ProductCard.tsx        # Individual product card
│   │   ├── ProductGrid.tsx        # Products grid display
│   │   ├── AddProductModal.tsx    # Add product dialog
│   │   └── EditProductModal.tsx   # Edit product sections
│   └── ui/                        # shadcn/ui components
├── contexts/
│   ├── ProductContext.tsx         # Product state management
│   └── ThemeContext.tsx           # Dark/Light mode
├── services/
│   └── api.ts                     # WooCommerce API client
├── types/
│   └── product.ts                 # TypeScript interfaces
├── pages/
│   ├── Index.tsx                  # Products page
│   ├── Settings.tsx               # API configuration
│   └── Stats.tsx                  # Statistics dashboard
└── App.tsx                        # Main app component
```

## 🎯 Usage

### Adding a Product

1. Click the **"Add Product"** button
2. Choose product type:
   - **Simple Product**: Standard product with one price
   - **Variable Product**: Product with variations (sizes, colors, etc.)
3. Fill in the required fields:
   - Product Name *
   - Regular Price *
   - Optional: Description, SKU, Categories, Images
4. Click **"Create Product"**

### Editing a Product

Click on any of the edit buttons in a product card:
- **General**: Edit name, description, price
- **Inventory**: Manage stock levels
- **Shipping**: Set dimensions and weight
- **Media**: Upload/manage images
- **Variations**: For variable products only

### Searching Products

Use the search bar at the top to find products by name or SKU.

### Changing Theme

Click the theme toggle button in the sidebar to switch between light and dark modes.

## 🔧 Configuration

### API Settings

You can update API credentials at runtime:
1. Navigate to **Settings** page (gear icon in sidebar)
2. Enter your API URL, Consumer Key, and Consumer Secret
3. Click **"Save Settings"**

### Customization

Edit `src/index.css` and `tailwind.config.ts` to customize:
- Colors and themes
- Spacing and sizing
- Animations and transitions

## 📊 Features Overview

### Product Card Actions

Each product card provides quick access to:
- **Edit Sections**: General, Inventory, Shipping, Linked, Media, Variations
- **Save**: Save all changes
- **Settings Menu**: Publishing options and delete

### Statistics Dashboard

View comprehensive store statistics:
- Total products count
- Stock status (In Stock / Out of Stock)
- Product types breakdown
- Sales information
- Average metrics

## 🐛 Troubleshooting

### CORS Errors

**Problem**: `Access to XMLHttpRequest has been blocked by CORS policy`

**Solution**: 
1. ✅ Install and activate the **"WooCommerce Products API Manager"** plugin on your WordPress site
2. ✅ Plugin location: `/api/woo-products-api.php` (included in this repository)
3. ✅ The plugin automatically adds required CORS headers
4. Clear your browser cache and refresh

The plugin adds these headers automatically:
```php
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Credentials: true
Access-Control-Allow-Headers: Authorization, Content-Type
```

### API Connection Issues

**Problem**: "API not configured" error
**Solution**: 
1. Check your `.env.local` file has correct credentials (see `ENV-SETUP-GUIDE.md`)
2. Verify your WooCommerce REST API is enabled
3. Ensure your store URL is correct and uses HTTPS
4. Make sure the API URL ends with `/wp-json/murjan-api/v1`

**Problem**: "Authentication failed" or 401 Unauthorized
**Solution**: 
1. Regenerate API keys in WooCommerce
2. Make sure permissions are set to "Read/Write"
3. Check for typos in Consumer Key/Secret
4. Verify your WordPress user has `manage_woocommerce` capability

### Build Issues

**Problem**: TypeScript errors
**Solution**: 
```bash
npm run build
```
If errors persist, delete `node_modules` and reinstall:
```bash
rm -rf node_modules package-lock.json
npm install
```

## 🚀 Deployment

### Build for Production

```bash
npm run build
```

The production-ready files will be in the `dist/` directory.

### Deploy to Vercel

```bash
npm install -g vercel
vercel
```

### Deploy to Netlify

1. Build the project: `npm run build`
2. Drag and drop the `dist/` folder to Netlify
3. Or use Netlify CLI:
```bash
npm install -g netlify-cli
netlify deploy --prod
```

## 📝 Environment Variables

| Variable | Description | Required |
|----------|-------------|----------|
| `VITE_WOOCOMMERCE_API_URL` | Your WooCommerce API base URL | Yes |
| `VITE_WOOCOMMERCE_CONSUMER_KEY` | WooCommerce Consumer Key | Yes |
| `VITE_WOOCOMMERCE_CONSUMER_SECRET` | WooCommerce Consumer Secret | Yes |
| `VITE_APP_NAME` | Application name | No |
| `VITE_ITEMS_PER_PAGE` | Products per page | No (default: 12) |

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📄 License

This project is open source and available under the MIT License.

## 🔗 Resources

- [WooCommerce REST API Documentation](https://woocommerce.github.io/woocommerce-rest-api-docs/)
- [React Documentation](https://react.dev)
- [Tailwind CSS Documentation](https://tailwindcss.com)
- [shadcn/ui Components](https://ui.shadcn.com)

## 📧 Support

For issues and questions:
- Open an issue on GitHub
- Check the troubleshooting section above
- Review the WooCommerce API documentation

---

**Built with ❤️ using React, TypeScript, and WooCommerce REST API**
