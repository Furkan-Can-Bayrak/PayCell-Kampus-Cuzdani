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
        <div class="balance-amount" id="balanceAmount">₺ 2.450,75</div>
      </div>

      <div class="card">
        <div class="section-title">Arkadaşlarını Seç</div>
        <div class="friends-grid" id="friendsGrid">
          <!-- Friends will be generated by JavaScript -->
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

      // Fake friends data
      const friends = [
        { id: 1, name: 'Ayşe Yılmaz', avatar: 'AY', status: 'Çevrimiçi' },
        { id: 2, name: 'Mehmet Demir', avatar: 'MD', status: 'Çevrimiçi' },
        { id: 3, name: 'Zeynep Kaya', avatar: 'ZK', status: 'Son görülme: 5 dk önce' },
        { id: 4, name: 'Can Yıldız', avatar: 'CY', status: 'Çevrimiçi' },
        { id: 5, name: 'Elif Özkan', avatar: 'EÖ', status: 'Son görülme: 1 saat önce' },
        { id: 6, name: 'Burak Şen', avatar: 'BŞ', status: 'Çevrimiçi' },
        { id: 7, name: 'Selin Ak', avatar: 'SA', status: 'Son görülme: 2 saat önce' },
        { id: 8, name: 'Emre Koç', avatar: 'EK', status: 'Çevrimiçi' },
      ];

      let selectedFriends = [];
      let currentBalance = 2450.75; // Güncel bakiye
      const friendsGrid = document.getElementById('friendsGrid');
      const selectedList = document.getElementById('selectedList');
      const amountInput = document.getElementById('amountInput');
      const sendBtn = document.getElementById('sendBtn');
      const summarySection = document.getElementById('summarySection');
      const friendCount = document.getElementById('friendCount');
      const perPersonAmount = document.getElementById('perPersonAmount');
      const totalAmount = document.getElementById('totalAmount');
      const balanceAmount = document.getElementById('balanceAmount');

      function createFriendCard(friend) {
        const el = document.createElement('div');
        el.className = 'friend-card';
        el.innerHTML = `
          <div class="friend-avatar">${friend.avatar}</div>
          <div class="friend-name">${friend.name}</div>
          <div class="friend-status">${friend.status}</div>
        `;
        
        el.addEventListener('click', () => {
          toggleFriendSelection(friend);
        });
        
        return el;
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
              <span>${friend.name}</span>
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
          const friendName = card.querySelector('.friend-name').textContent;
          const friend = friends.find(f => f.name === friendName);
          if (selectedFriends.find(f => f.id === friend.id)) {
            card.classList.add('selected');
          } else {
            card.classList.remove('selected');
          }
        });
        
        updateSelectedList();
        updateSummary();
        updateSendButton();
      }

      function renderFriends() {
        friendsGrid.innerHTML = '';
        friends.forEach(friend => {
          friendsGrid.appendChild(createFriendCard(friend));
        });
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
        const friendNames = selectedFriends.map(f => f.name).join(', ');
        
        if (confirm(`${friendNames} arkadaşlarına her birine ₺ ${amount.toFixed(2).replace('.', ',')} (toplam ₺ ${totalAmount.toFixed(2).replace('.', ',')}) göndermek istediğinizden emin misiniz?`)) {
          // Bakiye güncelle
          currentBalance -= totalAmount;
          updateBalance();
          
          alert('Para başarıyla gönderildi! (fake)');
          // Reset form
          selectedFriends = [];
          amountInput.value = '';
          updateUI();
        }
      });

      // Initialize
      renderFriends();
      updateUI();
      updateBalance();
    </script>
  </body>
</html>
