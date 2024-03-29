<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .app {
            width: 100%;
            min-height: 100vh;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container">
        <div class="app w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="card" style="width: 400px;">
                <div class="card-header pt-4 bg-success text-white">
                    <h5 class="card-title text-uppercase text-center">Login to Dashboard</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email address</label>
                            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="loginEmail" aria-describedby="emailHelp">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="loginPassword">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>