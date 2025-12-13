<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kalender Mitra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
</head>
<body>
    <div class="header fixed-top">
        <div class="left">
            <a href="{{ route('mitra.dashboard') }}" class="icon">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.88986 12.2951L1.60986 7.00008L6.88986 1.70508" stroke="#121927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
        <h3>Kalender</h3>
    </div>

    <div class="app-content style-3">
        <div class="tf-container">
            <div id="calendar" style="margin-top:16px;">
                <div style="padding:16px;border-radius:16px;background:var(--white);box-shadow:0 1px 6px rgba(0,0,0,.06);">
                    <div id="monthLabel" style="font-weight:700;font-size:20px;text-align:center;margin-bottom:12px;"></div>
                    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;align-items:center;text-align:center;margin-bottom:8px;opacity:.8;">
                        <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                    </div>
                    <div id="daysGrid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:8px;"></div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:12px;margin-top:12px;">
                        <button id="prevMonth" class="btn btn-outline-secondary btn-sm" aria-label="Sebelumnya">←</button>
                        <button id="nextMonth" class="btn btn-outline-secondary btn-sm" aria-label="Berikutnya">→</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('front.mitra.partials.menubar-footer', ['active' => 'calendar'])

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script>
        (function(){
            const monthLabel = document.getElementById('monthLabel');
            const daysGrid = document.getElementById('daysGrid');
            const prevBtn = document.getElementById('prevMonth');
            const nextBtn = document.getElementById('nextMonth');
            const dayUrlTemplate = '{{ route('mitra.calendar.day', ['date' => 'DATE_PLACEHOLDER']) }}';

            let current = new Date(); current.setDate(1);
            let monthData = {};

            const fmtMonth = (d) => d.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            const daysInMonth = (y, m) => new Date(y, m + 1, 0).getDate();
            const startDayIndex = (d) => { const idx = d.getDay(); return idx === 0 ? 6 : idx - 1; };
            const ym = (d) => d.toISOString().slice(0,7);
            const loadData = async () => {
                const url = '{{ route('mitra.calendar.data') }}' + '?month=' + ym(current);
                try {
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const json = await res.json();
                    monthData = {};
                    (json.data || []).forEach(row => { monthData[row.date] = row; });
                } catch (e) {
                    monthData = {};
                }
            };

            const render = () => {
                monthLabel.textContent = fmtMonth(current);
                daysGrid.innerHTML = '';
                const y = current.getFullYear(), m = current.getMonth();
                const total = daysInMonth(y, m);
                const startIdx = startDayIndex(current);
                const today = new Date();
                for (let i = 0; i < startIdx; i++) {
                    const spacer = document.createElement('div');
                    spacer.style.minHeight = '48px';
                    daysGrid.appendChild(spacer);
                }
                for (let d = 1; d <= total; d++) {
                    const dateStr = `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                    const dateObj = new Date(y, m, d);
                    const dow = dateObj.getDay();
                    const cell = document.createElement('div');
                    cell.style.minHeight = '56px';
                    cell.style.border = '1px solid rgba(0,0,0,.08)';
                    cell.style.borderRadius = '12px';
                    cell.style.display = 'flex';
                    cell.style.alignItems = 'center';
                    cell.style.justifyContent = 'center';
                    cell.style.background = 'var(--white)';
                    cell.style.position = 'relative';
                    if (dow === 0 || dow === 6) {
                        cell.style.background = 'rgba(0,0,0,.03)';
                    }
                    const isToday = today.getFullYear() === y && today.getMonth() === m && today.getDate() === d;
                    const label = document.createElement('div');
                    label.textContent = d;
                    label.style.width = '32px';
                    label.style.height = '32px';
                    label.style.display = 'flex';
                    label.style.alignItems = 'center';
                    label.style.justifyContent = 'center';
                    label.style.borderRadius = '50%';
                    if (isToday) {
                        label.style.background = 'var(--gold-card)';
                        label.style.color = '#000';
                        label.style.fontWeight = '700';
                    }
                    const info = monthData[dateStr];
                    if (info) {
                        cell.style.cursor = 'pointer';
                        const badge = document.createElement('div');
                        badge.textContent = info.count;
                        badge.style.position = 'absolute';
                        badge.style.right = '6px';
                        badge.style.bottom = '6px';
                        badge.style.minWidth = '20px';
                        badge.style.height = '20px';
                        badge.style.fontSize = '12px';
                        badge.style.borderRadius = '10px';
                        badge.style.display = 'flex';
                        badge.style.alignItems = 'center';
                        badge.style.justifyContent = 'center';
                        badge.style.background = 'var(--gold-card)';
                        badge.style.color = '#000';
                        cell.appendChild(badge);
                        cell.addEventListener('click', () => {
                            const url = dayUrlTemplate.replace('DATE_PLACEHOLDER', dateStr);
                            window.location.href = url;
                        });
                    }
                    cell.appendChild(label);
                    daysGrid.appendChild(cell);
                }
            };

            const rerender = async () => { await loadData(); render(); };

            prevBtn.addEventListener('click', async () => { current.setMonth(current.getMonth() - 1); await rerender(); });
            nextBtn.addEventListener('click', async () => { current.setMonth(current.getMonth() + 1); await rerender(); });
            rerender();
        })();
    </script>
</body>
</html>