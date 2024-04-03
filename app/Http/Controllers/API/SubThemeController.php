<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubTheme;
use App\Models\Theme; // Tambahkan model Theme
use Validator;
use App\Http\Resources\SubThemeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SubThemeController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $subthemeId = $request->input('sub_theme_id'); // Ambil sub_theme_id dari request
        $query = SubsubTheme::query()->with('subtheme'); // Menggunakan eager loading
    
        if ($subthemeId) {
            $query->where('sub_theme_id', $subthemeId); // Filter subsubthemes berdasarkan sub_theme_id
        }
    
        $subsubthemes = $query->get();
    
        return $this->sendResponse(SubsubThemeResource::collection($subsubthemes), 'SubSubthemes retrieved successfully.');
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'subthemes.*.name' => 'required', // Validasi subtema di setiap elemen subthemes
            'subthemes.*.theme_id' => 'required|exists:themes,id' // Validasi theme_id di setiap elemen subthemes
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
    
        $createdSubthemes = [];
    
        foreach ($input['subthemes'] as $subthemeData) {
            $theme = Theme::find($subthemeData['theme_id']);
    
            if (!$theme) {
                return $this->sendError('The selected theme id is invalid.');
            }
    
            foreach ($subthemeData['name'] as $subtemaName) {
                $slug = Str::slug($subtemaName);
    
                $subtheme = $theme->subThemes()->create(['name' => $subtemaName, 'slug' => $slug]);
                $createdSubthemes[] = new SubThemeResource($subtheme);
            }
        }
    
        return $this->sendResponse($createdSubthemes, 'Subthemes created successfully.');
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $subtheme = SubTheme::with('theme')->find($id); // Menggunakan eager loading
    
        if (is_null($subtheme)) {
            return $this->sendError('Subtheme not found');
        }
    
        return $this->sendResponse(new SubThemeResource($subtheme), 'Subtheme retrieved successfully.');
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubTheme $subtheme)
    {
        // Menggunakan eager loading saat mengonversi data subtema ke dalam bentuk array
        $subthemeData = new SubThemeResource($subtheme->load('theme'));

        // Mengembalikan data subtema dalam respons JSON
        return $this->sendResponse($subthemeData, 'Edit form data retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubTheme $subtheme): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'theme_id' => 'required|exists:themes,id' // Validasi theme_id
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Membuat slug baru jika nama subtema berubah
        $slug = Str::slug($input['name']);
        $input['slug'] = $slug;
        $input['theme_id'] = $input['theme_id']; // Menambahkan theme_id pada input

        $subtheme->update($input);

        return $this->sendResponse(new SubThemeResource($subtheme->load('theme')), 'Sub theme updated successfully.'); // Menggunakan eager loading
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubTheme $subtheme): JsonResponse
    {
        $subtheme->delete();
        return $this->sendResponse(null, 'Subtheme deleted successfully.');
    }
}
