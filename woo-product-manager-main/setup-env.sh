#!/bin/bash
# ====================================================================
# Script to create .env file with API credentials
# سكريبت لإنشاء ملف .env مع بيانات API
# ====================================================================

echo ""
echo "===================================================================="
echo "   Creating .env file with API credentials"
echo "   إنشاء ملف .env مع بيانات الاعتماد"
echo "===================================================================="
echo ""

# Check if .env already exists
if [ -f ".env" ]; then
    echo "[!] .env file already exists"
    echo "[!] ملف .env موجود بالفعل"
    echo ""
    read -p "Do you want to overwrite it? (y/n): " overwrite
    if [ "$overwrite" != "y" ] && [ "$overwrite" != "Y" ]; then
        echo ""
        echo "[x] Operation cancelled"
        echo "[x] تم إلغاء العملية"
        exit 0
    fi
fi

# Create .env file
cat > .env << 'EOF'
# WooCommerce API Configuration - إعدادات API
# ============================

# API Base URL - رابط API الأساسي
VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1

# WooCommerce Consumer Key - مفتاح المستهلك
VITE_WOOCOMMERCE_CONSUMER_KEY=ck_2210fb8d333da5da151029715a85618a4b283a52

# WooCommerce Consumer Secret - سر المستهلك
VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_7f1073e18d0af70d01c84692ce8c04609a722b5c

# Items per page - عدد العناصر في كل صفحة
VITE_ITEMS_PER_PAGE=12
EOF

echo ""
echo "===================================================================="
echo "   [✓] .env file created successfully!"
echo "   [✓] تم إنشاء ملف .env بنجاح!"
echo "===================================================================="
echo ""
echo "   API URL: https://dev.murjan.sa/wp-json/murjan-api/v1"
echo "   Consumer Key: ck_2210fb8d..."
echo "   Consumer Secret: cs_7f1073e1..."
echo ""
echo "===================================================================="
echo "   Next steps / الخطوات التالية:"
echo "===================================================================="
echo ""
echo "   1. Run: npm install"
echo "      تشغيل: npm install"
echo ""
echo "   2. Run: npm run dev"
echo "      تشغيل: npm run dev"
echo ""
echo "   3. Open: http://localhost:8080"
echo "      افتح: http://localhost:8080"
echo ""
echo "===================================================================="
echo ""

