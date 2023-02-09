<?php 
        function getTitle() {
        
            global $pageTitle;
            if(isset($pageTitle)){
                echo $pageTitle;
            } else {
                echo 'default';
            }
        }

        function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

            global $conn;
    
            $getAll = $conn->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    
            $getAll->execute();
    
            $all = $getAll->fetchAll();
    
            return $all;
    
        }


        function redirectHome($theMsg,$url=null,$seconds=3)
        {
            if ($url==null) {
                $url = "index.php";
                $Link = 'HomePage';
            }else {
                // $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != " " ? $_SERVER['HTTP_REFERER'] : "index.php";
                // $Link = "PreviousPage";
                if (isset($_SERVER['HTTP_REFERER'])&&$_SERVER['HTTP_REFERER'] !=" ") {
                    $url = $_SERVER['HTTP_REFERER'];
                    $Link = "PreviousPage";
                }else {
                    $url = "index.php";
                    $Link = 'HomePage';
                }

                
            }
            echo $theMsg;
            echo"<div class='alert alert-info'>You will be redircted to $Link the home page after $seconds seconds</div>";
            header("refresh:$seconds;url=$url");
            exit();
        }


        function checkItem($select ,$from,$value)
        {
                global $conn;
                $statement = $conn->prepare("SELECT $select FROM $from WHERE $select = ? ");
                $statement->execute(array($value));
                $count = $statement->rowCount();
                return $count;
        }

        function countItems($item,$table)
        {
            global $conn;
            $stmt2 = $conn->prepare("SELECT COUNT($item) FROM $table");
            $stmt2->execute();
            return $stmt2->fetchColumn();
        }

        function getLatest($select,$table,$order,$limit)
        {
            global $conn;
            $getStmt = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
            $getStmt->execute();
            $rows =$getStmt->fetchAll();
            return $rows;
        }

?>
