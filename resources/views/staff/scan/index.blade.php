@extends('layouts.staff')

@section('title', 'Scan QR Code')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-2xl mx-auto border border-gray-100">
            <div class="bg-black px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    Scan QR Code Peminjaman
                </h2>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-6 text-center">Scan menggunakan kamera ATAU upload gambar QR Code.</p>

                <div id="error-message"
                    class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm"></div>

                <!-- Scanner Area -->
                <div class="relative mb-6">
                    <!-- Placeholder / Reader -->
                    <div id="reader" class="mx-auto overflow-hidden rounded-lg bg-gray-100 border border-gray-200"
                        style="min-height: 300px;"></div>

                    <!-- Status Overlay (Camera Off) -->
                    <div id="camera-off-overlay"
                        class="absolute inset-0 flex items-center justify-center bg-gray-50 bg-opacity-80 z-10 pointer-events-none">
                        <div class="text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                </path>
                            </svg>
                            <p>Kamera Mati</p>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex flex-col gap-4">
                    <!-- Camera Toggle -->
                    <button id="toggle-camera"
                        class="w-full bg-black text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-800 transition shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span id="toggle-text">Buka Kamera</span>
                    </button>

                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="flex-shrink-0 mx-4 text-gray-400 text-sm">ATAU FILE GAMBAR</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <!-- File Picker -->
                    <div class="relative">
                        <input type="file" id="qr-input-file" accept="image/*" class="hidden">
                        <label for="qr-input-file"
                            class="w-full bg-white text-black border-2 border-dashed border-gray-300 px-6 py-3 rounded-lg font-bold hover:bg-gray-50 hover:border-gray-400 transition cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Pilih File Gambar
                        </label>
                    </div>
                </div>

                <div id="scan-result"
                    class="mt-6 hidden p-4 bg-gray-100 border border-gray-800 rounded text-gray-900 text-center shadow-sm">
                    <p class="font-bold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        QR Code Terdeteksi!
                    </p>
                    <div class="text-sm mt-1 break-all font-mono bg-white p-2 border border-gray-200 rounded my-2"
                        id="result-text"></div>
                    <p class="text-xs text-gray-500">Mengalihkan...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const html5QrCode = new Html5Qrcode("reader");
            const toggleBtn = document.getElementById('toggle-camera');
            const toggleText = document.getElementById('toggle-text');
            const fileInput = document.getElementById('qr-input-file');
            const errorDiv = document.getElementById('error-message');
            const resultDiv = document.getElementById('scan-result');
            const resultText = document.getElementById('result-text');
            const overlay = document.getElementById('camera-off-overlay');

            let isCameraRunning = false;

            function showError(msg) {
                errorDiv.textContent = msg;
                errorDiv.classList.remove('hidden');
                setTimeout(() => errorDiv.classList.add('hidden'), 5000);
            }

            function handleScanSuccess(decodedText, decodedResult) {
                console.log(`Scan success: ${decodedText}`);

                // Stop camera if running
                if (isCameraRunning) {
                    stopCamera();
                }

                // Show result
                resultDiv.classList.remove('hidden');
                resultText.textContent = decodedText;

                // Redirect logic
                setTimeout(() => {
                    window.location.href = decodedText;
                }, 1000);
            }

            function startCamera() {
                overlay.classList.add('hidden');
                errorDiv.classList.add('hidden');
                resultDiv.classList.add('hidden');

                const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    handleScanSuccess,
                    (errorMessage) => {
                        // Parse error if critical
                    }
                )
                    .then(() => {
                        isCameraRunning = true;
                        toggleText.textContent = "Matikan Kamera";
                        toggleBtn.classList.replace('bg-black', 'bg-red-600');
                        toggleBtn.classList.replace('hover:bg-gray-800', 'hover:bg-red-700');
                    })
                    .catch((err) => {
                        console.error("Error starting camera", err);
                        overlay.classList.remove('hidden');

                        let userMsg = "Gagal mengakses kamera. ";
                        if (err.name === 'NotAllowedError') userMsg += "Izin ditolak.";
                        else if (err.name === 'NotFoundError') userMsg += "Tidak ada kamera.";
                        else if (err.name === 'NotReadableError') userMsg += "Kamera sibuk.";
                        else userMsg += err.message;

                        showError(userMsg);
                    });
            }

            function stopCamera() {
                html5QrCode.stop().then(() => {
                    isCameraRunning = false;
                    toggleText.textContent = "Buka Kamera";
                    toggleBtn.classList.replace('bg-red-600', 'bg-black');
                    toggleBtn.classList.replace('hover:bg-red-700', 'hover:bg-gray-800');
                    overlay.classList.remove('hidden');
                }).catch(err => console.error("Failed to stop", err));
            }

            // Toggle Button Click
            toggleBtn.addEventListener('click', () => {
                if (isCameraRunning) {
                    stopCamera();
                } else {
                    startCamera();
                }
            });

            // File Input Change
            fileInput.addEventListener('change', e => {
                if (e.target.files.length == 0) return;

                errorDiv.classList.add('hidden');
                resultDiv.classList.add('hidden');

                const imageFile = e.target.files[0];

                // Scan file locally
                html5QrCode.scanFileV2(imageFile, true)
                    .then(decodedText => {
                        handleScanSuccess(decodedText, null);
                    })
                    .catch(err => {
                        showError("Gagal membaca QR Code dari gambar. Pastikan gambar jelas.");
                        console.error("Error scanning file", err);
                    });
            });
        });
    </script>
@endsection