<?php

	// Replace with the actual path to your upload folder
	$uploadFolder = '/home/kali/Documents/deloitte/fileupload-share/'; 

	$maxUploadSize = ini_get('upload_max_filesize');

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploadFile'])) {
    // File upload handling
		$targetDirectory = $uploadFolder;

		if (!file_exists($targetDirectory)) {
			mkdir($targetDirectory, 0777, true);
		}

		$file = $_FILES['uploadFile']['tmp_name'];
		$fileName = basename($_FILES['uploadFile']['name']);
		$targetFile = $targetDirectory . '/' . $fileName;

		if (file_exists($targetFile)) {
			$debugMessage = 'File already exists.';
		} elseif ($_FILES['uploadFile']['error'] === UPLOAD_ERR_INI_SIZE) {
			$debugMessage = 'Error uploading file: Exceeded the maximum file size allowed. Maximum upload size is ' . $maxUploadSize;
		} elseif (!move_uploaded_file($file, $targetFile)) {
			$debugMessage = 'Error uploading file: ' . $_FILES['uploadFile']['error'];
		} else {
			header('Location: ' . $_SERVER['PHP_SELF']);
			exit();
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>File Upload</title>
	</head>
	<body>
		<h1>File Upload</h1>
		<?php if (isset($debugMessage)): ?>
			<p>Debug Message: <?php echo $debugMessage; ?></p>
		<?php endif; ?>
		<p>Maximum Upload Size: <?php echo $maxUploadSize; ?></p>
		<form method="POST" enctype="multipart/form-data">
			<input type="file" name="uploadFile" required>
			<button type="submit">Upload</button>
		</form>

		<hr>

		<h1>Files in Upload Folder:</h1>
		<?php
		$files = scandir($uploadFolder);
		$files = array_diff($files, array('.', '..'));

		if (empty($files)) {
			echo 'No files found.';
		} else {
			echo '<ul>';
			foreach ($files as $file) {
				echo '<li>' . $file . '</li>';
			}
			echo '</ul>';
		}
		?>
	</body>
</html>
