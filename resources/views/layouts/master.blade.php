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

    @stack('styles')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #53b4e3 0%, #307192ff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #2d3748;
        }

        header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-section img {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        .logo-section h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #183b5cff 0%, #245380ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            white-space: nowrap;
        }

        .header-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .header-buttons a,
        .header-buttons button {
            color: #4a5568;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            white-space: nowrap;
            cursor: pointer;
        }

        .header-buttons button {
            background: #e53e3e;
            color: white;
            border: none;
        }

        .header-buttons button:hover {
            background: #c53030;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 62, 62, 0.4);
        }

        .header-buttons a:first-child {
            background: linear-gradient(135deg, #53b4e3 0%, #307192ff 100%);
            color: white;
        }

        .header-buttons a:first-child:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .header-buttons a:last-child {
            border: 2px solid #e2e8f0;
            background: white;
        }

        .header-buttons a:last-child:hover {
            border-color: #cbd5e0;
            background: #f7fafc;
        }

        main {
            flex: 1;
            padding: 2.5rem 2rem;
            max-width: 1500px;
            width: 100%;
            margin: 0 auto;
        }

        .content-wrapper {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            min-height: 400px;
        }

        footer {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 1.5rem 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .footer-section {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-section h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: #4a5568;
            transition: color 0.3s ease;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .footer-item:hover {
            color: #53b4e3;
        }

        .footer-item a {
            color: inherit;
            text-decoration: none;
            word-break: break-all;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            header {
                padding: 0.75rem 1rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .logo-section img {
                height: 40px;
            }

            .logo-section h1 {
                font-size: 1rem;
            }

            .header-buttons {
                gap: 6px;
                flex-wrap: wrap;
            }

            .header-buttons span {
                font-size: 0.8rem;
                margin-right: 0.5rem;
            }

            .header-buttons a,
            .header-buttons button {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            main {
                padding: 1rem 0.75rem;
            }

            .content-wrapper {
                padding: 1.25rem;
                border-radius: 12px;
            }

            footer {
                padding: 1.25rem 0.75rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .footer-section h3 {
                font-size: 0.85rem;
            }

            .footer-item {
                font-size: 0.85rem;
                align-items: flex-start;
            }

            .footer-item span:first-child {
                flex-shrink: 0;
                margin-top: 2px;
            }

            .footer-bottom {
                font-size: 0.8rem;
                margin-top: 1rem;
                padding-top: 1rem;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 0.6rem 0.75rem;
            }

            .logo-section img {
                height: 35px;
            }

            .logo-section h1 {
                font-size: 0.9rem;
            }

            .header-buttons a,
            .header-buttons button {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
            }

            .header-buttons span {
                display: none;
            }

            main {
                padding: 0.75rem 0.5rem;
            }

            .content-wrapper {
                padding: 1rem;
                border-radius: 10px;
            }

            .footer-item {
                font-size: 0.8rem;
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

        .toast-warning {
            background: #fefcbf;
            color: #744210;
            border-left: 4px solid #ed8936;
        }

        .toast-info {
            background: #bee3f8;
            color: #2c5282;
            border-left: 4px solid #3182ce;
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

    <header>
        <div class="logo-section">
            <a href="{{ route('home') }}" style="text-decoration: none; display: flex; align-items: center;">
                <img src="{{ asset('images/logo.png') }}" alt="CRUD Projetos Logo" style="height: 50px; width: auto;">
            </a>
        </div>
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
