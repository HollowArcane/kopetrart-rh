<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRH - Connexion</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ef 100%);
            min-height: 100vh;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
        }

        .login-header {
            background: var(--primary-color);
            padding: 2rem;
            color: white;
            text-align: center;
        }

        .login-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            padding: 0.8rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: none;
        }

        .input-group-text {
            background-color: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
        }

        .form-control {
            border-left: none;
        }

        .btn-login {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row min-vh-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="login-container">
                    <div class="login-header mb-4">
                        <i class="fas fa-users-cog"></i>
                        <h2>GRH System</h2>
                        <p class="mb-0">Gestion des Ressources Humaines</p>
                    </div>
                    
                    <div class="p-4 p-md-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf <!-- CSRF token for Laravel security -->                            
                            <div class="mb-4">
                                <label class="form-label">Identifiant</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="username" class="form-control" placeholder="Entrez votre identifiant">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </button>

                            <p class="text-center"><a href="/front/"> Se connecter en tant que client </a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>