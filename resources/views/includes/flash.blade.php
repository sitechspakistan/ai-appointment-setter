{{-- Session flash + validation error summary. --}}
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px">
        <div class="fw-semibold mb-1">Please check the form:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
