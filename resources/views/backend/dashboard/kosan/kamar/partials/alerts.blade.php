@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
    <i class="bi bi-check2-circle fs-5 me-2.5 text-success flex-shrink-0"></i>
    <div class="flex-grow-1">{{ session('success') }}</div>
    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error') || session('failed'))
  <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
    <i class="bi bi-exclamation-triangle fs-5 me-2.5 text-danger flex-shrink-0"></i>
    <div class="flex-grow-1">{{ session('error') ?? session('failed') }}</div>
    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(isset($errors) && $errors->any())
  <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
    <div class="d-flex align-items-center mb-1">
      <i class="bi bi-exclamation-octagon fs-5 me-2 text-danger flex-shrink-0"></i>
      <strong>Periksa kembali isian formulir:</strong>
    </div>
    <ul class="mb-0 ps-4 fs-8">
      @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
