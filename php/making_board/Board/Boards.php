<style>
    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .boardDetail {
        background-color: #f9f9f9;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .boardDetail:hover {
        background-color: #808080;
        cursor: pointer;
    }

    .boardDetail a {
        text-decoration: none;
    }

    .pagination {
        display: flex;
        margin-right: 20px;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination a {
        /* display :inline-block; */
        padding: 5px 10px;
        margin: 0 5px;
        text-decoration: none;
        color: #808080;
    }

    .post-btn {

        margin-bottom: 20px;
        border: 1px solid #f9f9f9;
        cursor: pointer;
        background-color: coral;
        color: #fff;
        /* overflow: hidden; */
        white-space: nowrap;
    }

    .post-btn:hover {
        background-color: #ffab91;
    }

    .currentpage {
        font-weight: bold;
    }
</style>
<!-- 게시글 목록 보여주기-->
<?php
global $connect;
$currentpage = 1; //현재 페이지와 맨 처음 들어왔을 때 보여지는 페이지
$DIVIDENUM = 3; //한 번에 몇 개의 게시글이 보여지는 가

//현재 페이지에 대한 정보를 받지 못하면 첫번째 페이지로
if (isset($_GET["page"])) {
    $currentpage = $_GET["page"];
}


//전체 게시판 개수 조회
try {
    
    $countsql =
        "SELECT COUNT(id) FROM BOARD";
        
    $count_results = $connect->query($countsql);
} catch (PDOException $ex) {
    echo "디비실패 " . $ex->getMessage();
}
$count = $count_results->fetch(PDO::FETCH_COLUMN);


//게시판 글 수를 한번에 보여줘야할 수로 나누어서 maxbar 계산
//전체 게시판 글 수
$MAXBOARD = (int)$count;
//표시되어야 할 barnum의 최대 개수
$MAXNUM = (int)($MAXBOARD / $DIVIDENUM);
//계산에서는 현재 페이지에서 -1 을 뺀 숫자를 이용
$CALNUM = $currentpage - 1;
if ($MAXNUM < $currentpage) {
    $CALNUM = $MAXNUM - 1;
}
if ($CALNUM < 0) {
    $CALNUM = 0;
}
$MINROW = $DIVIDENUM * $CALNUM + 1; //현재 페이지에서 보여줄 게시글의 최소 row수
$MAXROW = $DIVIDENUM * $CALNUM + $DIVIDENUM; //현재 페이지에서 보여줄 게시글의 최대 row수

//해당 페이지 게시글 조회
try {
    
    $viewsql =
        "SELECT id,title 
                FROM (SELECT @ROWN:=@ROWN+1 AS rown,id,title 
                        FROM board,(SELECT @ROWN:=0) TEMP) ROWBOARD 
                WHERE rown>='$MINROW' AND rown<='$MAXROW'";
    //전체 개수 조회 (검색추가 하려면 여기서 IF 로 분기태우자)

    $results = $connect->query($viewsql);
    
} catch (PDOException $ex) {
    echo "디비실패" . $ex->getMessage();
}
?>

<!-- 게시글 목록만들기 -->
<div class="container">
    <?php
    //게시글 목록에서 클릭 시 게시글에 연결

    //세로로 배열필요
    foreach ($results as $result) {
        $baseurl = BASE_URL . "/boards";
        $url = $baseurl . "/" . $result["id"];
    ?>
        <div class="boardDetail">
            <a href=<?php echo $url ?>><?php echo $result["title"] ?></a>
        </div>
    <?php } ?>

    <!-- 이제 페이지 넘기기-->
    <?php
    if (!($MAXBOARD % $DIVIDENUM === 0)) {
        $MAXNUM += 1;
    }
    $prevpage = $currentpage - 1;
    $nextpage = $currentpage + 1;
    if ($nextpage >= $MAXNUM) {
        $nextpage = $MAXNUM;
    } ?>

    <div class="pagination">

        <a href='<?= BASE_URL . "/boards" . "?page=1"; ?>'>
            <<</a>
                <a href="<?= BASE_URL . "/boards" . "?page=" . $prevpage; ?>">
                    <</a>
                        <?php
                        $BARRANGE = 5; //한 번에 보여줄 BARNUMBER의 개수

                        //최대값 넘어가면 최대값으로 고정
                        $SHARE = (int)($CALNUM / $BARRANGE);
                        //barnum 보여주는 반복문
                        for ($i = 0; $i < $BARRANGE; $i++) {
                            $barnum = $BARRANGE * $SHARE + $i + 1;
                            if ($MAXNUM < $barnum) {

                                break;
                            }

                        ?>
                            <a <?php if ($barnum == $currentpage) : ?> class="currentpage" <?php endif; ?> href="<?= BASE_URL . "/boards" . "?page=" . $barnum; ?>"><?php echo $barnum; ?></a>

                        <?php } ?>

                        <!-- 화살표로 숫자넘어가기 필요-->

                        <a href='<?= BASE_URL . "/boards" . "?page=" . $nextpage; ?>'>></a>
                        <a href="<?= BASE_URL . "/boards" . "?page=" . $MAXNUM ?>">>></a>
                        <button class="post-btn" onclick="getPage('/boards/postForm')">글쓰기</button>
    </div>

    <!-- 상수들 합치기 -->
</div>
<script>
    function getPage(url) {
        url = '<?= BASE_URL ?>' + url
        window.location.replace(url);
    }
</script>