<?php
include(__DIR__.'/Settings/config.php');
include_once(__DIR__ . '/Common/functions.php');
include_once(__DIR__ . '/Common/middleware.php');
$routes = include_once(__DIR__ . '/Settings/routes.php');

//미들웨어를 여기서 실행을 시키고 리턴을 받은게 false면 return; 을 해서 종료를 시킨다.
//return false가 되면 boards 페이지로 보내는게 제일 좋을듯?
if (!(chk())) {
    return;
};


if ($_SERVER['REQUEST_METHOD'] != "GET") :
    run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $routes);
    return;
endif;
?>
<?php
$url = parse_url($_SERVER['REQUEST_URI']);
$url = $url['path'];
if (preg_match('/^\/adminpage(\/.*)?$/', $url)) : ?>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>관리자 페이지</title>
        <link rel='stylesheet' href='/front/css/adminpage.css' type='text/css'>
    </head>

    <body>
        <?php
        require(__DIR__ . '/Settings/dbconfig.php');
        ?>
        <section>
            <aside>
                <ul class="managementList">
                    <li class="asideLI" onclick="onShow('')">Base</li>
                    <li class="asideLI" onclick="onShow('/rolemanagement')">Role</li>
                    <li class="asideLI" onclick="onShow('/usermanagement')">User</li>
                    <li class="asideLI" onclick="onShow('/boardmanagement')">Board</li>
                </ul>
            </aside>
            <article>
                <?php run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $routes); ?>
            </article>
        </section>
        <?php ?>
        <script>
            function onShow(selected) {
                let url = "<?= BASE_URL?>"+"/adminpage"+selected;
                
                window.location.replace(url);
            }
        </script>
    </body>

    </html>
<?php
else :
    if(!session_id()):
    session_start();
    endif;
    
?>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Index</title>
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <link rel='stylesheet' href='/front/css/index.css' type="text/css">
        <link rel='stylesheet' href='/front/css/main_header.css' type="text/css">
        
    </head>

    <body>
        <header>
            <div class="header-nav">
                <h1><a class="home-a" href="<?=BASE_URL?>/boards">HOME</a></h1>
            </div>
            <div class="header-nav">
                <nav>
                    <ul>
                        <?php     
                            if (!(isset($_SESSION["is_loggedin"]) && $_SESSION["is_loggedin"])): ?>
                            <li class="header-li"><a href="<?= BASE_URL?>/loginForm">로그인</a></li>
                            <li class="header-li"><a href="<?= BASE_URL?>/signupform">회원가입</a></li>
                        <?php else : ?>
                            <li class="header-li"><a  href="<?= BASE_URL?>/me">내 정보</a>
                            <li class="header-li"><a id="logout-a" href="#">로그아웃</a>
                        <?php endif; ?>
                        
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <?php run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $routes); ?>
        </main>
        <footer>
            <h1>Footer Area</h1>
        </footer>
        <script>
            const logoutA = document.querySelector("#logout-a");
            if(logoutA){
                logoutA.addEventListener("click",function(e){
                    e.preventDefault();
                    fetch("<?= BASE_URL?>/logout",{
                        method:"POST"
                    })
                    .then(()=>window.location.replace("<?= BASE_URL?>/boards"));
                })
            }
        </script>
    </body>

    </html>
<?php endif; ?>