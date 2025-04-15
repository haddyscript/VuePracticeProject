<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
</head>
<body>
    <h1>Payment Successful!</h1>
    <p>This tab will close automatically.</p>

    <script>
        setTimeout(function() {
            window.close(); 
        }, 2000);

        setTimeout(function() {
            window.location.href = "https://hadi-store.vercel.app/thankyou"; 
        }, 5000);
    </script>
</body>
</html>
