<html>

<head>
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
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>
        <span class="header">EasyTravel</span>
        <span class="date"><?php echo date('d-M-Y H:m:i'); ?></span>
        <HR>
        <HR>
    </header>

    <footer>
        Copyright &copy; <?php echo date('Y-M-d'); ?>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <h3 class="center">Rapport</h3>
        <p class="center">Période du </p>
        <!-- <h2 class="center">Lorem</h2> -->

    </main>
</body>

</html>
