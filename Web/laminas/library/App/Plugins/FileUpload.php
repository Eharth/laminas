<?php

class App_Plugins_FileUpload {

    public static function upload($caminho) {

        $adapter = new Zend_File_Transfer_Adapter_Http();
        foreach ($adapter->getFileInfo() as $file => $info) {
            if ($adapter->isUploaded($file)) {
                $name = time() . App_Plugins_String::removeAcentos($info['name']);
                $fname = $caminho . $name;
                $adapter->addFilter(new Zend_Filter_File_Rename(array('target' => $fname, 'overwrite' => true)), null, $file);
                if ($adapter->receive($file)) {
                    return $name;
                }
            }
        }
    }

}