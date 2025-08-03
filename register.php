<?php
require_once 'functions/Auth.php';

if (isLoggedIn()) {
    header('Location: profile.php');
    exit();
}
generateCsrfToken();
$successMessage = '';
$errorMessage = '';
$post_username = '';
$post_email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errorMessage = 'Geçersiz form isteği.';
    } else {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $post_username = $username;
        $post_email = $email;

        $result = registerUser($username, $email, $password);
        if ($result['success']) {
            $successMessage = $result['message'];
            $post_username = '';
            $post_email = '';
        } else {
            $errorMessage = $result['message'];
        }
    }
    generateCsrfToken();
}

$title = "Kayıt Ol";
require_once 'partials/_header.php';
?>
<div class="relative z-10 w-full max-w-sm bg-gray-800/60 backdrop-blur-xl rounded-3xl border border-white/10 shadow-2xl shadow-black/20 p-8">
    <h2 class="text-3xl font-light text-center mb-2 text-white">Hesap Oluştur</h2>
    <p class="text-center text-gray-400 mb-8">Aramıza katılmak için formu doldur.</p>

    <form action="register.php" method="post" class="space-y-6">
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
        <?php if (!empty($successMessage)) : ?>
            <div class="mb-6 rounded-lg border border-green-500/50 bg-green-500/10 px-4 py-3 text-sm text-green-300 flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-dashed-check">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95" />
                    <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44" />
                    <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92" />
                    <path d="M8.56 20.31a9 9 0 0 0 3.44 .69" />
                    <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95" />
                    <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44" />
                    <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92" />
                    <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <span><?= htmlspecialchars($successMessage) ?></span>
            </div>
        <?php endif; ?>
        <div>
            <label for="username" class="block mb-2 text-sm font-medium text-gray-400">Kullanıcı Adı</label>
            <input type="text" id="username" name="username" class="w-full p-3 bg-gray-900/50 border border-gray-700 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/70 focus:border-brand-green transition-all" placeholder="kullaniciadiniz" required>
        </div>

        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-400">E-posta</o>
                <input type="email" id="email" name="email" class="w-full p-3 bg-gray-900/50 border border-gray-700 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/70 focus:border-brand-green transition-all" placeholder="ornek@mail.com" required>
        </div>

        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-400">Şifre</label>
            <input type="password" id="password" name="password" class="w-full p-3 bg-gray-900/50 border border-gray-700 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/70 focus:border-brand-green transition-all" placeholder="••••••••" required>
        </div>

        <button type="submit" class="group relative inline-flex h-[56px] w-full max-w-sm items-center justify-center overflow-hidden rounded-full bg-brand-green pl-6 pr-14 font-semibold text-white">
            <span class="relative z-10 pr-2">Kayıt Ol</span>
            <div class="absolute right-1 inline-flex h-12 w-12 items-center justify-end rounded-full bg-gray-900 transition-[width] duration-300 ease-in-out group-hover:w-[calc(100%-8px)]">
                <div class="mr-3.5 flex items-center justify-center">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white">
                        <path d="M8.14645 3.14645C8.34171 2.95118 8.65829 2.95118 8.85355 3.14645L12.8536 7.14645C13.0488 7.34171 13.0488 7.65829 12.8536 7.85355L8.85355 11.8536C8.65829 12.0488 8.34171 12.0488 8.14645 11.8536C7.95118 11.6583 7.95118 11.3417 8.14645 11.1464L11.2929 8H2.5C2.22386 8 2 7.77614 2 7.5C2 7.22386 2.22386 7 2.5 7H11.2929L8.14645 3.85355C7.95118 3.65829 7.95118 3.34171 8.14645 3.14645Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </button>
        <p class="text-sm text-center text-gray-500 pt-4">
            Zaten bir hesabın var mı? <a href="login.php" class="font-medium text-brand-green hover:text-green-400">Giriş Yap</a>
        </p>
    </form>
</div>
<?php
require_once 'partials/_footer.php';
?>