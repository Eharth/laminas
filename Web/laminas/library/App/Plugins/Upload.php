<?php
class App_Plugins_Upload {
  var $file;
  var $up_name;
  var $type;
  var $path;
  var $up_file;
  //
  function App_Plugins_Upload($a) {
    $this->file = $a;
    $file_parts = array_reverse(explode('.',$this->file['name']));
    $this->type = strtolower($file_parts[0]);
  }

  function doName() {
    $found = 1;
    while ($found != 0) {
      $this->up_name = time().".".strtolower($this->type);
      $filePath = $this->path.$this->up_name;
      if(file_exists($filePath))
        $found = 1;
      else
        $found = 0;
    }

  }

  function isFile() {
    return is_file($this->file['tmp_name']);
  }

  function setPath($path) {
    $this->path = $path;
  }

  function setFilename($name) {
    $this->up_name = $name.".".$this->type;
  }
  function doFile() {
    if(!$this->up_name) {
      $this->doName();
    }
    $this->up_file = $this->path.$this->up_name;
  }

  function doCopy() {
    $this->doFile();
    return copy($this->file['tmp_name'], $this->up_file);
  }

  function doDelete() {
    return unlink($this->path.$this->up_name);
  }

  function getFilename(){
    return $this->up_name;
  }
}