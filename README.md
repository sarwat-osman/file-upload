
<h1><center>File Uploader App</center></h1>

<h2>Description:</h2> 

<p>A Laravel application that takes an uploaded CSV file from user, reads it and serializes the data in the format shown below.</p>

<h2>Input:</h2>

<p>Go to http://127.0.0.1:8000. Select a CSV file and click Upload button. If the file format is valid, it proceeds to output serialized data. Otherwise, it throws validation error.</p>

<img src="{{asset('uploader.png')}}">

<h2>Sample CSV file:</h2>

<img src="{{asset('sample_csv_file.png')}}">

<h2>Output:</h2>

<img src="{{asset('output.png')}}">


<h2>Versions used:</h2>

PHP 7.4.25
Laravel 8.73.0
FastExcel 3.1.0


In your controller, use the following namespace for the file upload service: 
```php
use App\Services\FileService;
```

Create an instance of the service:
```php
$fileService = new FileService();
```

Pass a valid CSV file as argument to the upload method of the service which returns serialized JSON. Then send it as response:

```php
$serializedOutput = $fileService->upload($file);

return response($serializedOutput, 200)->header('Content-Type', 'application/json');
```
