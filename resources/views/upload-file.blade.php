<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Upload Multiple Files in Laravel 8 with Coding Driver</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />


<style>
.invalid-feedback {
  display: block;
}
</style>
</head>
<body>

<div class="container mt-4">
  <h2>Upload Multiple Files in Laravel 8 with- <a href="https://codingdriver.com/">codingdriver.com</a></h2>
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <form method="post" action="/api/menuItem/uploadMedia/15" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
          <input type="file" name="file"  class="form-control" />
         
      </div>

      <div class="form-group">
        <button type="submit" name="submit"  class="btn btn-success">Save</button>
      </div>
    </form>
</div>
</body>
</html>