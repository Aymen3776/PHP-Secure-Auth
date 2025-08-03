<?php
require_once 'functions/Auth.php';

if (isLoggedIn()) {
    header('Location: profile.php');
    exit();
}

generateCsrfToken();

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errorMessage = 'Geçersiz form isteği.';
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember']);

        $result = loginUser($email, $password, $rememberMe);
        if ($result['success']) {
            header('Location: profile.php');
            exit();
        } else {
            $errorMessage = $result['message'];
        }
    }
    generateCsrfToken();
}

$title = "Giriş Yap";
require_once 'partials/_header.php';
?>

<div class="relative z-10 w-full max-w-sm bg-gray-800/60 backdrop-blur-xl rounded-3xl border border-white/10 shadow-2xl shadow-black/20 p-8">
    <h2 class="text-3xl font-light text-center mb-2 text-white">Hoş Geldiniz</h2>
    <p class="text-center text-gray-400 mb-8">Devam etmek için giriş yapın.</p>

    <form action="login.php" method="post" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

        <?php if (!empty($errorMessage)) : ?>
            <div class="mb-6 rounded-lg border border-red-500/50 bg-red-500/10 px-4 py-3 text-sm text-red-300 flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-dashed-x">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95" />
                    <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44" />
                    <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92" />
                    <path d="M8.56 20.31a9 9 0 0 0 3.44 .69" />
                    <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95" />
                    <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44" />
                    <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92" />
                    <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69" />
                    <path d="M14 14l-4 -4" />
                    <path d="M10 14l4 -4" />
                </svg>
                <span><?= htmlspecialchars($errorMessage) ?></span>
            </div>
        <?php endif; ?>
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-400">E-posta</label>
            <input type="email" id="email" name="email" class="w-full p-3 bg-gray-900/50 border border-gray-700 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/70 focus:border-brand-green transition-all" placeholder="ornek@mail.com" required>
        </div>

        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-400">Şifre</label>
            <input type="password" id="password" name="password" class="w-full p-3 bg-gray-900/50 border border-gray-700 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/70 focus:border-brand-green transition-all" placeholder="••••••••" required>
        </div>

        <div class="flex items-center text-sm">
            <label for="remember" class="flex items-center gap-3 cursor-pointer">
                <div class="relative">
                    <input id="remember" name="remember" type="checkbox"
                        class="peer h-5 w-5 cursor-pointer appearance-none rounded-md
                                  border border-gray-600 bg-gray-900/50 transition-all
                                  checked:border-brand-green checked:bg-brand-green
                                  focus:outline-none focus:ring-2 focus:ring-brand-green/70">

                    <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <span class="font-medium text-gray-400">Beni Hatırla</span>
            </label>
        </div>

        <button type="submit" class="group relative inline-flex h-[56px] w-full max-w-sm items-center justify-center overflow-hidden rounded-full bg-brand-green pl-6 pr-14 font-semibold text-white">
            <span class="relative z-10 pr-2">Giriş Yap</span>
            <div class="absolute right-1 inline-flex h-12 w-12 items-center justify-end rounded-full bg-gray-900 transition-[width] duration-300 ease-in-out group-hover:w-[calc(100%-8px)]">
                <div class="mr-3.5 flex items-center justify-center">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white">
                        <path d="M8.14645 3.14645C8.34171 2.95118 8.65829 2.95118 8.85355 3.14645L12.8536 7.14645C13.0488 7.34171 13.0488 7.65829 12.8536 7.85355L8.85355 11.8536C8.65829 12.0488 8.34171 12.0488 8.14645 11.8536C7.95118 11.6583 7.95118 11.3417 8.14645 11.1464L11.2929 8H2.5C2.22386 8 2 7.77614 2 7.5C2 7.22386 2.22386 7 2.5 7H11.2929L8.14645 3.85355C7.95118 3.65829 7.95118 3.34171 8.14645 3.14645Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </button>


        <p class="text-sm text-center text-gray-500 pt-4">
            Hesabın yok mu? <a href="register.php" class="font-medium text-brand-green hover:text-green-400">Kayıt Ol</a>
        </p>
    </form>
</div>

<?php
require_once 'partials/_footer.php';
?>