@extends('layouts.guest')

@section('title', 'Reservasi - Pemesanan Menu')


@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #F2E7D5;
    --panel: #F8F4E1;
    --green: #7a9a86;
    --dark-green: #6b8872;
    --gray-start: #6f787a;
    --gray-end: #434a4c;
    --outline: #93A68F;
    --text: #7a9a86;
  }

  * {
    box-sizing: border-box;
  }

  body {
    font-family: "Inter", sans-serif;
    background: var(--bg);
    color: #274035;
    margin: 0;
    -webkit-font-smoothing: antialiased;
  }

  .nav {
    background: var(--bg);
    padding: 12px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(147, 166, 143, .2);
  }

  .nav .logo {
    font-weight: 700;
    font-size: 18px;
    letter-spacing: .5px;
    color: var(--text);
  }

  .nav-links {
    display: flex;
    gap: 32px;
    align-items: center;
  }

  .nav-links a {
    text-decoration: none;
    color: var(--text);
    font-weight: 500;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: .5px;
  }

  .wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
  }

  .title-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
  }

  .hero-title {
    font-weight: 400;
    font-size: 42px;
    color: var(--text);
    letter-spacing: 1px;
  }

  .legend {
    display: flex;
    flex-direction: column;
    gap: 12px;
    font-weight: 500;
    color: var(--text);
  }

  .legend .item {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .legend .sw {
    width: 24px;
    height: 24px;
    border-radius: 6px;
  }

  .denah-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 320px 320px 320px;
    grid-template-areas: "indoor1 right" "kasir indoor2" "out1 out2";
    gap: 0;
    border: 3px solid var(--outline);
  }

  .box {
    background: var(--panel);
    border: 3px solid var(--outline);
    padding: 24px;
    position: relative;
    margin: -1.5px;
  }

  .indoor1 {
    grid-area: indoor1;
  }

  .right {
    grid-area: right;
    display: flex;
    flex-direction: column;
  }

  .kasir {
    grid-area: kasir;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding-left: 60px;
  }

  .indoor2 {
    grid-area: indoor2;
  }

  .out1 {
    grid-area: out1;
  }

  .out2 {
    grid-area: out2;
  }

  .area-title {
    color: var(--text);
    font-weight: 400;
    font-size: 16px;
    margin-bottom: 16px;
    letter-spacing: .5px;
  }

  .table-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: calc(100% - 40px);
  }

  .table-grid {
    display: grid;
    grid-template-columns: repeat(2, 52px);
    grid-template-rows: repeat(3, 52px);
    gap: 16px 24px;
  }

  .table-btn {
    width: 52px;
    height: 52px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
    transition: transform .2s;
  }

  .table-btn:hover {
    transform: translateY(-2px);
  }

  .table-available {
    background: var(--green);
  }

  .table-unavailable {
    background: linear-gradient(180deg, var(--gray-start), var(--gray-end));
    cursor: not-allowed;
  }

  .toilet-box {
    flex: 1;
    display: flex;
    justify-content: flex-end;
    align-items: flex-start;
    padding: 24px;
  }

  .toilet-inner {
    border: 3px solid var(--outline);
    padding: 16px;
    border-radius: 6px;
    background: var(--panel);
  }

  .toilet-title {
    color: var(--text);
    font-size: 16px;
    margin-bottom: 12px;
    letter-spacing: .5px;
  }

  .toilet-icon {
    width: 52px;
    height: 52px;
    background: var(--green);
    border-radius: 8px;
  }

  .kasir-box {
    width: 150px;
    height: 150px;
    border: 3px solid var(--outline);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background: var(--panel);
  }

  .kasir-icon {
    width: 52px;
    height: 52px;
    background: var(--green);
    border-radius: 8px;
    margin-bottom: 8px;
  }

  footer {
    margin-top: 60px;
    background: var(--dark-green);
    color: #eaf0e9;
    padding: 32px 40px;
  }

  .footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .footer-logo {
    font-weight: 700;
    font-size: 18px;
    letter-spacing: 1px;
    margin-bottom: 12px;
  }

  .footer-links {
    font-size: 13px;
    opacity: .95;
    display: flex;
    gap: 24px;
  }

  .modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 60;
  }

  .modal {
    background: var(--green);
    color: white;
    border-radius: 10px;
    padding: 28px;
    width: 680px;
    max-width: 92%;
    position: relative;
    box-shadow: 0 8px 30px rgba(0, 0, 0, .25);
  }

  .modal .close {
    position: absolute;
    top: 12px;
    right: 14px;
    background: transparent;
    border: none;
    color: white;
    font-size: 22px;
    cursor: pointer;
  }

  .modal .field {
    margin-bottom: 12px;
  }

  .modal input,
  .modal select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: none;
    color: #274035;
  }

  .confirm-modal {
    width: 420px;
    text-align: center;
  }

  #toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #274035;
    color: #fff;
    padding: 12px 18px;
    border-radius: 8px;
    display: none;
    z-index: 90;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
  }

  @media (max-width:980px) {
    .denah-grid {
      grid-template-columns: 1fr;
      grid-template-rows: auto;
      grid-template-areas: "indoor1" "right" "kasir" "indoor2" "out1" "out2";
    }

    .hero-title {
      font-size: 32px;
    }

    .nav {
      padding: 12px 20px;
    }
  }
</style>

@section('content')
  <div>

    <body>
      <div class="wrap">
        <div class="title-section">
          <h1 class="hero-title">Denah Meja</h1>
          <div class="legend">
            <div class="item">
              <div class="sw" style="background:var(--green)"></div>
              <div>Available</div>
            </div>
            <div class="item">
              <div class="sw" style="background:linear-gradient(180deg,var(--gray-start),var(--gray-end))"></div>
              <div>Not Available</div>
            </div>
          </div>
        </div>

        <div class="denah-grid">
          <div class="box indoor1">
            <div class="area-title">Ruangan Indoor 1</div>
            <div class="table-container">
              <div class="table-grid" id="indoor1-grid"></div>
            </div>
          </div>

          <div class="right">
            <div class="box" style="flex:1;border-bottom:none;">
              <div class="toilet-box">
                <div class="toilet-inner">
                  <div class="toilet-title">Toilet</div>
                  <div class="toilet-icon"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="box kasir">
            <div class="kasir-box">
              <div class="kasir-icon"></div>
              <div style="color:var(--text);font-size:16px;letter-spacing:.5px;">Kasir</div>
            </div>
          </div>

          <div class="box indoor2">
            <div class="area-title">Ruangan Indoor 2</div>
            <div class="table-container">
              <div class="table-grid" id="indoor2-grid"></div>
            </div>
          </div>

          <div class="box out1">
            <div class="area-title">Outdoor 1</div>
            <div class="table-container">
              <div class="table-grid" id="out1-grid"></div>
            </div>
          </div>

          <div class="box out2">
            <div class="area-title">Outdoor 2</div>
            <div class="table-container">
              <div class="table-grid" id="out2-grid"></div>
            </div>
          </div>
        </div>
      </div>


      <div id="modalReserve" class="modal-backdrop">
        <div class="modal">
          <button class="close" id="closeReserve">&times;</button>
          <h2 style="font-size:22px;font-weight:600;margin-bottom:12px;">Reservasi Meja</h2>
          <div style="background:rgba(255,255,255,.12);padding:14px;border-radius:8px;margin-bottom:12px;">
            <div style="font-size:14px;opacity:.95;">Meja: <span id="modalTableLabel"></span></div>
            <div style="font-size:12px;opacity:.85;">Kapasitas: <span id="modalTableCap">4</span> Orang</div>
          </div>
          <div class="field">
            <label style="color:rgba(255,255,255,.92);font-weight:600;display:block;margin-bottom:8px;">Masukan Jumlah
              Orang</label>
            <select id="peopleSelect">
              <option value="1">1 Orang</option>
              <option value="2">2 Orang</option>
              <option value="3">3 Orang</option>
              <option value="4" selected>4 Orang</option>
            </select>
          </div>
          <div class="field">
            <label style="color:rgba(255,255,255,.92);font-weight:600;display:block;margin-bottom:8px;">Pilih Waktu
              Kedatangan</label>
            <div style="display:flex;gap:2%;"><input id="dateInput" type="date" style="flex:1;" /><input id="timeInput"
                type="time" style="flex:1;" /></div>
          </div>
          <p style="color:#ffd8d8;font-size:12px;margin-top:6px;">*Waktu kedatangan di luar jam operasional akan tertolak
            (simulasi).</p>
          <div style="margin-top:18px;text-align:center;">
            <button id="toConfirmBtn"
              style="background:#F8F4E1;color:#35563a;padding:10px 24px;border-radius:8px;font-weight:700;border:none;cursor:pointer;">Lanjut
              ke Konfirmasi</button>
          </div>
        </div>
      </div>

      <div id="modalConfirm" class="modal-backdrop">
        <div class="modal confirm-modal">
          <button class="close" id="closeConfirm">&times;</button>
          <h3 style="font-size:20px;margin-bottom:10px;font-weight:600;">Kapasitas Meja</h3>
          <div id="confirmPeople" style="font-size:38px;font-weight:700;margin-bottom:18px;">4 Orang</div>
          <button id="finalReserve"
            style="background:#F8F4E1;color:#35563a;padding:10px 24px;border-radius:8px;font-weight:700;border:none;cursor:pointer;">Reservasi
            Sekarang</button>
        </div>
      </div>

      <div id="toast">Reservasi tersimpan (dummy)</div>

      <script>
        const availability = { indoor1: [1, 1, 0, 1, 1, 1], indoor2: [1, 0, 1, 1, 1, 1], out1: [1, 1, 1, 1, 1, 1], out2: [1, 1, 0, 0, 1, 0] };

        function makeTable(area, idx) {
          const btn = document.createElement('button');
          btn.className = 'table-btn ' + (availability[area][idx] ? 'table-available' : 'table-unavailable');
          btn.dataset.area = area; btn.dataset.idx = idx + 1; btn.dataset.cap = 4; btn.dataset.avail = availability[area][idx];
          btn.onclick = () => {
            if (btn.dataset.avail == '1') openReserveModal(btn);
            else btn.animate([{ transform: 'translateY(0)' }, { transform: 'translateY(-6px)' }, { transform: 'translateY(0)' }], { duration: 260 });
          };
          return btn;
        }

        ['indoor1', 'indoor2', 'out1', 'out2'].forEach(area => {
          const grid = document.getElementById(area + '-grid');
          for (let i = 0; i < 6; i++)grid.appendChild(makeTable(area, i));
        });

        const modalReserve = document.getElementById('modalReserve');
        const modalConfirm = document.getElementById('modalConfirm');
        const toast = document.getElementById('toast');

        function openReserveModal(btn) {
          document.getElementById('modalTableLabel').innerText = btn.dataset.area.replace(/([a-z])([0-9])/, '$1 $2').toUpperCase() + ' - Meja ' + btn.dataset.idx;
          document.getElementById('modalTableCap').innerText = btn.dataset.cap;
          document.getElementById('peopleSelect').value = btn.dataset.cap;
          document.getElementById('dateInput').value = new Date().toISOString().slice(0, 10);
          document.getElementById('timeInput').value = "12:00";
          modalReserve.style.display = 'flex';
          document.body.style.overflow = 'hidden';
        }

        document.getElementById('closeReserve').onclick = () => { modalReserve.style.display = 'none'; document.body.style.overflow = ''; };
        document.getElementById('toConfirmBtn').onclick = () => {
          document.getElementById('confirmPeople').innerText = document.getElementById('peopleSelect').value + ' Orang';
          modalReserve.style.display = 'none';
          modalConfirm.style.display = 'flex';
        };
        document.getElementById('closeConfirm').onclick = () => { modalConfirm.style.display = 'none'; document.body.style.overflow = ''; };
        document.getElementById('finalReserve').onclick = () => {
          modalConfirm.style.display = 'none';
          document.body.style.overflow = '';
          toast.style.display = 'block';
          setTimeout(() => toast.style.display = 'none', 2200);
        };

        document.querySelectorAll('.modal-backdrop').forEach(b => b.onclick = e => { if (e.target === b) { b.style.display = 'none'; document.body.style.overflow = ''; } });
      </script>

    </body>
  </div>
@endsection