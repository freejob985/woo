@echo off
REM ====================================================================
REM Script to create .env file with API credentials
REM سكريبت لإنشاء ملف .env مع بيانات API
REM ====================================================================

echo.
echo ====================================================================
echo    Creating .env file with API credentials
echo    إنشاء ملف .env مع بيانات الاعتماد
echo ====================================================================
echo.

REM Check if .env already exists
if exist ".env" (
    echo [!] .env file already exists
    echo [!] ملف .env موجود بالفعل
    echo.
    set /p "overwrite=Do you want to overwrite it? (y/n): "
    if /i not "%overwrite%"=="y" (
        echo.
        echo [x] Operation cancelled
        echo [x] تم إلغاء العملية
        pause
        exit /b
    )
)

REM Create .env file
echo # WooCommerce API Configuration - إعدادات API> .env
echo # ============================>> .env
echo.>> .env
echo # API Base URL - رابط API الأساسي>> .env
echo VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1>> .env
echo.>> .env
echo # WooCommerce Consumer Key - مفتاح المستهلك>> .env
echo VITE_WOOCOMMERCE_CONSUMER_KEY=ck_2210fb8d333da5da151029715a85618a4b283a52>> .env
echo.>> .env
echo # WooCommerce Consumer Secret - سر المستهلك>> .env
echo VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_7f1073e18d0af70d01c84692ce8c04609a722b5c>> .env
echo.>> .env
echo # Items per page - عدد العناصر في كل صفحة>> .env
echo VITE_ITEMS_PER_PAGE=12>> .env

echo.
echo ====================================================================
echo    [✓] .env file created successfully!
echo    [✓] تم إنشاء ملف .env بنجاح!
echo ====================================================================
echo.
echo    API URL: https://dev.murjan.sa/wp-json/murjan-api/v1
echo    Consumer Key: ck_2210fb8d...
echo    Consumer Secret: cs_7f1073e1...
echo.
echo ====================================================================
echo    Next steps / الخطوات التالية:
echo ====================================================================
echo.
echo    1. Run: npm install
echo       تشغيل: npm install
echo.
echo    2. Run: npm run dev
echo       تشغيل: npm run dev
echo.
echo    3. Open: http://localhost:8080
echo       افتح: http://localhost:8080
echo.
echo ====================================================================
echo.

pause

