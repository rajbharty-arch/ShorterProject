<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>

    <!-- Bootstrap CSS -->
   
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    
    <link href="https://cdn.datatables.net/v/dt/dt-2.3.8/datatables.min.css" rel="stylesheet" integrity="sha384-1BvCnyKidMPIGQGjvMn+w+90hHBhYJtF+R7os4NX2Abe7tSxWQadHRTSH5qb559A" crossorigin="anonymous">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>
<body>
    @include('navbar')

@yield('content')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/v/dt/dt-2.3.8/datatables.min.js" integrity="sha384-kjkli48Tmhwhaghq0IIRm8gFmMdnihfu1ywAOyyGWYMsoZZi/6AX2fWzpsqahoFw" crossorigin="anonymous"></script>

@yield('script')

</body>
</html>


 
