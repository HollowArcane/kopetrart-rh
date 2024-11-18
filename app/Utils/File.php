<?php

namespace App\Utils;

class File
{
    public static function save($file, string $directory): string
    {
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(storage_path('app/public/'.$directory), $filename);
        return $directory . '/' . $filename;
    }

    public static function save_test($file): string
    {
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(storage_path('app/test-candidate/'), $filename);
        return $filename;
    }
}
