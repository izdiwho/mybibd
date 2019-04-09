@if (session('status'))
    Swal.fire(
        '{{ $title }}',
        '{{ $description }}',
        '{{ $status }}'
        );
@endif