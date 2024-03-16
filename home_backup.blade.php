<!-- <!DOCTYPE html>
<html>
<head>
    <title>Excel Query Tool</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Excel Query Tool</h1>
        <form action="{{ route('execute.query') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="question">Stel uw vraag:</label>
                <input type="text" class="form-control" id="question" name="question">
            </div>
            <button type="submit" class="btn btn-primary">Verzenden</button>
        </form>
        <div class="mt-3 result">
            @if(session('sqlQuery'))
                <h3>Resultaten:</h3>
                <p>SQL-query: {{ session('sqlQuery') }}</p>
            @endif
            @if(session('eloquentQuery'))
                <p>Eloquent-query: {{ session('eloquentQuery') }}</p>
            @endif
            @if(session('sql_json'))
                <p>sql_json: {{ session('sql_json') }}</p>
            @endif
            @if(session('sqltabel'))
                <h3>Tabel resultaat:</h3>
                {!! session('sqltabel') !!}
            @endif           
            @if(session('sql_uitleg'))
                <p>sql_uitleg: {{ session('sql_uitleg') }}</p>
            @endif     
            @if(session('sql_normale_zin'))
                <p>sql_json: {{ session('sql_normale_zin') }}</p>
            @endif                                                
        </div>
    </div>
</body>
</html> -->

<!-- <!DOCTYPE html>
<html>
<head>
    <title>Excel Query Tool</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        html,body{
            height:100%;
        }

        #wrapper{
            position:relative;
            /*background:#333;*/
            height:100%;
        }

        .profile-main-loader{
            left: 50% !important;
            margin-left:-100px;
            position: fixed !important;
            top: 50% !important;
            margin-top: -100px;
            width: 45px;
            z-index: 9000 !important;
            display: none; /* De loader is standaard verborgen */
        }

        .profile-main-loader .loader {
          position: relative;
          margin: 0px auto;
          width: 200px;
          height:200px;
        }
        .profile-main-loader .loader:before {
          content: '';
          display: block;
          padding-top: 100%;
        }

        .circular-loader {
          -webkit-animation: rotate 2s linear infinite;
                  animation: rotate 2s linear infinite;
          height: 100%;
          -webkit-transform-origin: center center;
              -ms-transform-origin: center center;
                  transform-origin: center center;
          width: 100%;
          position: absolute;
          top: 0;
          left: 0;
          margin: auto;
        }

        .loader-path {
          stroke-dasharray: 150,200;
          stroke-dashoffset: -10;
          -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
                  animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
          stroke-linecap: round;
        }

        @-webkit-keyframes rotate {
          100% {
            -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
          }
        }

        @keyframes rotate {
          100% {
            -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
          }
        }
        @-webkit-keyframes dash {
          0% {
            stroke-dasharray: 1,200;
            stroke-dashoffset: 0;
          }
          50% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -35;
          }
          100% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -124;
          }
        }
        @keyframes dash {
          0% {
            stroke-dasharray: 1,200;
            stroke-dashoffset: 0;
          }
          50% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -35;
          }
          100% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -124;
          }
        }
        @-webkit-keyframes color {
          0% {
            stroke: #70c542;
          }
          40% {
            stroke: #70c542;
          }
          66% {
            stroke: #70c542;
          }
          80%, 90% {
            stroke: #70c542;
          }
        }
        @keyframes color {
          0% {
            stroke: #70c542;
          }
          40% {
            stroke: #70c542;
          }
          66% {
            stroke: #70c542;
          }
          80%, 90% {
            stroke: #70c542;
          }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div class="container mt-5">
            <h1>Excel Query Tool</h1>
            <form id="query-form" action="{{ route('execute.query') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="question">Stel uw vraag:</label>
                    <input type="text" class="form-control" id="question" name="question">
                </div>
                <button type="submit" class="btn btn-primary" id="submit-btn">Verzenden</button>
            </form>
            <div class="mt-3 result">
                <div class="profile-main-loader">
                    <div class="loader">
                        <svg class="circular-loader" viewBox="25 25 50 50" >
                            <circle class="loader-path" cx="50" cy="50" r="20" fill="none" stroke="#70c542" stroke-width="2" />
                        </svg>
                    </div>
                </div>

                @if(session('sqlQuery'))
                    <h3>Resultaten:</h3>
                    <p>SQL-query: {{ session('sqlQuery') }}</p>
                @endif
                @if(session('eloquentQuery'))
                    <p>Eloquent-query: {{ session('eloquentQuery') }}</p>
                @endif
                @if(session('sql_json'))
                    <p>sql_json: {{ session('sql_json') }}</p>
                @endif
                @if(session('sqltabel'))
                    <h3>Tabel resultaat:</h3>
                    {!! session('sqltabel') !!}
                @endif           
                @if(session('sql_uitleg'))
                    <p>sql_uitleg: {{ session('sql_uitleg') }}</p>
                @endif     
                @if(session('sql_normale_zin'))
                    <p>sql_json: {{ session('sql_normale_zin') }}</p>
                @endif                                                
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) { 
            document.getElementById("query-form").addEventListener("submit", function() {
                // Toon de loader bij verzenden
                document.querySelector('.profile-main-loader').style.display = 'block';
            });
        });
    </script>

</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
    <title>Excel Query Tool</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        html,body{
            height:100%;
        }

        #wrapper{
            position:relative;
            height:100%;
        }

        .profile-main-loader{
            left: 50% !important;
            margin-left:-100px;
            position: fixed !important;
            top: 50% !important;
            margin-top: -100px;
            width: 45px;
            z-index: 9000 !important;
            display: none; /* De loader is standaard verborgen */
        }

        .profile-main-loader .loader {
          position: relative;
          margin: 0px auto;
          width: 200px;
          height:200px;
        }
        .profile-main-loader .loader:before {
          content: '';
          display: block;
          padding-top: 100%;
        }

        .circular-loader {
          -webkit-animation: rotate 2s linear infinite;
                  animation: rotate 2s linear infinite;
          height: 100%;
          -webkit-transform-origin: center center;
              -ms-transform-origin: center center;
                  transform-origin: center center;
          width: 100%;
          position: absolute;
          top: 0;
          left: 0;
          margin: auto;
        }

        .loader-path {
          stroke-dasharray: 150,200;
          stroke-dashoffset: -10;
          -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
                  animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
          stroke-linecap: round;
        }

        @-webkit-keyframes rotate {
          100% {
            -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
          }
        }

        @keyframes rotate {
          100% {
            -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
          }
        }
        @-webkit-keyframes dash {
          0% {
            stroke-dasharray: 1,200;
            stroke-dashoffset: 0;
          }
          50% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -35;
          }
          100% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -124;
          }
        }
        @keyframes dash {
          0% {
            stroke-dasharray: 1,200;
            stroke-dashoffset: 0;
          }
          50% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -35;
          }
          100% {
            stroke-dasharray: 89,200;
            stroke-dashoffset: -124;
          }
        }
        @-webkit-keyframes color {
          0% {
            stroke: #70c542;
          }
          40% {
            stroke: #70c542;
          }
          66% {
            stroke: #70c542;
          }
          80%, 90% {
            stroke: #70c542;
          }
        }
        @keyframes color {
          0% {
            stroke: #70c542;
          }
          40% {
            stroke: #70c542;
          }
          66% {
            stroke: #70c542;
          }
          80%, 90% {
            stroke: #70c542;
          }
        }

        /* Voeg ruimte toe aan de rechterkant van de container */
        .container {
            padding-right: 20px;
        }

        /* Kopieerknop stijl */
        .copy-button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div class="container mt-5">
            <h1>Excel Query Tool</h1>
            <form id="query-form" action="{{ route('execute.query') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="question">Stel uw vraag:</label>
                    <input type="text" class="form-control" id="question" name="question">
                </div>
                <button type="submit" class="btn btn-primary" id="submit-btn">Verzenden</button>
            </form>
            <div class="mt-3 result">
                <div class="profile-main-loader">
                    <div class="loader">
                        <svg class="circular-loader" viewBox="25 25 50 50" >
                            <circle class="loader-path" cx="50" cy="50" r="20" fill="none" stroke="#70c542" stroke-width="2" />
                        </svg>
                    </div>
                </div>

                @if(session('sqlQuery'))
                    <h3>Resultaten:</h3>
                    <p>SQL-query: {{ session('sqlQuery') }}</p>
                    <button class="btn btn-primary copy-button" onclick="copyText('{{ session('sqlQuery') }}')">Kopieer tekst</button>
                @endif
                @if(session('eloquentQuery'))
                    <p>Eloquent-query: {{ session('eloquentQuery') }}</p>
                    <button class="btn btn-primary copy-button" onclick="copyText('{{ session('eloquentQuery') }}')">Kopieer tekst</button>
                @endif
                @if(session('sql_json'))
                    <p>sql_json: {{ session('sql_json') }}</p>
                    <button class="btn btn-primary copy-button" onclick="copyText('{{ session('sql_json') }}')">Kopieer tekst</button>
                @endif
                @if(session('sqltabel'))
                    <h3>Tabel resultaat:</h3>
                    {!! session('sqltabel') !!}
                    <button class="btn btn-primary copy-button" onclick="copyText('{{ session('sqltabel') }}')">Kopieer tekst</button>
                @endif           
                @if(session('sql_uitleg'))
                    <p>sql_uitleg: {{ session('sql_uitleg') }}</p>
                    <button class="btn btn-primary copy-button" onclick="copyText('{{ session('sql_uitleg') }}')">Kopieer tekst</button>
                @endif     
                @if(session('sql_normale_zin'))
                    <p>sql_json: {{ session('sql_normale_zin') }}</p>
                    <button class="btn btn-primary copy-button" onclick="copyText('{{ session('sql_normale_zin') }}')">Kopieer tekst</button>
                @endif                                                
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) { 
            document.getElementById("query-form").addEventListener("submit", function() {
                // Toon de loader bij verzenden
                document.querySelector('.profile-main-loader').style.display = 'block';
            });
        });

        function copyText(text) {
            var tempInput = document.createElement("textarea");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); /* For mobile devices */
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Tekst gekopieerd naar klembord: " + text);
        }
    </script>

</body>
</html>

<!-- <!DOCTYPE html>
<html>
<head>
    <title>Excel Query Tool</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Excel Query Tool</h1>
        <form action="{{ route('execute.query') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="question">Stel uw vraag:</label>
                <input type="text" class="form-control" id="question" name="question">
            </div>
            <button type="submit" class="btn btn-primary">Verzenden</button>
        </form>
        <div class="mt-3 result">          
            @if(session('sqlQuery'))
            <!-- <h3>Resultaten:</h3> -->
                <p>Dit zijn de resultaten voor uw zoekvraag: '{{ session('vraag') }}'</p>            
                <p>SQL-query: {{ session('sqlQuery') }}</p>
            @endif
            @if(session('eloquentQuery'))
                <p>Eloquent-query: {{ session('eloquentQuery') }}</p>
            @endif
            @if(session('sql_json'))
                <p>sql_json: {{ session('sql_json') }}</p>
            @endif
            @if(session('sqltabel'))
                <h3>Tabel resultaat:</h3>
                {!! session('sqltabel') !!}
            @endif           
            @if(session('sql_uitleg'))
                <p>sql_uitleg: {{ session('sql_uitleg') }}</p>
            @endif     
            @if(session('error'))
                <p>Dit zijn de resultaten voor uw zoekvraag: '{{ session('vraag') }}'<p>               
                <p>ERROR: {{ session('error') }}</p>
            @endif
            @if(session('error_uitleg'))
                <p>ERROR voor ontwikkelaars: {{ session('error_uitleg') }}</p>
            @endif                                                                                      
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            document.getElementById("query-form").addEventListener("submit", function() {
                // Toon de loader bij verzenden
                document.querySelector('.profile-main-loader').style.display = 'block';
            });
        });

        async function copyText(elementId) {
            const text = document.getElementById(elementId).innerText;
            try {
                await navigator.clipboard.writeText(text);
                alert("Tekst gekopieerd naar klembord");
            } catch (err) {
                console.error("Fout bij kopiëren naar klembord: ", err);
                alert("Er is een fout opgetreden bij het kopiëren naar het klembord.");
            }
        }
    </script>

</body>
</html> -->
