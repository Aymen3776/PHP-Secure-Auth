<?php
require_once 'functions/Auth.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}
$user = getUserById($_SESSION['user_id']);
if (!$user) {
    logOut();
    header('Location: login.php?error=user_not_found');
    exit();
}
$title = "Profil";
require_once 'partials/_header.php';
?>

<div class="relative z-10 w-full max-w-md bg-gray-800/60 backdrop-blur-xl rounded-3xl border border-white/10 shadow-2xl shadow-black/20 p-8">
    <div class="flex flex-col items-center">

        <img class="w-28 h-28 rounded-full object-cover mb-4 ring-4 ring-brand-green/50" src="https://picsum.photos/200" alt="Profil Resmi">

        <h2 class="text-3xl font-bold text-white"><?= htmlspecialchars($user['username']) ?></h2>
        <p class="text-md text-brand-green font-medium">@<?= htmlspecialchars($user['username']) ?></p>

        <div class="w-full mt-10 space-y-4 text-sm">
            <div class="flex justify-between items-center py-3 border-b border-gray-700">
                <span class="font-medium text-gray-400">E-posta</span>
                <span class="text-white"><?= htmlspecialchars($user['email']) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-700">
                <span class="font-medium text-gray-400">Katılma Tarihi</span>
                <span class="text-white"><?= turkceTarih($user['created_at']) ?></span>
            </div>
        </div>

        <div class="w-full mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <a href="logout.php" class="w-full sm:w-auto text-center font-semibold py-3 px-8 rounded-lg bg-gray-700/50 text-white hover:bg-gray-700 transition-colors">
                Çıkış Yap
            </a>
        </div>
    </div>
</div>
<?php
require_once 'partials/_footer.php';
?>