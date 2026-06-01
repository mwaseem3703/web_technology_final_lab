<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f2edf3;
            font-family: 'Ubuntu', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 10px;
            border: none;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .bg-purple-gradient {
            background: linear-gradient(to right, #da8cff, #9a55ff);
        }
        .form-control {
            border-radius: 6px;
            padding: 12px 15px;
            border: 1px solid #ebedf2;
        }
        .form-control:focus {
            border-color: #b66dff;
            box-shadow: 0 0 0 0.25rem rgba(182, 109, 255, 0.25);
        }
        .btn-purple {
            background: linear-gradient(to right, #da8cff, #9a55ff);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            transition: opacity 0.3s ease;
        }
        .btn-purple:hover {
            opacity: 0.9;
            color: white;
        }
        .brand-text {
            color: #b66dff;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <div class="login-card p-5">
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#b66dff" class="bi bi-layers-fill" viewBox="0 0 16 16">
                  <path d="M7.765 1.559a.5.5 0 0 1 .47 0l7.5 4a.5.5 0 0 1 0 .882l-7.5 4a.5.5 0 0 1-.47 0l-7.5-4a.5.5 0 0 1 0-.882l7.5-4z"/>
                  <path d="m2.125 8.567-1.86.992a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882l-1.86-.992-5.17 2.756a1.5 1.5 0 0 1-1.41 0l-5.17-2.756z"/>
                </svg>
                <span class="brand-text">Admin</span>
            </div>
            <h5 class="text-muted fw-normal">Administrative Portal</h5>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Admin Username</label>
                <input type="text" name="email" class="form-control" placeholder="Enter 'admin'" required autofocus>
            </div>
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Master Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter 'admin'" required>
            </div>
            
            <button type="submit" class="btn btn-purple w-100 text-uppercase tracking-wider">
                Secure Login
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="/" class="text-muted small text-decoration-none">← Return to Student Portal</a>
        </div>
    </div>

</body>
</html>