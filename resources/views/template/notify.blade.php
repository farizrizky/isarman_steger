@php
    $notify = session()->get('notify');
@endphp
<script>
    $(document).ready(function () {
        var placementFrom = "top";
        var placementAlign = "center";
        var state = "{{ $notify['state'] }}";
        var message = "{!! $notify['message'] !!}";
        var title = "{{ $notify['title'] }}"
        var content = {};

        content.message = message;
        content.title = title;
        content.icon = "fa fa-bell";

        $.notify(content, {
            type: state,
            placement: {
                from: 'bottom',
                align: 'right',
            },
            time: 1000,
            delay: 0,
            html:true
        });
    });
</script>