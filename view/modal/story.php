<?php
echo '<div>';
$file = "static/user/".$this->author->name."/stories/".$this->media;
if(preg_match('/image\//', mime_content_type($file))){
    echo "<img src='$file' />";
}
else if(preg_match('/video\//', mime_content_type($file))){
    echo "<video controls src='$file'></video>";
}
echo '</div>';