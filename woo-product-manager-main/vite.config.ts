import { defineConfig } from "vite";
import react from "@vitejs/plugin-react-swc";
import path from "path";
import { componentTagger } from "lovable-tagger";

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => ({
  server: {
    host: "::",
    port: 8080,
    cors: {
      origin: '*',
      methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'],
      allowedHeaders: ['Authorization', 'Content-Type', 'X-WP-Nonce', 'X-Requested-With', 'Accept', 'Origin', 'X-Api-Key'],
      exposedHeaders: ['X-WP-Total', 'X-WP-TotalPages'],
      credentials: true,
      maxAge: 86400,
    },
    proxy: {
      '/api': {
        target: 'https://dev.murjan.sa',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path.replace(/^\/api/, '/wp-json/murjan-api/v1'),
        configure: (proxy, _options) => {
          proxy.on('error', (err, _req, _res) => {
            console.log('❌ Proxy error:', err);
          });
          proxy.on('proxyReq', (proxyReq, req, _res) => {
            console.log('🚀 Proxy request:', req.method, req.url);
            // Add CORS headers to proxy request
            proxyReq.setHeader('Origin', 'https://woo-4pdx.vercel.app');
          });
          proxy.on('proxyRes', (proxyRes, req, _res) => {
            console.log('✅ Proxy response:', proxyRes.statusCode, req.url);
            // Ensure CORS headers are present in response
            proxyRes.headers['access-control-allow-origin'] = '*';
            proxyRes.headers['access-control-allow-credentials'] = 'true';
            proxyRes.headers['access-control-allow-methods'] = 'GET, POST, PUT, DELETE, OPTIONS, PATCH';
            proxyRes.headers['access-control-allow-headers'] = 'Authorization, Content-Type, X-WP-Nonce, X-Requested-With, Accept, Origin, X-Api-Key';
          });
        },
      },
    },
  },
  plugins: [react(), mode === "development" && componentTagger()].filter(Boolean),
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },
}));
