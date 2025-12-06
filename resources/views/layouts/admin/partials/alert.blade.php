@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0 ms-3">
      @foreach($errors->all() as $error)
        <li style="font-size:0.85rem;">{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@php($types = ['success','info','warning','danger','error'])
@foreach($types as $type)
  @if(session()->has($type))
    @php($cls = $type === 'error' ? 'danger' : $type)
    <div class="alert alert-{{ $cls }} alert-dismissible fade show" role="alert">
      {{ session($type) }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
@endforeach
