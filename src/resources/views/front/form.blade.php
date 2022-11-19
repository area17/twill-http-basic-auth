@if ($errors ?? false)
    @foreach ($errors->all() as $error)
        <div style="color:red">
            {{ $error }}<br>
        </div>
    @endforeach

    <br>
@endif
<form id="http-basic-auth-form"
      action="/debug/http-basic-auth"
      method="POST">
    @csrf

    <label for="input1">Label</label>

    <input id="input1"
           name="input1"
           type="text">

    @if ($TwillHttpBasicAuth['enabled'])
        <input id="g-recaptcha-response"
               name="g-recaptcha-response"
               type="hidden">

        <button type="button"
                onclick="return onSubmitClick();">
            Submit
        </button>
    @else
        <button type="button">Submit</button>
    @endif

    <br>

    <div>Site key: {{ $TwillHttpBasicAuth['keys']['site'] }}</div>
</form>

@if ($TwillHttpBasicAuth['enabled'])
    <script src="{{ $TwillHttpBasicAuth['asset'] }}"></script>

    <script>
        console.log('HTTP Basic Auth 3 loaded');

        function onSubmitClick(e) {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $TwillHttpBasicAuth['keys']['site'] }}', {
                    action: 'submit'
                }).then(function(token) {
                    document.getElementById("g-recaptcha-response").value = token;
                    document.getElementById("http-basic-auth-form").submit();
                });
            });
        }
    </script>
@endif
