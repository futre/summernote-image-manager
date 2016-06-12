# summernote-image-manager

Simple image manager, ovveride the default image button and open a modal with uploaded images

Do not forget to edit filemanager.php line 1 and 2 with the right path, the first must be absolute the second is relative.
If you move save.php and delete.php also modify the paths at line 150 and 175 DO NOT use absolute paths.

Save.php and delete.php are NOTE secured, that's your job.

Also you do not need to add any script where you use summernote, just a textarea with class "summernote", everything is in image-manager.js, here is the call to the manager (filemanager.php) and here you can add or remove buttons.

