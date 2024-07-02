<style>
    body {
        font-family: 'Lato', sans-serif;
        background: #f8f9fc;
    }

    .ftco-section {
        padding: 4em 0;
    }

    .heading-section {
        font-size: 2.5rem;
        color: #333;
    }

    .login-wrap {
        background: #fff;
        padding: 20px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .icon {
        font-size: 60px;
        color: #007bff;
        margin-bottom: 20px;
    }

    .form-control {
        height: 45px;
        border-radius: 25px;
        padding: 10px 20px;
        border: 1px solid #ddd;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .btn-primary {
        background: #007bff;
        border-color: #007bff;
        border-radius: 25px;
        padding: 10px 20px;
    }

    .btn-primary:hover {
        background: #0056b3;
        border-color: #0056b3;
    }

    .checkbox-wrap {
        display: flex;
        align-items: center;
    }

    .checkbox-wrap input {
        margin-right: 10px;
    }

    .checkbox-wrap .checkmark {
        position: relative;
    }

    a {
        color: #007bff;
    }

    a:hover {
        color: #0056b3;
    }
</style>
<!doctype html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Login Admin</h2>
                </div>
            </div>
            <form action="{{ route('loginVerify') }}" method="post">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="login-wrap p-4 p-md-5">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="fa fa-user-circle"></span>
                            </div>
                            <h3 class="text-center mb-4">Sign In</h3>
                            @if (session('error'))
                                <div class="alert alert-danger text-center" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('thongbao'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{ session('thongbao') }}
                                </div>
                            @endif
                            <form action="#" class="login-form">
                                <div class="form-group">
                                    <input type="text" class="form-control rounded-left" name="email"
                                        placeholder="Email" required>
                                </div>
                                <div class="form-group d-flex">
                                    <input type="password" class="form-control rounded-left" name="password"
                                        placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit"
                                        class="form-control btn btn-primary rounded submit px-3">Login</button>
                                </div>
                                <div class="form-group d-md-flex">
                                    <div class="w-50">
                                        <label class="checkbox-wrap checkbox-primary">Remember Me
                                            <input type="checkbox" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="w-50 text-md-right">
                                        <a href="#">Forgot Password</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
