<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RichText\StoreRichTextImageRequest;
use Illuminate\Http\JsonResponse;

class RichTextImageController extends Controller
{
    public function store(StoreRichTextImageRequest $request): JsonResponse
    {
        $path = $request->file('image')->store('rich-text', 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ]);
    }
}
