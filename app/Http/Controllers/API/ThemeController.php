<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Theme;
use Validator;
use App\Http\Resources\ThemeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ThemeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $themes = Theme::all();
    
        if ($themes->isEmpty()) {
            return $this->sendError('themes not found', [], JsonResponse::HTTP_NOT_FOUND);
        }
    
        return $this->sendResponse(ThemeResource::collection($themes), 'themes retrieved successfully.');
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'name.*' => 'required' // Menambahkan tanda "*" untuk menunjukkan multiple input
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
    
        $createdThemes = [];
    
        // Loop through each subtema input
        foreach ($input['name'] as $temaName) {
            // Membuat slug dari nama subtema
            $slug = Str::slug($temaName);
            
            // Simpan subtema ke database
            $theme = Theme::create(['name' => $temaName, 'slug' => $slug]);
            
            // Tambahkan subtema yang berhasil dibuat ke dalam array hasil
            $createdThemes[] = new ThemeResource($theme);
        }
    
        return $this->sendResponse($createdThemes, 'Tema created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $theme = Theme::find($id); // Mencari tema berdasarkan ID
    
        if (is_null($theme)) {
            return $this->sendError('Theme not found');
        }
    
        $themes = $theme->get(); // Mendapatkan semua subtema yang terkait dengan tema
    
        return $this->sendResponse(ThemeResource::collection($themes), 'Themes retrieved successfully.');
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme)
    {
        // Mengonversi data subtema ke dalam bentuk array
        $theme = new ThemeResource($theme);

        // Mengembalikan data subtema dalam respons JSON
        return $this->sendResponse($theme, 'Edit form data retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Theme $theme): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Membuat slug baru jika nama subtema berubah
        $slug = Str::slug($input['name']);
        $input['slug'] = $slug;

        $theme->update($input);

        return $this->sendResponse(new ThemeResource($theme), ' Theme updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme): JsonResponse
    {
        $theme->delete();
        return $this->sendResponse(null, 'theme deleted successfully.');
    }
}
