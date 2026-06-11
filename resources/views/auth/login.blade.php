<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2924 0%, #1a3a33 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #ffffff;
            padding: 45px 40px;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .icon {
            font-size: 55px;
            margin-bottom: 10px;
        }

        h2 {
            margin-bottom: 25px;
            color: #1a3a33;
            font-size: 26px;
            font-weight: 600;
        }

        .error {
            color: #842029;
            background: #f8d7da;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            text-align: left;
            color: #1a3a33;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px;
            margin-bottom: 22px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #1a3a33;
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 58, 51, 0.15);
        }

        button {
            width: 100%;
            padding: 13px;
            background: #1a3a33;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #0f2924;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="icon">🛒</div>
    <h2>Login Kasir</h2>

    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="kata_sandi">Kata Sandi</label>
            <input type="password" id="kata_sandi" name="kata_sandi" required>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>