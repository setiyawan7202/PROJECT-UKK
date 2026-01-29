<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.imgbb.com/1/upload';

    public function __construct()
    {
        $this->apiKey = env('API_SAVEIMAGE', '');
    }

    /**
     * Upload image to ImgBB
     *
     * @param UploadedFile $file
     * @param string $name Optional name for the image
     * @return string|null Returns the image URL on success, null on failure
     */
    public function upload(UploadedFile $file, string $name = null): ?string
    {
        try {
            // Convert image to base64
            $imageData = base64_encode(file_get_contents($file->getRealPath()));

            // Prepare the request
            $response = Http::asForm()->post($this->apiUrl, [
                'key' => $this->apiKey,
                'image' => $imageData,
                'name' => $name ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['url'])) {
                    return $data['data']['url'];
                }

                // Alternative: use display_url for better quality
                if (isset($data['data']['display_url'])) {
                    return $data['data']['display_url'];
                }
            }

            Log::error('ImgBB upload failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('ImgBB upload exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Check if the service is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}
