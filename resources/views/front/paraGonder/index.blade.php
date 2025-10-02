<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Para Gönder - Kampüs Dijital Cüzdan</title>
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
      
      .balance-section { text-align:center; margin-bottom:24px; }
      .balance-label { color:var(--muted); font-size:14px; margin-bottom:8px; }
      .balance-amount { font-size:32px; font-weight:800; color:var(--accent); }
      
      .section-title { font-size:16px; font-weight:700; margin-bottom:16px; color:var(--text); }
      
      .friends-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; margin-bottom:20px; }
      @media(min-width:640px){ .friends-grid { grid-template-columns: repeat(3, 1fr);} }
      @media(min-width:800px){ .friends-grid { grid-template-columns: repeat(4, 1fr);} }
      
      .friend-card { background:#0b1a33; border:2px solid var(--border); border-radius:12px; padding:12px; cursor:pointer; transition:.2s; text-align:center; }
      .friend-card:hover { background:#0d2142; transform: translateY(-1px); }
      .friend-card.selected { border-color:var(--accent); background:#0d2142; }
      .friend-avatar { width:48px; height:48px; border-radius:999px; background:#1f2937; display:flex; align-items:center; justify-content:center; font-weight:700; margin:0 auto 8px; color:#ffdd33; }
      .friend-name { font-weight:600; font-size:14px; }
      .friend-status { font-size:11px; color:var(--muted); margin-top:2px; }
      
      .amount-section { margin-bottom:20px; }
      .amount-input { width:100%; padding:16px; background:#0b1a33; border:1px solid var(--border); border-radius:12px; color:var(--text); font-size:18px; font-weight:600; text-align:center; }
      .amount-input:focus { outline:none; border-color:var(--accent); }
      .amount-label { display:block; margin-bottom:8px; font-weight:600; }
      
      .selected-friends { margin-bottom:20px; }
      .selected-list { display:flex; flex-wrap:wrap; gap:8px; }
      .selected-friend { background:#0d2142; border:1px solid var(--accent); border-radius:20px; padding:6px 12px; display:flex; align-items:center; gap:8px; font-size:12px; }
      .remove-friend { width:16px; height:16px; border-radius:999px; background:var(--danger); color:white; display:grid; place-items:center; cursor:pointer; font-size:10px; }
      
      .send-btn { width:100%; padding:16px; background:linear-gradient(135deg, var(--accent), #ffe066); color:#001; border:none; border-radius:12px; font-weight:700; font-size:16px; cursor:pointer; transition:.2s; box-shadow:0 6px 20px rgba(255,204,0,.2); }
      .send-btn:hover { transform: translateY(-2px); box-shadow:0 8px 25px rgba(255,204,0,.3); }
      .send-btn:disabled { opacity:.5; cursor:not-allowed; transform:none; }
      
      .summary { background:#0b1a33; border:1px solid var(--border); border-radius:12px; padding:16px; margin-bottom:20px; }
      .summary-row { display:flex; justify-content:space-between; margin-bottom:8px; }
      .summary-row:last-child { margin-bottom:0; font-weight:700; font-size:16px; color:var(--accent); border-top:1px solid var(--border); padding-top:8px; }
      
      /* Modal Styles */
      .modal { position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(8px); display:none; align-items:center; justify-content:center; z-index:1000; padding:20px; }
      .modal.show { display:flex !important; }
      .modal-content { background:linear-gradient(180deg, #071223, #060f1e); border:1px solid var(--border); border-radius:20px; padding:0; max-width:400px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.6); animation:modalSlideIn 0.3s ease; }
      .modal-header { padding:24px 24px 16px; border-bottom:1px solid var(--border); }
      .modal-title { font-size:20px; font-weight:700; color:var(--text); margin:0; }
      .modal-body { padding:20px 24px; }
      .modal-footer { padding:16px 24px 24px; display:flex; gap:12px; }
      .modal-btn { flex:1; padding:12px 20px; border:none; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.2s; }
      .modal-btn-primary { background:linear-gradient(135deg, var(--accent), #ffe066); color:#001; }
      .modal-btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(255,204,0,0.3); }
      .modal-btn-secondary { background:#0b1a33; color:var(--text); border:1px solid var(--border); }
      .modal-btn-secondary:hover { background:#0d2142; }
      .modal-text { color:var(--text); line-height:1.5; margin-bottom:16px; }
      .modal-amount { font-size:24px; font-weight:700; color:var(--accent); text-align:center; margin:16px 0; }
      .modal-friends { background:#0b1a33; border-radius:10px; padding:12px; margin:12px 0; }
      .modal-friend-item { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
      .modal-friend-item:last-child { margin-bottom:0; }
      .modal-friend-avatar { width:24px; height:24px; border-radius:50%; background:#1f2937; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#ffdd33; }
      .modal-friend-name { font-size:14px; color:var(--text); }
      
      @keyframes modalSlideIn {
        from { opacity:0; transform:scale(0.9) translateY(-20px); }
        to { opacity:1; transform:scale(1) translateY(0); }
      }
      
      .success-icon { width:60px; height:60px; margin:0 auto 16px; background:linear-gradient(135deg, #10b981, #059669); border-radius:50%; display:flex; align-items:center; justify-content:center; }
      .success-icon svg { width:30px; height:30px; }
      
      .confirm-icon { width:60px; height:60px; margin:0 auto 16px; background:linear-gradient(135deg, var(--accent), #ffe066); border-radius:50%; display:flex; align-items:center; justify-content:center; }
      .confirm-icon svg { width:30px; height:30px; }
      
      /* Loading Animation */
      .loading-spinner { width:40px; height:40px; margin:0 auto 16px; }
      .spinner { width:40px; height:40px; border:3px solid rgba(255,204,0,0.3); border-top:3px solid var(--accent); border-radius:50%; animation:spin 1s linear infinite; }
      @keyframes spin { 0% { transform:rotate(0deg); } 100% { transform:rotate(360deg); } }
      
      .loading-text { text-align:center; color:var(--text); font-size:16px; margin-bottom:8px; }
      .loading-subtext { text-align:center; color:var(--muted); font-size:14px; }
    </style>
  </head>
  <body>
    <canvas id="stars"></canvas>
    <div class="container">
      <div class="header">
        <a href="#" class="back-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke="#ffdd33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <div class="header-title">Para Gönder</div>
      </div>

      <div class="card balance-section">
        <div class="balance-label">Güncel Bakiye</div>
        <div class="balance-amount" id="balanceAmount">₺ {{ number_format($wallet->balance, 2, ',', '.') }}</div>
      </div>

      <div class="card">
        <div class="section-title">Arkadaşlarını Seç</div>
        <div class="friends-grid" id="friendsGrid">
          @forelse($friends as $friend)
            <div class="friend-card" data-friend-id="{{ $friend->id }}">
              <div class="friend-avatar">{{ strtoupper(substr($friend->name, 0, 2)) }}</div>
              <div class="friend-name">{{ $friend->name }} {{ $friend->surname }}</div>
              <div class="friend-status">Çevrimiçi</div>
            </div>
          @empty
            <div style="grid-column: 1 / -1; text-align: center; color: var(--muted); padding: 20px;">
              Henüz arkadaşınız yok
            </div>
          @endforelse
        </div>
      </div>

      <div class="card">
        <div class="section-title">Seçilen Arkadaşlar</div>
        <div class="selected-friends">
          <div class="selected-list" id="selectedList">
            <div style="color:var(--muted); font-size:14px;">Henüz arkadaş seçilmedi</div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="section-title">Gönderilecek Tutar</div>
        <div class="amount-section">
          <label class="amount-label" for="amountInput">Tutar (₺)</label>
          <input type="number" id="amountInput" class="amount-input" placeholder="0,00" step="0.01" min="0.01">
        </div>
        
        <div class="summary" id="summarySection" style="display:none;">
          <div class="summary-row">
            <span>Seçilen Arkadaş Sayısı:</span>
            <span id="friendCount">0</span>
          </div>
          <div class="summary-row">
            <span>Kişi Başı Tutar:</span>
            <span id="perPersonAmount">₺ 0,00</span>
          </div>
          <div class="summary-row">
            <span>Toplam Tutar:</span>
            <span id="totalAmount">₺ 0,00</span>
          </div>
        </div>
        
        <button class="send-btn" id="sendBtn" disabled>Para Gönder</button>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Para Gönderme Onayı</h3>
        </div>
        <div class="modal-body">
          <div class="confirm-icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke="#001" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="modal-text" style="text-align:center;">
            Aşağıdaki arkadaşlarınıza para göndermek istediğinizden emin misiniz?
          </div>
          <div class="modal-friends" id="confirmFriendsList"></div>
          <div class="modal-amount" id="confirmAmount"></div>
        </div>
        <div class="modal-footer">
          <button class="modal-btn modal-btn-secondary" id="cancelSendBtn">İptal</button>
          <button class="modal-btn modal-btn-primary" id="confirmSendBtn">Gönder</button>
        </div>
      </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal" id="loadingModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Para Gönderiliyor</h3>
        </div>
        <div class="modal-body">
          <div class="loading-spinner">
            <div class="spinner"></div>
          </div>
          <div class="loading-text">İşlem gerçekleştiriliyor...</div>
          <div class="loading-subtext">Lütfen bekleyin</div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div class="modal" id="successModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">İşlem Başarılı!</h3>
        </div>
        <div class="modal-body">
          <div class="success-icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="modal-text" style="text-align:center;">
            Para transferi başarıyla gerçekleştirildi!
          </div>
          <div class="modal-amount" id="successAmount"></div>
          <div class="modal-text" style="text-align:center; font-size:14px; color:var(--muted);">
            Yeni bakiyeniz: <span id="newBalance"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button class="modal-btn modal-btn-primary" id="closeSuccessBtn" style="margin:0 auto;">Tamam</button>
        </div>
      </div>
    </div>

    <script>
      // Starfield animation (same as main page)
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

      // Real friends data from backend
      const friends = @json($friends);
      let selectedFriends = [];
      let currentBalance = {{ $wallet->balance }}; // Gerçek bakiye
      const friendsGrid = document.getElementById('friendsGrid');
      const selectedList = document.getElementById('selectedList');
      const amountInput = document.getElementById('amountInput');
      const sendBtn = document.getElementById('sendBtn');
      const summarySection = document.getElementById('summarySection');
      const friendCount = document.getElementById('friendCount');
      const perPersonAmount = document.getElementById('perPersonAmount');
      const totalAmount = document.getElementById('totalAmount');
      const balanceAmount = document.getElementById('balanceAmount');
      
      // Modal elements
      const confirmModal = document.getElementById('confirmModal');
      const loadingModal = document.getElementById('loadingModal');
      const successModal = document.getElementById('successModal');
      const confirmFriendsList = document.getElementById('confirmFriendsList');
      const confirmAmount = document.getElementById('confirmAmount');
      const successAmount = document.getElementById('successAmount');
      const newBalance = document.getElementById('newBalance');
      const cancelSendBtn = document.getElementById('cancelSendBtn');
      const confirmSendBtn = document.getElementById('confirmSendBtn');
      const closeSuccessBtn = document.getElementById('closeSuccessBtn');

      // Modal functions
      function showConfirmModal(amount, count, totalAmount, friendNames) {
        // Update confirm modal content
        confirmFriendsList.innerHTML = selectedFriends.map(friend => `
          <div class="modal-friend-item">
            <div class="modal-friend-avatar">${friend.name.charAt(0).toUpperCase()}${friend.surname.charAt(0).toUpperCase()}</div>
            <div class="modal-friend-name">${friend.name} ${friend.surname}</div>
          </div>
        `).join('');
        
        confirmAmount.innerHTML = `
          <div>Kişi başı: ₺ ${amount.toFixed(2).replace('.', ',')}</div>
          <div>Toplam: ₺ ${totalAmount.toFixed(2).replace('.', ',')}</div>
        `;
        
        confirmModal.classList.add('show');
      }
      
      function hideConfirmModal() {
        confirmModal.classList.remove('show');
      }
      
      function showSuccessModal(totalAmount, newBalanceAmount) {
        successAmount.textContent = `₺ ${totalAmount.toFixed(2).replace('.', ',')}`;
        newBalance.textContent = `₺ ${newBalanceAmount.toFixed(2).replace('.', ',')}`;
        successModal.classList.add('show');
      }
      
      function hideSuccessModal() {
        successModal.classList.remove('show');
      }
      
      function showLoadingModal() {
        loadingModal.classList.add('show');
      }
      
      function hideLoadingModal() {
        loadingModal.classList.remove('show');
      }

      function toggleFriendSelection(friend) {
        const index = selectedFriends.findIndex(f => f.id === friend.id);
        if (index > -1) {
          selectedFriends.splice(index, 1);
        } else {
          selectedFriends.push(friend);
        }
        updateUI();
      }

      function updateSelectedList() {
        if (selectedFriends.length === 0) {
          selectedList.innerHTML = '<div style="color:var(--muted); font-size:14px;">Henüz arkadaş seçilmedi</div>';
        } else {
          selectedList.innerHTML = selectedFriends.map(friend => `
            <div class="selected-friend">
              <span>${friend.name} ${friend.surname}</span>
              <div class="remove-friend" onclick="removeFriend(${friend.id})">×</div>
            </div>
          `).join('');
        }
      }

      function removeFriend(friendId) {
        selectedFriends = selectedFriends.filter(f => f.id !== friendId);
        updateUI();
      }

      function updateSummary() {
        const amount = parseFloat(amountInput.value) || 0;
        const count = selectedFriends.length;
        
        if (count > 0 && amount > 0) {
          const perPerson = amount; // Her kişiye aynı tutar
          const total = amount * count; // Toplam gönderilecek tutar
          
          friendCount.textContent = count;
          perPersonAmount.textContent = `₺ ${perPerson.toFixed(2).replace('.', ',')}`;
          totalAmount.textContent = `₺ ${total.toFixed(2).replace('.', ',')}`;
          summarySection.style.display = 'block';
        } else {
          summarySection.style.display = 'none';
        }
      }

      function updateSendButton() {
        const amount = parseFloat(amountInput.value) || 0;
        const count = selectedFriends.length;
        const totalAmount = amount * count; // Toplam gönderilecek tutar
        const canSend = count > 0 && amount > 0 && totalAmount <= currentBalance;
        sendBtn.disabled = !canSend;
      }

      function updateBalance() {
        balanceAmount.textContent = `₺ ${currentBalance.toFixed(2).replace('.', ',')}`;
      }

      function updateUI() {
        // Update friend cards visual state
        document.querySelectorAll('.friend-card').forEach(card => {
          const friendId = parseInt(card.getAttribute('data-friend-id'));
          if (selectedFriends.find(f => f.id === friendId)) {
            card.classList.add('selected');
          } else {
            card.classList.remove('selected');
          }
        });
        
        updateSelectedList();
        updateSummary();
        updateSendButton();
      }


      // Event listeners
      amountInput.addEventListener('input', () => {
        updateSummary();
        updateSendButton();
      });

      sendBtn.addEventListener('click', () => {
        const amount = parseFloat(amountInput.value);
        const count = selectedFriends.length;
        const totalAmount = amount * count;
        const friendNames = selectedFriends.map(f => `${f.name} ${f.surname}`).join(', ');
        
        showConfirmModal(amount, count, totalAmount, friendNames);
      });

      // Modal event listeners
      cancelSendBtn.addEventListener('click', () => {
        hideConfirmModal();
      });

      confirmSendBtn.addEventListener('click', () => {
        const amount = parseFloat(amountInput.value);
        const count = selectedFriends.length;
        const totalAmount = amount * count;
        
        // Hide confirm modal and show loading modal
        hideConfirmModal();
        showLoadingModal();
        
        // Simulate processing for 1 second
        setTimeout(() => {
          // Bakiye güncelle
          currentBalance -= totalAmount;
          updateBalance();
          
          // Hide loading modal and show success modal
          hideLoadingModal();
          showSuccessModal(totalAmount, currentBalance);
          
          // Reset form
          selectedFriends = [];
          amountInput.value = '';
          updateUI();
        }, 1000);
      });

      closeSuccessBtn.addEventListener('click', () => {
        hideSuccessModal();
      });

      // Close modals when clicking outside
      confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) {
          hideConfirmModal();
        }
      });

      // Loading modal should not close when clicking outside
      loadingModal.addEventListener('click', (e) => {
        if (e.target === loadingModal) {
          // Do nothing - prevent closing during loading
        }
      });

      successModal.addEventListener('click', (e) => {
        if (e.target === successModal) {
          hideSuccessModal();
        }
      });

      // Add click listeners to friend cards
      document.querySelectorAll('.friend-card').forEach(card => {
        const friendId = parseInt(card.getAttribute('data-friend-id'));
        const friend = friends.find(f => f.id === friendId);
        
        card.addEventListener('click', () => {
          toggleFriendSelection(friend);
        });
      });

      // Initialize
      updateUI();
      updateBalance();
    </script>
  </body>
</html>
