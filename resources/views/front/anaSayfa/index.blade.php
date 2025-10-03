<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
      .item { display:flex; align-items:center; justify-content:space-between; gap:10px; background:#0b1a33; border:1px solid var(--border); border-radius:12px; padding:12px; cursor:pointer; transition:.2s; }
      .item:hover { background:#0d2142; transform: translateY(-1px); }
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
      
      .budget-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; }
      @media(min-width:640px){ .budget-grid { grid-template-columns: repeat(3, 1fr);} }
      @media(min-width:960px){ .budget-grid { grid-template-columns: repeat(4, 1fr);} }
      .budget-card { background:#0b1a33; border:1px solid var(--border); border-radius:12px; padding:14px; cursor:pointer; transition:.2s; }
      .budget-card:hover { background:#0d2142; transform: translateY(-1px); }
      .budget-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
      .budget-icon { width:36px; height:36px; border-radius:8px; display:grid; place-items:center; font-weight:700; font-size:16px; }
      .budget-title { font-weight:600; font-size:14px; }
      .budget-amount { font-size:18px; font-weight:700; margin:8px 0; }
      .budget-limit { font-size:11px; color:var(--muted); margin-bottom:8px; }
      .progress-bar { width:100%; height:6px; background:#0e162b; border-radius:3px; overflow:hidden; }
      .progress-fill { height:100%; border-radius:3px; transition:.3s ease; }
      .progress-market { background:linear-gradient(90deg, #22c55e, #16a34a); }
      .progress-food { background:linear-gradient(90deg, #f59e0b, #d97706); }
      .progress-entertainment { background:linear-gradient(90deg, #8b5cf6, #7c3aed); }
      .progress-bills { background:linear-gradient(90deg, #ef4444, #dc2626); }
      .progress-default { background:linear-gradient(90deg, #06b6d4, #0891b2); }
      .add-category { background:#0b1a33; border:2px dashed var(--border); border-radius:12px; padding:14px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; cursor:pointer; transition:.2s; min-height:120px; }
      .add-category:hover { background:#0d2142; border-color:var(--accent); }
      .add-icon { width:36px; height:36px; border-radius:8px; background:rgba(255,204,0,.15); color:#ffcc00; display:grid; place-items:center; font-size:20px; }
      .add-text { font-weight:600; font-size:14px; color:var(--accent); }
      
      /* Modal Styles */
      .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        z-index: 1000;
        align-items: center;
        justify-content: center;
      }
      
      .modal.show {
        display: flex !important;
      }
      
      .modal-content {
        background: linear-gradient(180deg, #071223, #060f1e);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
        width: 90%;
        max-width: 400px;
        position: relative;
      }
      
      .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
      }
      
      .modal-title {
        font-weight: 700;
        font-size: 20px;
        color: var(--text);
      }
      
      .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #0b1a33;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--text);
      }
      
      .modal-close:hover {
        background: #0d2142;
        transform: translateY(-1px);
      }
      
      .modal-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }
      
      .modal-input {
        width: 100%;
        padding: 12px 16px;
        background: #0b1a33;
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-size: 16px;
        transition: all 0.2s ease;
        text-align: center;
      }
      
      .modal-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(255,204,0,.1);
      }
      
      .modal-input::placeholder {
        color: var(--muted);
      }
      
      .modal-buttons {
        display: flex;
        gap: 12px;
      }
      
      .modal-btn {
        flex: 1;
        padding: 12px 16px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
      }
      
      .modal-btn-primary {
        background: linear-gradient(135deg, var(--accent), #ffe066);
        color: #001;
        box-shadow: 0 6px 20px rgba(255,204,0,.2);
      }
      
      .modal-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(255,204,0,.3);
      }
      
      .modal-btn-secondary {
        background: #0b1a33;
        color: var(--text);
        border: 1px solid var(--border);
      }
      
      .modal-btn-secondary:hover {
        background: #0d2142;
        transform: translateY(-1px);
      }
      
      .modal-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
      }
      
      /* QR Payment Modal Styles */
      .qr-content {
        display: flex;
        flex-direction: column;
        gap: 24px;
      }
      
      .qr-info {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }
      
      .qr-merchant {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #0b1a33;
        border: 1px solid var(--border);
        border-radius: 12px;
      }
      
      .merchant-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255,204,0,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
      }
      
      .merchant-details {
        flex: 1;
      }
      
      .merchant-name {
        font-weight: 700;
        font-size: 18px;
        color: var(--text);
      }
      
      .merchant-category {
        font-size: 14px;
        color: var(--muted);
        margin-top: 2px;
      }
      
      .qr-amount {
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, rgba(255,204,0,.1), rgba(0,58,112,.1));
        border: 1px solid var(--border);
        border-radius: 12px;
      }
      
      .amount-label {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 8px;
      }
      
      .amount-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--accent);
      }
      
      .qr-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
        background: #0b1a33;
        border: 1px solid var(--border);
        border-radius: 12px;
      }
      
      .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
      }
      
      .detail-row span:first-child {
        color: var(--muted);
      }
      
      .detail-row span:last-child {
        color: var(--text);
        font-weight: 600;
      }
      
      /* Alert Styles */
      .alert {
        margin-bottom: 16px;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid;
        position: relative;
        z-index: 10;
      }
      
      .alert-error {
        background: linear-gradient(135deg, rgba(239,68,68,.1), rgba(220,38,38,.1));
        border-color: var(--danger);
        color: #fca5a5;
      }
      
      .alert-success {
        background: linear-gradient(135deg, rgba(34,197,94,.1), rgba(22,163,74,.1));
        border-color: #22c55e;
        color: #86efac;
      }
      
      .alert-content {
        display: flex;
        align-items: center;
        gap: 12px;
      }
      
      .alert-icon {
        font-size: 18px;
      }
      
      .alert-text {
        flex: 1;
        font-weight: 600;
      }
      
      .alert-close {
        background: none;
        border: none;
        color: inherit;
        font-size: 18px;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: .2s;
      }
      
      .alert-close:hover {
        background: rgba(255,255,255,.1);
      }
      
      /* Activities List Scroll */
      .activities-list {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 4px; /* Scrollbar için alan bırak */
      }
      
      /* Custom Scrollbar */
      .activities-list::-webkit-scrollbar {
        width: 6px;
      }
      
      .activities-list::-webkit-scrollbar-track {
        background: #0b1a33;
        border-radius: 3px;
      }
      
      .activities-list::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
        transition: background 0.2s ease;
      }
      
      .activities-list::-webkit-scrollbar-thumb:hover {
        background: var(--accent);
      }
      
      /* Firefox scrollbar */
      .activities-list {
        scrollbar-width: thin;
        scrollbar-color: var(--border) #0b1a33;
      }
      
      /* Transaction Detail Modal */
      .transaction-detail-content {
        display: flex;
        flex-direction: column;
        gap: 24px;
      }
      
      .transaction-main-info {
        text-align: center;
        padding: 20px;
        background: #0b1a33;
        border: 1px solid var(--border);
        border-radius: 12px;
      }
      
      .transaction-icon-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 700;
        background: linear-gradient(135deg, rgba(255,204,0,.15), rgba(0,58,112,.15));
        border: 2px solid var(--accent);
      }
      
      .transaction-amount-large {
        font-size: 32px;
        font-weight: 800;
        color: var(--accent);
        margin-bottom: 8px;
      }
      
      .transaction-title-large {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
      }
      
      .transaction-date-large {
        font-size: 14px;
        color: var(--muted);
      }
      
      .transaction-details-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
      }
      
      .transaction-detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: #0b1a33;
        border: 1px solid var(--border);
        border-radius: 8px;
      }
      
      .transaction-detail-label {
        font-size: 14px;
        color: var(--muted);
      }
      
      .transaction-detail-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        text-align: right;
      }
      
      .split-btn {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      
      .split-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(34,197,94,.3);
      }
    </style>
  </head>
  <body>
    <canvas id="stars"></canvas>
      <div class="container">
        @if(session('error'))
          <div class="alert alert-error" id="flashMessage">
            <div class="alert-content">
              <div class="alert-icon">❌</div>
              <div class="alert-text">{{ session('error') }}</div>
              <button class="alert-close" onclick="document.getElementById('flashMessage').style.display='none'">×</button>
            </div>
          </div>
        @endif
        
        @if(session('success'))
          <div class="alert alert-success" id="flashMessage">
            <div class="alert-content">
              <div class="alert-icon">✅</div>
              <div class="alert-text">{{ session('success') }}</div>
              <button class="alert-close" onclick="document.getElementById('flashMessage').style.display='none'">×</button>
            </div>
          </div>
        @endif
        
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
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-sub">Öğrenci</div>
          </div>
          <div class="avatar" id="profileBtn">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>

          <div class="profile-panel" id="profilePanel">
            <div class="profile-header">
              <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
              <div class="profile-info">
                <div class="profile-name">{{ auth()->user()->name }}</div>
                <div class="profile-email">{{ auth()->user()->email }}</div>
              </div>
            </div>
            <div class="profile-menu">
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
              <span class="chip" id="notifChip">2 yeni</span>
            </div>
            <div class="section-title">Paylaşım İstekleri</div>
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
                <div class="balance-amount" id="balanceAmount">₺ {{ number_format($wallet->balance, 2, ',', '.') }}</div>
              </div>
            </div>
            <div class="quick-grid">
              <div class="quick" data-action="qr">
              <div class="qicon q-qr">QR</div>
                <div class="qtext">
                  <div class="qtitle">QR Kod</div>
                  <div class="qsub">Hızlı ödeme</div>
                </div>
              </div>
              <a href="{{ route('para-gonder') }}" class="quick">
              <div class="qicon q-send">→</div>
                <div class="qtext">
                  <div class="qtitle">Para Gönder</div>
                  <div class="qsub">Arkadaşına</div>
                </div>
              </a>
              <div class="quick" data-action="load" id="quickLoadBtn">
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
            <div class="section-title">Bütçe Kartları</div>
            <div class="budget-grid" id="budgetGrid">
              <!-- Budget cards will be generated by JavaScript -->
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <div class="section-title">Son Hareketler</div>
            <div class="list activities-list" id="lastActivities"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- QR Kod Input Modal -->
    <div class="modal" id="qrInputModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">QR Kod Girin</h2>
          <button class="modal-close" id="closeQrInputModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        
        <form class="modal-form" id="qrInputForm">
          <div style="text-align: center; margin-bottom: 20px;">
            <div style="font-size: 48px; margin-bottom: 12px;">📱</div>
            <div style="font-size: 16px; color: var(--muted);">QR kod verisini aşağıya yapıştırın</div>
          </div>
          
          <textarea 
            id="qrDataInput" 
            class="modal-input" 
            placeholder='{"qr_id":"QR-12345","merchant_id":"2","amount":120.00,"currency":"TRY","ts":"2025-11-10T19:30:00Z"}'
            rows="6"
            required
            style="resize: vertical; min-height: 120px; font-family: monospace; font-size: 12px;"
          ></textarea>
          
          <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-secondary" id="cancelQrInputBtn">İptal</button>
            <button type="submit" class="modal-btn modal-btn-primary" id="processQrBtn">Ödemeye Geç</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div class="modal" id="transactionDetailModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">İşlem Detayları</h2>
          <button class="modal-close" id="closeTransactionModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        
        <div class="transaction-detail-content">
          <div class="transaction-main-info">
            <div class="transaction-icon-large" id="transactionIconLarge">₺</div>
            <div class="transaction-amount-large" id="transactionAmountLarge">₺ 120,00</div>
            <div class="transaction-title-large" id="transactionTitleLarge">Burger King</div>
            <div class="transaction-date-large" id="transactionDateLarge">2 Ekim 2025, 14:30</div>
          </div>
          
          <div class="transaction-details-grid" id="transactionDetailsGrid">
            <!-- Details will be populated by JavaScript -->
          </div>
          
          <div class="modal-buttons" id="transactionModalButtons">
            <button type="button" class="modal-btn modal-btn-secondary" id="closeTransactionBtn">Kapat</button>
            <!-- Split button will be added dynamically for payments -->
          </div>
        </div>
      </div>
    </div>

    <!-- Para Yükle Modal -->
    <div class="modal" id="loadMoneyModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Para Yükle</h2>
          <button class="modal-close" id="closeModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        
        <form class="modal-form" id="loadMoneyForm">
          <input 
            type="number" 
            id="amountInput" 
            class="modal-input" 
            placeholder="Miktar girin (₺)"
            min="1"
            max="10000"
            required
          >
          
          <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-secondary" id="cancelBtn">İptal</button>
            <button type="submit" class="modal-btn modal-btn-primary" id="submitBtn">Yükle</button>
          </div>
        </form>
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
      // Gerçek transaction verilerini backend'den al
      const recentTransactions = @json($recentTransactions);
      
      // Gerçek pending split verilerini backend'den al
      const pendingSplits = @json($pendingSplits);

      const budgetCategories = [
        { id: 'market', name: 'Market', icon: '🛒', spent: 245.50, limit: 500.00, color: 'market' },
        { id: 'food', name: 'Yemek', icon: '🍽️', spent: 180.75, limit: 300.00, color: 'food' },
        { id: 'entertainment', name: 'Eğlence', icon: '🎬', spent: 95.00, limit: 200.00, color: 'entertainment' },
        { id: 'bills', name: 'Fatura', icon: '⚡', spent: 320.00, limit: 400.00, color: 'bills' },
      ];

      const lastActivitiesEl = document.getElementById('lastActivities');
      const notifSharesEl = document.getElementById('notifShares');
      const notifBtn = document.getElementById('notifBtn');
      const notifPanel = document.getElementById('notifPanel');
      const notifCount = document.getElementById('notifCount');
      const notifChip = document.getElementById('notifChip');
      const profileBtn = document.getElementById('profileBtn');
      const profilePanel = document.getElementById('profilePanel');
      const budgetGrid = document.getElementById('budgetGrid');

      function formatAmount(a){
        const sign = a > 0 ? '+' : '';
        return `${sign}₺ ${a.toFixed(2).replace('.', ',')}`;
      }

      function createActivityItem(transaction){
        const el = document.createElement('div');
        el.className = 'item';
        
        // Transaction type'a göre başlık ve ikon belirle
        let title, icon, isPositive;
        
        switch(transaction.type) {
          case 'topup':
            title = 'Bakiye Yükleme';
            icon = '+';
            isPositive = true;
            break;
          case 'payment':
            title = transaction.merchant ? transaction.merchant.name : 'Ödeme';
            icon = '₺';
            isPositive = false;
            break;
          case 'transfer_in':
            title = 'Para Aldınız';
            icon = '↓';
            isPositive = true;
            break;
          case 'transfer_out':
            title = 'Para Gönderdiniz';
            icon = '↑';
            isPositive = false;
            break;
          case 'cashback':
            title = 'Cashback';
            icon = '%';
            isPositive = true;
            break;
          default:
            title = 'İşlem';
            icon = '•';
            isPositive = transaction.amount > 0;
        }
        
        // Tarihi formatla
        const date = new Date(transaction.created_at);
        const formattedDate = date.toLocaleDateString('tr-TR', {
          day: 'numeric',
          month: 'long',
          hour: '2-digit',
          minute: '2-digit'
        });
        
        // Tutarı formatla (transfer_out ve payment negatif göster)
        const displayAmount = (transaction.type === 'transfer_out' || transaction.type === 'payment') 
          ? -Math.abs(transaction.amount) 
          : Math.abs(transaction.amount);
        
        el.innerHTML = `
          <div class="item-left">
            <div class="item-icon" style="background:${isPositive?'rgba(255,204,0,.18)':'rgba(239,68,68,.12)'}; color:${isPositive?'#ffcc00':'#ef4444'}">${icon}</div>
            <div>
              <div class="item-title">${title}</div>
              <div class="item-sub">${formattedDate}</div>
            </div>
          </div>
          <div style="display:flex; align-items:center; gap:8px;">
            <div class="${isPositive?'amount-pos':'amount-neg'}">${formatAmount(displayAmount)}</div>
            <button class="share-btn" title="Detayları gör">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" fill="#94a3b8"/>
              </svg>
            </button>
          </div>
        `;
        
        // Detay butonu event listener
        const shareBtn = el.querySelector('.share-btn');
        shareBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          showTransactionDetails(transaction);
        });
        
        return el;
      }
      
      function showTransactionDetails(transaction) {
        const modal = document.getElementById('transactionDetailModal');
        const iconLarge = document.getElementById('transactionIconLarge');
        const amountLarge = document.getElementById('transactionAmountLarge');
        const titleLarge = document.getElementById('transactionTitleLarge');
        const dateLarge = document.getElementById('transactionDateLarge');
        const detailsGrid = document.getElementById('transactionDetailsGrid');
        const modalButtons = document.getElementById('transactionModalButtons');
        
        // Ana bilgileri güncelle
        let icon, title, isPositive;
        
        switch(transaction.type) {
          case 'topup':
            title = 'Bakiye Yükleme';
            icon = '+';
            isPositive = true;
            break;
          case 'payment':
            title = transaction.merchant ? transaction.merchant.name : 'Ödeme';
            icon = '₺';
            isPositive = false;
            break;
          case 'transfer_in':
            title = 'Para Aldınız';
            icon = '↓';
            isPositive = true;
            break;
          case 'transfer_out':
            title = 'Para Gönderdiniz';
            icon = '↑';
            isPositive = false;
            break;
          case 'cashback':
            title = 'Cashback';
            icon = '%';
            isPositive = true;
            break;
          default:
            title = 'İşlem';
            icon = '•';
            isPositive = transaction.amount > 0;
        }
        
        iconLarge.textContent = icon;
        iconLarge.style.color = isPositive ? '#ffcc00' : '#ef4444';
        
        const displayAmount = (transaction.type === 'transfer_out' || transaction.type === 'payment') 
          ? -Math.abs(transaction.amount) 
          : Math.abs(transaction.amount);
        
        amountLarge.textContent = formatAmount(displayAmount);
        amountLarge.style.color = isPositive ? '#ffcc00' : '#ef4444';
        titleLarge.textContent = title;
        
        const date = new Date(transaction.created_at);
        dateLarge.textContent = date.toLocaleDateString('tr-TR', {
          day: 'numeric',
          month: 'long',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
        
        // Detay bilgilerini oluştur
        detailsGrid.innerHTML = '';
        
        // Temel bilgiler
        addDetailItem('İşlem Tipi', getTransactionTypeText(transaction.type));
        addDetailItem('Para Birimi', transaction.currency);
        addDetailItem('İşlem ID', `#${transaction.id}`);
        
        if (transaction.merchant) {
          addDetailItem('Merchant', transaction.merchant.name);
          if (transaction.merchant.category) {
            addDetailItem('Kategori', transaction.merchant.category.name);
          }
        }
        
        // Meta bilgileri
        if (transaction.meta) {
          Object.entries(transaction.meta).forEach(([key, value]) => {
            if (key !== 'recipient_ids' && key !== 'sender_id') {
              const label = getMetaLabel(key);
              const displayValue = typeof value === 'object' ? JSON.stringify(value) : value;
              addDetailItem(label, displayValue);
            }
          });
        }
        
        // Butonları güncelle
        modalButtons.innerHTML = `
          <button type="button" class="modal-btn modal-btn-secondary" id="closeTransactionBtn">Kapat</button>
        `;
        
        // Eğer payment ise bölüş butonu ekle
        if (transaction.type === 'payment' && transaction.merchant) {
          modalButtons.innerHTML = `
            <button type="button" class="modal-btn modal-btn-secondary" id="closeTransactionBtn">Kapat</button>
            <button type="button" class="split-btn" id="splitPaymentBtn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 4v4h4m-4 0L8 16m8-8L8 16m8-8v4m0-4h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Arkadaşlarla Böl
            </button>
          `;
          
          // Bölüş butonuna event listener ekle
          document.getElementById('splitPaymentBtn').addEventListener('click', () => {
            // Transaction bilgilerini URL parametresi olarak gönder
            const params = new URLSearchParams({
              transaction_id: transaction.id,
              merchant_name: transaction.merchant.name,
              amount: transaction.amount,
              date: transaction.created_at
            });
            window.location.href = `/arkadas-bol?${params.toString()}`;
          });
        }
        
        // Modal event listeners
        document.getElementById('closeTransactionBtn').addEventListener('click', () => {
          modal.classList.remove('show');
        });
        
        // Modal'ı göster
        modal.classList.add('show');
      }
      
      function addDetailItem(label, value) {
        const detailsGrid = document.getElementById('transactionDetailsGrid');
        const item = document.createElement('div');
        item.className = 'transaction-detail-item';
        item.innerHTML = `
          <span class="transaction-detail-label">${label}</span>
          <span class="transaction-detail-value">${value}</span>
        `;
        detailsGrid.appendChild(item);
      }
      
      function getTransactionTypeText(type) {
        const types = {
          'topup': 'Para Yükleme',
          'payment': 'Ödeme',
          'transfer_in': 'Para Alma',
          'transfer_out': 'Para Gönderme',
          'cashback': 'Geri Ödeme'
        };
        return types[type] || type;
      }
      
      function getMetaLabel(key) {
        const labels = {
          'method': 'Yöntem',
          'previous_balance': 'Önceki Bakiye',
          'new_balance': 'Yeni Bakiye',
          'qr_id': 'QR ID',
          'payment_method': 'Ödeme Yöntemi',
          'merchant_category': 'Merchant Kategorisi',
          'recipient_name': 'Alıcı',
          'sender_name': 'Gönderen',
          'transfer_note': 'Not',
          'cashback_percentage': 'Cashback Oranı',
          'cashback_reason': 'Cashback Nedeni',
          'campaign_id': 'Kampanya ID'
        };
        return labels[key] || key;
      }

      function renderActivities(){
        lastActivitiesEl.innerHTML = '';
        
        if (recentTransactions && recentTransactions.length > 0) {
          recentTransactions.forEach(transaction => {
            lastActivitiesEl.appendChild(createActivityItem(transaction));
          });
        } else {
          // Hiç transaction yoksa bilgi mesajı göster
          const noDataEl = document.createElement('div');
          noDataEl.style.cssText = 'text-align:center; padding:20px; color:var(--muted); font-size:14px;';
          noDataEl.textContent = 'Henüz işlem geçmişi bulunmuyor';
          lastActivitiesEl.appendChild(noDataEl);
        }
      }

      function createBudgetCard(category){
        const percentage = Math.min((category.spent / category.limit) * 100, 100);
        const remaining = category.limit - category.spent;
        
        const el = document.createElement('div');
        el.className = 'budget-card';
        el.innerHTML = `
          <div class="budget-header">
            <div class="budget-icon" style="background:rgba(255,204,0,.15); color:#ffcc00">${category.icon}</div>
            <div class="budget-title">${category.name}</div>
          </div>
          <div class="budget-amount">₺ ${category.spent.toFixed(2).replace('.', ',')}</div>
          <div class="budget-limit">Limit: ₺ ${category.limit.toFixed(2).replace('.', ',')} • Kalan: ₺ ${remaining.toFixed(2).replace('.', ',')}</div>
          <div class="progress-bar">
            <div class="progress-fill progress-${category.color}" style="width: ${percentage}%"></div>
          </div>
        `;
        
        el.addEventListener('click', () => {
          const newLimit = prompt(`${category.name} kategorisi için yeni limit girin:`, category.limit);
          if (newLimit && !isNaN(newLimit) && newLimit > 0) {
            category.limit = parseFloat(newLimit);
            renderBudgetCards();
            alert(`${category.name} limiti ₺ ${newLimit} olarak güncellendi (fake)`);
          }
        });
        
        return el;
      }

      function createAddCategoryCard(){
        const el = document.createElement('div');
        el.className = 'add-category';
        el.innerHTML = `
          <div class="add-icon">+</div>
          <div class="add-text">Kategori Ekle</div>
        `;
        
        el.addEventListener('click', () => {
          const name = prompt('Yeni kategori adı:');
          if (name && name.trim()) {
            const newCategory = {
              id: name.toLowerCase().replace(/\s+/g, '_'),
              name: name.trim(),
              icon: '📁',
              spent: 0,
              limit: 100,
              color: 'default'
            };
            budgetCategories.push(newCategory);
            renderBudgetCards();
            alert(`${name} kategorisi eklendi (fake)`);
          }
        });
        
        return el;
      }

      function renderBudgetCards(){
        budgetGrid.innerHTML = '';
        budgetCategories.forEach(category => {
          budgetGrid.appendChild(createBudgetCard(category));
        });
        budgetGrid.appendChild(createAddCategoryCard());
      }



      function createNotifShare(split){
        const el = document.createElement('div');
        el.className = 'item';
        
        const merchantName = split.transaction?.merchant?.name || split.meta?.merchant_name || 'Bilinmeyen İşletme';
        const requesterName = split.requester?.name + ' ' + (split.requester?.surname || '');
        
        el.innerHTML = `
          <div class="item-left">
            <div class="item-icon" style="background:rgba(255,204,0,.18); color:#ffcc00">⇄</div>
            <div>
              <div class="item-title">${requesterName}</div>
              <div class="item-sub">${merchantName} hesabını paylaşmak istiyor • Payın: ${formatAmount(split.share_amount)}</div>
            </div>
          </div>
          <div class="actions">
            <button class="btn btn-primary accept" data-split-id="${split.id}">Kabul</button>
            <button class="btn btn-danger reject" data-split-id="${split.id}">Reddet</button>
          </div>
        `;
        
        // Accept button event
        el.querySelector('.accept').addEventListener('click', async (e) => {
          const splitId = e.target.dataset.splitId;
          await handleSplitAction(splitId, 'accept', el);
        });
        
        // Reject button event
        el.querySelector('.reject').addEventListener('click', async (e) => {
          const splitId = e.target.dataset.splitId;
          await handleSplitAction(splitId, 'reject', el);
        });
        
        return el;
      }
      
      async function handleSplitAction(splitId, action, element) {
        try {
          element.style.opacity = 0.5;
          
          const endpoint = action === 'accept' ? '/accept-split' : '/reject-split';
          const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ split_id: parseInt(splitId) })
          });
          
          const data = await response.json();
          
          if (response.ok && data.success) {
            // Success message
            alert(data.message);
            
            // Update balance if accepted
            if (action === 'accept' && data.your_new_balance) {
              const balanceEl = document.getElementById('balanceAmount');
              if (balanceEl) {
                balanceEl.textContent = `₺ ${data.your_new_balance.toFixed(2).replace('.', ',')}`;
              }
            }
            
            // Remove the notification item
            element.remove();
            
            // Update notification count
            updateNotificationCount();
          } else {
            alert(data.message || `Bölüşme isteği ${action === 'accept' ? 'kabul edilirken' : 'reddedilirken'} hata oluştu.`);
            element.style.opacity = 1;
          }
        } catch (error) {
          console.error('Split action error:', error);
          alert('Bağlantı hatası oluştu. Lütfen tekrar deneyin.');
          element.style.opacity = 1;
        }
      }

      function renderNotifications(){
        if (pendingSplits && pendingSplits.length > 0) {
          pendingSplits.forEach(split => notifSharesEl.appendChild(createNotifShare(split)));
        }
        const total = pendingSplits ? pendingSplits.length : 0;
        notifCount.textContent = total;
        notifChip.textContent = `${total} yeni`;
      }
      
      function updateNotificationCount() {
        const remainingItems = notifSharesEl.children.length;
        notifCount.textContent = remainingItems;
        notifChip.textContent = `${remainingItems} yeni`;
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
          if (action === 'qr') {
            openQrInputModal();
          }
        });
      });


      // Profile menu items
      document.querySelectorAll('.profile-item').forEach(item=>{
        item.addEventListener('click', ()=>{
          const action = item.getAttribute('data-action');
          if (action === 'logout') {
            if(confirm('Çıkış yapmak istediğinizden emin misiniz?')) {
              // POST method ile logout formu oluştur ve gönder
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = '/auth/logout';
              
              // CSRF token ekle
              const csrfToken = document.createElement('input');
              csrfToken.type = 'hidden';
              csrfToken.name = '_token';
              csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
              form.appendChild(csrfToken);
              
              // Formu body'ye ekle ve gönder
              document.body.appendChild(form);
              form.submit();
            }
          }
          profilePanel.style.display = 'none';
        });
      });

      // Modal functionality
      const loadMoneyModal = document.getElementById('loadMoneyModal');
      const qrInputModal = document.getElementById('qrInputModal');
      const transactionDetailModal = document.getElementById('transactionDetailModal');
      const addMoneyBtn = document.getElementById('addMoneyBtn');
      const quickLoadBtn = document.getElementById('quickLoadBtn');
      const closeModal = document.getElementById('closeModal');
      const closeQrInputModal = document.getElementById('closeQrInputModal');
      const closeTransactionModal = document.getElementById('closeTransactionModal');
      const cancelBtn = document.getElementById('cancelBtn');
      const cancelQrInputBtn = document.getElementById('cancelQrInputBtn');
      const processQrBtn = document.getElementById('processQrBtn');
      const qrInputForm = document.getElementById('qrInputForm');
      const qrDataInput = document.getElementById('qrDataInput');
      const loadMoneyForm = document.getElementById('loadMoneyForm');
      const amountInput = document.getElementById('amountInput');
      const submitBtn = document.getElementById('submitBtn');
      const balanceAmount = document.getElementById('balanceAmount');

      // Function to open load money modal
      function openModal() {
        if (loadMoneyModal) {
          loadMoneyModal.classList.add('show');
          loadMoneyModal.style.display = 'flex';
          if (amountInput) {
            setTimeout(() => amountInput.focus(), 100);
          }
        }
      }

      // Function to open QR input modal
      function openQrInputModal() {
        if (qrInputModal) {
          qrInputModal.classList.add('show');
          qrInputModal.style.display = 'flex';
          if (qrDataInput) {
            setTimeout(() => qrDataInput.focus(), 100);
          }
        }
      }

      // Open modal - Ana para yükle butonu
      if (addMoneyBtn) {
        addMoneyBtn.addEventListener('click', (e) => {
          e.preventDefault();
          openModal();
        });
      }

      // Open modal - Quick para yükle butonu
      if (quickLoadBtn) {
        quickLoadBtn.addEventListener('click', (e) => {
          e.preventDefault();
          openModal();
        });
      }

      // Close modal functions
      function closeModalFunc() {
        if (loadMoneyModal) {
          loadMoneyModal.classList.remove('show');
          loadMoneyModal.style.display = 'none';
        }
        if (loadMoneyForm) {
          loadMoneyForm.reset();
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Yükle';
        }
      }

      // Close QR input modal function
      function closeQrInputModalFunc() {
        if (qrInputModal) {
          qrInputModal.classList.remove('show');
          qrInputModal.style.display = 'none';
        }
        if (qrInputForm) {
          qrInputForm.reset();
        }
      }

      if (closeModal) {
        closeModal.addEventListener('click', closeModalFunc);
      }
      if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModalFunc);
      }

      // QR input modal event listeners
      if (closeQrInputModal) {
        closeQrInputModal.addEventListener('click', closeQrInputModalFunc);
      }
      if (cancelQrInputBtn) {
        cancelQrInputBtn.addEventListener('click', closeQrInputModalFunc);
      }
      
      // QR form submission
      if (qrInputForm) {
        qrInputForm.addEventListener('submit', (e) => {
          e.preventDefault();
          
          const qrDataText = qrDataInput.value.trim();
          if (!qrDataText) {
            alert('Lütfen QR kod verisini girin.');
            return;
          }
          
          try {
            // Debug için konsola yazdır
            console.log('Raw input:', qrDataText);
            console.log('Input length:', qrDataText.length);
            
            // Satır sonlarını ve fazla boşlukları temizle
            const cleanedText = qrDataText
              .replace(/\r?\n/g, '') // Satır sonlarını kaldır
              .replace(/\s+/g, ' ')  // Çoklu boşlukları tek boşluğa çevir
              .trim();               // Başındaki ve sonundaki boşlukları kaldır
            console.log('Cleaned input:', cleanedText);
            
            // QR verisini parse et
            const qrData = JSON.parse(cleanedText);
            console.log('Parsed QR data:', qrData);
            
            // Gerekli alanları kontrol et
            if (!qrData.qr_id || !qrData.merchant_id || !qrData.amount || !qrData.currency) {
              alert('QR kod verisi eksik veya hatalı.');
              return;
            }
            
            // Merchant ID'yi kontrol et ve gerekirse dönüştür
            if (typeof qrData.merchant_id === 'number' || /^\d+$/.test(qrData.merchant_id)) {
              // Sadece sayı ise M- prefix'i ekle
              qrData.merchant_id = `M-${qrData.merchant_id}`;
            }
            
            console.log('Final QR data:', qrData);
            
            // QR verisini encode et ve ödeme sayfasına yönlendir
            const qrString = btoa(JSON.stringify(qrData));
            window.location.href = `/odeme-yap?qr=${encodeURIComponent(qrString)}`;
          } catch (error) {
            console.error('Parse error:', error);
            alert('Geçersiz QR kod formatı. Hata: ' + error.message);
          }
        });
      }

      // Close modal when clicking outside
      if (loadMoneyModal) {
        loadMoneyModal.addEventListener('click', (e) => {
          if (e.target === loadMoneyModal) {
            closeModalFunc();
          }
        });
      }

      if (qrInputModal) {
        qrInputModal.addEventListener('click', (e) => {
          if (e.target === qrInputModal) {
            closeQrInputModalFunc();
          }
        });
      }

      // Transaction modal event listeners
      if (closeTransactionModal) {
        closeTransactionModal.addEventListener('click', () => {
          transactionDetailModal.classList.remove('show');
        });
      }

      if (transactionDetailModal) {
        transactionDetailModal.addEventListener('click', (e) => {
          if (e.target === transactionDetailModal) {
            transactionDetailModal.classList.remove('show');
          }
        });
      }

      // Handle form submission
      if (loadMoneyForm) {
        loadMoneyForm.addEventListener('submit', async (e) => {
          e.preventDefault();
          
          const amount = parseInt(amountInput.value);
          
          if (!amount || amount < 1 || amount > 10000) {
            alert('Lütfen 1-10000 arasında bir miktar girin.');
            return;
          }

          // Loading state
          if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Yükleniyor...';
          }

          try {
            const response = await fetch('/load-money', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              },
              body: JSON.stringify({ amount })
            });

            const data = await response.json();

            if (response.ok && data.success) {
              // Update balance display
              if (balanceAmount) {
                balanceAmount.textContent = `₺ ${data.balance.toFixed(2).replace('.', ',')}`;
              }
              
              // Close modal
              closeModalFunc();
            } else {
              // Show specific error message from server
              alert(data.message || 'Para yükleme işlemi başarısız!');
            }
          } catch (error) {
            console.error('Error:', error);
            alert('Bağlantı hatası oluştu. Lütfen tekrar deneyin.');
          } finally {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = 'Yükle';
            }
          }
        });
      }

      renderActivities();
      renderNotifications();
      renderBudgetCards();
    </script>
  </body>
</html>


