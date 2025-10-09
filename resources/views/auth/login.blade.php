<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRUD Projetos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #a1d7f0ff 0%, #f1f1f1ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 550px;
        }

        .side-panel {
            background: linear-gradient(135deg, #53b4e3 0%, #307192ff 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
        }

        .side-panel h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .side-panel p {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .side-panel .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .form-panel {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-container {
            display: none;
        }

        .form-container.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #718096;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #53b4e3;
            box-shadow: 0 0 0 3px rgba(83, 180, 227, 0.1);
        }

        .form-group input::placeholder {
            color: #a0aec0;
        }

        .error-message {
            display: none;
            color: #e53e3e;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .error-message.active {
            display: block;
        }

        .btn-primary {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #53b4e3 0%, #307192ff 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(83, 180, 227, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #718096;
            font-size: 0.9rem;
        }

        .form-footer a {
            color: #307192ff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: #53b4e3;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        .alert-success {
            background: #c6f6d5;
            color: #276749;
            border: 1px solid #9ae6b4;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .side-panel {
                display: none;
            }

            .form-panel {
                padding: 2rem 1.5rem;
            }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            min-width: 300px;
            max-width: 400px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
            font-size: 0.95rem;
            font-weight: 500;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast.hiding {
            animation: slideOut 0.3s ease forwards;
        }

        .toast-error {
            background: #fed7d7;
            color: #c53030;
            border-left: 4px solid #e53e3e;
        }

        .toast-success {
            background: #c6f6d5;
            color: #276749;
            border-left: 4px solid #38a169;
        }

        .toast-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .toast-message {
            flex: 1;
            word-break: break-word;
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .toast-close:hover {
            opacity: 1;
            background: rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 480px) {
            .toast-container {
                left: 10px;
                right: 10px;
                top: 10px;
            }

            .toast {
                min-width: auto;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="container">
        <!-- Painel Lateral -->
        <div class="side-panel">
            <div class="icon">📊</div>
            <h1>CRUD Projetos</h1>
            <p>Crud para registros de projetos, com objetivo de gerenciar projetos diversos. Faça login ou crie sua conta para começar!</p>
        </div>

        <!-- Painel de Formulário -->
        <div class="form-panel">
            <!-- Formulário de Login -->
            <div class="form-container active" id="loginForm">
                <h2 class="form-title">Bem-vindo!</h2>
                <p class="form-subtitle">Entre com suas credenciais</p>

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{route('login')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="login-email">E-mail</label>
                        <input
                            type="email"
                            id="login-email"
                            name="email"
                            placeholder="seu@email.com"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="login-password">Senha</label>
                        <input
                            type="password"
                            id="login-password"
                            name="password"
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-primary">Entrar</button>
                </form>

                <div class="form-footer">
                    Não tem uma conta? <a href="#" onclick="toggleForms(); return false;">Cadastre-se</a>
                </div>
            </div>

            <!-- Formulário de Registro -->
            <div class="form-container" id="registerForm">
                <h2 class="form-title">Criar Conta</h2>
                <p class="form-subtitle">Preencha os dados abaixo</p>

                @if( isset($errors) && $errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{route('register')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="register-name">Nome Completo</label>
                        <input
                            type="text"
                            id="register-name"
                            name="name"
                            placeholder="Seu nome completo"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="register-email">E-mail</label>
                        <input
                            type="email"
                            id="register-email"
                            name="email"
                            placeholder="seu@email.com"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="register-password">Senha</label>
                        <input
                            type="password"
                            id="register-password"
                            name="password"
                            placeholder="Mínimo 8 caracteres"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="register-password-confirm">Confirmar Senha</label>
                        <input
                            type="password"
                            id="register-password-confirm"
                            name="password_confirmation"
                            placeholder="Repita a senha"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-primary">Criar Conta</button>
                </form>

                <div class="form-footer">
                    Já tem uma conta? <a href="#" onclick="toggleForms(); return false;">Faça login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleForms() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            loginForm.classList.toggle('active');
            registerForm.classList.toggle('active');
        }

        // criar/exibir toast
        function showToast(message, type = 'error') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            const icon = type === 'error' ? '❌' : '✅';

            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="closeToast(this)">×</button>
            `;

            container.appendChild(toast);

            // Auto-remove
            setTimeout(() => {
                const closeButton = toast.querySelector('.toast-close');
                if (closeButton) closeToast(closeButton);
            }, 5000);
        }

        function closeToast(button) {
            const toast = button.closest('.toast');
            if (!toast) return;

            toast.classList.add('hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        // Se houver erros de validação no registro, mostra o formulário de registro
        @if(old('name') || (request()->is('register') && $errors->any()))
            toggleForms();
        @endif
    </script>
</body>
</html>
