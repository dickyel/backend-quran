<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\JsonResponse;

use App\Models\Surat;
use App\Models\SaveAyatSurat;


class SaveContentThemeController extends BaseController
{
    //
    public function saveContentTheme(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Unauthorized. User must login first.', [], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        // Periksa apakah pengguna sudah menyimpan ayat surat sebelumnya dengan ayat_surat_id yang sama
        $existingSaveContentTheme = ContentTheme::where('user_id', $user->id)
                                              ->where('content_theme_id', $request->content_theme_id)
                                              ->first();
    
        if (!$existingSaveContentTheme) {
            $request->validate([
                'content_theme_id' => 'required|exists:content_themes,id',
            ]);
    
            $data = [
                'user_id' => $user->id,
                'content_theme_id' => $request->content_theme_id,
            ];
    
            $saveContentTheme = SaveContentTheme::create($data);
    
            return $this->sendResponse($saveContentTheme, 'content theme successfully saved.');
        }
    
        return $this->sendError('Cannot save the same content theme again.', [], JsonResponse::HTTP_BAD_REQUEST);
    }
}
