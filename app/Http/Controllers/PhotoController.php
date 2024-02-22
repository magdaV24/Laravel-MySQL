<?php

namespace App\Http\Controllers;

use Cloudinary\Api\Admin\AdminApi;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function delete($public_id)
    {
       $photo = Photo::where("url", $public_id);

       if (!$photo) {
        return response()->json(['message' => 'Photo not found'], 404);
    }

    $result = (new AdminApi())->deleteAssets($public_id,
    ["resource_type" => "image", "type" => "upload"]);

    if ($result) {
        $photo->delete();
        return redirect()->back()->with('success', 'Photo deleted successfully.');
    } else {
        return response()->json(['message' => 'Failed to delete photo from Cloudinary'], 500);
    }

    }
}
