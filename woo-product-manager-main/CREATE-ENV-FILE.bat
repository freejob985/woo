@echo off
REM ============================================
REM Create .env file automatically
REM ============================================

echo.
echo ============================================
echo   Creating .env file...
echo ============================================
echo.

REM Create .env file with credentials
(
echo # WooCommerce API Configuration
echo VITE_WOOCOMMERCE_API_URL=https://dev.murjan.sa/wp-json/murjan-api/v1
echo VITE_WOOCOMMERCE_CONSUMER_KEY=ck_2210fb8d333da5da151029715a85618a4b283a52
echo VITE_WOOCOMMERCE_CONSUMER_SECRET=cs_7f1073e18d0af70d01c84692ce8c04609a722b5c
) > .env

echo [SUCCESS] .env file created successfully!
echo.
echo ============================================
echo   Next steps:
echo ============================================
echo 1. Install dependencies: npm install
echo 2. Run the app: npm run dev
echo 3. Open: http://localhost:8080
echo.
echo ============================================

pause

