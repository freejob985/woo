import { useState, useEffect } from 'react';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Loader2, CheckCircle2, XCircle, Info } from "lucide-react";
import { wooCommerceAPI } from "@/services/api";

interface ApiSetupModalProps {
  open: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export function ApiSetupModal({ open, onClose, onSuccess }: ApiSetupModalProps) {
  const [apiUrl, setApiUrl] = useState('');
  const [consumerKey, setConsumerKey] = useState('');
  const [consumerSecret, setConsumerSecret] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [testStatus, setTestStatus] = useState<'idle' | 'testing' | 'success' | 'error'>('idle');
  const [errorMessage, setErrorMessage] = useState('');

  useEffect(() => {
    // Load saved credentials from localStorage
    const savedUrl = localStorage.getItem('woo_api_url') || '';
    const savedKey = localStorage.getItem('woo_consumer_key') || '';
    const savedSecret = localStorage.getItem('woo_consumer_secret') || '';
    
    setApiUrl(savedUrl);
    setConsumerKey(savedKey);
    setConsumerSecret(savedSecret);
  }, [open]);

  const testConnection = async () => {
    if (!apiUrl || !consumerKey || !consumerSecret) {
      setErrorMessage('الرجاء إدخال جميع البيانات المطلوبة');
      setTestStatus('error');
      return;
    }

    setIsLoading(true);
    setTestStatus('testing');
    setErrorMessage('');

    try {
      // Update credentials temporarily
      wooCommerceAPI.updateCredentials(apiUrl, consumerKey, consumerSecret);
      
      // Test connection by fetching products
      await wooCommerceAPI.getProducts({ per_page: 1 });
      
      // Save to localStorage on success
      localStorage.setItem('woo_api_url', apiUrl);
      localStorage.setItem('woo_consumer_key', consumerKey);
      localStorage.setItem('woo_consumer_secret', consumerSecret);
      
      setTestStatus('success');
      
      // Close modal and notify success after 1.5 seconds
      setTimeout(() => {
        onSuccess();
        onClose();
      }, 1500);
      
    } catch (error: unknown) {
      console.error('Connection test failed:', error);
      setTestStatus('error');
      
      const err = error as { isCorsError?: boolean; isAuthError?: boolean; message?: string };
      
      if (err.isCorsError) {
        setErrorMessage(
          '❌ خطأ CORS - لا يمكن الاتصال بالـ API:\n' +
          '1. تأكد من تفعيل WordPress plugin\n' +
          '2. تحقق من إعدادات CORS في الخادم\n' +
          '3. راجع بيانات API\n\n' +
          'CORS Error: Cannot connect to API. Please ensure:\n' +
          '1. WordPress plugin is activated\n' +
          '2. CORS headers are properly configured (check cors-headers.php)\n' +
          '3. API credentials are correct\n' +
          '4. Your domain is added to allowed origins list'
        );
      } else if (err.isAuthError) {
        setErrorMessage(
          '🔐 خطأ في المصادقة - Authentication failed.\n' +
          'تحقق من Consumer Key و Consumer Secret'
        );
      } else {
        setErrorMessage(err.message || 'فشل الاتصال بالـ API');
      }
    } finally {
      setIsLoading(false);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    testConnection();
  };

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-[600px]" dir="rtl">
        <DialogHeader>
          <DialogTitle className="text-2xl text-center">
            إعداد الاتصال بـ WooCommerce API
          </DialogTitle>
          <DialogDescription className="text-center">
            أدخل بيانات API للاتصال بمتجر WooCommerce الخاص بك
          </DialogDescription>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Info Alert */}
          <Alert>
            <Info className="h-4 w-4" />
            <AlertDescription className="text-sm">
              يمكنك الحصول على مفاتيح API من: <br />
              <strong>لوحة WordPress</strong> → <strong>WooCommerce</strong> → <strong>الإعدادات</strong> → <strong>متقدم</strong> → <strong>REST API</strong>
            </AlertDescription>
          </Alert>

          {/* API URL */}
          <div className="space-y-2">
            <Label htmlFor="apiUrl" className="text-right block">
              رابط API
            </Label>
            <Input
              id="apiUrl"
              type="url"
              placeholder="https://dev.murjan.sa/wp-json/murjan-api/v1"
              value={apiUrl}
              onChange={(e) => setApiUrl(e.target.value)}
              dir="ltr"
              className="text-left"
              disabled={isLoading}
              required
            />
            <p className="text-xs text-muted-foreground text-right">
              مثال: https://yoursite.com/wp-json/murjan-api/v1
            </p>
          </div>

          {/* Consumer Key */}
          <div className="space-y-2">
            <Label htmlFor="consumerKey" className="text-right block">
              Consumer Key
            </Label>
            <Input
              id="consumerKey"
              type="text"
              placeholder="ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
              value={consumerKey}
              onChange={(e) => setConsumerKey(e.target.value)}
              dir="ltr"
              className="text-left font-mono"
              disabled={isLoading}
              required
            />
          </div>

          {/* Consumer Secret */}
          <div className="space-y-2">
            <Label htmlFor="consumerSecret" className="text-right block">
              Consumer Secret
            </Label>
            <Input
              id="consumerSecret"
              type="password"
              placeholder="cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
              value={consumerSecret}
              onChange={(e) => setConsumerSecret(e.target.value)}
              dir="ltr"
              className="text-left font-mono"
              disabled={isLoading}
              required
            />
          </div>

          {/* Status Messages */}
          {testStatus === 'testing' && (
            <Alert className="bg-blue-50 border-blue-200">
              <Loader2 className="h-4 w-4 animate-spin" />
              <AlertDescription>
                جاري اختبار الاتصال بالـ API...
              </AlertDescription>
            </Alert>
          )}

          {testStatus === 'success' && (
            <Alert className="bg-green-50 border-green-200">
              <CheckCircle2 className="h-4 w-4 text-green-600" />
              <AlertDescription className="text-green-800">
                ✅ تم الاتصال بنجاح! جاري تحميل البيانات...
              </AlertDescription>
            </Alert>
          )}

          {testStatus === 'error' && errorMessage && (
            <Alert className="bg-red-50 border-red-200">
              <XCircle className="h-4 w-4 text-red-600" />
              <AlertDescription className="text-red-800 whitespace-pre-line text-sm">
                {errorMessage}
              </AlertDescription>
            </Alert>
          )}

          <DialogFooter className="flex justify-between gap-2">
            <Button
              type="button"
              variant="outline"
              onClick={onClose}
              disabled={isLoading}
            >
              إلغاء
            </Button>
            <Button
              type="submit"
              disabled={isLoading || !apiUrl || !consumerKey || !consumerSecret}
            >
              {isLoading ? (
                <>
                  <Loader2 className="ml-2 h-4 w-4 animate-spin" />
                  جاري الاختبار...
                </>
              ) : (
                'اختبار الاتصال والحفظ'
              )}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  );
}

