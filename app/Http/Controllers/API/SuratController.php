<?php

namespace App\Http\Controllers\API;

use App\Models\Surat;
use App\Models\SaveAyatSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\JsonResponse;


class SuratController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $surats = Surat::all();
        return $this->sendResponse($surats, 'Surats retrieved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug): JsonResponse
    {
        $surat = Surat::where('slug', $slug)->with('ayatSurats')->first();
        if (!$surat) {
            return $this->sendError('Surat not found');
        }
        return $this->sendResponse($surat, 'Surat retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAyatSurat(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Unauthorized. User must login first.', [], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        // Periksa apakah pengguna sudah menyimpan ayat surat sebelumnya dengan ayat_surat_id yang sama
        $existingSaveAyatSurat = SaveAyatSurat::where('user_id', $user->id)
                                              ->where('ayat_surat_id', $request->ayat_surat_id)
                                              ->first();
    
        if (!$existingSaveAyatSurat) {
            $request->validate([
                'ayat_surat_id' => 'required|exists:ayat_surats,id',
            ]);
    
            $data = [
                'user_id' => $user->id,
                'ayat_surat_id' => $request->ayat_surat_id,
            ];
    
            $saveAyatSurat = SaveAyatSurat::create($data);
    
            return $this->sendResponse($saveAyatSurat, 'Ayat surat successfully saved.');
        }
    
        return $this->sendError('Cannot save the same ayat surat again.', [], JsonResponse::HTTP_BAD_REQUEST);
    }
    
    


       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroySavedAyatSurat($id): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Unauthorized. User must login first.', [], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Periksa apakah pengguna memiliki simpanan ayat surat dengan ID yang diberikan
        $savedAyatSurat = SaveAyatSurat::where('id', $id)->where('user_id', $user->id)->first();
        if (!$savedAyatSurat) {
            return $this->sendError('Saved ayat surat not found.', [], JsonResponse::HTTP_NOT_FOUND);
        }

        // Hapus simpanan ayat surat
        $savedAyatSurat->delete();

        return $this->sendResponse(null, 'Saved ayat surat successfully deleted.');
    }


    /**
     * Search ayat surat by query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAyatSurat(Request $request): JsonResponse
    {
        $query = $request->input('query');
        
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Unauthorized. User must login first.', [], JsonResponse::HTTP_UNAUTHORIZED);
        }
        
        // Query ayat surat berdasarkan teks arab, teks indonesia, atau teks latin
        $results = AyatSurat::where('teks_arab', 'like', "%$query%")
                        ->orWhere('teks_indonesia', 'like', "%$query%")
                        ->orWhere('teks_latin', 'like', "%$query%")
                        ->get();
        
        return $this->sendResponse($results, 'Ayat surat search results.');
    }
}
