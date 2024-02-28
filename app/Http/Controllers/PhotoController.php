<?php

namespace App\Http\Controllers;

use Cloudinary\Api\Admin\AdminApi;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function delete($public_id)
    {
        try {

            $photo = Photo::where("url", $public_id);

            if (!$photo) {
                return back()->with("error", "Photo not found.");
            }

            $result = (new AdminApi())->deleteAssets(
                $public_id,
                ["resource_type" => "image", "type" => "upload"]
            );

            if ($result) {
                $photo->delete();
                return back()->with('success', 'Photo deleted successfully.');
            } else {
                return back()->with('error', 'Could not delete the photo.');
            }
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
}
