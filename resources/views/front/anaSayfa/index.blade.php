<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Kampüs Dijital Cüzdan - Ana Ekran</title>
    <style>
      :root { --bg:#000816; --panel:#071223; --muted:#94a3b8; --text:#e8edf6; --accent:#ffcc00; --accent-2:#003a70; --danger:#ef4444; --warning:#ffcc00; --card:#06101f; --border:#0f2747; }
      * { box-sizing: border-box; }
      html,body { margin:0; padding:0; background:var(--bg); color:var(--text); font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; }
      a { color: inherit; text-decoration: none; }
      #stars { position:fixed; inset:0; width:100%; height:100%; z-index:0; background: radial-gradient(1400px 700px at 80% -10%, rgba(0,58,112,.35), transparent 62%), radial-gradient(900px 480px at 12% 112%, rgba(10,32,64,.6), transparent 60%), linear-gradient(180deg, #000a1a 0%, #00040b 100%); }
      .container { max-width: 1200px; margin: 0 auto; padding: 16px; position:relative; z-index:1; }
      .topbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 0; }
      .brand { display:flex; align-items:center; gap:12px; }
      .brand-logo { width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg, var(--accent-2), var(--accent)); display:flex; align-items:center; justify-content:center; font-weight:700; color:#001; box-shadow:0 0 0 2px rgba(255,204,0,.15) inset; }
      .brand-title { font-weight:700; letter-spacing:.3px; }
      .user-area { display:flex; align-items:center; gap:12px; position:relative; }
      .avatar { width:40px; height:40px; border-radius:999px; background:#0b1a33; border:1px solid var(--border); display:flex; align-items:center; justify-content:center; font-weight:700; color:#ffdd33; }
      .user-info { display:flex; flex-direction:column; line-height:1.1; }
      .user-name { font-weight:600; }
      .user-sub { font-size:12px; color:var(--muted); }
      .icon-btn { width:40px; height:40px; border-radius:10px; background:#0b1a33; display:grid; place-items:center; cursor:pointer; border:1px solid var(--border); transition:.2s ease; }
      .icon-btn:hover { transform: translateY(-1px); background:#0d2142; }
      .bell { position:relative; }
      .badge { position:absolute; top:-4px; right:-4px; background:var(--accent); color:#001; font-size:10px; padding:2px 6px; border-radius:999px; border:1px solid rgba(255,255,255,.1); font-weight:700; }

      .grid { display:grid; grid-template-columns: 1fr; gap:16px; }
      @media(min-width: 960px){ .grid { grid-template-columns: 2fr 1fr; } }

      .card { background: linear-gradient(180deg, #071223, #060f1e); border:1px solid var(--border); border-radius:16px; padding:16px; box-shadow: 0 10px 30px rgba(0,0,0,.35); }
      .balance-card { position:relative; overflow:hidden; }
      .balance-row { display:flex; align-items:end; justify-content:space-between; gap:12px; }
      .balance-amount { font-size:34px; font-weight:800; letter-spacing:.5px; }
      .balance-label { color:var(--muted); font-size:13px; }
      .quick-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap:10px; margin-top:16px; }
      @media(min-width:640px){ .quick-grid { grid-template-columns: repeat(4, 1fr);} }
      .quick { background:#0b1a33; border:1px solid var(--border); border-radius:12px; padding:12px; display:flex; align-items:center; gap:10px; cursor:pointer; transition:.2s; }
      .quick:hover { background:#0d2142; transform: translateY(-1px); }
      .quick .qicon { width:38px; height:38px; border-radius:10px; display:grid; place-items:center; font-weight:700; }
      .q-qr { background:rgba(0,58,112,.18); color:var(--accent-2); }
      .q-send { background:rgba(255,204,0,.18); color:var(--accent); }
      .q-load { background:rgba(0,58,112,.18); color:#5eb3ff; }
      .q-cashback { background:rgba(255,204,0,.18); color:var(--warning); }
      .qtext { display:flex; flex-direction:column; }
      .qtitle { font-weight:600; font-size:14px; }
      .qsub { font-size:12px; color:var(--muted); }

      .section-title { font-size:14px; color:var(--muted); margin:8px 0 12px; }
      .list { display:flex; flex-direction:column; gap:10px; }
      .item { display:flex; align-items:center; justify-content:space-between; gap:10px; background:#0b1a33; border:1px solid var(--border); border-radius:12px; padding:12px; }
      .item-left { display:flex; align-items:center; gap:12px; }
      .item-icon { width:40px; height:40px; border-radius:10px; display:grid; place-items:center; font-weight:700; }
      .item-title { font-weight:600; }
      .item-sub { font-size:12px; color:var(--muted); }
      .amount-pos { color:var(--accent); font-weight:700; }
      .amount-neg { color:#ef4444; font-weight:700; }

      .sidebar { position:sticky; top:12px; height:fit-content; }

      .notif-panel { position:absolute; top:48px; right:0; width:340px; max-width:90vw; background:#06101f; border:1px solid var(--border); border-radius:16px; padding:12px; box-shadow:0 10px 30px rgba(0,0,0,.45); display:none; z-index:50; }
      .notif-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
      .chip { font-size:11px; color:#001; background:var(--accent); border:1px solid rgba(0,0,0,.1); padding:4px 8px; border-radius:999px; font-weight:700; }
      .actions { display:flex; align-items:center; gap:8px; }
      .btn { border:1px solid var(--border); background:#0b1a33; color:var(--text); padding:8px 10px; border-radius:10px; cursor:pointer; transition:.2s; font-size:13px; }
      .btn:hover { background:#0d2142; }
      .btn-primary { background:linear-gradient(135deg, var(--accent), #ffe066); color:#001; border:none; box-shadow:0 6px 20px rgba(255,204,0,.2); }
      .btn-danger { background:#201414; border-color:#3a2828; color:#fca5a5; }

      .menu-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap:10px; }
      @media(min-width:840px){ .menu-grid { grid-template-columns: repeat(4, 1fr);} }
      .menu-item { background:#0b1a33; border:1px solid var(--border); border-radius:12px; padding:14px; display:flex; flex-direction:column; gap:8px; cursor:pointer; transition:.2s; }
      .menu-item:hover { background:#0d2142; transform: translateY(-1px); box-shadow:0 8px 20px rgba(0,58,112,.25); }
      .menu-icon { width:42px; height:42px; border-radius:10px; display:grid; place-items:center; background:rgba(255,204,0,.15); color:#ffdd33; font-weight:700; }
      .muted { color:var(--muted); }
      
      .share-btn { width:28px; height:28px; border-radius:8px; background:#0b1a33; border:1px solid var(--border); display:grid; place-items:center; cursor:pointer; transition:.2s; }
      .share-btn:hover { background:#0d2142; transform: translateY(-1px); }
      .share-btn svg { transition:.2s; }
      .share-btn:hover svg { fill: var(--accent); }
      
      .profile-panel { position:absolute; top:48px; right:0; width:320px; max-width:90vw; background:#06101f; border:1px solid var(--border); border-radius:16px; padding:12px; box-shadow:0 10px 30px rgba(0,0,0,.45); display:none; z-index:50; }
      .profile-header { display:flex; align-items:center; gap:12px; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--border); }
      .profile-avatar { width:48px; height:48px; border-radius:999px; background:#0b1a33; border:1px solid var(--border); display:flex; align-items:center; justify-content:center; font-weight:700; color:#ffdd33; font-size:18px; }
      .profile-info { flex:1; }
      .profile-name { font-weight:700; font-size:16px; }
      .profile-email { font-size:12px; color:var(--muted); margin-top:2px; }
      .profile-menu { display:flex; flex-direction:column; gap:8px; }
      .profile-item { display:flex; align-items:center; gap:12px; padding:10px; border-radius:10px; cursor:pointer; transition:.2s; }
      .profile-item:hover { background:#0b1a33; }
      .profile-icon { width:32px; height:32px; border-radius:8px; display:grid; place-items:center; background:rgba(255,204,0,.15); color:#ffdd33; }
      .profile-text { flex:1; }
      .profile-title { font-weight:600; font-size:14px; }
      .profile-desc { font-size:11px; color:var(--muted); margin-top:1px; }
    </style>
  </head>
  <body>
    <canvas id="stars"></canvas>
    <div class="container">
      <div class="topbar">
        <div class="brand">
          <div class="brand-logo">KC</div>
          <div class="brand-title">Kampüs Cüzdan</div>
        </div>
        <div class="user-area">
          <div class="icon-btn bell" id="notifBtn" aria-label="Bildirimler">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Zm6-6V11a6 6 0 1 0-12 0v5l-2 2v1h18v-1l-2-2Z" fill="#ffdd33"/>
            </svg>
            <span class="badge" id="notifCount">3</span>
          </div>
          <div class="user-info">
            <div class="user-name">Ali Veli</div>
            <div class="user-sub">Öğrenci</div>
          </div>
          <div class="avatar" id="profileBtn">AV</div>

          <div class="profile-panel" id="profilePanel">
            <div class="profile-header">
              <div class="profile-avatar">AV</div>
              <div class="profile-info">
                <div class="profile-name">Ali Veli</div>
                <div class="profile-email">ali.veli@university.edu.tr</div>
              </div>
            </div>
            <div class="profile-menu">
              <div class="profile-item" data-action="profile">
                <div class="profile-icon">👤</div>
                <div class="profile-text">
                  <div class="profile-title">Profil Ayarları</div>
                  <div class="profile-desc">Kişisel bilgilerini düzenle</div>
                </div>
              </div>
              <div class="profile-item" data-action="security">
                <div class="profile-icon">🔒</div>
                <div class="profile-text">
                  <div class="profile-title">Güvenlik</div>
                  <div class="profile-desc">Şifre ve güvenlik ayarları</div>
                </div>
              </div>
              <div class="profile-item" data-action="notifications">
                <div class="profile-icon">🔔</div>
                <div class="profile-text">
                  <div class="profile-title">Bildirimler</div>
                  <div class="profile-desc">Bildirim tercihlerini yönet</div>
                </div>
              </div>
              <div class="profile-item" data-action="privacy">
                <div class="profile-icon">🛡️</div>
                <div class="profile-text">
                  <div class="profile-title">Gizlilik</div>
                  <div class="profile-desc">Gizlilik ve veri ayarları</div>
                </div>
              </div>
              <div class="profile-item" data-action="help">
                <div class="profile-icon">❓</div>
                <div class="profile-text">
                  <div class="profile-title">Yardım</div>
                  <div class="profile-desc">SSS ve destek</div>
                </div>
              </div>
              <div class="profile-item" data-action="logout">
                <div class="profile-icon">🚪</div>
                <div class="profile-text">
                  <div class="profile-title">Çıkış Yap</div>
                  <div class="profile-desc">Hesabından güvenle çık</div>
                </div>
              </div>
            </div>
          </div>

          <div class="notif-panel" id="notifPanel">
            <div class="notif-header">
              <div style="font-weight:700">Bildirimler</div>
              <span class="chip" id="notifChip">3 yeni</span>
            </div>
            <div class="section-title">Gelen Para Hareketleri</div>
            <div class="list" id="notifIncomings"></div>
            <div class="section-title" style="margin-top:14px">Paylaşım İstekleri</div>
            <div class="list" id="notifShares"></div>
          </div>
        </div>
      </div>

      <div class="grid">
        <div class="main">
          <div class="card balance-card">
            <div class="balance-row">
              <div>
                <div class="balance-label">Güncel Bakiye</div>
                <div class="balance-amount" id="balanceAmount">₺ 2.450,75</div>
              </div>
              <button class="btn btn-primary" id="addMoneyBtn">Para Yükle</button>
            </div>
            <div class="quick-grid">
              <div class="quick" data-action="qr">
              <div class="qicon q-qr">QR</div>
                <div class="qtext">
                  <div class="qtitle">QR Kod</div>
                  <div class="qsub">Hızlı ödeme</div>
                </div>
              </div>
              <div class="quick" data-action="send">
              <div class="qicon q-send">→</div>
                <div class="qtext">
                  <div class="qtitle">Para Gönder</div>
                  <div class="qsub">Arkadaşına</div>
                </div>
              </div>
              <div class="quick" data-action="load">
              <div class="qicon q-load">+</div>
                <div class="qtext">
                  <div class="qtitle">Para Yükle</div>
                  <div class="qsub">Karttan</div>
                </div>
              </div>
              <div class="quick" data-action="cashback">
              <div class="qicon q-cashback">%</div>
                <div class="qtext">
                  <div class="qtitle">Cashback</div>
                  <div class="qsub">Kazanımlar</div>
                </div>
              </div>
            </div>
          </div>

          <div style="margin-top:16px" class="card">
            <div class="section-title">Menüler</div>
            <div class="menu-grid">
              <div class="menu-item" data-nav="butce">
                <div class="menu-icon">₺</div>
                <div style="font-weight:600">Bütçe Kartları</div>
                <div class="muted">Harcamaları yönet</div>
              </div>
              <div class="menu-item" data-nav="yukle">
                <div class="menu-icon">↑</div>
                <div style="font-weight:600">Para Yükle</div>
                <div class="muted">Kart ekle / yükle</div>
              </div>
              <div class="menu-item" data-nav="gonder">
                <div class="menu-icon">→</div>
                <div style="font-weight:600">Para Gönder</div>
                <div class="muted">Arkadaşlarına gönder</div>
              </div>
              <div class="menu-item" data-nav="cashback">
                <div class="menu-icon">%</div>
                <div style="font-weight:600">Cashback</div>
                <div class="muted">İndirimler ve ödüller</div>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="section-title">Son Hareketler</div>
            <div class="list" id="lastActivities"></div>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Starfield: denser night-sky with subtle twinkle
      (function(){
        const canvas = document.getElementById('stars');
        const ctx = canvas.getContext('2d');
        let stars = [];
        let specialStar = null; // parlayan yıldız
        let tick = 0;
        function resize(){
          canvas.width = window.innerWidth;
          canvas.height = window.innerHeight;
          generate();
          draw();
        }
        function generate(){
          const count = Math.min(1500, Math.floor((canvas.width*canvas.height)/3000));
          stars = new Array(count).fill(0).map(()=>({
            x: Math.random()*canvas.width,
            y: Math.random()*canvas.height,
            r: Math.random()*1.4 + 0.2,
            a: Math.random()*0.6 + 0.25
          }));
          // sprinkle larger but faint stars
          for(let i=0;i<Math.max(16, Math.floor(count*0.02));i++){
            stars.push({
              x: Math.random()*canvas.width,
              y: Math.random()*canvas.height,
              r: Math.random()*2.2 + 1.2,
              a: Math.random()*0.35 + 0.15
            });
          }
          // place a special glowing star at a nice position
          specialStar = {
            x: canvas.width * 0.92, // sağa yakın
            y: canvas.height * 0.88, // alta yakın
            r: 2.2,
            phase: Math.random() * Math.PI * 2
          };
        }
        function draw(){
          ctx.clearRect(0,0,canvas.width,canvas.height);
          tick++;
          for(const s of stars){
            ctx.beginPath();
            ctx.fillStyle = `rgba(235,242,255,${s.a})`;
            ctx.arc(s.x, s.y, s.r, 0, Math.PI*2);
            ctx.fill();
            // subtle yellowish twinkle
            if(Math.random() < 0.015){
              ctx.fillStyle = 'rgba(255,204,0,0.45)';
              ctx.fillRect(s.x-0.5, s.y-0.5, 1, 1);
            }
          }

          // draw special glowing star with pulse and sparkle
          if(specialStar){
            const pulse = 1 + 0.6 * Math.sin(specialStar.phase + tick * 0.06);
            const haloR = specialStar.r * (16 + pulse * 6);
            const grad = ctx.createRadialGradient(specialStar.x, specialStar.y, 0, specialStar.x, specialStar.y, haloR);
            grad.addColorStop(0, 'rgba(255,220,60,0.45)');
            grad.addColorStop(0.35, 'rgba(255,204,0,0.18)');
            grad.addColorStop(1, 'rgba(255,204,0,0.0)');
            ctx.beginPath();
            ctx.fillStyle = grad;
            ctx.arc(specialStar.x, specialStar.y, haloR, 0, Math.PI*2);
            ctx.fill();

            // core
            ctx.beginPath();
            ctx.fillStyle = '#fff4c2';
            ctx.arc(specialStar.x, specialStar.y, specialStar.r * (1.6 + pulse*0.2), 0, Math.PI*2);
            ctx.fill();

            // sparkle cross
            ctx.save();
            ctx.translate(specialStar.x, specialStar.y);
            ctx.rotate((tick * 0.01) % (Math.PI*2));
            ctx.strokeStyle = 'rgba(255,220,60,0.8)';
            ctx.lineWidth = 1;
            const len = specialStar.r * (10 + pulse*3);
            ctx.beginPath(); ctx.moveTo(-len, 0); ctx.lineTo(len, 0); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(0, -len); ctx.lineTo(0, len); ctx.stroke();
            ctx.restore();

            // occasional flash
            if(tick % 240 === 0){ specialStar.phase = Math.random()*Math.PI*2; }
          }
          requestAnimationFrame(draw);
        }
        window.addEventListener('resize', resize);
        resize();
      })();
      const fakeActivities = [
        { title: 'Kafeterya Ödemesi', sub: '02 Ekim, 12:45', amount: -58.75, color: '#0d2142' },
        { title: 'Bakiye Yükleme', sub: '02 Ekim, 09:15', amount: 150.00, color: '#ffcc00' },
        { title: 'Spor Salonu', sub: '01 Ekim, 18:10', amount: -25.00, color: '#0d2142' },
        { title: 'Arkadaştan Geldi', sub: '01 Ekim, 17:00', amount: 40.00, color: '#ffcc00' },
      ];

      const fakeNotifIncomings = [
        { from: 'Ayşe Yılmaz', sub: 'Kantin geri ödeme', amount: 20.00 },
        { from: 'Burs Ödemesi', sub: 'Ekim ödemesi', amount: 500.00 },
      ];

      const fakeNotifRequests = [];

      const fakeNotifShares = [
        { from: 'Zeynep Kaya', sub: 'Kafeterya hesabını paylaşmak istiyor', total: 72.50, yourPart: 24.17 },
        { from: 'Can Yıldız', sub: 'Spor salonu üyeliği paylaşımı', total: 120.00, yourPart: 40.00 },
      ];

      const lastActivitiesEl = document.getElementById('lastActivities');
      const notifIncomingsEl = document.getElementById('notifIncomings');
      const notifRequestsEl = null;
      const notifSharesEl = document.getElementById('notifShares');
      const notifBtn = document.getElementById('notifBtn');
      const notifPanel = document.getElementById('notifPanel');
      const notifCount = document.getElementById('notifCount');
      const notifChip = document.getElementById('notifChip');
      const profileBtn = document.getElementById('profileBtn');
      const profilePanel = document.getElementById('profilePanel');

      function formatAmount(a){
        const sign = a > 0 ? '+' : '';
        return `${sign}₺ ${a.toFixed(2).replace('.', ',')}`;
      }

      function createActivityItem(act){
        const el = document.createElement('div');
        el.className = 'item';
        el.innerHTML = `
          <div class="item-left">
            <div class="item-icon" style="background:${act.amount>0?'rgba(255,204,0,.18)':'rgba(239,68,68,.12)'}; color:${act.amount>0?'#ffcc00':'#ef4444'}">${act.amount>0?'+':'-'}</div>
            <div>
              <div class="item-title">${act.title}</div>
              <div class="item-sub">${act.sub}</div>
            </div>
          </div>
          <div style="display:flex; align-items:center; gap:8px;">
            <div class="${act.amount>0?'amount-pos':'amount-neg'}">${formatAmount(act.amount)}</div>
            <button class="share-btn" title="Arkadaşlarla paylaş">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a3 3 0 0 0-3 3c0 .35.07.68.18 1H12a5 5 0 0 0-5 5c0 .35.07.68.18 1H9a3 3 0 0 0-3-3 3 3 0 0 0 0 6 3 3 0 0 0 3-3c0-.35-.07-.68-.18-1H12a5 5 0 0 0 5-5c0-.35-.07-.68-.18-1H15a3 3 0 0 0 3-3 3 3 0 0 0 0-6Z" fill="#94a3b8"/>
              </svg>
            </button>
          </div>
        `;
        
        // Paylaşım butonu event listener
        const shareBtn = el.querySelector('.share-btn');
        shareBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          alert(`${act.title} hareketi arkadaşlarla paylaşılacak (fake)`);
        });
        
        return el;
      }

      function renderActivities(){
        lastActivitiesEl.innerHTML = '';
        fakeActivities.forEach(a => lastActivitiesEl.appendChild(createActivityItem(a)));
      }

      function createNotifIncoming(n){
        const el = document.createElement('div');
        el.className = 'item';
        el.innerHTML = `
          <div class="item-left">
            <div class="item-icon" style="background:rgba(255,204,0,.18); color:#ffcc00">₺</div>
            <div>
              <div class="item-title">${n.from}</div>
              <div class="item-sub">${n.sub}</div>
            </div>
          </div>
          <div class="amount-pos">${formatAmount(n.amount)}</div>
        `;
        return el;
      }


      function createNotifShare(n){
        const el = document.createElement('div');
        el.className = 'item';
        el.innerHTML = `
          <div class="item-left">
            <div class="item-icon" style="background:rgba(255,204,0,.18); color:#ffcc00">⇄</div>
            <div>
              <div class="item-title">${n.from}</div>
              <div class="item-sub">${n.sub} • Toplam: ${formatAmount(n.total)} • Payın: ${formatAmount(n.yourPart)}</div>
            </div>
          </div>
          <div class="actions">
            <button class="btn">Detay</button>
            <button class="btn btn-primary accept">Kabul</button>
            <button class="btn btn-danger reject">Reddet</button>
          </div>
        `;
        el.querySelector('.accept').addEventListener('click', ()=>{ el.style.opacity=.5; });
        el.querySelector('.reject').addEventListener('click', ()=>{ el.style.opacity=.5; });
        return el;
      }

      function renderNotifications(){
        notifIncomingsEl.innerHTML = '';
        fakeNotifIncomings.forEach(n => notifIncomingsEl.appendChild(createNotifIncoming(n)));
        fakeNotifShares.forEach(n => notifSharesEl.appendChild(createNotifShare(n)));
        const total = fakeNotifIncomings.length + fakeNotifShares.length;
        notifCount.textContent = total;
        notifChip.textContent = `${total} yeni`;
      }

      notifBtn.addEventListener('click', (e)=>{
        e.stopPropagation();
        notifPanel.style.display = notifPanel.style.display === 'block' ? 'none' : 'block';
        profilePanel.style.display = 'none'; // close profile panel when opening notifications
      });
      
      profileBtn.addEventListener('click', (e)=>{
        e.stopPropagation();
        profilePanel.style.display = profilePanel.style.display === 'block' ? 'none' : 'block';
        notifPanel.style.display = 'none'; // close notification panel when opening profile
      });
      
      document.addEventListener('click', ()=>{ 
        notifPanel.style.display='none'; 
        profilePanel.style.display='none';
      });
      notifPanel.addEventListener('click', (e)=> e.stopPropagation());
      profilePanel.addEventListener('click', (e)=> e.stopPropagation());

      document.querySelectorAll('.quick').forEach(q=>{
        q.addEventListener('click', ()=>{
          const action = q.getAttribute('data-action');
          alert(`${action} ekranı (fake)`);
        });
      });

      document.querySelectorAll('.menu-item').forEach(m=>{
        m.addEventListener('click', ()=>{
          const nav = m.getAttribute('data-nav');
          alert(`${nav} sayfasına yönlendirme (fake)`);
        });
      });

      // Profile menu items
      document.querySelectorAll('.profile-item').forEach(item=>{
        item.addEventListener('click', ()=>{
          const action = item.getAttribute('data-action');
          switch(action) {
            case 'profile':
              alert('Profil Ayarları sayfası (fake)');
              break;
            case 'security':
              alert('Güvenlik ve Şifre ayarları sayfası (fake)');
              break;
            case 'notifications':
              alert('Bildirim tercihleri sayfası (fake)');
              break;
            case 'privacy':
              alert('Gizlilik ayarları sayfası (fake)');
              break;
            case 'help':
              alert('Yardım ve Destek sayfası (fake)');
              break;
            case 'logout':
              if(confirm('Çıkış yapmak istediğinizden emin misiniz?')) {
                alert('Çıkış yapıldı (fake)');
              }
              break;
          }
          profilePanel.style.display = 'none';
        });
      });

      renderActivities();
      renderNotifications();
    </script>
  </body>
</html>


