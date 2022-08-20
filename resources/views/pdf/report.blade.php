<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>rEPORT</title>
    <style>
        body {
            font-family: 'Trebuchet MS', Arial, sans-serif;
            color: #181818;
        }

        #bloc_page {
            margin: auto;
        }

        section h1,
        footer h1,
        nav a {
            font-family: Dayrom, serif;
            font-weight: normal;
        }

        /* Header */

        header {
            justify-content: space-between;
            align-items: flex-end;
            text-align: right;
            margin-top: 50px;
        }

        nav ul {
            list-style-type: none;
            /* display: flex; */
        }

        nav li {
            margin-right: 15px;
        }

        nav a {
            font-size: 1.3em;
            color: #181818;
            padding-bottom: 3px;
            text-decoration: none;
        }

        /* Corps */

        section {
            display: flex;
            margin-bottom: 20px;
        }

        article {
            text-align: justify;
        }

        article {
            margin-right: 20px;
            flex: 3;
        }

        article p {
            font-size: 0.8em;
        }

        aside {
            flex: 1.2;
            position: relative;
            background-color: #706b64;
            box-shadow: 0px 2px 5px #1c1a19;
            border-radius: 5px;
            padding: 10px;
            color: white;
            font-size: 0.9em;
        }

        footer {
            display: flex;
            padding-top: 25px;
        }

        footer h1 {
            font-size: 1.1em;
        }
    </style>
</head>

<body>
    <div id="bloc_page">
        <header>
            <nav>
                <ul>
                    <li><a href="#">{{ $patient }}</a></li>
                    {{-- <li><a href="#">Blog</a></li> --}}
                </ul>
            </nav>
        </header>

        <section>
            <article>
                <h1>{{ $title }}</h1>
                <p>{{ $content }}</p>
            </article>

        </section>

        <footer>
            <div>
                <h1>{{ $signatory1 }}</h1>
                <p><img src="{{ storage_path('app/public/' . $signature1) }}" alt="" srcset=""></p>
            </div>
        </footer>
    </div>
</body>

</html>
