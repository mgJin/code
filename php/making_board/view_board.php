<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시글 보기</title>
</head>
<body>
    <!--권한 제한된 유저를 만들어서 그놈이 보는걸로 -->
    <?php 
        require_once('dbconnect_root.php');
        $board_id = $_GET["id"];
        
        $sql = "SELECT title,text,writer FROM board WHERE id = $board_id";
        $stmt = $connect->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
    ?>
    <div>
        <h2>제목 : <?php echo $result["title"] ?></h2>
        <span>작성자 : <?php echo $result["writer"]?></span>
        <br>
        <span><?php echo $result["text"]?></span>
    </div>
</body>
</html>