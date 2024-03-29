<?php



namespace App\Utilities;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUploader {

    public static function upload($image,$path,$diskType){
        if(!is_null($image)){
            Storage::disk( $diskType )->put($path ,File::get($image));

        }
    }

    public static function multiUploader(array $images,$path,$diskType = 'public_storage'){

        $imagesPath =[];

        foreach($images as $key => $image ){
            $fullPath = $path . $key .'_'. $image->getClientOriginalName();
            if(!is_null($image)){
                Storage::disk( $diskType )->put($path ,File::get($image));
    
            }
            $imagesPath += [ $key => $fullPath];
        }
        return $imagesPath;
    }
}