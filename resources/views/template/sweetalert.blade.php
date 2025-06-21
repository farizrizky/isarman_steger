@php
    $sweetalert = session()->get('sweetalert');
@endphp
<script>
    var title = "{{ $sweetalert['title'] }}";
    var message = "{!! $sweetalert['message'] !!}";
    var state = "{{ $sweetalert['state'] }}";
    showAlert(title, message, state);
</script>