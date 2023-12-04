<?php
    session_start();
    include('connect.php'); 
    if(isset($_SESSION['rfid']))
    {
        header("Location: user_data");
    }
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
        <link rel="icon" type="image/x-icon" href="../favicon.ico">
        <link href="../dist/output.css" rel="stylesheet">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ล็อกอิน</title>
    </head>
    <script>
        $('#rfid').on('blur', function() {
            var blurEL = $(this);
            setTimeout(function() {
                blurEL.focus()
            }, 10);
        });
    </script>
    <body class="">
    <form class="absolute space-y-4 md:space-y-6 mt-3" action="login process.php" method="POST">
        <div>
            <input onblur="this.focus()" name="rfid" id="rfid" class="opacity-0 bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5 " autofocus maxlength="10"></input>
        </div>
        <div class="flex justify-center">
            <button type="submit" name="login_user" id="login_user" class=" hidden  w-6/12 h-12 text-white rounded-full from-rose-400 via-fuchsia-500 to-indigo-500 bg-gradient-to-r">
                <span class=" flex m-[0.1em] place-items-center h-11 justify-center rounded-full bg-white ">
                    <p class="text-black font-bold ">Submit<p>
                </span>
            </button> 
        </div>
    </form>
    <section style="background-image: url('https://www.spts.ac.th/main/wp-content/uploads/2022/01/build_spt.png');" class=" mix-blend-overlay absolute bg-no-repeat bg-bottom bg-contain min-h-screen w-full">  
        <div class="bg-gradient-to-t from-sky-300 h-full w-full absolute -z-10  mix-blend-overlay">
            </div>
            <div class="flex flex-col items-center justify-center px-6 py-16 w-full mx-auto md:h-screen lg:py-0">
                    <div class="min-h-fit backdrop-blur-sm bg-white/30 rounded-lg shadow dark:border md:mt-0 xl:p-0">
                    <div class="p-6 space-y-4 md:space-y- sm:p-8">
                        <img class=" h-20 lg:h-22 m-auto pr-5" src="https://www.spts.ac.th/main/wp-content/uploads/2022/08/logohead.png">
                        <h1 class="text-6xl font-Kanit p-4 text-center font-bold tracking-tighter animate-text leading-tight from-blue-600 via-pink-600 to-blue-600 bg-gradient-to-r bg-clip-text text-transparent">
                            โปรดสแกนบัตร!
                        </h1>
                        <?php if (isset($_SESSION['error'])) : ?>
                            <div class="error">
                                    <h3 class=" text-red-800 text-center font-bold">
                                    <?php 
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                    ?>
                                    </h3>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
