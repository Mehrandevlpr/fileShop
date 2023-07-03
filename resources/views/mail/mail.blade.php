<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FileShop</title>

<style>
    #wrapper {
        font-family: sans-serif;
        display: block;
        width: 650px;
        margin: auto;
        border-radius: 5px;
        margin-top: 55px;
        box-shadow: rgba(0, 0, 0, 0.15) 2px 3px 35px 0;
        overflow: hidden;
    }

    #top-img {
        background: linear-gradient(45deg, #07203c 0%, #491c1c 100%);
        color: #fff;
        font-size: 20px;
        font-weight: 100;
        text-align: center;
        padding: 75px 0;
    }

    #top-img b {
        display: block;
        margin-bottom: 12px;
        font-size: 32px;
    }

    #content {
        margin: 40px 25px;
    }

    hr {
        margin: 20px 5px;
        border: none;
        background: linear-gradient(45deg, #07203c 0%, #491c1c 100%);
        height: 1px;
    }

    .btn {
        display: block;
        width: 40%;
        text-align: center;
        margin: auto;
        background-color: #ff1531;
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        font-weight: 100;
        padding: 15px 0;
        border-radius: 45px;
        margin-top: 15px;
    }
</style>
</head>

<body>
<div id="wrapper">
  <div id="top-img">
    <b>File Shop</b>
    Here Your File
  </div>
  <div id="content">
        Dear {{ $user->name }},
        <br />
        Thanks you ! We're excited to help you ...
        <br />
        <br />
        Click on the button below to Download your File.
        <br />
        @foreach($images as $key => $img)
         <a href="{{ asset('storage/app/local_storage/'.$img) }}" target="_blank" class="btn">Download image {{ $key }}</a>
        @endforeach
        <hr />
        Any doubts? have some questions?
        <br />
        Send us an email to <a href="mailto:product@company.name">product@FileShop.name</a> we will happy to help.
        <br />
        <br />
        Have a great [product usage]!
  </div>
</div>
</body>

</html>