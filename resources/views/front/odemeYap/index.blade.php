<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Kod Ödeme - Kampüs Dijital Cüzdan</title>
    <style>
      :root { --bg:#000816; --panel:#071223; --muted:#94a3b8; --text:#e8edf6; --accent:#ffcc00; --accent-2:#003a70; --danger:#ef4444; --warning:#ffcc00; --card:#06101f; --border:#0f2747; }
      * { box-sizing: border-box; }
      html,body { margin:0; padding:0; background:var(--bg); color:var(--text); font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; }
      a { color: inherit; text-decoration: none; }
      #stars { position:fixed; inset:0; width:100%; height:100%; z-index:0; background: radial-gradient(1400px 700px at 80% -10%, rgba(0,58,112,.35), transparent 62%), radial-gradient(900px 480px at 12% 112%, rgba(10,32,64,.6), transparent 60%), linear-gradient(180deg, #000a1a 0%, #00040b 100%); }
      .container { max-width: 800px; margin: 0 auto; padding: 16px; position:relative; z-index:1; }
      
      .header { display:flex; align-items:center; gap:12px; padding:12px 0; margin-bottom:24px; }
      .back-btn { width:40px; height:40px; border-radius:10px; background:#0b1a33; display:grid; place-items:center; cursor:pointer; border:1px solid var(--border); transition:.2s; }
      .back-btn:hover { background:#0d2142; transform: translateY(-1px); }
      .header-title { font-weight:700; font-size:20px; }
      
      .card { background: linear-gradient(180deg, #071223, #060f1e); border:1px solid var(--border); border-radius:16px; padding:20px; box-shadow: 0 10px 30px rgba(0,0,0,.35); margin-bottom:16px; }
      
      .payment-info { margin-bottom: 24px; }
      .merchant-section { display: flex; align-items: center; gap: 16px; padding: 20px; background: #0b1a33; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 20px; }
      .merchant-icon { width: 64px; height: 64px; border-radius: 16px; background: rgba(255,204,0,.15); display: flex; align-items: center; justify-content: center; font-size: 32px; }
      .merchant-details { flex: 1; }
      .merchant-name { font-weight: 700; font-size: 20px; color: var(--text); }
      .merchant-category { font-size: 14px; color: var(--muted); margin-top: 4px; }
      
      .amount-section { text-align: center; padding: 24px; background: linear-gradient(135deg, rgba(255,204,0,.1), rgba(0,58,112,.1)); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 20px; }
      .amount-label { font-size: 16px; color: var(--muted); margin-bottom: 8px; }
      .amount-value { font-size: 48px; font-weight: 800; color: var(--accent); }
      
      .balance-info { display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #0b1a33; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 20px; }
      .balance-label { font-size: 14px; color: var(--muted); }
      .balance-amount { font-size: 18px; font-weight: 700; color: var(--text); }
      
      .payment-details { margin-bottom: 24px; }
      .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; }
      .detail-item { padding: 12px; background: #0b1a33; border: 1px solid var(--border); border-radius: 8px; }
      .detail-label { font-size: 12px; color: var(--muted); margin-bottom: 4px; }
      .detail-value { font-size: 14px; font-weight: 600; color: var(--text); }
      
      .pay-btn { width: 100%; padding: 16px; background: linear-gradient(135deg, var(--accent), #ffe066); color: #001; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: .2s; box-shadow: 0 6px 20px rgba(255,204,0,.2); }
      .pay-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,204,0,.3); }
      .pay-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }
      
      .insufficient-balance { background: linear-gradient(135deg, rgba(239,68,68,.1), rgba(220,38,38,.1)); border-color: var(--danger); }
      .insufficient-text { color: var(--danger); text-align: center; margin-bottom: 16px; font-weight: 600; }
      
      .success-message { background: linear-gradient(135deg, rgba(34,197,94,.1), rgba(22,163,74,.1)); border: 1px solid #22c55e; border-radius: 12px; padding: 16px; text-align: center; margin-bottom: 20px; }
      .success-icon { font-size: 48px; margin-bottom: 12px; }
      .success-title { font-size: 18px; font-weight: 700; color: #22c55e; margin-bottom: 8px; }
      .success-desc { font-size: 14px; color: var(--muted); }
      
      /* Payment Confirmation Modal */
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
        text-align: center;
      }
      
      .modal-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        background: linear-gradient(135deg, rgba(255,204,0,.15), rgba(0,58,112,.15));
        border: 2px solid var(--accent);
      }
      
      .modal-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
      }
      
      .modal-desc {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 24px;
        line-height: 1.5;
      }
      
      .modal-amount {
        font-size: 32px;
        font-weight: 800;
        color: var(--accent);
        margin-bottom: 8px;
      }
      
      .modal-merchant {
        font-size: 16px;
        color: var(--text);
        margin-bottom: 24px;
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
    </style>
  </head>
  <body>
    <canvas id="stars"></canvas>
    <div class="container">
      <div class="header">
        <a href="{{ route('home') }}" class="back-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke="#ffdd33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <div class="header-title">QR Kod ile Ödeme</div>
      </div>

      @if($qrData && $merchant)
        <div class="card">
          <div class="merchant-section">
            <div class="merchant-icon">🏪</div>
            <div class="merchant-details">
              <div class="merchant-name">{{ $merchant->name }}</div>
              <div class="merchant-category">{{ $merchant->category->name }}</div>
            </div>
          </div>
          
          <div class="amount-section">
            <div class="amount-label">Ödenecek Tutar</div>
            <div class="amount-value">₺ {{ number_format($qrData['amount'], 2, ',', '.') }}</div>
          </div>
          
          <div class="balance-info {{ $wallet->balance < $qrData['amount'] ? 'insufficient-balance' : '' }}">
            <div>
              <div class="balance-label">Mevcut Bakiyeniz</div>
              <div class="balance-amount">₺ {{ number_format($wallet->balance, 2, ',', '.') }}</div>
            </div>
            @if($wallet->balance >= $qrData['amount'])
              <div style="color: #22c55e;">✓ Yeterli</div>
            @else
              <div style="color: var(--danger);">✗ Yetersiz</div>
            @endif
          </div>
          
          @if($wallet->balance < $qrData['amount'])
            <div class="insufficient-text">
              Yetersiz bakiye! {{ number_format($qrData['amount'] - $wallet->balance, 2, ',', '.') }} ₺ daha yüklemeniz gerekiyor.
            </div>
          @endif
          
          <div class="payment-details">
            <div class="detail-grid">
              <div class="detail-item">
                <div class="detail-label">QR ID</div>
                <div class="detail-value">{{ $qrData['qr_id'] }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Merchant ID</div>
                <div class="detail-value">{{ $qrData['merchant_id'] }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Para Birimi</div>
                <div class="detail-value">{{ $qrData['currency'] }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">İşlem Zamanı</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($qrData['ts'])->format('d.m.Y H:i') }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Kalan Bakiye</div>
                <div class="detail-value">₺ {{ number_format(max(0, $wallet->balance - $qrData['amount']), 2, ',', '.') }}</div>
              </div>
            </div>
          </div>
          
          <button 
            class="pay-btn" 
            id="payBtn"
            {{ $wallet->balance < $qrData['amount'] ? 'disabled' : '' }}
          >
            @if($wallet->balance >= $qrData['amount'])
              Ödemeyi Onayla
            @else
              Yetersiz Bakiye
            @endif
          </button>
        </div>
      @else
        <div class="card">
          <div style="text-align: center; padding: 40px;">
            <div style="font-size: 48px; margin-bottom: 16px;">❌</div>
            <div style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Geçersiz QR Kod</div>
            <div style="color: var(--muted);">QR kod verisi bulunamadı veya geçersiz.</div>
          </div>
        </div>
      @endif
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal" id="confirmationModal">
      <div class="modal-content">
        <div class="modal-icon">
          💳
        </div>
        <div class="modal-title">Ödemeyi Onaylıyor musunuz?</div>
        <div class="modal-desc">
          Aşağıdaki tutarı ödemeyi onaylıyor musunuz? Bu işlem geri alınamaz.
        </div>
        
        <div class="modal-amount" id="confirmAmount">₺ 120,00</div>
        <div class="modal-merchant" id="confirmMerchant">Burger King</div>
        
        <div class="modal-buttons">
          <button class="modal-btn modal-btn-secondary" id="cancelPayment">İptal</button>
          <button class="modal-btn modal-btn-primary" id="confirmPayment">Ödemeyi Onayla</button>
        </div>
      </div>
    </div>

    <script>
      // Starfield animation (same as other pages)
      (function(){
        const canvas = document.getElementById('stars');
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
          const count = Math.min(1500, Math.floor((canvas.width*canvas.height)/3000));
          stars = new Array(count).fill(0).map(()=>({
            x: Math.random()*canvas.width,
            y: Math.random()*canvas.height,
            r: Math.random()*1.4 + 0.2,
            a: Math.random()*0.6 + 0.25
          }));
          for(let i=0;i<Math.max(16, Math.floor(count*0.02));i++){
            stars.push({
              x: Math.random()*canvas.width,
              y: Math.random()*canvas.height,
              r: Math.random()*2.2 + 1.2,
              a: Math.random()*0.35 + 0.15
            });
          }
          specialStar = {
            x: canvas.width * 0.92,
            y: canvas.height * 0.88,
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
            if(Math.random() < 0.015){
              ctx.fillStyle = 'rgba(255,204,0,0.45)';
              ctx.fillRect(s.x-0.5, s.y-0.5, 1, 1);
            }
          }
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
            ctx.beginPath();
            ctx.fillStyle = '#fff4c2';
            ctx.arc(specialStar.x, specialStar.y, specialStar.r * (1.6 + pulse*0.2), 0, Math.PI*2);
            ctx.fill();
            ctx.save();
            ctx.translate(specialStar.x, specialStar.y);
            ctx.rotate((tick * 0.01) % (Math.PI*2));
            ctx.strokeStyle = 'rgba(255,220,60,0.8)';
            ctx.lineWidth = 1;
            const len = specialStar.r * (10 + pulse*3);
            ctx.beginPath(); ctx.moveTo(-len, 0); ctx.lineTo(len, 0); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(0, -len); ctx.lineTo(0, len); ctx.stroke();
            ctx.restore();
            if(tick % 240 === 0){ specialStar.phase = Math.random()*Math.PI*2; }
          }
          requestAnimationFrame(draw);
        }
        window.addEventListener('resize', resize);
        resize();
      })();

      // Payment functionality
      const payBtn = document.getElementById('payBtn');
      const confirmationModal = document.getElementById('confirmationModal');
      const cancelPayment = document.getElementById('cancelPayment');
      const confirmPayment = document.getElementById('confirmPayment');
      const confirmAmount = document.getElementById('confirmAmount');
      const confirmMerchant = document.getElementById('confirmMerchant');
      
      @if($qrData && $merchant)
      const qrData = @json($qrData);
      const merchant = @json($merchant);
      @endif
      
      // Modal functions
      function showConfirmationModal() {
        if (qrData && merchant) {
          confirmAmount.textContent = `₺ ${qrData.amount.toFixed(2).replace('.', ',')}`;
          confirmMerchant.textContent = merchant.name;
        }
        confirmationModal.classList.add('show');
      }
      
      function hideConfirmationModal() {
        confirmationModal.classList.remove('show');
      }
      
      // Event listeners
      if (payBtn && !payBtn.disabled && qrData) {
        payBtn.addEventListener('click', () => {
          showConfirmationModal();
        });
      }
      
      if (cancelPayment) {
        cancelPayment.addEventListener('click', hideConfirmationModal);
      }
      
      if (confirmationModal) {
        confirmationModal.addEventListener('click', (e) => {
          if (e.target === confirmationModal) {
            hideConfirmationModal();
          }
        });
      }
      
      if (confirmPayment && qrData) {
        confirmPayment.addEventListener('click', async () => {
          hideConfirmationModal();
            payBtn.disabled = true;
            payBtn.textContent = 'İşlem yapılıyor...';
            
            try {
              const response = await fetch('/process-qr-payment', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                  qr_id: qrData.qr_id,
                  merchant_id: qrData.merchant_id,
                  amount: qrData.amount,
                  currency: qrData.currency
                })
              });

              const data = await response.json();

              if (response.ok && data.success) {
                // Show success message
                const card = document.querySelector('.card');
                card.innerHTML = `
                  <div class="success-message">
                    <div class="success-icon">✅</div>
                    <div class="success-title">Ödeme Başarılı!</div>
                    <div class="success-desc">${merchant.name}'a ₺ ${qrData.amount.toFixed(2).replace('.', ',')} ödemeniz gerçekleştirildi.</div>
                    <div style="margin-top: 12px; font-size: 14px; color: var(--muted);">
                      Yeni bakiyeniz: ₺ ${data.balance.toFixed(2).replace('.', ',')}
                    </div>
                  </div>
                  <button onclick="window.location.href='{{ route('home') }}'" class="pay-btn">Ana Sayfaya Dön</button>
                `;
              } else {
                // Show error message
                alert(data.message || 'Ödeme işlemi başarısız!');
                payBtn.disabled = false;
                payBtn.textContent = 'Ödemeyi Onayla';
              }
            } catch (error) {
              console.error('Payment error:', error);
              alert('Bağlantı hatası oluştu. Lütfen tekrar deneyin.');
              payBtn.disabled = false;
              payBtn.textContent = 'Ödemeyi Onayla';
            }
        });
      }
    </script>
  </body>
</html>
