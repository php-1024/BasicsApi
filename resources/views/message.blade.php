<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>{{$message}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link href="{{asset('assets/plugins')}}/sweetalert/css/sweetalert.css" rel="stylesheet"/>
</head>
<body>
<script src="{{asset('assets/plugins')}}/sweetalert/js/sweetalert.min.js"></script>
{{--error--}}
<script>
    swal({
        title: "提示信息",
        text: "{{$message}}",
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "返回"
    }, function () {
        window.location.href = "{{config('app.hi_fans_url')}}/app/#/plan/officeAccount";
    });
</script>
</body>
</html>



