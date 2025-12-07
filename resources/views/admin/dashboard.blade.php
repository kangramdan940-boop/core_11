@extends('layouts.admin')

@section('title', 'Dashboard Admin - Jasa Emas')
@section('page_title', 'Dashboard Admin')

@section('content')
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-2">PO Emas</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small text-muted">Pending</div>
                        <div class="h5 mb-0" id="countPoPending">-</div>
                    </div>
                    <a href="{{ route('admin.trans.po.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat PO</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-2">Emas Ready</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small text-muted">Pending</div>
                        <div class="h5 mb-0" id="countReadyPending">-</div>
                    </div>
                    <a href="{{ route('admin.trans.ready.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat Ready</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-2">Cicilan Emas</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small text-muted">Aktif</div>
                        <div class="h5 mb-0" id="countCicilanAktif">-</div>
                    </div>
                    <a href="{{ route('admin.trans.cicilan.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat Cicilan</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-title mb-0">Order Baru</h6>
                        <small class="text-muted">Terakhir update: <span id="lastUpdate">-</span></small>
                    </div>
                    <ul class="list-group list-group-flush" id="newOrdersList"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-title mb-0">Payment Baru</h6>
                        <small class="text-muted">Pending: <span id="countPaymentPending">-</span></small>
                    </div>
                    <ul class="list-group list-group-flush" id="newPaymentsList"></ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (function(){
        let lastSeen = new Date().toISOString();
        const counts = {
            po: document.getElementById('countPoPending'),
            ready: document.getElementById('countReadyPending'),
            cicilan: document.getElementById('countCicilanAktif'),
            payment: document.getElementById('countPaymentPending')
        };
        const ordersList = document.getElementById('newOrdersList');
        const paymentsList = document.getElementById('newPaymentsList');
        const lastUpdateEl = document.getElementById('lastUpdate');

        async function loadStats(initial=false) {
            try {
                const url = initial ? '{{ route('admin.dashboard.stats') }}' : '{{ route('admin.dashboard.stats') }}?since=' + encodeURIComponent(lastSeen);
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                counts.po.textContent = data.counts?.po_pending ?? '-';
                counts.ready.textContent = data.counts?.ready_pending ?? '-';
                counts.cicilan.textContent = data.counts?.cicilan_aktif ?? '-';
                if (counts.payment) counts.payment.textContent = data.counts?.payment_pending ?? '-';

                if (Array.isArray(data.new_since)) {
                    data.new_since.forEach(function(item){
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.innerHTML = '<div><strong>' + (item.type || '').toUpperCase() + '</strong> ' +
                                       '<span class="text-muted">' + (item.code || '-') + '</span>' +
                                       '<span class="badge bg-secondary ms-2">' + (item.status || '').toUpperCase() + '</span></div>' +
                                       '<small class="text-muted">' + (item.created_at || '-') + '</small>';
                        ordersList.prepend(li);
                    });
                }

                if (Array.isArray(data.payments_new_since)) {
                    data.payments_new_since.forEach(function(pl){
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.innerHTML = '<div><strong>' + (pl.ref_type || '').toUpperCase() + '</strong> ' +
                                       '<span class="text-muted">' + (pl.code || '-') + '</span>' +
                                       '<span class="badge bg-' + (pl.status==='paid'?'success':(pl.status==='failed'?'danger':'secondary')) + ' ms-2">' + (pl.status || '').toUpperCase() + '</span>' +
                                       '</div>' +
                                       '<small class="text-muted">' + (pl.created_at || '-') + '</small>';
                        paymentsList.prepend(li);
                    });
                }

                lastSeen = new Date().toISOString();
                lastUpdateEl.textContent = new Date().toLocaleTimeString();
            } catch (e) {}
        }

        loadStats(true);
        setInterval(loadStats, 30000);
    })();
    </script>
    @endpush
@endsection
