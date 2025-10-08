<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PRODEB Projetos')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .logo-section h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #183b5cff 0%, #245380ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-buttons {
            display: flex;
            gap: 12px;
        }

        .header-buttons a {
            color: #4a5568;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
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
            max-width: 1200px;
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
            padding: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            max-height: 210px;
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
        }

        .footer-item:hover {
            color: #53b4e3;
        }

        .footer-item a {
            color: inherit;
            text-decoration: none;
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
                padding: 1rem;
            }

            .logo-section h1 {
                font-size: 1.2rem;
            }

            .header-buttons {
                gap: 8px;
            }

            .header-buttons a {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
            }

            main {
                padding: 1.5rem 1rem;
            }

            .content-wrapper {
                padding: 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-section">
            <h1>CRUD PROJETOS</h1>
        </div>
        <div class="header-buttons">
            <a href="#">Ver Perfil</a>
            <a href="#">Sair</a>
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
</body>
</html>
