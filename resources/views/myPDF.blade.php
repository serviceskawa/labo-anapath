<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>REPORT</title>
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
            /* background: url('images/separateur.png') repeat-x bottom;
            display: flex; */
            justify-content: space-between;
            align-items: flex-end;
            text-align: right;
        }

        header h1 {
            font-family: 'BallparkWeiner', serif;
            font-size: 2.5em;
            font-weight: normal;
            margin: 0 0 0 10px;
        }

        header h2 {
            font-family: Dayrom, serif;
            font-size: 1.1em;
            margin-top: 0px;
            font-weight: normal;
        }

        /* Navigation */

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

        article,
        aside {
            text-align: justify;
        }

        article {
            margin-right: 20px;
            flex: 3;
        }

        .ico_categorie {
            vertical-align: middle;
            margin-right: 8px;
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

        /* Footer */

        footer {
            display: flex;
            padding-top: 25px;
        }

        footer p,
        footer ul {
            font-size: 0.8em;
        }

        footer h1 {
            font-size: 1.1em;
        }


        #tweet {
            width: 33%;
        }

        #mes_photos {
            width: 33%;
        }

        #mes_amis {
            width: 33%;
        }

        #mes_photos img {
            border: 1px solid #181818;
            /* margin-right: 2px; */
        }

        #listes_amis {
            display: flex;
            justify-content: space-between;
            margin-top: 0;
        }

        /* #mes_amis ul {
            list-style-image: url('images/ico_liensexterne.png');
            padding-left: 2px;
        }

        #mes_amis a {
            text-decoration: none;
            color: #760001;
        } */
    </style>
</head>

<body>
    <div id="bloc_page">
        <header>

            <nav>
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </nav>
        </header>

        <section>
            <article>
                <h1>Je suis un grand
                    voyageur</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam nec sagittis massa. Nulla facilisi.
                    Cras id arcu lorem, et semper purus. Cum sociis natoque penatibus et magnis dis parturient montes,
                    nascetur ridiculus mus. Duis vel enim mi, in lobortis sem. Vestibulum luctus elit eu libero ultrices
                    id fermentum sem sagittis. Nulla imperdiet mauris sed sapien dignissim id aliquam est aliquam.
                    Maecenas non odio ipsum, a elementum nisi. Mauris non erat eu erat placerat convallis. Mauris in
                    pretium urna. Cras laoreet molestie odio, consequat consequat velit commodo eu. Integer vitae lectus
                    ac nunc posuere pellentesque non at eros. Suspendisse non lectus lorem.</p>
                <p>Vivamus sed libero nec mauris pulvinar facilisis ut non sem. Quisque mollis ullamcorper diam vel
                    faucibus. Vestibulum sollicitudin facilisis feugiat. Nulla euismod sodales hendrerit. Donec quis
                    orci arcu. Vivamus fermentum magna a erat ullamcorper dignissim pretium nunc aliquam. Aenean
                    pulvinar condimentum enim a dignissim. Vivamus sit amet lectus at ante adipiscing adipiscing eget
                    vitae felis. In at fringilla est. Cras id velit ut magna rutrum commodo. Etiam ut scelerisque purus.
                    Duis risus elit, venenatis vel rutrum in, imperdiet in quam. Sed vestibulum, libero ut bibendum
                    consectetur, eros ipsum ultrices nisl, in rutrum diam augue non tortor. Fusce nec massa et risus
                    dapibus aliquam vitae nec diam.</p>
                <p>Phasellus ligula massa, congue ac vulputate non, dignissim at augue. Sed auctor fringilla quam quis
                    porttitor. Praesent vitae dignissim magna. Pellentesque quis sem purus, vel elementum mi.
                    Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                    Maecenas consectetur euismod urna. In hac habitasse platea dictumst. Quisque tincidunt porttitor
                    vestibulum. Ut iaculis, lacus at molestie lacinia, ipsum mi adipiscing ligula, vel mollis sem risus
                    eu lectus. Nunc elit quam, rutrum ut dignissim sit amet, egestas at sem.</p>
            </article>

        </section>

        <footer>
            <div id="tweet">
                <h1>Mes photos</h1>
                <p><img src="{{ asset('adminassets/images/bg-auth.jpg') }}" alt="Photographie" /></p>
            </div>
            <div id="mes_photos">
                <h1>Mes photos</h1>
                <p><img src="{{ asset('adminassets/images/bg-auth.jpg') }}" alt="Photographie" /></p>
            </div>
            <div id="mes_amis">
                <h1>Mes photos</h1>
                <p><img src="{{ asset('adminassets/images/bg-auth.jpg') }}" alt="Photographie" /></p>
            </div>
        </footer>
    </div>
</body>

</html>
