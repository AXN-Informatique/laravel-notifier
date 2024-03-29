<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        timerProgressBar: true,
        width: '35em',
        showConfirmButton: false,
    @if ($title)
        title: '{!! $title !!}',
    @endif
        html: '{!! $message !!}',
        icon: '{!! $type !!}',
        showClass: {
            popup: 'animate__animated animate__bounceInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__bounceOut'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        buttonsStyling: false,
        customClass: {
            @switch ($type)
                @case ('info')
                    timerProgressBar: 'bg-info',
                    confirmButton: 'btn btn-info',
                    htmlContainer: 'text-info'
                @break

                @case ('success')
                    timerProgressBar: 'bg-success',
                    confirmButton: 'btn btn-success',
                    htmlContainer: 'text-success'
                @break

                @case ('warning')
                    timerProgressBar: 'bg-warning',
                    confirmButton: 'btn btn-warning',
                    htmlContainer: 'text-warning'
                @break

                @case ('error')
                    timerProgressBar: 'bg-danger',
                    confirmButton: 'btn btn-danger',
                    htmlContainer: 'text-danger'
                @break
            @endswitch
        }
    })

    @switch ($type)
        @case ('info')
            Toast.fire({
                timer: {!! $delay !!}
            })
        @break

        @case ('success')
            Toast.fire({
                timer: {!! $delay !!}
            })
        @break

        @case ('warning')
            Toast.fire({
                timer: {!! $delay !!}
            })
        @break

        @case ('error')
            Toast.fire({
                timer: {!! $delay * $errorsCount !!}
            })
        @break
    @endswitch
</script>
