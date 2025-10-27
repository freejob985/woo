import React, { Component, ErrorInfo, ReactNode } from 'react';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { AlertCircle, RefreshCw } from 'lucide-react';

interface Props {
  children: ReactNode;
}

interface State {
  hasError: boolean;
  error: Error | null;
  errorInfo: ErrorInfo | null;
}

class ErrorBoundary extends Component<Props, State> {
  constructor(props: Props) {
    super(props);
    this.state = {
      hasError: false,
      error: null,
      errorInfo: null,
    };
  }

  static getDerivedStateFromError(error: Error): State {
    return {
      hasError: true,
      error,
      errorInfo: null,
    };
  }

  componentDidCatch(error: Error, errorInfo: ErrorInfo) {
    console.error('❌ ErrorBoundary caught an error:', error, errorInfo);
    this.setState({
      error,
      errorInfo,
    });
  }

  handleReset = () => {
    this.setState({
      hasError: false,
      error: null,
      errorInfo: null,
    });
    window.location.reload();
  };

  render() {
    if (this.state.hasError) {
      return (
        <div className="min-h-screen flex items-center justify-center p-6 bg-background">
          <div className="max-w-2xl w-full space-y-4">
            <Alert variant="destructive">
              <AlertCircle className="h-5 w-5" />
              <AlertTitle className="text-xl font-bold">
                حدث خطأ غير متوقع
                <br />
                Unexpected Error Occurred
              </AlertTitle>
              <AlertDescription className="mt-4 space-y-4">
                <div className="bg-destructive/10 p-4 rounded-md">
                  <p className="font-mono text-sm break-all">
                    {this.state.error?.message || 'Unknown error'}
                  </p>
                </div>
                
                {process.env.NODE_ENV === 'development' && this.state.errorInfo && (
                  <details className="mt-4">
                    <summary className="cursor-pointer font-semibold mb-2">
                      Error Details (Development Only)
                    </summary>
                    <pre className="bg-muted p-4 rounded-md text-xs overflow-auto max-h-64">
                      {this.state.errorInfo.componentStack}
                    </pre>
                  </details>
                )}

                <div className="flex gap-2 mt-4">
                  <Button onClick={this.handleReset} className="gap-2">
                    <RefreshCw className="h-4 w-4" />
                    إعادة تحميل الصفحة / Reload Page
                  </Button>
                  <Button
                    variant="outline"
                    onClick={() => window.location.href = '/'}
                  >
                    العودة للرئيسية / Go Home
                  </Button>
                </div>

                <div className="mt-4 text-sm text-muted-foreground">
                  <p>إذا استمرت المشكلة، يرجى:</p>
                  <ul className="list-disc list-inside mt-2 space-y-1">
                    <li>مسح ذاكرة التخزين المؤقت للمتصفح</li>
                    <li>التحقق من إعدادات الـ API في ملف .env</li>
                    <li>التأكد من تفعيل WordPress plugin</li>
                    <li>مراجعة console للحصول على تفاصيل إضافية</li>
                  </ul>
                </div>
              </AlertDescription>
            </Alert>
          </div>
        </div>
      );
    }

    return this.props.children;
  }
}

export default ErrorBoundary;

