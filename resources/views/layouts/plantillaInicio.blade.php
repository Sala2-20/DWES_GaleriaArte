<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="../images/arte.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield("cssInicio")

</head>

<body>
    @yield('contentInicio')
    @yield('scriptInicio')

    <footer id="piePagina"
        class="w-100 text-white d-flex flex-row justify-content-center align-items-center pt-5 bg-black">
        <h3 id="contactanos" class="text-uppercase">buscanos</h3>
        <div>
            <img src="{{asset(path: 'images/ig.png')}}" alt="imagen no disponible" class="fotImg">
            <img src="{{asset(path: 'images/x.png')}}" alt="imagen no disponible" class="fotImg">
            <img src="{{asset(path: 'images/f.png')}}" alt="imagen no disponible" class="fotImg"> <br>
            <p xmlns:cc="http://creativecommons.org/ns#" xmlns:dct="http://purl.org/dc/terms/"><a property="dct:title"
                    rel="cc:attributionURL" href="https://github.com/Sala2-20/DWES_GaleriaArte.git">Galeria de arte</a>
                by <a rel="cc:attributionURL dct:creator" property="cc:attributionName"
                    href="https://github.com/Sala2-20">Jorge Atienza,Mario Alcaide,Sergio Cerrada,Hernán Uña</a> is marked with <a
                    href="https://creativecommons.org/publicdomain/zero/1.0/?ref=chooser-v1" target="_blank"
                    rel="license noopener noreferrer" style="display:inline-block;">CC0 1.0<img
                        style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
                        src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1" alt=""><img
                        style="height:22px!important;margin-left:3px;vertical-align:text-bottom;"
                        src="https://mirrors.creativecommons.org/presskit/icons/zero.svg?ref=chooser-v1" alt=""></a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
