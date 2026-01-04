@extends('layouts.admin.master')

@section('title', 'Master Asset - Admin')
@section('sub-title', 'Master Asset')
@section('pagetitle', 'Dashboard')
@section('subLink', route('admin.master.assets.index'))

@section('content')
    <div class="card shadow-sm">
        <table id="assetsTable" class="data-table-added table-hover align-middle table table-nowrap w-100">
            <thead class="bg-light bg-opacity-30">
                <tr>
                    <th width="10px;">ID</th>
                    <th>Preview</th>
                    <th>Judul</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th style="width: 75px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assets as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>
                            @php($raw = $a->url ?? '')
                            @php($src = Str::startsWith($raw, ['http://','https://']) ? $raw : ($raw ? asset($raw) : ''))
                            @php($ext = Str::lower($a->file_extension ?? ''))
                            @php($isImg = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']))
                            @if($isImg && !empty($src))
                                <a href="javascript:;" class="zoomable-thumb" data-src="{{ $src }}" data-label="{{ $a->title ?? ('#'.$a->id) }}">
                                    <img src="{{ $src }}" alt="asset" style="height:36px;object-fit:cover;cursor:zoom-in;">
                                </a>
                            @elseif(!empty($src))
                                <a href="{{ $src }}" target="_blank">{{ basename($raw) }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-truncate" style="max-width:220px;">{{ $a->title }}</td>
                        <td>{{ $a->type }}</td>
                        <td>{{ $a->category }}</td>
                        <td class="text-center">
                            @if(($a->status ?? 'active') === 'active')
                                <span class="badge rounded-pill bg-success">Aktif</span>
                            @else
                                <span class="badge rounded-pill bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="hstack gap-2 fs-15">
                                <a href="{{ route('admin.master.assets.edit', $a) }}" class="btn icon-btn-sm btn-light-primary">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a href="#" class="btn icon-btn-sm btn-light-danger delete-item"
                                   data-action="{{ route('admin.master.assets.destroy', $a) }}"
                                   data-label="{{ $a->title ? $a->title : ('#' . $a->id) }}">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                            <form action="{{ route('admin.master.assets.destroy', $a) }}" method="POST" class="d-none delete-form">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.3.0/css/dataTables.checkboxes.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.6.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        const el=document.getElementById('assetsTable');
        if(!el||typeof $==='undefined'||!$.fn.DataTable)return;
        const dt=$('#assetsTable').DataTable({responsive:false,scrollX:true,lengthMenu:[10,20,50],pageLength:10,ordering:true,order:[[0,'desc']],columnDefs:[{targets:-1,orderable:false}],dom:'<"card-header dt-head d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3"<"head-label"><"d-flex flex-column flex-sm-row align-items-center justify-content-sm-end gap-3 w-100"f<"add_button">>>t<"card-footer d-flex flex-column align-items-center gap-2"<"row w-100 align-items-center g-2"<"col-12 col-md-5 d-flex align-items-center justify-content-md-start justify-content-center gap-1"l i><"col-12 col-md-7 d-flex justify-content-md-end justify-content-center"p>>>',language:{sLengthMenu:'Show _MENU_',search:'',searchPlaceholder:'Search Files',paginate:{next:'<i class="ri-arrow-right-s-line"></i>',previous:'<i class="ri-arrow-left-s-line"></i>'}}});
        const head=document.querySelector('div.head-label');if(head){head.innerHTML='<h5 class="card-title text-nowrap mb-0">Daftar Master Asset</h5>'}
        const addc=document.querySelector('.add_button');if(addc){addc.innerHTML='<a class="btn btn-primary" href="{{ route('admin.master.assets.create') }}">Add Data</a>'}
        const saved=sessionStorage.getItem('assetsTablePage');if(saved!==null){const p=parseInt(saved,10);if(!Number.isNaN(p))dt.page(p).draw('page');sessionStorage.removeItem('assetsTablePage')}
        document.querySelectorAll('.delete-item').forEach(function(btn){btn.addEventListener('click',function(e){e.preventDefault();const label=this.getAttribute('data-label')||'Asset';const row=this.closest('tr');const form=row?row.querySelector('form.delete-form'):null;if(!form)return;Swal.fire({title:'Konfirmasi Hapus',html:`Anda yakin hapus Asset <b>${label}</b> ini?`,icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonColor:'#6c757d',confirmButtonText:'Ya, Hapus',cancelButtonText:'Batal'}).then((r)=>{if(r.isConfirmed){try{const info=dt.page.info();sessionStorage.setItem('assetsTablePage',String(info.page));}catch(_){ }form.submit();}})});});
        document.addEventListener('click',function(e){const a=e.target.closest('.zoomable-thumb');if(!a)return;e.preventDefault();const src=a.getAttribute('data-src');const label=a.getAttribute('data-label')||'Preview';Swal.fire({title:label,imageUrl:src,imageAlt:label,showCloseButton:true,showConfirmButton:false,width:'auto'})});
        setTimeout(function(){const fi=document.querySelector('.dataTables_filter .form-control');const ls=document.querySelector('.dataTables_length .form-select');if(fi)fi.classList.remove('form-control-sm');if(ls)ls.classList.remove('form-select-sm');},300);
    });
    </script>
@endsection