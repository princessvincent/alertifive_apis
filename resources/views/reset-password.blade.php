@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>

    @endif

    <form method="POST" >
        @csrf
        
        <input type="hidden" name="id" value="{{ $user[0]['id'] }}">
        <input type="password" name="password" class="form-control mb-5 w-50" placeholder="New Password">
        <input type="password" name="confirm_pass" class="form-control mb-5 w-50" placeholder="Confirm Password">
        <button type="submit" class="form-control btn btn-primary w-50">Reset Password</button>
    </form>
</body>

</html>
@endsection