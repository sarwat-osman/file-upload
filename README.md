
<center><h1>File Uploader App</h1></center>

<h2>Description:</h2> 

<p>A Laravel application that takes an uploaded CSV file from user, reads it and serializes the data in the format shown below.</p>

<h2>Input:</h2>

<p>After running the app, go to http://127.0.0.1:8000. Select a CSV file and click Upload button. If the file format is valid, it proceeds to output serialized data. Otherwise, it throws validation error.</p>

<img src="https://github.com/sarwat-osman/file-upload/blob/master/public/uploader.png">

<h2>Sample CSV file:</h2>

<img src="https://github.com/sarwat-osman/file-upload/blob/master/public/sample_csv_file.png">

<h2>Output:</h2>

<img src="https://github.com/sarwat-osman/file-upload/blob/master/public/output.png">


<h2>Versions used:</h2>

PHP 7.4.25<br>
Laravel 8.73.0<br>
FastExcel 3.1.0

<h2>Instructions on how to use the File Upload Service:</h2>

In your controller, use the following namespace for the service: 
```php
use App\Services\FileService;
```

Create an instance of the service where required:
```php
$fileService = new FileService();
```

Pass a valid CSV file as argument to the upload method of the service which returns serialized JSON. Then send it as response:

```php
$serializedOutput = $fileService->upload($file);

return response($serializedOutput, 200)->header('Content-Type', 'application/json');
```
