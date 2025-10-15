<!DOCTYPE html>
<html lang="id" data-theme="cupcake">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Menu - Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg: #efe6d7;         /* Light cream/beige for body background */
            --panel: #c3d7c3;      /* Light green for card background */
            --panel-2: #e8f1e8;    /* Even lighter green/off-white for image background */
            --text: #2b2b2b;       /* Dark text for readability */
            --muted: #666;         /* Muted gray for subtitles */
            --white: #fff;
            --border: #e5e7eb;
            --shadow: 0 6px 18px rgba(0,0,0,.08);
        }
        body {
            background-color: var(--bg);
            color: var(--text);
        }
        header {
            position: sticky; top: 0; z-index: 20;
            background: rgba(239, 230, 215, .85); /* Slightly transparent header background */
            backdrop-filter: saturate(180%) blur(10px);
            border-bottom: 1px solid var(--border);
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .title { font-size: 32px; margin: 10px 0 4px 0; letter-spacing: .5px; }
        .subtitle { color: var(--muted); margin: 0 0 18px 0; }

        .layout { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
        @media (max-width: 980px) { .layout { grid-template-columns: 1fr; } }

        .section-title { font-size: 22px; margin: 24px 0 12px; }
        .menu-grid { display: grid; grid-template-columns: repeat(6, minmax(0,1fr)); gap: 14px; }
        @media (max-width: 1280px) { .menu-grid { grid-template-columns: repeat(5, 1fr); } }
        @media (max-width: 1024px) { .menu-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 860px)  { .menu-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 640px)  { .menu-grid { grid-template-columns: repeat(2, 1fr); } }

        /* Custom styles for consistency with DaisyUI */
        .card.custom-card {
            background-color: var(--panel); /* Use panel color for cards */
            color: var(--text);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card.custom-card .card-body {
            padding: 1rem;
        }
        .add-btn { margin-top: auto; }
        
        .card .name { font-weight: 700; line-height: 1.2; min-height: 2.4em; }
        .card .price { color: #143814; font-weight: 600; } /* Darker green for price */

        /* Adjust button and input styles for the quantity selector */
        .join .btn {
            background-color: #365e37; /* Darker green for quantity buttons */
            color: var(--white);
            border-color: #365e37;
        }
        .join .btn:hover {
            background-color: #2f855a; /* Slightly lighter on hover */
            border-color: #2f855a;
        }
        .join .input-bordered {
            border-color: #9bb99b; /* Muted green border for input */
            background-color: #f1f6f1; /* Light background for input */
            color: var(--text);
        }
        .add-btn {
            background-color: #365e37; /* Green for "Tambah" button */
            border: none;
            color: var(--white);
        }
        .add-btn.added {
            background-color: #2f855a; /* Slightly darker green when added */
        }
        .add-btn:hover {
            background-color: #2f855a;
        }

        /* Category chips */
        nav.sticky .btn-outline {
            color: #0b3a1b; /* Darker green text for chips */
            background: #e8f1e8; /* Light background for chips */
            border-color: #cfe2cf; /* Muted green border for chips */
        }
        nav.sticky .btn-outline:hover {
            background: #d4ead4;
        }

        /* Adjusting colors for the aside (ringkasan pesanan) */
        .btn-primary {
            background-color: #e57373; /* Example orange/red color */
            border-color: #e57373;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #ef5350;
            border-color: #ef5350;
        }
        .btn-outline {
            color: #757575;
            border-color: #757575;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="flex items-center justify-between">
                <div>
                    <div class="title">Daftar Menu</div>
                    <div class="subtitle">Pilih menu favorit Anda, atur jumlah, lalu lanjutkan pemesanan.</div>
                </div>
                <div>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-sm btn-ghost text-gray-700">&larr; Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <nav class="sticky top-[64px] z-10 py-2 bg-transparent">
            <div class="flex gap-2 flex-wrap" id="chipNav"></div>
        </nav>
        <div class="layout">
            <main id="menuSections"></main>
            <aside class="sticky top-20">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body p-4">
                        <h2 class="card-title">Ringkasan Pesanan</h2>
                        <div class="text-sm text-gray-500 mb-4">Item yang Anda tambahkan akan muncul di sini</div>
                        <div class="items" id="cartItems"></div>
                        <div class="divider"></div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span id="cartTotal">Rp 0</span>
                        </div>
                        <div class="card-actions justify-end mt-4">
                            <button class="btn btn-sm btn-outline" id="clearCart">Bersihkan</button>
                            <button class="btn btn-sm btn-primary" id="checkoutBtn">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <template id="cardTpl">
        <div class="card custom-card">
            <figure class="aspect-square bg-[#e8f1e8]"> 
                <img class="w-2/3 h-2/3 object-cover drop-shadow-lg" alt="Menu" />
            </figure>
            <div class="card-body p-2 flex flex-col">
                <h3 class="font-bold text-lg leading-tight min-h-[2.4em] line-clamp-2 name"></h3>
                <p class="font-semibold text-white price"></p> 
                <div class="join w-full my-2">
                    <button class="btn join-item btn-sm w-8 rounded-full dec">-</button>
                    <input type="number" min="0" step="1" value="0" class="input input-bordered w-full text-center join-item input-sm qty-input" />
                    <button class="btn join-item btn-sm w-8 rounded-full inc">+</button>
                </div>
                <div class="card-actions justify-end mt-2">
                    <button class="btn btn-sm add-btn text-white w-full">Tambah</button>
                </div>
            </div>
        </div>
    </template>

    <script>
        const CATEGORIES = [
            { id: 'snacks', name: 'Snacks', items: [{ id: 'snk-1', name: 'Burger Telur', price: 18000, img: 'https://images.unsplash.com/photo-1559962933-34eb34bc9fd1?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }, { id: 'snk-2', name: 'Kentang Goreng', price: 15000, img: 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?q=80&w=800&auto=format&fit=crop' }, { id: 'snk-3', name: 'Risoles Mayo', price: 12000, img: 'https://images.unsplash.com/photo-1629570583328-998f0ac031f5?q=80&w=800&auto=format&fit=crop' }, { id: 'snk-4', name: 'Sosis Bakar', price: 14000, img: 'https://images.unsplash.com/photo-1629570583328-998f0ac031f5?q=80&w=800&auto=format&fit=crop' }] },
            { id: 'heavy', name: 'Heavy Meal', items: [{ id: 'hvy-1', name: 'Nasi Ayam Teriyaki', price: 28000, img: 'https://plus.unsplash.com/premium_photo-1695167739750-a1e7c856438b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Y2hpY2tlbiUyMHRlcml5YWtpfGVufDB8fDB8fHww' }, { id: 'hvy-2', name: 'Beef Burger', price: 32000, img: 'https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=800&auto=format&fit=crop' }, { id: 'hvy-3', name: 'Pasta Carbonara', price: 30000, img: 'https://images.unsplash.com/photo-1651585594107-859f80b4ca3a?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FyYm9uYXJhJTIwcGFzdGF8ZW58MHx8MHx8fDA%3D' }, { id: 'hvy-4', name: 'Chicken Katsu', price: 29000, img: 'https://images.unsplash.com/photo-1576402187878-974f70c890a5?q=80&w=800&auto=format&fit=crop' }] },
            { id: 'traditional', name: 'Traditional', items: [{ id: 'trd-1', name: 'Soto Ayam', price: 22000, img: 'https://images.unsplash.com/photo-1589307004173-3c95204c045b?q=80&w=800&auto=format&fit=crop' }, { id: 'trd-2', name: 'Nasi Uduk', price: 18000, img: 'https://images.unsplash.com/photo-1564198941238-3e71a8650f66?q=80&w=800&auto=format&fit=crop' }, { id: 'trd-3', name: 'Gado-Gado', price: 17000, img: 'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?q=80&w=800&auto=format&fit=crop' }] },
            { id: 'juice', name: 'Juice', items: [{ id: 'juc-1', name: 'Jus Alpukat', price: 16000, img: 'https://images.unsplash.com/photo-1553530979-7ee52fd2bb84?q=80&w=800&auto=format&fit=crop' }, { id: 'juc-2', name: 'Jus Mangga', price: 15000, img: 'https://images.unsplash.com/photo-1615485290382-441e4d4a9e4e?q=80&w=800&auto=format&fit=crop' }, { id: 'juc-3', name: 'Jus Jambu', price: 15000, img: 'https://images.unsplash.com/photo-1584645285349-9d8f12b38009?q=80&w=800&auto=format&fit=crop' }] },
            { id: 'fresh', name: 'Fresh Drink', items: [{ id: 'frs-1', name: 'Es Teh Manis', price: 8000, img: 'https://images.unsplash.com/photo-1517705008128-361805f42e86?q=80&w=800&auto=format&fit=crop' }, { id: 'frs-2', name: 'Es Jeruk', price: 9000, img: 'https://images.unsplash.com/photo-1542442828-287219c8544e?q=80&w=800&auto=format&fit=crop' }] },
            { id: 'special', name: 'Special Taste', items: [{ id: 'spc-1', name: 'Sushi Roll', price: 35000, img: 'https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?q=80&w=800&auto=format&fit=crop' }, { id: 'spc-2', name: 'Steak Mini', price: 42000, img: 'https://images.unsplash.com/photo-1553163147-622ab57be1c7?q=80&w=800&auto=format&fit=crop' }] },
            { id: 'coffee', name: 'Coffee', items: [{ id: 'cof-1', name: 'Americano', price: 18000, img: 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?q=80&w=800&auto=format&fit=crop' }, { id: 'cof-2', name: 'Cappuccino', price: 20000, img: 'https://images.unsplash.com/photo-1503481766315-7a586b20f66b?q=80&w=800&auto=format&fit=crop' }, { id: 'cof-3', name: 'Latte', price: 20000, img: 'https://images.unsplash.com/photo-1527073621771-153fd6f33e1e?q=80&w=800&auto=format&fit=crop' }] },
            { id: 'icecream', name: 'Ice Cream', items: [{ id: 'ice-1', name: 'Vanilla Cup', price: 12000, img: 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?q=80&w=800&auto=format&fit=crop' }, { id: 'ice-2', name: 'Chocolate Cone', price: 13000, img: 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?q=80&w=800&auto=format&fit=crop' }] },
        ];

        const rupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n || 0);

        const state = { cart: new Map() };

        function renderCategories() {
            const sections = document.getElementById('menuSections');
            const chipNav = document.getElementById('chipNav');
            sections.innerHTML = '';
            chipNav.innerHTML = '';

            CATEGORIES.forEach(cat => {
                const chip = document.createElement('a');
                chip.href = `#${cat.id}`;
                chip.textContent = cat.name;
                chip.className = 'btn btn-sm btn-outline'; // DaisyUI button class for chips
                chipNav.appendChild(chip);

                const wrap = document.createElement('section');
                wrap.id = cat.id;
                const h = document.createElement('h2');
                h.className = 'section-title';
                h.textContent = cat.name;
                const grid = document.createElement('div');
                grid.className = 'menu-grid';

                cat.items.forEach(item => grid.appendChild(createCard(item)));

                wrap.appendChild(h);
                wrap.appendChild(grid);
                sections.appendChild(wrap);
            });
        }

        function createCard(item) {
            const tpl = document.getElementById('cardTpl');
            const node = tpl.content.cloneNode(true);
            const card = node.querySelector('.card');
            card.dataset.id = item.id;
            card.querySelector('img').src = item.img;
            card.querySelector('img').alt = item.name;
            node.querySelector('.name').textContent = item.name;
            node.querySelector('.price').textContent = rupiah(item.price);

            const qtyInput = card.querySelector('.qty-input');
            const inc = card.querySelector('.inc');
            const dec = card.querySelector('.dec');
            const addBtn = card.querySelector('.add-btn');

            inc.addEventListener('click', () => { qtyInput.value = (+qtyInput.value || 0) + 1; });
            dec.addEventListener('click', () => { qtyInput.value = Math.max(0, (+qtyInput.value || 0) - 1); });

            addBtn.addEventListener('click', () => {
                const qty = Math.max(0, parseInt(qtyInput.value || '0', 10));
                if (!qty) { addBtn.classList.remove('added'); updateCart(); return; }
                state.cart.set(item.id, { ...item, qty });
                addBtn.classList.add('added');
                updateCart();
            });

            return node;
        }

        function updateCart() {
            const list = document.getElementById('cartItems');
            list.innerHTML = '';
            let total = 0;

            for (const [id, it] of state.cart) {
                if (!it.qty) { state.cart.delete(id); continue; }
                const row = document.createElement('div');
                row.className = 'flex justify-between items-center py-2';

                const meta = document.createElement('div');
                const name = document.createElement('div');
                name.className = 'font-semibold';
                name.textContent = it.name;
                const mut = document.createElement('div');
                mut.className = 'text-gray-500 text-sm';
                mut.textContent = `${it.qty} x ${rupiah(it.price)}`;
                meta.appendChild(name); meta.appendChild(mut);

                const right = document.createElement('div');
                right.className = 'text-right';
                right.innerHTML = `
                    <div class="font-bold">${rupiah(it.qty * it.price)}</div>
                    <button class="btn btn-xs btn-ghost text-error remove-item">Hapus</button>
                `;
                right.querySelector('.remove-item').addEventListener('click', () => { state.cart.delete(id); updateCart(); });

                row.appendChild(meta); row.appendChild(right);
                list.appendChild(row);

                total += it.qty * it.price;
            }

            document.getElementById('cartTotal').textContent = rupiah(total);
        }

        document.getElementById('clearCart').addEventListener('click', () => { state.cart.clear(); updateCart(); });
        document.getElementById('checkoutBtn').addEventListener('click', () => {
            if (!state.cart.size) { alert('Keranjang masih kosong. Tambahkan item terlebih dahulu.'); return; }
            const items = Array.from(state.cart.values()).map(i => ({ id: i.id, name: i.name, qty: i.qty, price: i.price }));
            alert('Pesanan siap diproses:\n' + items.map(i => `- ${i.name} (${i.qty} x ${rupiah(i.price)})`).join('\n'));
        });

        renderCategories();
        updateCart();
    </script>
</body>
</html>