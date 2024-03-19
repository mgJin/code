<?php
global $connect;
$sql = "SELECT user_pk,user_id,role_id FROM member";
$stmt = $connect->prepare($sql);
$stmt->execute();
$userArrays = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<head>
    <style>
        .management-li {
            display: block;
        }

        .del-btn {
            background-color: #dc3545;
            float: right;
            color: #fff;
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div id="UserManagement" class="managements">
        <nav>
            <ul>
                <?php foreach ($userArrays as $userInfo) { ?>
                    <li class="management-li list-li">
                        <a class="infoA" <?php echo "href='http://localhost:3000/adminpage/userinfo/" . $userInfo['user_pk'] . "'"; ?>>
                            <?php echo $userInfo["user_id"] ?>
                        </a>
                        <button class="del-btn" onclick="delfetch('<?= $userInfo['user_id']; ?>')">X</button>
                    </li>
                <?php }; ?>
            </ul>
        </nav>
    </div>
    <script>
        
        function delfetch(data){
            const formdata = {
                user_id : data,
                admin : true
            }
            fetch("http://localhost:3000/adminpage/usermanagement",{
                method:"DELETE",
                body:JSON.stringify(formdata)
            })
            .then(response=>response.json())
            .then(data=>{
                console.log(data);
                const {serverResponse,deniedReason}= data;
                if(serverResponse){
                    window.location.reload;
                }else{
                    alert(deniedReason);
                }
            }
            )
            .catch(error=>console.log(error));
        }
    </script>
</body>