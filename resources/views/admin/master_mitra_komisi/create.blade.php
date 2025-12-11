@extends('layouts.admin.master')

@section('title', 'Tambah Komisi Mitra - Admin')
@section('sub-title', 'Komisi Mitra')
@section('breadcrumbExtra', 'Tambah Komisi Mitra')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.mitra-komisi.index'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.master.mitra-komisi.store') }}" method="POST">
                @csrf

                @include('admin.master_mitra_komisi._form', ['komisi' => null, 'mitras' => $mitras])

                <div class="d-flex justify-content-end mt-5 gap-2">
                    <a href="{{ route('admin.master.mitra-komisi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy-fill fs-6 me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/air-datepicker/air-datepicker.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/libs/air-datepicker/air-datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/air-datepicker.init.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var localeEn = {
                days: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                daysShort: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
                daysMin: ['Su','Mo','Tu','We','Th','Fr','Sa'],
                months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                monthsShort: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                today: 'Today',
                clear: 'Clear',
                dateFormat: 'yyyy-MM-dd',
                timeFormat: 'hh:ii',
                firstDay: 1
            };
            var startEl = document.querySelector('#komisiStart');
            var endEl = document.querySelector('#komisiEnd');
            if (typeof AirDatepicker !== 'undefined') {
                var startVal = startEl && startEl.value ? new Date(startEl.value) : null;
                var endVal = endEl && endEl.value ? new Date(endEl.value) : null;
                if (startEl) new AirDatepicker('#komisiStart', { dateFormat: 'yyyy-MM-dd', locale: localeEn, autoClose: true, selectedDates: startVal ? [startVal] : undefined });
                if (endEl) new AirDatepicker('#komisiEnd', { dateFormat: 'yyyy-MM-dd', locale: localeEn, autoClose: true, selectedDates: endVal ? [endVal] : undefined });
            }
        });
    </script>
@endsection
