<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1cm;

            /** Extra personal styles **/
            background-color: #fff;
            color: black;
            /* text-align: left; */
            line-height: 1cm;

            margin-left: 2cm;
            margin-right: 2cm;

        }

        .date {
            float: right;
        }

        .center {
            text-align: center;
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        td,
        th

        /* Mettre une bordure sur les td ET les th */
            {
            border: 1px solid black;
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Extra personal styles **/
            background-color: #fff;
            color: black;
            text-align: center;
            line-height: 1.5cm;
        }

        /*  */
        .flexContainer {

            background-color: #f1f1f1;
        }

        .flex-item-left {
            background-color: #f1f1f1;

        }

        .flex-item-right {
            background-color: dodgerblue;
        }

        .test {
            background-color: DodgerBlue;
            color: white;
        }

        .right {
            float: right;
            text-align: right !important
        }

        .bg_bleu {
            background-color: #212529;
            line-height: 1.5cm;
            color: #fff
        }
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>

    </header>

    <footer>
        Copyright &copy; <?php echo date('Y-M-d'); ?>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <div class="flexContainer">
            <span class="flex-item-left">
                <img src="{{ asset('adminassets/images/CAAP_Logo_2_1.png') }}" alt="">

            </span>
            <span class="right flex-item-right">
                <span>CENTRE ADECHINA ANATOMIE PATHOLOGIE <br></span>
                <span>Laboratoire d’Anatomie Pathologique</span>
            </span>
        </div>

        <div class="bg_bleu">
            <h3 class="center">COMPTE RENDU HISTOPATHOLOGIE</h3>
        </div>

        <div>
            <h3>Informations prélèvement</h3>
            <div class="flexContainer">
                <div>Nom & prenoms</div>
                <div>Thuret</div>
            </div>

        </div>
    </main>
</body>

</html>
