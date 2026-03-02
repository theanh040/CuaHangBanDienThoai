<?php

// Hàm lấy tất cả danh mục
function checkuser($username, $password)
{
    // $kq = '';
    $conn = connectdb();
    // Lỗi cú pháp ở đây !!!
    $sql = "SELECT * FROM tbl_nguoidung WHERE tai_khoan = '$username' AND mat_khau = '$password'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    // return $kq;

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    // Chưa check user
    // var_dump($kq);
    if (count($kq) > 0) {
        // echo "kq: " . var_dump($kq);
        return $kq[0]['vai_tro'];
    } else {
        return -1;
    }
    // return $kq;
}
function checkuser2($email, $password)
{
    $conn = connectdb();
    $sql = "SELECT * FROM tbl_nguoidung WHERE email = ? AND mat_khau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $password]);

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    
    // DEBUG
    echo "=== DEBUG checkuser2 ===<br>";
    echo "Email input: " . htmlspecialchars($email) . "<br>";
    echo "Password hash input: " . htmlspecialchars($password) . "<br>";
    echo "Found records: " . count($kq) . "<br>";
    if (count($kq) > 0) {
        echo "User found: " . $kq[0]['ho_ten'] . "<br>";
        echo "DB password: " . $kq[0]['mat_khau'] . "<br>";
        echo "vai_tro: " . $kq[0]['vai_tro'] . "<br>";
    } else {
        echo "User NOT found!<br>";
        // Check if email exists
        $sql2 = "SELECT * FROM tbl_nguoidung WHERE email = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$email]);
        $stmt2->setFetchMode(PDO::FETCH_ASSOC);
        $kq2 = $stmt2->fetchAll();
        echo "Email exists in DB: " . (count($kq2) > 0 ? "YES" : "NO") . "<br>";
        if (count($kq2) > 0) {
            echo "DB password for this email: " . $kq2[0]['mat_khau'] . "<br>";
        }
    }
    echo "===================<br>";
    
    if (count($kq) > 0) {
        return $kq[0]['vai_tro'];
    } else {
        return -1;
    }
    // return $kq;
}

function insertuser($name, $username, $email, $password)
{
    $conn = connectdb();
    $sql = "INSERT INTO tbl_nguoidung (name, user, email,pass)
    VALUES ('$name', '$username', '$email','$password')";
    $conn->exec($sql);
}

function getuserinfo($username, $password)
{
    // $kq = '';
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM tbl_nguoidung WHERE tai_khoan = '$username' AND mat_khau = '$password'");
    $stmt->execute();
    // return $kq;
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    return $kq;
}

function getuserinfo2($email, $password)
{
    // $kq = '';
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM tbl_nguoidung WHERE email = ? AND mat_khau = ?");
    $stmt->execute([$email, $password]);
    // return $kq;
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    return $kq;
}

function getuserinfobyid($iduser)
{
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM tbl_nguoidung WHERE id = '$iduser'");
    $stmt->execute();
    // return $kq;
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    return $kq;
}

function get_all_user($role)
{
    try {
        $conn = connectdb();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM tbl_nguoidung WHERE role = '$role'");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    echo "</table>";
}

function delete_user($iduser)
{
    try {
        $conn = connectdb();
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // sql to delete a record
        $sql = "DELETE FROM tbl_nguoidung WHERE id=" . $iduser;

        // use exec() because no results are returned
        $conn->exec($sql);
        return true;
        echo "Record deleted successfully";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}

function updateuser($iduser, $urlavatar, $name, $email, $phonenumber, $address)
{
    try {
        $conn = connectdb();
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE tbl_nguoidung SET avatar='$urlavatar', name='$name', email='$email', phonenumber='$phonenumber',address='$address'  WHERE id=" . $iduser;

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // execute the query
        $stmt->execute();

        // echo a message to say the UPDATE succeeded
        return true;
        // echo $stmt->rowCount() . " records UPDATED successfully";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}
function insertnewuser($urlavatar, $name, $email, $phonenumber, $address, $username, $password)
{
    try {

        $conn = connectdb();
        $sql = "INSERT INTO tbl_nguoidung (name, address,email, phonenumber, user,pass,role, avatar)
        VALUES ('$name', '$address', '$email','$phonenumber','$username','$password',0,'$urlavatar')";
        $conn->exec($sql);
        return true;
        // echo "add data successfully";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}
function insertnewadmin($urlavatar, $name, $email, $phonenumber, $address, $username, $password)
{
    try {
        $conn = connectdb();
        $sql = "INSERT INTO tbl_nguoidung (name, address,email, phonenumber, user,pass,role, avatar)
        VALUES ('$name', '$address', '$email','$phonenumber','$username','$password',1,'$urlavatar')";
        $conn->exec($sql);
        return true;
        // echo "add data successfully";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}

function updatepass($iduser, $newpass)
{
    $sql = "";
    try {
        $conn = connectdb();
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE tbl_nguoidung SET pass='$newpass'  WHERE id=" . $iduser;

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // execute the query
        $stmt->execute();

        // echo a message to say the UPDATE succeeded
        return true;
        echo $stmt->rowCount() . " records UPDATED successfully";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}
