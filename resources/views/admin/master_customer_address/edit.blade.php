@extends('layouts.admin.master')

@section('title', 'Edit Alamat Customer - Admin')
@section('sub-title', 'Master Customer')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.customer-addresses.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h6 class="mb-3">Pencarian Sys User</h6>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchTerm" class="form-control" placeholder="Cari nama / username / email sys_user">
                </div>
                <div class="col-md-2">
                    <button id="searchBtn" class="btn btn-outline-primary w-100">Cari</button>
                </div>
                <div class="col-md-4">
                    <div id="selectedUserInfo" class="form-text">User terpilih: #{{ $address->sys_user_id }}</div>
                </div>
            </div>

            <div id="searchResults" class="table-responsive mb-4" style="display:none;">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Customer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="resultsBody"></tbody>
                </table>
            </div>

            <h6 class="mb-3">Form Alamat</h6>
            <form action="{{ route('admin.master.customer-addresses.update', $address) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <input type="hidden" name="sys_user_id" id="sysUserId" value="{{ old('sys_user_id', $address->sys_user_id) }}">

                <div class="col-md-6">
                    <label class="form-label">Nama Penerima</label>
                    <input type="text" name="name" id="fieldName" class="form-control" required maxlength="150" value="{{ old('name', $address->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="phone" id="fieldPhone" class="form-control" maxlength="50" value="{{ old('phone', $address->phone) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat (satu atau beberapa baris)</label>
                    <textarea name="lines_text" id="fieldLines" class="form-control" rows="2" required>{{ old('lines_text', implode("\n", (array)($address->lines ?? []))) }}</textarea>
                    <div class="form-text">Pisahkan baris dengan Enter. Disimpan sebagai array JSON.</div>
                </div>

                <div class="col-md-6 position-relative" id="shipping-section">
                    <label class="form-label">Kecamatan Tujuan</label>
                    <div class="d-flex gap-2">
                        <div class="position-relative w-100">
                            <input type="text" id="shipping_city_input" class="form-control" required placeholder="Masukan Kota Tujuan (min. 4 huruf)" autocomplete="off" >
                            <input type="hidden" id="shipping_city_code" value="">
                            <div id="city-results" class="list-group position-absolute w-100 start-0" style="display:none; z-index: 1000; max-height: 250px; overflow-y: auto; top: 100%; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                        </div>
                        <div class="position-relative" style="flex-shrink: 0;">
                            <button type="button" id="btnCheckOngkir" class="btn btn-primary" style="width: auto; padding: 0 20px; height: 100%;">Hitung</button>
                        </div>
                    </div>
                    <div id="jne-result" class="mt-2" style="display:none;">
                        <label class="form-label">Pilih Layanan Pengiriman</label>
                        <select id="shipping_service" class="form-select"></select>
                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost', $address->shipping_cost) }}">
                    </div>
                    <input type="hidden" name="city" id="fieldCity" value="{{ old('city', $address->city) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tag</label>
                    <input type="text" name="tag" id="fieldTag" class="form-control" maxlength="50" value="{{ old('tag', $address->tag) }}">
                </div>

                <div class="col-10">
                    
                </div>
  <div class="col-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.master.customer-addresses.index') }}" class="btn btn-light">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script>
        (function($){
            const searchBtn = document.getElementById('searchBtn');
            const searchTerm = document.getElementById('searchTerm');
            const resultsEl = document.getElementById('searchResults');
            const resultsBody = document.getElementById('resultsBody');
            const selectedInfo = document.getElementById('selectedUserInfo');

            const sysUserIdEl = document.getElementById('sysUserId');
            const nameEl = document.getElementById('fieldName');
            const phoneEl = document.getElementById('fieldPhone');
            const linesEl = document.getElementById('fieldLines');
            const cityEl = document.getElementById('fieldCity');
            const tagEl = document.getElementById('fieldTag');

            function renderResults(items) {
                resultsBody.innerHTML = '';
                if (!items || items.length === 0) {
                    resultsEl.style.display = 'none';
                    return;
                }
                items.forEach(function(u) {
                    const cust = u.customer || null;
                    const row = document.createElement('tr');
                    row.innerHTML =
                        '<td>' + (u.id || '') + '</td>' +
                        '<td>' + (u.name || '') + '</td>' +
                        '<td>' + (u.username || '') + '</td>' +
                        '<td>' + (u.email || '') + '</td>' +
                        '<td>' + (cust ? (cust.full_name || '') : '-') + '</td>' +
                        '<td><button type="button" class="btn btn-sm btn-outline-success">Pilih</button></td>';
                    const pickBtn = row.querySelector('button');
                    pickBtn.addEventListener('click', function() {
                        sysUserIdEl.value = String(u.id || '');
                        nameEl.value = String((cust && cust.full_name) ? cust.full_name : (u.name || ''));
                        phoneEl.value = String((cust && cust.phone_wa) ? cust.phone_wa : '');
                        linesEl.value = String((cust && cust.address_line) ? cust.address_line : '');
                        const cityParts = [];
                        if (cust && cust.kota) cityParts.push(String(cust.kota));
                        if (cust && cust.provinsi) cityParts.push(String(cust.provinsi));
                        if (cust && cust.kode_pos) cityParts.push('ID ' + String(cust.kode_pos));
                        cityEl.value = cityParts.join(', ');
                        tagEl.value = 'Utama';
                        selectedInfo.textContent = 'User terpilih: ' + (u.name || '') + ' (' + (u.username || '') + ')';
                    });
                    resultsBody.appendChild(row);
                });
                resultsEl.style.display = 'block';
            }

            function search() {
                const q = String(searchTerm.value || '').trim();
                if (q === '') {
                    renderResults([]);
                    return;
                }
                const url = '{{ route('admin.master.customer-addresses.search-users') }}' + '?q=' + encodeURIComponent(q);
                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(function(r) { return r.json(); })
                    .then(function(data) { renderResults(data); })
                    .catch(function() { renderResults([]); });
            }

            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                search();
            });

            var searchTimer;
            $('#shipping_city_input').on('input', function(){
                var query = $(this).val();
                $('#fieldCity').val(query);
                var $results = $('#city-results');
                clearTimeout(searchTimer);
                if(query.length >= 4){
                    searchTimer = setTimeout(function(){
                        $.ajax({
                            url: '{{ route("customer.api.jne.cities") }}',
                            data: { search: query },
                            method: 'GET',
                            success: function(response){
                                if(response.status && response.data && response.data.length > 0){
                                    var html = '';
                                    $.each(response.data, function(i, item){
                                        html += '<a href="#" class="list-group-item list-group-item-action city-item" data-code="'+item.code+'" data-label="'+item.label+'">'+item.label+'</a>';
                                    });
                                    $results.html(html).show();
                                } else {
                                    $results.hide();
                                }
                            },
                            error: function(){ $results.hide(); }
                        });
                    }, 500);
                } else {
                    $results.hide();
                }
            });

            $(document).on('click', '.city-item', function(e){
                e.preventDefault();
                var label = $(this).data('label');
                var code = $(this).data('code');
                $('#shipping_city_input').val(label);
                $('#shipping_city_code').val(code);
                $('#fieldCity').val(label);
                $('#city-results').hide();
            });

            $(document).on('click', function(e){
                if (!$(e.target).closest('#shipping-section').length){
                    $('#city-results').hide();
                }
            });

            $('#btnCheckOngkir').on('click', function(){
                $('#shipping-indicator').fadeOut();
                var code = $('#shipping_city_code').val();
                if(!code){
                    alert('Silakan pilih kota tujuan terlebih dahulu dari dropdown pencarian.');
                    return;
                }
                var $btn = $(this);
                var originalText = $btn.text();
                $btn.prop('disabled', true).text('Loading...');
                $.ajax({
                    url: '{{ route("customer.api.jne.shipping-fee") }}',
                    method: 'GET',
                    data: { destination: code },
                    success: function(response){
                        if(response.status && response.data && response.data.length > 0){
                            var $select = $('#shipping_service');
                            $select.empty().append('');
                            $.each(response.data, function(i, item){
                                $select.append($('<option>', {
                                    value: item.price,
                                    text: item.label,
                                    'data-service': item.service,
                                    'data-etd': item.etd
                                }));
                            });
                            $select.find('option').prop('disabled', false).first().prop('selected', true);
                            $select.trigger('change');
                            $('#jne-result').show();
                        } else {
                            alert('Tidak ada layanan pengiriman yang tersedia.');
                        }
                    },
                    error: function(){ alert('Gagal mengambil ongkir. Coba lagi nanti.'); },
                    complete: function(){ $btn.prop('disabled', false).text(originalText); }
                });
            });

            $('#shipping_service').on('change', function(){
                var cost = $(this).val();
                $('#shipping_cost').val(cost);
            });

            $('#shipping_city_input').on('blur', function(){ $('#fieldCity').val($(this).val()); });
            $('form').on('submit', function(){ $('#fieldCity').val($('#shipping_city_input').val()); });
        })(jQuery);
    </script>
@endsection