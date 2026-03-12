@if (session('success'))
    <div class="alert alert-success">
        <div style="color: green;">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        <div style="color: red;">{{ session('error') }}</div>
    </div>
@endif


@if (session('warning'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
