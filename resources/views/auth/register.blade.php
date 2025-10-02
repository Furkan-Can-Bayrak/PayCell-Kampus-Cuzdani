<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kayıt Ol - Kampüs Cüzdan</title>
    <style>
        :root { 
            --bg: #000816; 
            --panel: #071223; 
            --muted: #94a3b8; 
            --text: #e8edf6; 
            --accent: #ffcc00; 
            --accent-2: #003a70; 
            --danger: #ef4444; 
            --warning: #ffcc00; 
            --card: #06101f; 
            --border: #0f2747; 
        }
        * { box-sizing: border-box; }
        html, body { 
            margin: 0; 
            padding: 0; 
            background: var(--bg); 
            color: var(--text); 
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; 
            min-height: 100vh;
        }
        
        #stars { 
            position: fixed; 
            inset: 0; 
            width: 100%; 
            height: 100%; 
            z-index: 0; 
            background: radial-gradient(1400px 700px at 80% -10%, rgba(0,58,112,.35), transparent 62%), radial-gradient(900px 480px at 12% 112%, rgba(10,32,64,.6), transparent 60%), linear-gradient(180deg, #000a1a 0%, #00040b 100%); 
        }
        
        .auth-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-card {
            background: linear-gradient(180deg, #071223, #060f1e);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,.35);
            width: 100%;
            max-width: 400px;
        }
        
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            justify-content: center;
        }
        
        .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #001;
            box-shadow: 0 0 0 2px rgba(255,204,0,.15) inset;
            font-size: 18px;
        }
        
        .brand-title {
            font-weight: 700;
            letter-spacing: .3px;
            font-size: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: #0b1a33;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,204,0,.1);
        }
        
        .form-input::placeholder {
            color: var(--muted);
        }
        
        .btn {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 16px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #ffe066);
            color: #001;
            box-shadow: 0 6px 20px rgba(255,204,0,.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(255,204,0,.3);
        }
        
        .btn-secondary {
            background: #0b1a33;
            color: var(--text);
            border: 1px solid var(--border);
        }
        
        .btn-secondary:hover {
            background: #0d2142;
            transform: translateY(-1px);
        }
        
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }
        
        .auth-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-link:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: var(--muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.2s ease;
        }
        
        .back-home:hover {
            color: var(--accent);
        }
        
        .password-requirements {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <canvas id="stars"></canvas>
    
    <a href="/" class="back-home">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5m7-7-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Ana Sayfa
    </a>
    
    <div class="auth-container">
        <div class="auth-card">
            <div class="brand">
                <div class="brand-logo">KC</div>
                <div class="brand-title">Kampüs Cüzdan</div>
            </div>
            
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Ad Soyad</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input" 
                        placeholder="Adınız ve soyadınız"
                        value="{{ old('name') }}"
                        required 
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">E-posta</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="ornek@university.edu.tr"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Şifre</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="En az 8 karakter"
                        required
                    >
                    <div class="password-requirements">
                        Şifre en az 8 karakter olmalıdır
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Şifre Tekrar</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input" 
                        placeholder="Şifrenizi tekrar girin"
                        required
                    >
                </div>
                
                <button type="submit" class="btn btn-primary">
                    Kayıt Ol
                </button>
            </form>
            
            <div class="auth-links">
                <p>Zaten hesabınız var mı? <a href="{{ route('login') }}" class="auth-link">Giriş Yap</a></p>
            </div>
        </div>
    </div>
    
    <script>
        // Starfield animation
        (function(){
            const canvas = document.getElementById('stars');
            const ctx = canvas.getContext('2d');
            let stars = [];
            let tick = 0;
            
            function resize(){
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                generate();
                draw();
            }
            
            function generate(){
                const count = Math.min(800, Math.floor((canvas.width*canvas.height)/4000));
                stars = new Array(count).fill(0).map(()=>({
                    x: Math.random()*canvas.width,
                    y: Math.random()*canvas.height,
                    r: Math.random()*1.2 + 0.3,
                    a: Math.random()*0.6 + 0.25
                }));
            }
            
            function draw(){
                ctx.clearRect(0,0,canvas.width,canvas.height);
                tick++;
                
                for(const s of stars){
                    ctx.beginPath();
                    ctx.fillStyle = `rgba(235,242,255,${s.a})`;
                    ctx.arc(s.x, s.y, s.r, 0, Math.PI*2);
                    ctx.fill();
                    
                    // subtle twinkle
                    if(Math.random() < 0.01){
                        ctx.fillStyle = 'rgba(255,204,0,0.4)';
                        ctx.fillRect(s.x-0.5, s.y-0.5, 1, 1);
                    }
                }
                
                requestAnimationFrame(draw);
            }
            
            window.addEventListener('resize', resize);
            resize();
        })();
    </script>
</body>
</html>
