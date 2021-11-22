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
