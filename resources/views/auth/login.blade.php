<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRUD Projetos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="container">
        <!-- Painel Lateral -->
        <div class="side-panel">
            <div class="icon"><img src="{{ asset('images/logo.png') }}" alt="CRUD Projetos Logo" style="height: 50px; width: auto;"></div>
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
