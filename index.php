<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PSO</title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.js"></script>
    <style>
        * {
            padding: 0;
            margin: 0;
            vertical-align: top;
        }

        body {
            font: 18px/1.5em "proxima-nova", Helvetica, Arial, sans-serif;
        }

        a {
            color: #069;
        }

        a:hover {
            color: #28b;
        }

        h2 {
            margin-top: 15px;
            font: normal 32px "omnes-pro", Helvetica, Arial, sans-serif;
        }

        h3 {
            margin-left: 30px;
            font: normal 26px "omnes-pro", Helvetica, Arial, sans-serif;
            color: #666;
        }

        p {
            margin-top: 10px;
        }

        button {
            font-size: 18px;
            padding: 1px 7px;
        }

        input {
            font-size: 18px;
        }

        input[type=checkbox] {
            margin: 7px;
        }

        #header {
            position: relative;
            width: 900px;
            margin: auto;
        }

        #header h2 {
            margin-left: 10px;
            vertical-align: middle;
            font-size: 42px;
            font-weight: bold;
            text-decoration: none;
            color: #000;
        }

        #content {
            width: 880px;
            margin: 0 auto;
            padding: 10px;
        }

        #footer {
            margin-top: 25px;
            margin-bottom: 10px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        .demo-container {
            box-sizing: border-box;
            width: 850px;
            height: 450px;
            padding: 20px 15px 15px 15px;
            margin: 15px auto 30px auto;
            border: 1px solid #ddd;
            background: #fff;
            background: linear-gradient(#f6f6f6 0, #fff 50px);
            background: -o-linear-gradient(#f6f6f6 0, #fff 50px);
            background: -ms-linear-gradient(#f6f6f6 0, #fff 50px);
            background: -moz-linear-gradient(#f6f6f6 0, #fff 50px);
            background: -webkit-linear-gradient(#f6f6f6 0, #fff 50px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            -o-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            -ms-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            -webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .demo-placeholder {
            width: 100%;
            height: 100%;
            font-size: 14px;
            line-height: 1.2em;
        }

        .legend table {
            border-spacing: 5px;
        }
    </style>
    <script type="text/javascript">

        $(function () {

            var data = <?php
                require_once "PSO.php";
                $swarmMember = 7;//Küme eleman sayısı
                $swarmSize = 15;//Oluşturulacak küme sayısı
                $w = 0.6;///eylemsizlik katsayısı
                $c1 = 2;//Bilişsel katsayı
                $c2 = 2;//Sosyal katsayı
                $iteration = 1000;//Iterasyon katsayı
                $pso = new PSO($swarmMember, $swarmSize, $w, $c1, $c2, $iteration);
                echo $pso->calculate();?>;

            console.log(data);
            var dataFixed = [];
            for (var i = 0; i < data.length; i++) {
                dataFixed.push([i, data[i]])
            }
            $.plot("#placeholder", [dataFixed]);

        });

    </script>
</head>
<body>

<div id="header">
    <h2>PSO <span style="font-size: 14px;">f(x) = cos(0.5 * x) * sin(5.5 * x)</span></h2>
</div>

<div id="content">

    <div class="demo-container">
        <div id="placeholder" class="demo-placeholder"></div>
    </div>

</div>
</body>
</html>
