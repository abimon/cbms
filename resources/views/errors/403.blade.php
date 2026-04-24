<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Access Denied | CBMS</title>
    <link href="https://fonts.bunny.net/css?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #dc3545 0%, #b02a37 50%, #212529 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-container { text-align: center; color: white; padding: 40px; }
        .error-code { font-size: 8rem; font-weight: 700; line-height: 1; text-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .error-title { font-size: 2rem; font-weight: 600; margin: 20px 0 10px; }
        .error-message { font-size: 1.1rem; opacity: 0.9; max-width: 500px; margin: 0 auto 30px; }
        .error-icon { font-size: 5rem; margin-bottom: 20px; opacity: 0.8; }
        .btn-light { padding: 12px 30px; font-weight: 500; border-radius: 8px; }
        .btn-outline-light { padding: 12px 30px; font-weight: 500; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">Access Denied</h1>
        <p class="error-message">
            You don't have permission to access this page. 
            Please contact the administrator if you believe this is an error.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ url('/home') }}" class="btn btn-light">
                <i class="bi bi-house-door me-2"></i>Go to Dashboard
            </a>
            <a href="{{ url()->previous() }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-2"></i>Go Back
            </a>
        </div>
    </div>
</body>
</html>