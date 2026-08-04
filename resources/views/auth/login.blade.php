<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex justify-center items-center h-screen m-0" style="font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#0f2924 0%,#1a3a33 100%)">

<div class="bg-white py-11 px-10 rounded-2xl shadow-[0_20px_40px_rgba(0,0,0,0.15)] w-full max-w-100 text-center">
    <div class="text-[55px] mb-2.5">🛒</div>
    <h2 class="mb-6 text-[26px] font-semibold" style="color:#1a3a33">Login Kasir</h2>

    @if (session('error'))
        <p class="text-[#842029] bg-[#f8d7da] py-3 rounded-lg mb-5 text-sm">{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <div class="text-[#842029] bg-[#f8d7da] py-3 px-4 rounded-lg mb-5 text-sm text-left">
            <ul class="m-0 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-5">
            <label for="email" class="block mb-1.5 font-medium text-left" style="color:#1a3a33">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   oninvalid="this.setCustomValidity('Email wajib diisi.')" oninput="this.setCustomValidity('')"
                   class="w-full p-3.5 border {{ $errors->has('email') ? 'border-red-600' : 'border-[#dcdcdc]' }} rounded-lg text-[15px] outline-none transition-all box-border focus:border-[#1a3a33] focus:shadow-[0_0_0_3px_rgba(26,58,51,0.15)]">
            @error('email')
                <div class="text-[12px] text-left mt-1" style="color:#842029">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-6">
            <label for="kata_sandi" class="block mb-1.5 font-medium text-left" style="color:#1a3a33">Kata sandi</label>
            <input type="password" id="kata_sandi" name="kata_sandi" required
                   oninvalid="this.setCustomValidity('Kata sandi wajib diisi.')" oninput="this.setCustomValidity('')"
                   class="w-full p-3.5 border {{ $errors->has('kata_sandi') ? 'border-red-600' : 'border-[#dcdcdc]' }} rounded-lg text-[15px] outline-none transition-all box-border focus:border-[#1a3a33] focus:shadow-[0_0_0_3px_rgba(26,58,51,0.15)]">
            @error('kata_sandi')
                <div class="text-[12px] text-left mt-1" style="color:#842029">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
                class="w-full p-3.5 text-white border-0 rounded-lg text-base font-medium cursor-pointer transition-all bg-[#1a3a33] hover:bg-[#0f2924] hover:-translate-y-px">
            Login
        </button>
    </form>
</div>

</body>
</html>