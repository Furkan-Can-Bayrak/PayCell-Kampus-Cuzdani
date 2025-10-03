<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Arkadaşlarla Böl - Kampüs Dijital Cüzdan</title>
    <style>
        :root { --bg:#000816; --panel:#071223; --muted:#94a3b8; --text:#e8edf6; --accent:#ffcc00; --accent-2:#003a70; --danger:#ef4444; --warning:#ffcc00; --card:#06101f; --border:#0f2747; }
        * { box-sizing: border-box; }
        html,body { margin:0; padding:0; background:var(--bg); color:var(--text); font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; }
        a { color: inherit; text-decoration: none; }
        #stars { position:fixed; inset:0; width:100%; height:100%; z-index:0; background: radial-gradient(1400px 700px at 80% -10%, rgba(0,58,112,.35), transparent 62%), radial-gradient(900px 480px at 12% 112%, rgba(10,32,64,.6), transparent 60%), linear-gradient(180deg, #000a1a 0%, #00040b 100%); }
        .container { max-width: 1200px; margin: 0 auto; padding: 16px; position:relative; z-index:1; }

        .header { display:flex; align-items:center; gap:12px; padding:12px 0; margin-bottom:24px; }
        .back-btn { width:40px; height:40px; border-radius:10px; background:#0b1a33; display:grid; place-items:center; cursor:pointer; border:1px solid var(--border); transition:.2s; }
        .back-btn:hover { background:#0d2142; transform: translateY(-1px); }
        .header-title { font-weight:700; font-size:20px; }

        .split-container { display:grid; grid-template-columns: 1fr; gap:20px; }
        @media(min-width: 1200px){ .split-container { grid-template-columns: 1fr 1fr; } }

        .card { background: linear-gradient(180deg, #071223, #060f1e); border:1px solid var(--border); border-radius:16px; padding:20px; box-shadow: 0 10px 30px rgba(0,0,0,.35); margin-bottom:16px; }
        .card-header { border-bottom:1px solid var(--border); padding-bottom:16px; margin-bottom:20px; }
        .card-title { font-size:18px; font-weight:700; color:var(--accent); display:flex; align-items:center; gap:8px; }

        .section-title { font-size:16px; font-weight:700; margin-bottom:16px; color:var(--text); display:flex; align-items:center; gap:8px; }

        .form-group { margin-bottom:16px; }
        .form-label { display:block; margin-bottom:8px; font-weight:600; color:var(--text); }
        .form-input, .form-select, .form-textarea { width:100%; padding:12px; background:#0b1a33; border:1px solid var(--border); border-radius:8px; color:var(--text); font-size:14px; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { outline:none; border-color:var(--accent); }
        .form-input[readonly] { background:#0d2142; color:var(--muted); }

        .form-row { display:grid; grid-template-columns: 1fr; gap:12px; }
        @media(min-width:640px){ .form-row { grid-template-columns: 1fr 1fr; } }

        .info-box { background:#0b1a33; border:1px solid var(--accent-2); border-radius:8px; padding:12px; margin-bottom:16px; }
        .info-text { font-size:13px; color:var(--muted); }

        .summary-section { border-top:1px solid var(--border); padding-top:16px; }
        .summary-item { display:flex; justify-content:space-between; margin-bottom:8px; }
        .summary-label { color:var(--muted); }
        .summary-value { font-weight:600; color:var(--accent); }

        .search-box { position:relative; margin-bottom:20px; }
        .search-input { width:100%; padding:12px 40px 12px 12px; background:#0b1a33; border:1px solid var(--border); border-radius:8px; color:var(--text); }
        .search-icon { position:absolute; right:12px; top:50%; transform:translateY(-50%); color:var(--muted); }

        .friends-section { margin-bottom:20px; }
        .friends-list { max-height:300px; overflow-y:auto; }
        .friend-card { background:#0b1a33; border:2px solid var(--border); border-radius:12px; padding:12px; cursor:pointer; transition:.2s; margin-bottom:8px; }
        .friend-card:hover { background:#0d2142; transform: translateY(-1px); }
        .friend-card.selected { border-color:var(--accent); background:#0d2142; }
        .friend-content { display:flex; align-items:center; gap:12px; }
        .friend-avatar { width:40px; height:40px; border-radius:999px; background:#1f2937; display:flex; align-items:center; justify-content:center; font-weight:700; color:#ffdd33; }
        .friend-info { flex:1; }
        .friend-name { font-weight:600; font-size:14px; }
        .friend-phone { font-size:12px; color:var(--muted); }
        .friend-checkbox { }
        .friend-check { width:18px; height:18px; }

        .selected-friends-section { margin-bottom:20px; }
        .selected-friends { max-height:200px; overflow-y:auto; }
        .selected-friend { background:#0d2142; border:1px solid var(--accent); border-radius:20px; padding:8px 12px; display:flex; align-items:center; gap:8px; margin-bottom:8px; }
        .selected-friend-avatar { width:24px; height:24px; border-radius:999px; background:#1f2937; display:flex; align-items:center; justify-content:center; font-size:12px; color:#ffdd33; }
        .selected-friend-info { flex:1; }
        .selected-friend-name { font-size:13px; font-weight:600; margin-bottom:2px; }
        .selected-friend-amounts { display:flex; gap:8px; font-size:11px; }
        .amount-text { color:var(--accent); font-weight:600; }
        .percentage-text { color:var(--muted); }
        .remove-friend { width:18px; height:18px; border-radius:999px; background:var(--danger); color:white; display:grid; place-items:center; cursor:pointer; font-size:10px; }

        .share-type-section { margin-bottom:20px; }
        .share-type-buttons { display:grid; grid-template-columns: repeat(3, 1fr); gap:8px; }
        .share-type-btn { padding:12px; background:#0b1a33; border:1px solid var(--border); border-radius:8px; cursor:pointer; transition:.2s; text-align:center; font-size:13px; font-weight:600; }
        .share-type-btn:hover { background:#0d2142; }
        .share-type-btn.active { border-color:var(--accent); background:#0d2142; color:var(--accent); }
        .share-type-input { display:none; }

        .percentage-section, .custom-section { margin-bottom:20px; }
        .percentage-item, .custom-item { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
        .percentage-label, .custom-label { width:120px; font-size:13px; color:var(--muted); }
        .percentage-input, .custom-input { flex:1; padding:8px; background:#0b1a33; border:1px solid var(--border); border-radius:6px; color:var(--text); text-align:center; }
        .percentage-unit, .custom-unit { font-size:12px; color:var(--muted); }

        .warning-box { background:#0b1a33; border:1px solid var(--warning); border-radius:8px; padding:12px; margin-top:12px; }
        .warning-text { font-size:12px; color:var(--warning); }

        .summary-section { border-top:1px solid var(--border); padding-top:16px; }
        .summary-item { display:flex; justify-content:space-between; margin-bottom:8px; }
        .summary-label { color:var(--muted); }
        .summary-value { font-weight:600; color:var(--accent); }

        .action-buttons { display:flex; gap:12px; margin-top:20px; }
        .btn { padding:12px 24px; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:.2s; }
        .btn-secondary { background:#0b1a33; color:var(--text); border:1px solid var(--border); }
        .btn-secondary:hover { background:#0d2142; }
        .btn-primary { background:linear-gradient(135deg, var(--accent), #ffe066); color:#001; box-shadow:0 6px 20px rgba(255,204,0,.2); }
        .btn-primary:hover { transform: translateY(-1px); }
        .btn-primary:disabled { opacity:0.5; cursor:not-allowed; transform:none; }

        .empty-state { text-align:center; padding:40px 20px; color:var(--muted); }
        .empty-icon { font-size:32px; margin-bottom:12px; }
        .empty-text { font-size:14px; }

        /* Modal Styles */
        .modal { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); display:none; z-index:1000; }
        .modal.show { display:flex; align-items:center; justify-content:center; }
        .modal-content { background:linear-gradient(180deg, #071223, #060f1e); border:1px solid var(--border); border-radius:16px; padding:24px; max-width:400px; width:90%; box-shadow:0 20px 40px rgba(0,0,0,0.5); }
        .modal-header { text-align:center; margin-bottom:20px; }
        .modal-title { font-size:20px; font-weight:700; color:var(--accent); margin-bottom:8px; }
        .modal-subtitle { font-size:14px; color:var(--muted); }
        .modal-body { margin-bottom:20px; }
        .modal-text { font-size:14px; line-height:1.5; color:var(--text); margin-bottom:12px; }
        .modal-amount { font-size:18px; font-weight:700; color:var(--accent); text-align:center; margin:16px 0; }
        .modal-friends { margin-top:16px; }
        .modal-friend-item { display:flex; align-items:center; gap:12px; padding:8px 0; border-bottom:1px solid var(--border); }
        .modal-friend-item:last-child { border-bottom:none; }
        .modal-friend-avatar { width:32px; height:32px; border-radius:999px; background:#1f2937; display:flex; align-items:center; justify-content:center; font-size:12px; color:#ffdd33; font-weight:700; }
        .modal-friend-name { flex:1; font-size:13px; font-weight:600; }
        .modal-friend-amount { font-size:13px; color:var(--accent); font-weight:600; }
        .modal-footer { display:flex; gap:12px; justify-content:center; }
        .modal-btn { padding:12px 24px; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:.2s; }
        .modal-btn-primary { background:linear-gradient(135deg, var(--accent), #ffe066); color:#001; }
        .modal-btn-primary:hover { transform:translateY(-1px); }
        .modal-btn-secondary { background:#0b1a33; color:var(--text); border:1px solid var(--border); }
        .modal-btn-secondary:hover { background:#0d2142; }
        .success-icon { width:64px; height:64px; border-radius:999px; background:rgba(16,185,129,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
        .success-icon svg { width:32px; height:32px; color:#10b981; }
        .loading-spinner { width:32px; height:32px; border:3px solid var(--border); border-top:3px solid var(--accent); border-radius:999px; animation:spin 1s linear infinite; margin:0 auto 16px; }
        @keyframes spin { 0% { transform:rotate(0deg); } 100% { transform:rotate(360deg); } }
        .loading-text { text-align:center; font-size:16px; font-weight:600; color:var(--accent); margin-bottom:8px; }
        .loading-subtext { text-align:center; font-size:13px; color:var(--muted); }

        .transaction-detail { margin-bottom:20px; }
        .transaction-header { display:flex; align-items:center; gap:12px; padding:16px; background:#0b1a33; border-radius:12px; margin-bottom:16px; }
        .transaction-icon { width:48px; height:48px; border-radius:12px; background:rgba(255,204,0,.18); display:grid; place-items:center; color:var(--accent); }
        .transaction-info { flex:1; }
        .transaction-business { font-weight:700; font-size:16px; margin-bottom:4px; }
        .transaction-date { font-size:13px; color:var(--muted); }
        .transaction-amount { font-size:20px; font-weight:800; color:var(--accent); }
        .transaction-details { display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; margin-bottom:16px; }
        .detail-item { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); }
        .detail-label { color:var(--muted); font-size:13px; }
        .detail-value { font-weight:600; font-size:13px; }
        .detail-value.success { color:#10b981; }
        .transaction-description { background:#0b1a33; border-radius:8px; padding:12px; }
        .description-label { color:var(--muted); font-size:12px; margin-bottom:6px; }
        .description-text { font-size:14px; line-height:1.4; }

        @media (max-width: 768px) {
            .split-container { grid-template-columns: 1fr; }
            .share-type-buttons { grid-template-columns: 1fr; }
            .action-buttons { flex-direction:column; }
        }
    </style>
</head>
  <body>
    <canvas id="stars"></canvas>
    
    <div class="container">
    <!-- Header -->
    <div class="header">
        <a href="{{ route('home') }}" class="back-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke="#ffdd33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        <h1 class="header-title">Arkadaşlarla Böl</h1>
    </div>

    <div class="split-container">
        <!-- Sol Taraf - Hesap Hareketleri -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,19H5V5H19V19Z"/>
                    </svg>
                    Hesap Hareketi
                </h2>
            </div>

            <!-- İşletme Seçimi -->
            <div class="section-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8Z"/>
                </svg>
                İşletme Seçimi
            </div>

            <!-- Hesap Hareketi Detayı -->
            <div class="transaction-detail">
                <div class="transaction-header">
                    <div class="transaction-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8Z"/>
                        </svg>
                    </div>
              <div class="transaction-info">
                <div class="transaction-business" id="transactionBusiness">{{ $transactionData['merchant_name'] ?? 'Bilinmeyen İşletme' }}</div>
                <div class="transaction-date" id="transactionDate">
                  @if($transactionData['date'])
                    {{ \Carbon\Carbon::parse($transactionData['date'])->format('d F Y, H:i') }}
                  @else
                    Tarih bilgisi yok
                  @endif
                </div>
              </div>
              <div class="transaction-amount" id="transactionAmount">₺{{ number_format($transactionData['amount'] ?? 0, 2, ',', '.') }}</div>
                </div>

                <div class="transaction-details">
                    <div class="detail-item">
                        <span class="detail-label">İşlem Türü:</span>
                        <span class="detail-value">Harcama</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Kategori:</span>
                        <span class="detail-value">Yeme & İçme</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Ödeme Yöntemi:</span>
                        <span class="detail-value">Kampüs Kart</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Durum:</span>
                        <span class="detail-value success">Tamamlandı</span>
                    </div>
                </div>

                @if($transactionData['transaction_id'])
                    <div class="transaction-description">
                        <div class="description-label">İşlem ID:</div>
                        <div class="description-text" id="transactionDescription">
                            #{{ $transactionData['transaction_id'] }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Paylaşım Özeti -->
            <div class="summary-section">
                <div class="section-title">Paylaşım Özeti</div>
                <div class="summary-item">
                    <span class="summary-label">Toplam Tutar:</span>
                    <span class="summary-value" id="summaryTotalAmount">₺{{ number_format($transactionData['amount'] ?? 0, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Seçilen Arkadaş:</span>
                    <span class="summary-value" id="summaryFriendCount">0 kişi</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Kişi Başına:</span>
                    <span class="summary-value" id="summaryPerPerson">₺0.00</span>
                </div>
                <div class="summary-item" style="border-top:1px solid var(--border); padding-top:8px; margin-top:8px;">
                    <span class="summary-label">Sizin Payınız:</span>
                    <span class="summary-value" id="summaryYourShare" style="color:var(--accent);">₺{{ number_format($transactionData['amount'] ?? 0, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf - Arkadaş Listesi ve Tutar Dağılımı -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16,4C18.21,4 20,5.79 20,8C20,10.21 18.21,12 16,12C15.71,12 15.44,11.97 15.19,11.92L14.5,13.5C14.5,13.5 15.5,15 16,15C16.5,15 17.5,13.5 17.5,13.5L16.81,11.92C16.56,11.97 16.29,12 16,12C13.79,12 12,10.21 12,8C12,5.79 13.79,4 16,4M16,6A2,2 0 0,0 14,8A2,2 0 0,0 16,10A2,2 0 0,0 18,8A2,2 0 0,0 16,6M8,4C10.21,4 12,5.79 12,8C12,10.21 10.21,12 8,12C7.71,12 7.44,11.97 7.19,11.92L6.5,13.5C6.5,13.5 7.5,15 8,15C8.5,15 9.5,13.5 9.5,13.5L8.81,11.92C8.56,11.97 8.29,12 8,12C5.79,12 4,10.21 4,8C4,5.79 5.79,4 8,4M8,6A2,2 0 0,0 6,8A2,2 0 0,0 8,10A2,2 0 0,0 10,8A2,2 0 0,0 8,6M16,16C18.21,16 20,17.79 20,20C20,21.1 19.1,22 18,22H14C12.9,22 12,21.1 12,20C12,17.79 13.79,16 16,16M8,16C10.21,16 12,17.79 12,20C12,21.1 11.1,22 10,22H6C4.9,22 4,21.1 4,20C4,17.79 5.79,16 8,16Z"/>
                    </svg>
                    Arkadaş Listesi ve Tutar Dağılımı
                </h2>
            </div>

            <!-- Arkadaş Arama -->
            <div class="search-box">
                <input type="text" class="search-input" id="friendSearch" placeholder="Arkadaş ara...">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"/>
                </svg>
            </div>

            <!-- Arkadaş Listesi -->
            <div class="friends-section">
                <div class="section-title">Arkadaşlarınız</div>
                <div class="friends-list" id="friendsList">
                    <div class="empty-state">
                        <div class="empty-icon">👥</div>
                        <div class="empty-text">Arkadaşlar yükleniyor...</div>
                    </div>
                </div>
            </div>

            <!-- Seçilen Arkadaşlar ve Tutar Dağılımı -->
            <div class="selected-friends-section">
                <div class="section-title">Seçilen Arkadaşlar ve Tutarlar</div>
                <div id="selectedFriends" class="selected-friends">
                    <div class="empty-state">
                        <div class="empty-icon">👤</div>
                        <div class="empty-text">Henüz arkadaş seçilmedi</div>
                    </div>
                </div>
            </div>

            <!-- Paylaşım Türü -->
            <div class="share-type-section">
                <div class="section-title">Paylaşım Türü</div>
                <div class="share-type-buttons">
                    <label class="share-type-btn active" for="equalShare">
                        <input type="radio" class="share-type-input" name="shareType" id="equalShare" value="equal" checked>
                        Eşit Paylaşım
                    </label>

                    <label class="share-type-btn" for="percentageShare">
                        <input type="radio" class="share-type-input" name="shareType" id="percentageShare" value="percentage">
                        Yüzdesel Paylaşım
                    </label>

                    <label class="share-type-btn" for="customShare">
                        <input type="radio" class="share-type-input" name="shareType" id="customShare" value="custom">
                        Özel Tutar
                    </label>
                </div>
            </div>

            <!-- Yüzdesel Paylaşım Seçenekleri -->
            <div id="percentageOptions" class="percentage-section" style="display: none;">
                <div class="section-title">Yüzde Dağılımı</div>
                <div class="percentage-distribution" id="percentageDistribution">
                    <!-- Dinamik olarak oluşturulacak -->
                </div>
                <div class="warning-box">
                    <div class="warning-text">Toplam yüzde 100 olmalıdır!</div>
                </div>
            </div>

            <!-- Özel Tutar Seçenekleri -->
            <div id="customOptions" class="custom-section" style="display: none;">
                <div class="section-title">Özel Tutarlar</div>
                <div class="custom-amounts" id="customAmounts">
                    <!-- Dinamik olarak oluşturulacak -->
                </div>
            </div>

            <!-- Tutar Dağılım Özeti -->
            <div class="summary-section">
                <div class="section-title">Tutar Dağılım Özeti</div>
                <div id="shareSummary">
                    <div class="empty-state">
                        <div class="empty-icon">📊</div>
                        <div class="empty-text">Tutar dağılımı burada görünecek</div>
                    </div>
                </div>
            </div>

            <!-- Alt Butonlar -->
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" id="resetBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px;">
                        <path d="M12,4V1L8,5L12,9V6A6,6 0 0,1 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12H4A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4Z"/>
                    </svg>
                    Sıfırla
                </button>
                <button type="button" class="btn btn-primary" id="shareBtn" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px;">
                        <path d="M18,16.08C17.24,16.08 16.56,16.38 16.04,16.85L8.91,12.7C8.96,12.47 9,12.24 9,12C9,11.76 8.96,11.53 8.91,11.3L15.96,7.19C16.5,7.69 17.21,8 18,8A3,3 0 0,0 21,5A3,3 0 0,0 18,2A3,3 0 0,0 15,5C15,5.24 15.04,5.47 15.09,5.7L8.04,9.81C7.5,9.31 6.79,9 6,9A3,3 0 0,0 3,12A3,3 0 0,0 6,15C6.79,15 7.5,14.69 8.04,14.19L15.16,18.34C15.11,18.55 15.08,18.77 15.08,19C15.08,20.61 16.39,21.91 18,21.91C19.61,21.91 20.92,20.61 20.92,19A2.92,2.92 0 0,0 18,16.08Z"/>
                    </svg>
                    Paylaşımı Gönder
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Modals -->
    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="loading-spinner"></div>
                <div class="loading-text">Paylaşım Gönderiliyor</div>
                <div class="loading-subtext">Lütfen bekleyin...</div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="success-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                    </svg>
                </div>
                <div class="modal-title">Paylaşım Başarılı!</div>
                <div class="modal-subtitle">Arkadaşlarınıza istek gönderildi</div>
            </div>
            <div class="modal-body">
                <div class="modal-text" id="successMessage">
                    Paylaşım başarıyla gönderildi!
                </div>
                <div class="modal-amount" id="successAmount">
                    ₺0.00
                </div>
                <div class="modal-friends" id="successFriends">
                    <!-- Dinamik olarak doldurulacak -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-primary" id="closeSuccessBtn">
                    Ana Sayfaya Dön
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="success-icon" style="background:rgba(239,68,68,0.2);">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="color:#ef4444;">
                        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                    </svg>
                </div>
                <div class="modal-title">Hata Oluştu</div>
                <div class="modal-subtitle">Paylaşım gönderilemedi</div>
            </div>
            <div class="modal-body">
                <div class="modal-text" id="errorMessage">
                    Bir hata oluştu. Lütfen tekrar deneyin.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn modal-btn-secondary" id="closeErrorBtn">
                    Kapat
                </button>
                <button type="button" class="modal-btn modal-btn-primary" id="retryBtn">
                    Tekrar Dene
                </button>
            </div>
        </div>
    </div>

    <script>
    // Starfield animation
    (function(){
      const canvas = document.getElementById('stars');
      if (!canvas) return;
      
      const ctx = canvas.getContext('2d');
      let stars = [];
      let specialStar = null;
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
          r: Math.random()*1.2 + 0.2,
          a: Math.random()*0.5 + 0.25
        }));
        
        specialStar = {
          x: canvas.width * 0.9,
          y: canvas.height * 0.85,
          r: 2.0,
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
          
          if(Math.random() < 0.01){
            ctx.fillStyle = 'rgba(255,204,0,0.4)';
            ctx.fillRect(s.x-0.5, s.y-0.5, 1, 1);
          }
        }
        
        if(specialStar){
          const pulse = 1 + 0.5 * Math.sin(specialStar.phase + tick * 0.05);
          const haloR = specialStar.r * (12 + pulse * 4);
          const grad = ctx.createRadialGradient(specialStar.x, specialStar.y, 0, specialStar.x, specialStar.y, haloR);
          grad.addColorStop(0, 'rgba(255,220,60,0.4)');
          grad.addColorStop(0.4, 'rgba(255,204,0,0.15)');
          grad.addColorStop(1, 'rgba(255,204,0,0.0)');
          ctx.beginPath();
          ctx.fillStyle = grad;
          ctx.arc(specialStar.x, specialStar.y, haloR, 0, Math.PI*2);
          ctx.fill();
          
          ctx.beginPath();
          ctx.fillStyle = '#fff4c2';
          ctx.arc(specialStar.x, specialStar.y, specialStar.r * (1.4 + pulse*0.15), 0, Math.PI*2);
          ctx.fill();
          
          if(tick % 200 === 0){ specialStar.phase = Math.random()*Math.PI*2; }
        }
        requestAnimationFrame(draw);
      }
      
      window.addEventListener('resize', resize);
      resize();
    })();
    
    document.addEventListener('DOMContentLoaded', function() {
        // Gerçek arkadaş verisi backend'den gelir
        const friends = @json($friends);

        // Transaction verisi URL parametrelerinden gelir
        const transactionData = {
            transaction_id: '{{ $transactionData["transaction_id"] ?? "" }}',
            merchant_name: '{{ $transactionData["merchant_name"] ?? "" }}',
            amount: {{ $transactionData["amount"] ?? 0 }},
            date: '{{ $transactionData["date"] ?? "" }}'
        };

        let selectedFriends = [];
        let currentShareType = 'equal';

        // Sayfa yüklendiğinde arkadaş listesini oluştur
        loadFriends();

        // Event listeners - Hesap hareketi verisi sabit olduğu için sadece arkadaş seçimi dinleniyor
        document.getElementById('friendSearch').addEventListener('input', filterFriends);
        document.querySelectorAll('input[name="shareType"]').forEach(radio => {
            radio.addEventListener('change', handleShareTypeChange);
        });
        document.getElementById('resetBtn').addEventListener('click', resetForm);
        document.getElementById('shareBtn').addEventListener('click', shareBill);

        // Modal event listeners
        document.getElementById('closeSuccessBtn').addEventListener('click', closeSuccessModal);
        document.getElementById('closeErrorBtn').addEventListener('click', closeErrorModal);
        document.getElementById('retryBtn').addEventListener('click', retryShare);

        // Modal dışına tıklayınca kapatma
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) closeSuccessModal();
        });
        document.getElementById('errorModal').addEventListener('click', function(e) {
            if (e.target === this) closeErrorModal();
        });

        function loadFriends() {
            const friendList = document.getElementById('friendsList');
            friendList.innerHTML = '';

            if (friends && friends.length > 0) {
                friends.forEach(friend => {
                    const friendCard = createFriendCard(friend);
                    friendList.appendChild(friendCard);
                });
            } else {
                friendList.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">👥</div>
                        <div class="empty-text">Arkadaş bulunamadı</div>
                    </div>
                `;
            }
        }

        function createFriendCard(friend) {
            const friendCard = document.createElement('div');
            friendCard.className = 'friend-card';
            friendCard.setAttribute('data-friend-id', friend.id);

            friendCard.innerHTML = `
                <div class="friend-content">
                    <div class="friend-avatar">${friend.name.charAt(0)}${friend.surname ? friend.surname.charAt(0) : ''}</div>
                    <div class="friend-info">
                        <div class="friend-name">${friend.name} ${friend.surname || ''}</div>
                        <div class="friend-phone">${friend.phone || 'Telefon bilgisi yok'}</div>
                    </div>
                    <div class="friend-checkbox">
                        <input type="checkbox" class="friend-check">
                    </div>
                </div>
            `;

            // Click event
            friendCard.addEventListener('click', function() {
                toggleFriendSelection(friend.id);
            });

            // Checkbox event
            const checkbox = friendCard.querySelector('.friend-check');
            checkbox.addEventListener('change', function(e) {
                e.stopPropagation();
                toggleFriendSelection(friend.id);
            });

            return friendCard;
        }

        function toggleFriendSelection(friendId) {
            const friend = friends.find(f => f.id == friendId);
            const friendCard = document.querySelector(`[data-friend-id="${friendId}"]`);
            const checkbox = friendCard.querySelector('.friend-check');

            if (selectedFriends.find(f => f.id === friendId)) {
                // Arkadaşı listeden çıkar
                selectedFriends = selectedFriends.filter(f => f.id !== friendId);
                friendCard.classList.remove('selected');
                checkbox.checked = false;
            } else {
                // Arkadaşı listeye ekle
                selectedFriends.push(friend);
                friendCard.classList.add('selected');
                checkbox.checked = true;
            }

            updateSelectedFriendsDisplay();
            updateCalculations();
            updateShareOptions();
        }

        function updateSelectedFriendsDisplay() {
            const container = document.getElementById('selectedFriends');

            if (selectedFriends.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">👤</div>
                        <div class="empty-text">Henüz arkadaş seçilmedi</div>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            selectedFriends.forEach(friend => {
                const transactionAmount = transactionData.amount || 0;
                let amount = 0;
                let percentage = 0;

                if (currentShareType === 'equal') {
                    amount = transactionAmount / (selectedFriends.length + 1);
                    percentage = 100 / (selectedFriends.length + 1);
                } else if (currentShareType === 'percentage') {
                    const percentageInput = document.querySelector(`[data-friend-id="${friend.id}"].percentage-input`);
                    percentage = parseFloat(percentageInput?.value) || 0;
                    amount = transactionAmount * percentage / 100;
                } else if (currentShareType === 'custom') {
                    const customInput = document.querySelector(`[data-friend-id="${friend.id}"].custom-input`);
                    amount = parseFloat(customInput?.value) || 0;
                    percentage = transactionAmount > 0 ? (amount / transactionAmount) * 100 : 0;
                }

                const selectedFriend = document.createElement('div');
                selectedFriend.className = 'selected-friend';
                selectedFriend.innerHTML = `
                    <div class="selected-friend-avatar">${friend.name.charAt(0)}${friend.surname ? friend.surname.charAt(0) : ''}</div>
                    <div class="selected-friend-info">
                        <div class="selected-friend-name">${friend.name} ${friend.surname || ''}</div>
                        <div class="selected-friend-amounts">
                            <span class="amount-text">₺${amount.toFixed(2)}</span>
                            <span class="percentage-text">(${percentage.toFixed(1)}%)</span>
                        </div>
                    </div>
                    <div class="remove-friend" data-friend-id="${friend.id}">×</div>
                `;

                const removeBtn = selectedFriend.querySelector('.remove-friend');
                removeBtn.addEventListener('click', function() {
                    toggleFriendSelection(friend.id);
                });

                container.appendChild(selectedFriend);
            });
        }

        function filterFriends() {
            const searchTerm = document.getElementById('friendSearch').value.toLowerCase();
            const friendCards = document.querySelectorAll('.friend-card');

            friendCards.forEach(card => {
                const name = card.querySelector('.friend-name').textContent.toLowerCase();
                const phone = card.querySelector('.friend-phone').textContent.toLowerCase();

                if (name.includes(searchTerm) || phone.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function handleShareTypeChange() {
            currentShareType = document.querySelector('input[name="shareType"]:checked').value;

            // Tüm butonları pasif yap
            document.querySelectorAll('.share-type-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Seçilen butonu aktif yap
            const selectedBtn = document.querySelector(`label[for="${currentShareType}Share"]`);
            if (selectedBtn) {
                selectedBtn.classList.add('active');
            }

            // Tüm seçenekleri gizle
            document.getElementById('percentageOptions').style.display = 'none';
            document.getElementById('customOptions').style.display = 'none';

            // Seçilen seçeneği göster
            if (currentShareType === 'percentage') {
                document.getElementById('percentageOptions').style.display = 'block';
                updatePercentageOptions();
            } else if (currentShareType === 'custom') {
                document.getElementById('customOptions').style.display = 'block';
                updateCustomOptions();
            }

            updateCalculations();
        }

        function updateShareOptions() {
            if (currentShareType === 'percentage') {
                updatePercentageOptions();
            } else if (currentShareType === 'custom') {
                updateCustomOptions();
            }
        }

        function updatePercentageOptions() {
            const container = document.getElementById('percentageDistribution');
            container.innerHTML = '';

            selectedFriends.forEach(friend => {
                const percentageItem = document.createElement('div');
                percentageItem.className = 'percentage-item';
                percentageItem.innerHTML = `
                    <div class="percentage-label">${friend.name} ${friend.surname || ''}</div>
                    <input type="number" class="percentage-input" data-friend-id="${friend.id}"
                           placeholder="0" min="0" max="100" step="0.01">
                    <div class="percentage-unit">%</div>
                `;
                container.appendChild(percentageItem);
            });

            // Event listeners for percentage inputs
            container.querySelectorAll('.percentage-input').forEach(input => {
                input.addEventListener('input', updateCalculations);
            });
        }

        function updateCustomOptions() {
            const container = document.getElementById('customAmounts');
            container.innerHTML = '';

            selectedFriends.forEach(friend => {
                const customItem = document.createElement('div');
                customItem.className = 'custom-item';
                customItem.innerHTML = `
                    <div class="custom-label">${friend.name} ${friend.surname || ''}</div>
                    <input type="number" class="custom-input" data-friend-id="${friend.id}"
                           placeholder="0.00" min="0" step="0.01">
                    <div class="custom-unit">₺</div>
                `;
                container.appendChild(customItem);
            });

            // Event listeners for custom inputs
            container.querySelectorAll('.custom-input').forEach(input => {
                input.addEventListener('input', updateCalculations);
            });
        }

        function updateCalculations() {
            const transactionAmount = transactionData.amount || 0;
            const totalPeople = selectedFriends.length + 1; // +1 for the user

            // Özet güncellemeleri
            document.getElementById('summaryTotalAmount').textContent = `₺${transactionAmount.toFixed(2)}`;
            document.getElementById('summaryFriendCount').textContent = `${selectedFriends.length} kişi`;

            let yourShare = transactionAmount;
            let perPersonAmount = 0;

            if (selectedFriends.length > 0) {
                if (currentShareType === 'equal') {
                    perPersonAmount = transactionAmount / totalPeople;
                    yourShare = perPersonAmount;
                } else if (currentShareType === 'percentage') {
                    const totalPercentage = Array.from(document.querySelectorAll('.percentage-input'))
                        .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

                    if (totalPercentage <= 100) {
                        yourShare = transactionAmount * (100 - totalPercentage) / 100;
                        perPersonAmount = transactionAmount / totalPeople; // Ortalama için
                    }
                } else if (currentShareType === 'custom') {
                    const totalCustomAmount = Array.from(document.querySelectorAll('.custom-input'))
                        .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

                    yourShare = transactionAmount - totalCustomAmount;
                    perPersonAmount = transactionAmount / totalPeople; // Ortalama için
                }
            }

            document.getElementById('summaryPerPerson').textContent = `₺${perPersonAmount.toFixed(2)}`;
            document.getElementById('summaryYourShare').textContent = `₺${yourShare.toFixed(2)}`;

            // Seçilen arkadaşların görünümünü güncelle
            updateSelectedFriendsDisplay();

            // Paylaşım özetini güncelle
            updateShareSummary();

            // Paylaşım butonunu aktif/pasif yap
            const shareBtn = document.getElementById('shareBtn');
            shareBtn.disabled = selectedFriends.length === 0;
        }

        function updateShareSummary() {
            const container = document.getElementById('shareSummary');
            const transactionAmount = transactionData.amount || 0;

            if (selectedFriends.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">📊</div>
                        <div class="empty-text">Tutar dağılımı burada görünecek</div>
                    </div>
                `;
                return;
            }

            let summaryHTML = '';

            selectedFriends.forEach(friend => {
                let amount = 0;
                let percentage = 0;

                if (currentShareType === 'equal') {
                    amount = transactionAmount / (selectedFriends.length + 1);
                    percentage = 100 / (selectedFriends.length + 1);
                } else if (currentShareType === 'percentage') {
                    const percentageInput = document.querySelector(`[data-friend-id="${friend.id}"].percentage-input`);
                    percentage = parseFloat(percentageInput?.value) || 0;
                    amount = transactionAmount * percentage / 100;
                } else if (currentShareType === 'custom') {
                    const customInput = document.querySelector(`[data-friend-id="${friend.id}"].custom-input`);
                    amount = parseFloat(customInput?.value) || 0;
                    percentage = transactionAmount > 0 ? (amount / transactionAmount) * 100 : 0;
                }

                summaryHTML += `
                    <div class="summary-item">
                        <span class="summary-label">${friend.name} ${friend.surname || ''}</span>
                        <span class="summary-value">₺${amount.toFixed(2)} (${percentage.toFixed(1)}%)</span>
                    </div>
                `;
            });

            const yourShare = parseFloat(document.getElementById('summaryYourShare').textContent.replace('₺', '')) || 0;
            const yourPercentage = transactionAmount > 0 ? (yourShare / transactionAmount) * 100 : 0;

            summaryHTML += `
                <div class="summary-item" style="border-top:1px solid var(--border); padding-top:8px; margin-top:8px;">
                    <span class="summary-label">Sizin Payınız</span>
                    <span class="summary-value" style="color:var(--accent);">₺${yourShare.toFixed(2)} (${yourPercentage.toFixed(1)}%)</span>
                </div>
                <div class="summary-item" style="font-weight:700;">
                    <span class="summary-label">Toplam</span>
                    <span class="summary-value">₺${transactionAmount.toFixed(2)} (100%)</span>
                </div>
            `;

            container.innerHTML = summaryHTML;
        }

        function resetForm() {
            // Hesap hareketi verisi sabit olduğu için sadece arkadaş seçimlerini sıfırla

            // Arkadaş seçimlerini sıfırla
            selectedFriends = [];
            document.querySelectorAll('.friend-card').forEach(card => {
                card.classList.remove('selected');
                card.querySelector('.friend-check').checked = false;
            });

            // Paylaşım türünü eşit paylaşıma döndür
            document.getElementById('equalShare').checked = true;
            currentShareType = 'equal';

            // Görünümleri güncelle
            updateSelectedFriendsDisplay();
            updateCalculations();
            handleShareTypeChange();

            // Arama kutusunu temizle
            document.getElementById('friendSearch').value = '';
            filterFriends();
        }

        // Modal Functions
        function showLoadingModal() {
            document.getElementById('loadingModal').classList.add('show');
        }

        function hideLoadingModal() {
            document.getElementById('loadingModal').classList.remove('show');
        }

        function showSuccessModal(data) {
            const transactionAmount = transactionData.amount || 0;
            const totalAmount = data.total_amount || 0;
            const splitsCount = data.splits_count || 0;

            // Success message güncelle
            document.getElementById('successMessage').textContent = 
                `${splitsCount} kişiye toplam ₺${totalAmount.toFixed(2)} tutarında istek gönderildi.`;

            // Total amount göster
            document.getElementById('successAmount').textContent = `₺${totalAmount.toFixed(2)}`;

            // Seçilen arkadaşları göster
            const friendsContainer = document.getElementById('successFriends');
            friendsContainer.innerHTML = '';

            selectedFriends.forEach(friend => {
                const amount = getFriendAmount(friend.id);
                const friendItem = document.createElement('div');
                friendItem.className = 'modal-friend-item';
                friendItem.innerHTML = `
                    <div class="modal-friend-avatar">${friend.name.charAt(0)}${friend.surname ? friend.surname.charAt(0) : ''}</div>
                    <div class="modal-friend-name">${friend.name} ${friend.surname || ''}</div>
                    <div class="modal-friend-amount">₺${amount.toFixed(2)}</div>
                `;
                friendsContainer.appendChild(friendItem);
            });

            document.getElementById('successModal').classList.add('show');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
            // Ana sayfaya yönlendir
            setTimeout(() => {
                window.location.href = '{{ route("home") }}';
            }, 300);
        }

        function showErrorModal(message) {
            document.getElementById('errorMessage').textContent = message || 'Bir hata oluştu. Lütfen tekrar deneyin.';
            document.getElementById('errorModal').classList.add('show');
        }

        function closeErrorModal() {
            document.getElementById('errorModal').classList.remove('show');
        }

        function retryShare() {
            closeErrorModal();
            shareBill();
        }

        async function shareBill() {
            const transactionAmount = transactionData.amount || 0;
            const businessName = transactionData.merchant_name || 'Bilinmeyen İşletme';

            if (selectedFriends.length === 0) {
                showErrorModal('Lütfen en az bir arkadaş seçin.');
                return;
            }

            // Paylaşım verilerini hazırla (backend API formatına uygun)
            const shareData = {
                transaction_id: parseInt(transactionData.transaction_id),
                share_type: currentShareType,
                friends: selectedFriends.map(friend => ({
                    id: parseInt(friend.id),
                    name: `${friend.name} ${friend.surname || ''}`.trim(),
                    amount: getFriendAmount(friend.id),
                    percentage: currentShareType === 'percentage' ? getFriendPercentage(friend.id) : null,
                    weight: currentShareType === 'equal' ? 1.00 : (getFriendAmount(friend.id) / transactionAmount)
                }))
            };

            console.log('Paylaşım verisi:', shareData);

            // Loading state
            const shareBtn = document.getElementById('shareBtn');
            const originalText = shareBtn.textContent;
            shareBtn.disabled = true;
            shareBtn.textContent = 'Gönderiliyor...';
            
            // Loading modal göster
            showLoadingModal();
            
            // API çağrısı yap
            try {
                const response = await fetch('/create-split', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(shareData)
                });
                
                const data = await response.json();
                
                // Loading modal'ı kapat
                hideLoadingModal();
                
                if (response.ok && data.success) {
                    shareBtn.textContent = 'Başarılı!';
                    // Success modal göster
                    showSuccessModal(data);
                } else {
                    shareBtn.disabled = false;
                    shareBtn.textContent = originalText;
                    showErrorModal(data.message || 'Paylaşım isteği gönderilirken hata oluştu.');
                }
            } catch (error) {
                console.error('Split creation error:', error);
                hideLoadingModal();
                shareBtn.disabled = false;
                shareBtn.textContent = originalText;
                showErrorModal('Bağlantı hatası oluştu. Lütfen tekrar deneyin.');
            }
        }

        function getFriendAmount(friendId) {
            const transactionAmount = transactionData.amount || 0;

            if (currentShareType === 'equal') {
                return transactionAmount / (selectedFriends.length + 1);
            } else if (currentShareType === 'percentage') {
                const percentageInput = document.querySelector(`[data-friend-id="${friendId}"].percentage-input`);
                const percentage = parseFloat(percentageInput?.value) || 0;
                return transactionAmount * percentage / 100;
            } else if (currentShareType === 'custom') {
                const customInput = document.querySelector(`[data-friend-id="${friendId}"].custom-input`);
                return parseFloat(customInput?.value) || 0;
            }
            return 0;
        }

        function getFriendPercentage(friendId) {
            const transactionAmount = transactionData.amount || 0;
            const amount = getFriendAmount(friendId);

            if (transactionAmount > 0) {
                return (amount / transactionAmount) * 100;
            }
            return 0;
        }
    });
</script>
</body>
</html>
