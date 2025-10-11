<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PRODEB Projetos')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    @stack('styles')

</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    <header>
        <div class="logo-section">
            <a href="{{ route('home') }}" style="text-decoration: none; display: flex; align-items: center;">
                <img src="{{ asset('images/logo.png') }}" alt="CRUD Projetos Logo" style="height: 50px; width: auto;">
            </a>
        </div>

        <!-- Menu de Navegação Central -->
        <nav class="main-nav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                📊 Projetos
            </a>
            <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                📋 Tarefas
            </a>
        </nav>

        <div class="header-buttons">
            @auth
                <span style="color: #4a5568; font-weight: 500; margin-right: 1rem;">
                    👤 {{ Auth::user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="
                        background: #e53e3e;
                        color: white;
                        padding: 0.6rem 1.2rem;
                        border: none;
                        border-radius: 8px;
                        font-weight: 500;
                        font-size: 0.9rem;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    ">
                        Sair
                    </button>
                </form>
            @endauth
        </div>
    </header>

    <main>
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contato</h3>
                <div class="footer-item">
                    <span>📞</span>
                    <a href="tel:+5571986949813">71 98694-9813</a>
                </div>
                <div class="footer-item">
                    <span>📧</span>
                    <a href="mailto:thiagoguilherme.barbosaa@gmail.com">thiagoguilherme.barbosaa@gmail.com</a>
                </div>
                <div class="footer-item">
                    <span>📍</span>
                    <span>Salvador, BA</span>
                </div>
            </div>

            <div class="footer-section">
                <h3>Desenvolvedor</h3>
                <div class="footer-item">
                    <span>👤</span>
                    <span>Thiago Guilherme</span>
                </div>
            </div>

            <div class="footer-section">
                <h3>Redes Sociais</h3>
                <div class="footer-item">
                    <span>🐙</span>
                    <a href="https://github.com/ThiagoGuilherme71" target="_blank">GitHub</a>
                </div>
                <div class="footer-item">
                    <span>🔗</span>
                    <a href="https://linkedin.com/in/thiagoguilhermebarbosa" target="_blank">LinkedIn</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 Desafio Crud Projetos </p>
        </div>
    </footer>

    <script>
        // Função para criar e exibir toast
        function showToast(message, type = 'error') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            const icons = {
                error: '❌',
                success: '✅',
                warning: '⚠️',
                info: 'ℹ️'
            };

            const icon = icons[type] || '❌';

            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="closeToast(this)">×</button>
            `;

            container.appendChild(toast);

            // Auto-remove após 5 segundos
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

        // Exibir erros de validação do Laravel
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif

        // Exibir mensagens de erro da sessão
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        // Exibir mensagens de sucesso
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        // Exibir mensagens de aviso
        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif

        // Exibir mensagens informativas
        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif
    </script>

    <!-- jQuery (necessário para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    @stack('scripts')
</body>
</html>
