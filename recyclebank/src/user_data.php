<?php 
    session_start();
    require 'connect.php';

    if(!isset($_SESSION['rfid']))
    {
        header("Location: login");
    }
    $uid = null;
    if ( !empty($_SESSION['rfid'])) {
        $uid = $_SESSION['rfid'];
    }    
    $pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM st_master where id = ?";
    $sql2 = "SELECT * FROM trans_Virtue_BANK where id_ST = ?";
	$q = $pdo->prepare($sql);
    $q2 = $pdo->prepare($sql2);
	$q->execute(array($uid));
    $q2->execute(array($uid));
	$data = $q->fetch(PDO::FETCH_ASSOC);
    $virtue = $q2->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
        <link rel="icon" type="image/x-icon" href="../favicon.ico">
        <link href="../dist/output.css" rel="stylesheet">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>หน้ารวม</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <script>
        var serverReponseGeneral;
        var serverReponsePlastic;
        var serverReponseBottle;
        var serverReponseCan;

        async function GeneralData() {
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
            
            var raw = JSON.stringify({
            "firstSignalGeneral": true,
            "firstSignalPlastic": false,
            "firstSignalBottle": false,
            "firstSignalCan": false
            });
            
            var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
            };
            const response = await fetch('http://127.0.0.1:5000/receive_data', requestOptions)
            .then(response => response.text())
            .then((text) => {
                console.log(text);
                if (text == "true") {
                    serverReponseGeneral = true;
                } else if(text == "false") {
                    serverReponseGeneral = false;
                } else {
                    console.log("invalid response!");
                }
            });
            if (serverReponseGeneral == true) {
                window.location.href = "logout?logout=1";
            } else if (serverReponseGeneral == false) {
                Swal.close();
            }
        }
        async function PlasticData() {
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
            
            var raw = JSON.stringify({
            "firstSignalGeneral": false,
            "firstSignalPlastic": true,
            "firstSignalBottle": false,
            "firstSignalCan": false
            });
            
            var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
            };
            const response = await fetch('http://127.0.0.1:5000/receive_data', requestOptions)
            .then(response => response.text())
            .then((text) => {
                if (text == "true") {
                    serverReponsePlastic = true;
                } else if(text == "false") {
                    serverReponsePlastic = false;
                } else {
                    console.log("invalid response!");
                }
            });
            if (serverReponsePlastic == true) {
                window.location.href = "logout?logout=1";
            } else if (serverReponsePlastic == false) {
                Swal.close();
            }
        }
        async function BottleData() {
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
            
            var raw = JSON.stringify({
            "firstSignalGeneral": false,
            "firstSignalPlastic": false,
            "firstSignalBottle": true,
            "firstSignalCan": false
            });
            
            var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
            };
            const response = await fetch('http://127.0.0.1:5000/receive_data', requestOptions)
            .then(response => response.text())
            .then((text) => {
                if (text == "true") {
                    serverReponseBottle = true;
                } else if(text == "false") {
                    serverReponseBottle = false;
                } else {
                    console.log("invalid response!");
                }
            });
            if (serverReponseBottle == true) {
                window.location.href = "logout?logout=1";
            } else if (serverReponseBottle == false) {
                Swal.close();
            }
        }
        async function CanData() {
            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
            
            var raw = JSON.stringify({
            "firstSignalGeneral": false,
            "firstSignalPlastic": false,
            "firstSignalBottle": false,
            "firstSignalCan": true
            });
            
            var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
            };
            const response = await fetch('http://127.0.0.1:5000/receive_data', requestOptions)
            .then(response => response.text())
            .then((text) => {
                if (text == "true") {
                    serverReponseCan = true;
                } else if(text == "false") {
                    serverReponseCan = false;
                } else {
                    console.log("invalid response");
                }
            });
            if (serverReponseCan == true) {
                window.location.href = "logout?logout=1";
            } else if (serverReponseCan == false) {
                Swal.close();
            }   
        }
    </script>
    <script>
    var general_message = "ขยะทั่วไป";
    var plastic_message = "แก้วพลาสติก";
    var bottle_message = "ขวดนํ้า";
    var can_message = "กระป๋อง";
    var general = document.getElementById("general");
    var plastic = document.getElementById("plastic");
    var bottle = document.getElementById("bottle");
    var can = document.getElementById("can");
    var chosen_message = null;

    function openAlert(clicked_id) {
        console.log(clicked_id);
        switch(clicked_id) {
            case "1" :
                chosen_message = general_message;
                break;
            case "3" :
                chosen_message = plastic_message;
                break;
            case "5" :
                chosen_message = bottle_message;
                break;
            case "2" :
                chosen_message = can_message;
                break;
            }
        
        Swal.fire({
            title: 'ประเภทขยะที่ท่านเลือกคือ\n' + chosen_message + ' ใช่หรือไม่?',
            showDenyButton: true,
            confirmButtonText: 'ยืนยัน',
            denyButtonText: `ยกเลิก`,
        }).then((result) => {
            if (result.isConfirmed) {
                switch(clicked_id) {
                    case "1" :
                        Swal.fire({
                            title: 'โปรดนำขยะไปทิ้งที่ถังขยะหมายเลข 4',
                            html: 'หน้าต่างนี้จะปิดลงเองใน <b></b> วินาที',
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 30000,
                            timerProgressBar: true,
                            didOpen: () => {
                                const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                    b.textContent = (Swal.getTimerLeft() / 1000).toFixed()
                                }, 100)
                                GeneralData();
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        }).then(function() {
                            timerLeft = Swal.getTimerLeft();
                            if (timerLeft >= 1) {
                                Swal.close();
                            } else {
                                window.location.href = "logout";  
                            }
                        });
                        break;
                    case "3" :
                        Swal.fire({
                            title: 'โปรดนำขยะไปทิ้งที่ถังขยะหมายเลข 3',
                            html: 'หน้าต่างนี้จะปิดลงเองใน <b></b> วินาที',
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: true,
                            timer: 30000,
                            timerProgressBar: true,
                            didOpen: () => {
                                const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                    b.textContent = (Swal.getTimerLeft() / 1000).toFixed()
                                }, 100)
                                PlasticData();
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        }).then(function() {
                            timerLeft = Swal.getTimerLeft();
                            if (timerLeft >= 1) {
                                Swal.close();
                            } else {
                                window.location.href = "logout";  
                            }
                        });
                        break;
                        break;
                    case "5" :
                        Swal.fire({
                            title: 'โปรดนำขยะไปทิ้งที่ถังขยะหมายเลข 1',
                            html: 'หน้าต่างนี้จะปิดลงเองใน <b></b> วินาที',
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: true,
                            timer: 30000,
                            timerProgressBar: true,
                            didOpen: () => {
                                const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                    b.textContent = (Swal.getTimerLeft() / 1000).toFixed()
                                }, 100)
                                BottleData();
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        }).then(function() {
                            timerLeft = Swal.getTimerLeft();
                            if (timerLeft >= 1) {
                                Swal.close();
                            } else {
                                window.location.href = "logout";  
                            }
                        });
                        break;
                    case "2" :
                        Swal.fire({
                            title: 'โปรดนำขยะไปทิ้งที่ถังขยะหมายเลข 2',
                            html: 'หน้าต่างนี้จะปิดลงเองใน <b></b> วินาที',
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: true,
                            timer: 30000,
                            timerProgressBar: true,
                            didOpen: () => {
                                const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                    b.textContent = (Swal.getTimerLeft() / 1000).toFixed()
                                }, 100)
                                CanData();
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        }).then(function() {
                            timerLeft = Swal.getTimerLeft();
                            if (timerLeft >= 1) {
                                Swal.close();
                            } else {
                                window.location.href = "logout";  
                            }
                        });
                        break;
                }
            }
        })
    }
    </script>
    <script>
        let counter = 0;
        let counterTimeout = 0;
         
        function fetchData() {
            if (counter < 5) {
                counter++;
                $.get("http://127.0.0.1:5000/check_update_endpoint", function(data) {
                    var beforeString = data;
                    var asString = beforeString.toString();
                    console.log("Received Data:", asString);
                    if (!Swal.isVisible() && asString !== "4" && asString !== "0" && counter < 4) {
                        openAlert(asString);
                    }
                }).fail(function() {
                    if (counterTimeout < 5) {
                        console.log("Error retrieving data. Retrying in 1 second...");
                        setTimeout(fetchData, 1000);
                        counterTimeout++;
                    } else {
                        console.log("Stopped retrying after 5 attempts");
                    }
                }).always(function() {
                    // Call fetchData again after the request is complete (success, error, or both)
                    setTimeout(fetchData, 2000);
                });
            }
            counter = 0;
            counterTimeout = 0;
        }
         
        function runningTime() {
            fetchData();
         
            setTimeout(function() {
                console.log("3 seconds have passed");
                // Stop fetchData after 3 seconds
                counter = 5;
            }, 3000);
            counter = 0;
        }
    </script>
    
    <body class=" overflow-x-hidden ">  
        <section style="background-image: url('https://www.spts.ac.th/main/wp-content/uploads/2022/01/build_spt.png');" class="mix-blend-overlay absolute bg-no-repeat bg-bottom bg-contain min-h-screen min-w-full">  
            <div class="bg-gradient-to-t from-sky-300 h-full w-full absolute -z-10 mix-blend-overlay">
            </div>
        <div id="navigation" class="z-20">      
            <script>
                $.ajaxSetup({ cache: false });
                $.get("navibar.php", 
                function(data) { 
                    $("#navigation").replaceWith(data);
                }
                );
            </script> 
        </div>
            <div class=" justify-between flex flex-row flex-wrap object-left px-6 py-12" id="hero-con">     
                <div class="shadow-lg rounded-lg border dark:border backdrop-blur-[2.1px] bg-white/60  p-6 space-y-4 max-w-screen-sm">
                    <h1 class="text-center text-xl sm:text-4xl font-Kanit font-bold sm:tracking-widest text-spts">
                        ข้อมูลผู้ใช้งาน
                    </h1>
                    <div class="flex flex-row">
                        <img class=" w-20 h-20 sm:w-32 sm:h-32 shrink-0 grow-0 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" src="https://sk-soft.in.th/spt_ID/pic_st/<?php echo $data['id'];?>.JPG" alt="รูปประจำตัว">
                        <div class="rounded-lg p-5 flex flex-col leading-snug tracking-wide font-Kanit font-bold">
                            <p type="text" name="nameall" id="nameall" class=" text-spts sm:text-2xl rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5"><?php echo $data['pre']; echo $data['name']; echo("&nbsp"); echo $data['surname'];?></p>
                            <p type="text" name="score" id="score" class=" text-spts sm:text-2xl rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5">คะแนนสะสม : <span class="bg-gradient-to-r animate-text leading-tight from-blue-600 via-pink-600 to-blue-600 text-transparent bg-clip-text"><?php echo $virtue['kanal'];?></span> คะแนน</p>
                        </div>  
                    </div>  
                </div>   
                <div class=" relative flex flex-col max-w-screen-sm items-center justify-center">
                <a href="choose">
                    <div class=" h-32 w-full shadow-lg shadow-sky-500/50 rounded-lg bg-gradient-to-r animate-text from-blue-500 via-purple-500 to-blue-500 p-[0.1rem] group">
                        <div class="flex h-full w-full items-center justify-center bg-white rounded-lg relative ">
                        <!-- hover:bg-gradient-to-r hover:from-blue-600 hover:via-pink-600 hover:to-blue-600 hover:text-transparent hover:bg-clip-text backdrop-blur-lg -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-14 h-14 pl-5">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                        </svg>

                            <h2 class="font-Kanit font-thin text-2xl  animate-text leading-tight text-black rounded-lg p-6 group-hover:bg-gradient-to-r transition-all ease-in duration-200 group-hover:from-blue-600 group-hover:via-pink-600 group-hover:to-blue-600 group-hover:text-transparent group-hover:bg-clip-text">เลือกประเภทขยะด้วยตนเอง</h2>
                            <span class="flex absolute h-8 w-8 top-[-0.5rem] -right-2 -mt-2 -mr-1">
                                <span class="animate-ping absolute flex h-full w-full rounded-full bg-emerald-400 group-hover:bg-sky-500 opacity-75 transition-all ease-linear duration-200"></span>
                                <span class="relative flex rounded-full h-8 w-8 bg-green-500 group-hover:bg-sky-500 transition-all ease-linear duration-200"></span>
                            </span>
                         </div>
                    </div>
                </a>
                </div>
                <div class=" fixed bottom-0 flex w-screen justify-center items-center  px-6 py-12" id="back-con">      
                    <div style="cursor: pointer;" onclick="runningTime();" class="  flex flex-col w-72 max-w-xl">
                            <div class=" shadow-lg shadow-sky-500/50 rounded-lg bg-gradient-to-r animate-text from-blue-500 via-purple-500 to-blue-500 p-[0.1rem] group">
                                <div class="flex h-full w-full items-center justify-center bg-white rounded-lg relative ">
                                <svg class="w-14 h-14" fill="none" stroke="url(#grad1)" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#00d4ff;stop-opacity:1" />
                                    <stop offset="50%" style="stop-color:#090979;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#00d4ff;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                    <h2 class="font-Kanit font-thin text-2xl  animate-text leading-tight text-black rounded-lg p-6 group-hover:bg-gradient-to-r transition-all ease-in duration-200 group-hover:from-blue-600 group-hover:via-pink-600 group-hover:to-blue-600 group-hover:text-transparent group-hover:bg-clip-text">กดเพื่อสแกนขยะ</h2>
                            </div>
                    </div>                             
                </div>          
        </section>
    </body>
</html>

