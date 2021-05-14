<?php

namespace App\Functions;


class CommonFunctions {
   
    public static function delete_files($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
    
            foreach( $files as $file ){
                self::delete_files( $file );      
            }
    
            if(is_dir($target))  rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }
}