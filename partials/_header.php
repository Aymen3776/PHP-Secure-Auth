<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codifyzen - <?php echo $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-green': '#72b22c',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes blob-move {
            0% {
                transform: scale(1) translate(0px, 0px);
            }

            50% {
                transform: scale(1.2) translate(50px, -50px);
            }

            100% {
                transform: scale(1) translate(0px, 0px);
            }
        }

        .animate-blob {
            animation: blob-move 12s infinite ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-900 text-gray-200 font-sans">
    <div class="relative min-h-screen w-full flex flex-col items-center justify-center p-4 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-green/20 rounded-full filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-brand-green/20 rounded-full filter blur-3xl opacity-70 animate-blob" style="animation-delay: 6s;"></div>
        </div>
        <a href="#" class="absolute top-8 z-20 text-3xl font-bold tracking-wider">
            codifyzen<span class="text-brand-green">.</span>
        </a>