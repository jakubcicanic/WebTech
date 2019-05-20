<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--=======Font Open Sans======-->
    
    <!--StyleSheet-->
    <link rel="stylesheet" href="logins.css">
</head>
<body>
<div class="forms">
    <ul class="tab-group">
        <li class="tab active"><a href="#login">Log In</a></li>
        <li class="tab"><a href="#signup">Sign Up</a></li>
    </ul>
    <form action="#" id="login">
        <h1>Login on w3iscool</h1>
        <div class="input-field">
            <label for="email">Email</label>
            <input type="email" name="email" required="email" />
            <label for="password">Password</label>
            <input type="password" name="password" required/>
            <input type="submit" value="Login" class="button"/>
            <p class="text-p"> <a href="#">Forgot password?</a> </p>
        </div>
    </form>
    <form action="#" id="signup">
        <h1>Sign Up on w3iscool</h1>
        <div class="input-field">
            <label for="email">Email</label>
            <input type="email" name="email" required="email"/>
            <label for="password">Password</label>
            <input type="password" name="password" required/>
            <label for="password">Confirm Password</label>
            <input type="password" name="password" required/>
            <input type="submit" value="Sign up" class="button" />
        </div>
    </form>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.tab a').on('click', function (e) {
            e.preventDefault();

            $(this).parent().addClass('active');
            $(this).parent().siblings().removeClass('active');

            var href = $(this).attr('href');
            $('.forms > form').hide();
            $(href).fadeIn(500);
        });
    });
</script>
</body>
</html>