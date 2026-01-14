<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SIAPRAS - Sistem Informasi Sarana dan Prasarana SMKN 1 Boyolangu">

    <title>SIAPRAS - Sarana & Prasarana</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/img/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* Logo Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.7; }
            100% { transform: scale(0.95); opacity: 1; }
        }
        
        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes glow-pulse {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(0,0,0,0.1), 0 0 40px rgba(0,0,0,0.05);
            }
            50% { 
                box-shadow: 0 0 30px rgba(0,0,0,0.2), 0 0 60px rgba(0,0,0,0.1);
            }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .glow-animation {
            animation: glow-pulse 3s ease-in-out infinite;
        }

        .pulse-ring {
            animation: pulse-ring 2s ease-in-out infinite;
        }
        
        .fade-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .fade-up-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-up-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .fade-up-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .fade-up-delay-4 { animation-delay: 0.4s; opacity: 0; }
        .fade-up-delay-5 { animation-delay: 0.5s; opacity: 0; }

        .gradient-bg {
            background: linear-gradient(-45deg, #fafafa, #ffffff, #f5f5f5, #ffffff);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        .text-gradient {
            background: linear-gradient(135deg, #000000, #333333, #000000);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientMove 4s linear infinite;
        }

        /* Interactive Logo */
        .logo-container {
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05);
        }

        .logo-container:hover .logo-ring {
            animation: rotate-slow 8s linear infinite;
        }

        .logo-ring {
            position: absolute;
            inset: -10px;
            border: 2px dashed rgba(0,0,0,0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .logo-container:hover .logo-ring {
            border-color: rgba(0,0,0,0.3);
        }

        /* Button Styles */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #000000, #1a1a1a, #000000);
            background-size: 200% 200%;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            background-position: 100% 100%;
        }

        .btn-primary:active {
            transform: translateY(-2px);
        }

        .btn-secondary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-secondary::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #000;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .btn-secondary:hover::after {
            transform: scaleX(1);
        }

        .btn-secondary:hover {
            color: #000;
        }

        /* Feature Cards */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            background: white;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            background: #000;
        }

        .feature-card:hover .feature-icon svg {
            color: white;
        }

        .feature-icon {
            transition: all 0.3s ease;
        }

        /* Badge Animation */
        .badge-shimmer {
            background: linear-gradient(90deg, #000, #333, #000);
            background-size: 200% 100%;
            animation: shimmer 3s infinite;
        }

        /* Arrow Animation */
        .arrow-bounce {
            animation: bounce-subtle 1.5s ease-in-out infinite;
        }

        /* Decorative Elements */
        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5;
            pointer-events: none;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0,0,0,0.03) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            animation: pulse-ring 4s ease-in-out infinite;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(0,0,0,0.02) 0%, transparent 70%);
            bottom: 100px;
            left: -50px;
            animation: pulse-ring 5s ease-in-out infinite 1s;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col gradient-bg text-gray-900 overflow-x-hidden">
    
    <!-- Decorative Circles -->
    <div class="decoration-circle circle-1"></div>
    <div class="decoration-circle circle-2"></div>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center px-6 py-16 relative z-10">
        
        <!-- Hero Section -->
        <div class="flex flex-col md:flex-row items-center gap-10 md:gap-16 max-w-5xl mb-16">
            
            <!-- Animated Logo -->
            <div class="fade-up">
                <div class="logo-container float-animation">
                    <div class="logo-ring"></div>
                    <div class="glow-animation rounded-full p-5 bg-white">
                        <img 
                            src="{{ Vite::asset('resources/img/logo.png') }}" 
                            alt="Logo SIAPRAS" 
                            class="w-36 h-36 md:w-44 md:h-44 object-contain"
                        >
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="text-center md:text-left">
                
                
                <h1 class="text-5xl md:text-6xl font-extrabold mb-3 tracking-tight fade-up fade-up-delay-2">
                    <span class="text-gradient">SIAPRAS</span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-600 mb-2 fade-up fade-up-delay-2">
                    Aplikasi Sarana dan Prasarana
                </p>
                
                <p class="text-gray-400 mb-8 fade-up fade-up-delay-3">
                    SMKN 1 Boyolangu Tulungagung
                </p>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 fade-up fade-up-delay-4">
                    <a 
                        href="/login" 
                        class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-black text-white rounded-2xl font-semibold text-lg btn-primary"
                    >
                        <span>Masuk ke Sistem</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Feature Highlights -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl w-full fade-up fade-up-delay-5">
            <!-- Feature 1 -->
            <div class="feature-card bg-white/70 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center">
                <div class="feature-icon w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Peminjaman</h3>
                <p class="text-sm text-gray-500">Digunakan untuk mencatat dan mengelola peminjaman barang.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="feature-card bg-white/70 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center">
                <div class="feature-icon w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Inventaris</h3>
                <p class="text-sm text-gray-500">Menampilkan data barang sekolah yang tersimpan dan terkelola dengan baik.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="feature-card bg-white/70 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center">
                <div class="feature-icon w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Laporan</h3>
                <p class="text-sm text-gray-500">Berisi rangkuman data peminjaman barang sebagai dokumentasi.</p>
            </div>
        </div>
        
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200/50 py-6 bg-white/60 backdrop-blur-sm relative z-10">
        <div class="max-w-5xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img 
                    src="{{ Vite::asset('resources/img/logo.png') }}" 
                    alt="Logo" 
                    class="w-8 h-8 object-contain"
                >
                <span class="font-bold text-gray-700">SIAPRAS</span>
            </div>
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} SMKN 1 Boyolangu Tulungagung
            </p>
        </div>
    </footer>

</body>
</html>