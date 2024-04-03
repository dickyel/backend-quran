<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubsubTheme;
use App\Models\SubTheme; // Tambahkan model SubTheme
use Validator;
use App\Http\Resources\SubsubThemeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SubsubThemeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $subthemeId = $request->input('sub_theme_id'); // Ambil sub_theme_id dari request
        $query = SubsubTheme::query()->with('subtheme'); // Menggunakan eager loading

        if ($subthemeId) {
            $query->where('sub_theme_id', $subthemeId); // Filter subthemes berdasarkan sub_theme_id
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
            'subsub_themes.*.name' => 'required', // Validasi subtema di setiap elemen subthemes
            'subsub_themes.*.sub_theme_id' => 'required|exists:sub_themes,id' // Validasi theme_id di setiap elemen subthemes
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $createdSubsubthemes = [];

        // Periksa apakah $input['subsub_themes'] merupakan array
        if (isset($input['subsub_themes']) && is_array($input['subsub_themes'])) {
            foreach ($input['subsub_themes'] as $subsub_themeData) {
                $subtheme = SubTheme::find($subsub_themeData['sub_theme_id']);

                if (!$subtheme) {
                    return $this->sendError('The selected subtheme id is invalid.');
                }

                foreach ($subsub_themeData['name'] as $subsub_temaName) {
                    $slug = Str::slug($subsub_temaName);

                    $subsub_theme = $subtheme->subsubThemes()->create(['name' => $subsub_temaName, 'slug' => $slug]);
                    $createdSubsubthemes[] = new SubsubThemeResource($subsub_theme);
                }
            }
        } else {
            return $this->sendError('Invalid input data. Subsub_themes must be an array.');
        }

        return $this->sendResponse($createdSubsubthemes, 'Subthemes created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $subsubtheme = SubsubTheme::with('subtheme')->find($id); // Menggunakan eager loading
    
        if (is_null($subsubtheme)) {
            return $this->sendError('SubSubtheme not found');
        }
    
        return $this->sendResponse(new SubsubThemeResource($subsubtheme), 'SubSubtheme retrieved successfully.');
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subsubtheme = SubsubTheme::find($id);

        if (is_null($subsubtheme)) {
            return $this->sendError('SubSubtheme not found');
        }

        return $this->sendResponse(new SubsubThemeResource($subsubtheme), 'Edit form data retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'sub_theme_id' => 'required|exists:sub_themes,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $subsubtheme = SubsubTheme::find($id);

        if (is_null($subsubtheme)) {
            return $this->sendError('SubSubtheme not found');
        }

        $slug = Str::slug($input['name']);
        $input['slug'] = $slug;

        $subsubtheme->update($input);

        return $this->sendResponse(new SubsubThemeResource($subsubtheme), 'Sub Sub theme updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $subsubtheme = SubsubTheme::find($id);

        if (is_null($subsubtheme)) {
            return $this->sendError('SubSubtheme not found');
        }

        $subsubtheme->delete();

        return $this->sendResponse(null, 'SubSubtheme deleted successfully.');
    }
}
