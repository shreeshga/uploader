<?php
require_once 'configure.php'
?>
<html>
<body>
<form enctype="multipart/form-data" name="upload" action="uploader.php" method="POST">
Album: <input type="text" name="album" />
<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
Choose a file to upload: <input name="file" type="file" />
Message: <input type="text" name="message" /><br />
<input type="submit" value="Upload File" />
</form>
</body>
<html>
