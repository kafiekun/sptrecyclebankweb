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
        <title>เลือกประเภทขยะ</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <style>
        body.swal2-shown > [aria-hidden="true"] {
            transition: 0.5s filter;
            filter: blur(10px);
            }
    </style>
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
                window.location.href = "logout";
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
                window.location.href = "logout";
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
                window.location.href = "logout";
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
                window.location.href = "logout";
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
            case "general" :
                chosen_message = general_message;
                break;
            case "plastic" :
                chosen_message = plastic_message;
                break;
            case "bottle" :
                chosen_message = bottle_message;
                break;
            case "can" :
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
                    case "general" :
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
                    case "plastic" :
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
                    case "bottle" :
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
                    case "can" :
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
            <div class=" justify-evenly flex flex-row flex-wrap object-left px-6 py-12" id="hero-con">      
                <div class=" relative w-max flex flex-col max-w-screen-sm items-center justify-center">
                <button onclick="openAlert(this.id);" id="general">
                    <div class=" shadow-lg shadow-sky-500/50 rounded-lg bg-gradient-to-r animate-text from-blue-500 via-purple-500 to-blue-500 p-[0.1rem] group">
                        <div class="flex h-full w-full items-center justify-center bg-white rounded-lg relative ">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-10 pl-4">
                        <path fill-rule="evenodd" d="M4.72 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 010-1.06zm6 0a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                            <h2 class="font-Kanit font-thin text-2xl  animate-text leading-tight text-black rounded-lg p-6 group-hover:bg-gradient-to-r transition-all ease-in duration-200 group-hover:from-blue-600 group-hover:via-pink-600 group-hover:to-blue-600 group-hover:text-transparent group-hover:bg-clip-text">ขยะทั่วไป</h2>
                            <span class="flex absolute h-8 w-8 top-[-0.5rem] -right-2 -mt-2 -mr-1">
                                <span class=" group-hover:animate-ping absolute flex h-full w-full rounded-full bg-emerald-400 group-hover:bg-sky-500 opacity-75 transition-all ease-linear duration-200"></span>
                                <span class="relative flex rounded-full h-8 w-8 bg-green-500 group-hover:bg-sky-500 transition-all ease-linear duration-200"></span>
                            </span>
                         </div>
                    </div>
                </button>
                </div>
                <div class=" relative w-max flex flex-col max-w-screen-sm items-center justify-center">
                <button onclick="openAlert(this.id);" id="plastic">
                    <div class=" shadow-lg shadow-sky-500/50 rounded-lg bg-gradient-to-r animate-text from-blue-500 via-purple-500 to-blue-500 p-[0.1rem] group">
                        <div class="flex h-full w-full items-center justify-center bg-white rounded-lg relative ">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-10 pl-4">
                        <path fill-rule="evenodd" d="M4.72 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 010-1.06zm6 0a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                            <h2 class="font-Kanit font-thin text-2xl  animate-text leading-tight text-black rounded-lg p-6 group-hover:bg-gradient-to-r transition-all ease-in duration-200 group-hover:from-blue-600 group-hover:via-pink-600 group-hover:to-blue-600 group-hover:text-transparent group-hover:bg-clip-text">แก้วพลาสติก</h2>
                            <span class="flex absolute h-8 w-8 top-[-0.5rem] -right-2 -mt-2 -mr-1">
                                <span class="group-hover:animate-ping absolute flex h-full w-full rounded-full bg-emerald-400 group-hover:bg-sky-500 opacity-75 transition-all ease-linear duration-200"></span>
                                <span class="relative flex rounded-full h-8 w-8 bg-green-500 group-hover:bg-sky-500 transition-all ease-linear duration-200"></span>
                            </span>
                         </div>
                    </div>
                </button>
                </div>
                <div class=" relative w-max flex flex-col max-w-screen-sm items-center justify-center">
                <button onclick="openAlert(this.id);" id="bottle">
                    <div class=" shadow-lg shadow-sky-500/50 rounded-lg bg-gradient-to-r animate-text from-blue-500 via-purple-500 to-blue-500 p-[0.1rem] group">
                        <div class="flex h-full w-full items-center justify-center bg-white rounded-lg relative ">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-10 pl-4">
                        <path fill-rule="evenodd" d="M4.72 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 010-1.06zm6 0a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>

                            <h2 class="font-Kanit font-thin text-2xl  animate-text leading-tight text-black rounded-lg p-6 group-hover:bg-gradient-to-r transition-all ease-in duration-200 group-hover:from-blue-600 group-hover:via-pink-600 group-hover:to-blue-600 group-hover:text-transparent group-hover:bg-clip-text">ขวดนํ้า</h2>
                            <span class="flex absolute h-8 w-8 top-[-0.5rem] -right-2 -mt-2 -mr-1">
                                <span class="group-hover:animate-ping absolute flex h-full w-full rounded-full bg-emerald-400 group-hover:bg-sky-500 opacity-75 transition-all ease-linear duration-200"></span>
                                <span class="relative flex rounded-full h-8 w-8 bg-green-500 group-hover:bg-sky-500 transition-all ease-linear duration-200"></span>
                            </span>
                         </div>
                    </div>
                </button>
                </div>
                <div class=" relative w-max flex flex-col max-w-screen-sm items-center justify-center">
                <button onclick="openAlert(this.id);" id="can">
                    <div class="shadow-lg shadow-sky-500/50 rounded-lg bg-gradient-to-r animate-text from-blue-500 via-purple-500 to-blue-500 p-[0.1rem] group">
                        <div class="flex h-full w-full items-center justify-center bg-white rounded-lg relative ">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-10 pl-4">
                        <path fill-rule="evenodd" d="M4.72 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 010-1.06zm6 0a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                            <h2 class="font-Kanit font-thin text-2xl  animate-text leading-tight text-black rounded-lg p-6 group-hover:bg-gradient-to-r transition-all ease-in duration-200 group-hover:from-blue-600 group-hover:via-pink-600 group-hover:to-blue-600 group-hover:text-transparent group-hover:bg-clip-text">กระป๋อง</h2>
                            <span class="flex absolute h-8 w-8 top-[-0.5rem] -right-2 -mt-2 -mr-1">
                                <span class="group-hover:animate-ping absolute flex h-full w-full rounded-full bg-emerald-400 group-hover:bg-sky-500 opacity-75 transition-all ease-linear duration-200"></span>
                                <span class="relative flex rounded-full h-8 w-8 bg-green-500 group-hover:bg-sky-500 transition-all ease-linear duration-200"></span>
                            </span>
                         </div>
                    </div>
                </button>
                </div>
            </div>      
            <div class=" fixed bottom-0 left-0 flex w-screen object-left px-6 py-12" id="back-con">      
                <div class="  flex flex-col w-72 max-w-xl">
                <a href="user_data">
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


                        <h2 class="font-Kanit font-thin text-4xl  animate-text leading-tight text-black rounded-lg p-6 group-hover:bg-gradient-to-r transition-all ease-in duration-200 group-hover:from-blue-600 group-hover:via-pink-600 group-hover:to-blue-600 group-hover:text-transparent group-hover:bg-clip-text">ย้อนกลับ</h2>
                         </div>
                    </div>
                </a>
            </div>   
        </section>
        
    </body>
</html>

