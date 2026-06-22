<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Recebe uma imagem (capa, logo, favicon, anúncio) e devolve a URL pública.
     * Storage plugável via config('uploads.disk') (public | s3).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:'.implode(',', config('uploads.mimes')),
                'max:'.config('uploads.max_kb'),
            ],
        ]);

        $disk = config('uploads.disk');
        $path = $request->file('file')->store('uploads', $disk);

        return response()->json([
            'url' => Storage::disk($disk)->url($path),
        ]);
    }
}
