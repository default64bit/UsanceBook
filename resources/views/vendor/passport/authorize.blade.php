<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Authorization</title>
</head>
<body>
    <form name="approve" method="post" action="{{ route('passport.authorizations.approve') }}">
        @csrf
        <input type="hidden" name="state" value="{{ $request->state }}">
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <input type="hidden" name="auth_token" value="{{ $authToken }}">
        {{-- <button type="submit">approve</button> --}}
    </form>
    <form name="deny" method="post" action="{{ route('passport.authorizations.deny') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="state" value="{{ $request->state }}">
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <input type="hidden" name="auth_token" value="{{ $authToken }}">
        {{-- <button class="btn btn-danger">deny</button> --}}
    </form>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        let a = urlParams.get('a');
        if(a == '1'){
            document.querySelector('form[name="approve"]').submit();
        }else{ document.querySelector('form[name="deny"]').submit(); }
    </script>
</body>
</html>
