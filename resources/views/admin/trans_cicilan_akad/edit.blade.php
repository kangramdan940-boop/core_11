@extends('layouts.admin.master')

@section('title', 'Edit Akad Murabahah - Admin')
@section('sub-title', 'Akad Murabahah')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.trans.cicilan-akad.index'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Akad: {{ $akad->nomor_akad }}</h5>
            <a href="{{ route('admin.trans.cicilan-akad.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Kembali</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.trans.cicilan-akad.update', $akad) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                @include('admin.trans_cicilan_akad._form')
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit"><i class="ri-check-line"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
<style>
.select2-container--bootstrap-5 .select2-selection{min-height:38px;padding:.375rem .75rem;border-color:var(--bs-border-color);border-radius:var(--bs-border-radius)}
.select2-container--bootstrap-5 .select2-selection__rendered{line-height:1.5;color:var(--bs-body-color)}
.select2-container--bootstrap-5 .select2-selection__placeholder{color:var(--bs-secondary-color)}
.select2-container--bootstrap-5 .select2-dropdown{border-color:var(--bs-border-color)}
.select2-container--bootstrap-5 .select2-results__option{color:var(--bs-body-color)}
.select2-container--bootstrap-5 .select2-results__option--highlighted{background-color:var(--bs-primary);color:#fff}
</style>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var s2 = document.getElementById('agenSelectAkad'); if (s2) new Choices(s2, { searchEnabled: true, itemSelectText: '', shouldSort: false });
  var s3 = document.getElementById('cicilanEmasSelectAkad'); if (s3) new Choices(s3, { searchEnabled: true, itemSelectText: '', shouldSort: false });
  if (window.jQuery) {
    jQuery(function($){
      var $cust = $('#customerSelectAkad');
      if ($cust.length) {
        $cust.select2({ theme: 'bootstrap-5', width: '100%', placeholder: '-- Pilih Customer --', allowClear: true, minimumResultsForSearch: 5 });
      }
    });
  }
});
</script>
@endsection