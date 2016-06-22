<?php
if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = date('YmdHis');
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];
                $destination = '../upload/' . $filename; //edit path
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                $return_data=array('error'=>0,'content'=>'http://localhost/summernote/upload/' . $filename); //edit path
				echo json_encode($return_data);
            }
            else
            {
              echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
            }
        }