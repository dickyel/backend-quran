<?php

namespace App\Http\Controllers\API;

use App\Models\Hadith;
use App\Models\SaveAyatHadith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\JsonResponse;


class HadithController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $surats = Hadith::all();
        return $this->sendResponse($surats, 'Hadiths retrieved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug): JsonResponse
    {
        $hadith = Hadith::where('slug', $slug)->with('ayatHadiths')->first();
        if (!$hadith) {
            return $this->sendError('hadith not found');
        }
        return $this->sendResponse($hadith, 'hadith retrieved successfully.');
    }


       /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAyatHadith(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Unauthorized. User must login first.', [], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        // Periksa apakah pengguna sudah menyimpan ayat surat sebelumnya dengan ayat_surat_id yang sama
        $existingSaveAyatHadith = SaveAyatHadith::where('user_id', $user->id)
                                              ->where('ayat_hadith_id', $request->ayat_hadith_id)
                                              ->first();
    
        if (!$existingSaveAyatHadith) {
            $request->validate([
                'ayat_hadith_id' => 'required|exists:ayat_hadiths,id',
            ]);
    
            $data = [
                'user_id' => $user->id,
                'ayat_hadith_id' => $request->ayat_hadith_id,
            ];
    
            $saveAyatHadith = SaveAyatHadith::create($data);
    
            return $this->sendResponse($saveAyatHadith, 'Ayat hadith successfully saved.');
        }
    
        return $this->sendError('Cannot save the same ayat hadith again.', [], JsonResponse::HTTP_BAD_REQUEST);
    }
    


       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroySavedAyatHadith($id): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Unauthorized. User must login first.', [], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        // Periksa apakah pengguna memiliki simpanan ayat hadith dengan ID yang diberikan
        $savedAyatHadith = SaveAyatHadith::where('id', $id)->where('user_id', $user->id)->first();
        if (!$savedAyatHadith) {
            return $this->sendError('Saved ayat hadith not found.', [], JsonResponse::HTTP_NOT_FOUND);
        }
    
        // Hapus simpanan ayat hadith
        $savedAyatHadith->delete();
    
        return $this->sendResponse(null, 'Saved ayat hadith successfully deleted.');
    }
    


    /**
     * Search ayat surat by query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAyatHadith(Request $request): JsonResponse
    {
        $query = $request->input('query');
        
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Unauthorized. User must login first.', [], JsonResponse::HTTP_UNAUTHORIZED);
        }
        
        // Query ayat surat berdasarkan teks arab, teks indonesia, atau teks latin
        $results = AyatHadith::where('arab', 'like', "%$query%")
                        ->orWhere('indonesia', 'like', "%$query%")
                        
                        ->get();
        
        return $this->sendResponse($results, 'Ayat hadith search results.');
    }
}
