<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContentTheme;
use Validator;
use App\Http\Resources\ContentThemeResource;
use Illuminate\Http\JsonResponse;

class ContentThemeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $contentThemes = ContentTheme::with('ayatSurat', 'ayatHadith', 'subsubTheme')->get();

        return $this->sendResponse(ContentThemeResource::collection($contentThemes), 'Content Themes retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'content_themes.*.deskripsi' => 'required',
            'content_themes.*.referensi' => 'required',
            'content_themes.*.hadith_tambah' => 'required',
            'content_themes.*.ayat_surat_id' => 'required|exists:ayat_surats,id',
            'content_themes.*.ayat_hadith_id' => 'required|exists:ayat_hadiths,id',
            'content_themes.*.subsub_theme_id' => 'required|exists:subsub_themes,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $createdContentThemes = [];

        if (isset($input['content_themes']) && is_array($input['content_themes'])) {
            foreach ($input['content_themes'] as $contentThemeData) {
                $contentTheme = ContentTheme::create($contentThemeData);
                $createdContentThemes[] = new ContentThemeResource($contentTheme);
            }
        } else {
            return $this->sendError('Invalid input data. Content_themes must be an array.');
        }

        return $this->sendResponse($createdContentThemes, 'Content Themes created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $contentTheme = ContentTheme::with('ayatSurat', 'ayatHadith', 'subsubTheme')->find($id);

        if (is_null($contentTheme)) {
            return $this->sendError('Content Theme not found');
        }

        return $this->sendResponse(new ContentThemeResource($contentTheme), 'Content Theme retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contentTheme = ContentTheme::with('ayatSurat', 'ayatHadith', 'subsubTheme')->find($id);

        if (is_null($contentTheme)) {
            return $this->sendError('Content Theme not found');
        }

        return $this->sendResponse(new ContentThemeResource($contentTheme), 'Edit form data retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $contentTheme = ContentTheme::find($id);

        if (is_null($contentTheme)) {
            return $this->sendError('Content Theme not found');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'deskripsi' => 'required',
            'referensi' => 'required',
            'hadith_tambah' => 'required',
            'ayat_surat_id' => 'required|exists:ayat_surats,id',
            'ayat_hadith_id' => 'required|exists:ayat_hadiths,id',
            'subsub_theme_id' => 'required|exists:subsub_themes,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $contentTheme->update($input);

        return $this->sendResponse(new ContentThemeResource($contentTheme), 'Content Theme updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $contentTheme = ContentTheme::find($id);

        if (is_null($contentTheme)) {
            return $this->sendError('Content Theme not found');
        }

        $contentTheme->delete();

        return $this->sendResponse(null, 'Content Theme deleted successfully.');
    }
}

