<?php
require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    if(isset($_GET['id'])){
        $user_data = $user_obj->find_user_by_id($_GET['id']);
        if($user_data ===  false){
            header('Location: profile.php');
            exit;
        }
        else{
            if($user_data->id == $_SESSION['user_id']){
                header('Location: profile.php');
                exit;
            }
        }
    }
}
else{
    header('Location: logout.php');
    exit;
}
// CHECK FRIENDS
$is_already_friends = $frnd_obj->is_already_friends($_SESSION['user_id'], $user_data->id);
//  IF I AM THE REQUEST SENDER
$check_req_sender = $frnd_obj->am_i_the_req_sender($_SESSION['user_id'], $user_data->id);
// IF I AM THE REQUEST RECEIVER
$check_req_receiver = $frnd_obj->am_i_the_req_receiver($_SESSION['user_id'], $user_data->id);
// TOTAL REQUESTS
$get_req_num = $frnd_obj->request_notification($_SESSION['user_id'], false);
// TOTAL FRIENDS
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user_id'], false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo  $user_data->username;?></title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
<body style="background-color:powderblue;">>
    <div class="profile_container">
    <style>
            *{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
}
body{
    padding: 20px;
    margin: 0;
    font-family: 'Open Sans', sans-serif;
    background-color: #000;
}
a{
    color: inherit;
    text-decoration: none;
    outline: none;
}
h1{
    text-align: center;
    color: #932424;
}
.main_container{
    max-width: 750px;
    margin: 0 auto;
    background-color: #F8FAFC;
    padding: 20px;
    border: 1px solid rgba(23,23,23, .1);
    border-radius: 3px;
}
.login_signup_container input[type='text'],
.login_signup_container input[type='email'],
.login_signup_container input[type='password']{
    width: 100%;
    padding: 10px;
    border: 0;
    border-bottom: 1px solid #8795A1;
    outline: none;
    margin-bottom: 10px;
    font-size: 14px;
    background: none;
    color: #232323;
    font-family: 'Open Sans', sans-serif;
}
.login_signup_container input[type='text']:focus,
.login_signup_container input[type='email']:focus,
.login_signup_container input[type='password']:focus{
    border-color: #606F7B;
    
}
.login_signup_container label{
    font-weight: bold;
    color: #232323;
}
.login_signup_container input[type='submit']{
    color: #ffffff;
    padding: 10px 20px;
    font-size: 14px;
    text-transform: uppercase;
    cursor: pointer;
    border: 1px solid rgba(23,23,23, .1);
    outline: none;
    background-color: #1F9D55;
    
}
.form_link{
    float: right;
    font-size: 16px;
}
.site_link,.login_signup_container p{
    font-size: 14px;
    text-align: center;
    color: #3D4852;
    text-transform: uppercase;
    letter-spacing: 3px;
}
.site_link:hover,.form_link:hover,.login_signup_container p a:hover{
    text-decoration: underline;
    color: #1F9D55;
}

.login_signup_container .errorMsg{
    border: 2px solid #CC1F1A;
    color: #CC1F1A;
    letter-spacing: normal;
    font-size: 16px;
    padding: 10px;
}
.login_signup_container .successMsg{
    border: 2px solid #1F9D55;
    color: #1F9D55;
    letter-spacing: normal;
    font-size: 16px;
    padding: 10px;
}

/* PROFILE.PHP */
.profile_container{
    margin:  0 auto;
    max-width: 100%;
    background-color: #F8FAFC;
    border: 1px solid rgba(23,23,23, .1);
    padding: 10px;
}

.profile_container nav ul{
    list-style: none;
    padding: 5px 0;
    margin:10px 0;
    display: flex;
    flex-wrap: wrap;
    border-top: 1px solid rgba(23,23,23, .2);
    border-bottom: 1px solid rgba(23,23,23, .2);
    justify-content: center;
    background: #3439d4;
    border-radius: 2px;
}
.profile_container nav ul li a{
    color: #FFF;
    font-size: 14px;
    display: block;
    padding:5px 10px;
    margin:0 3px;
}

.profile_container nav ul li a:hover{
    background-color: #F3EBFF;
    color: #000000;
}

.profile_container nav .badge{
    background: #FFF;
    display: inline-block;
    padding:0 5px;
    margin-left: 3px;
    color: #000;    
    border-radius: 20px;
}

.profile_container nav .redBadge{
    background-color: #E3342F;
    color: #FFF;
}

.inner_profile .img{
    overflow: hidden;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #FFF;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.12),
            0 2px 4px 0 rgba(0,0,0,0.08);
    border: 3px solid #3D4852;
    margin: 0 auto;
}
.inner_profile .img img{
    max-height: 100%;
    width: auto;
}
.inner_profile h1{
    padding: 0;
    margin: 0;
    font-size: 25px;
    text-transform: capitalize;
}
.active{
    outline: none;
    background-color: #1F9D55 !important;
    color: #FFF !important;
}

/* ALL USERS */

.all_users .usersWrapper{   
    display: flex;

    flex-wrap: wrap;
    justify-content: center;
}
.all_users .user_box{
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    border: 1px solid rgba(4, 72, 97, 0.2);
    margin: 5px;
    padding: 5px;
    width: 48%;
    background-color: #FFF;
    align-items: stretch;
}
.user_box .user_img{

    width: 50px;
    height: 50px;
    overflow: hidden;
    border: 1px solid rgba(23,23,23, .1);
    margin-right: 5px;
    border-radius: 2px;
    background-color: #E6E8FF;
}
.user_box .user_img img{
    height: 100%;
    width: auto;
}
.user_box .user_info{
    flex-grow: 1;
}
.user_info span{
    display: block;
}
.user_info span:first-child{
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    text-transform: capitalize;
    color: #222222;
    font-weight: bold;
    padding-bottom: 5px;
}

.see_profileBtn{
    background-color: #4248e2;
    border: 1px solid rgba(23,23,23, .1);
    color: #FFF;
    font-size: 12px;
    padding:3px 7px;
    text-transform: uppercase;
}
.see_profileBtn:hover{
    background-color: #382B5F;
}

.actions{
    text-align: center;
    margin: 10px 0;
    border: 1px solid rgba(23,23,23, .1);
    padding: 10px;
    background-color: #FFF;
}

.req_actionBtn{
    font-size: 14px;
    text-transform: uppercase;
    padding:5px 10px;
    border: 1px solid rgba(23,23,23, .1);
    color: #222222;
}

.acceptRequest,.sendRequest{
    background-color: #794ACF;
    color: #FFF;
}
.acceptRequest:hover,.sendRequest:hover{
    background-color: #382B5F;
}
.unfriend, .ignoreRequest,.cancleRequest{
    background-color: #EF5753;
    color: #FFF;
}
.unfriend:hover,.ignoreRequest:hover,.cancleRequest:hover{
    background-color: #E3342F;
    
}

/* RESPONSIVE */
@media only screen and (max-width: 560px) {
    .all_users .user_box{
        width: 100%;
    }
  }
        </style> 
        <div class="inner_profile">
            <div class="img">
                <img src="profile_images/<?php echo $user_data->user_image; ?>" alt="Profile image">
            </div>
            <h1><?php echo  $user_data->username;?></h1>
            <nav>
            <ul>
                <li><a href="profile.php" rel="noopener noreferrer">domicile</a></li>
                <li><a href="notifications.php" rel="noopener noreferrer">demandes<span class="badge <?php
                if($get_req_num > 0){
                    echo 'redBadge';
                }
                ?>"><?php echo $get_req_num;?></span></a></li>
                <li><a href="friends.php" rel="noopener noreferrer">amis<span class="badge"><?php echo $get_frnd_num;?></span></a></li>
                <li><a href="logout.php" rel="noopener noreferrer">se deconnecter</a></li>
            </ul>
        </nav>
            <div class="actions">
                <?php
                if($is_already_friends){
                    echo '<a href="functions.php?action=unfriend_req&id='.$user_data->id.'" class="req_actionBtn unfriend">Unfriend</a>';
                }
                elseif($check_req_sender){
                    echo '<a href="functions.php?action=cancel_req&id='.$user_data->id.'" class="req_actionBtn cancleRequest">supprimer la demande</a>';
                }
                elseif($check_req_receiver){
                    echo '<a href="functions.php?action=ignore_req&id='.$user_data->id.'" class="req_actionBtn ignoreRequest">supprimer</a>&nbsp;
                    <a href="functions.php?action=accept_req&id='.$user_data->id.'" class="req_actionBtn acceptRequest">Accepter</a>';
                }
                else{
                    echo '<a href="functions.php?action=send_req&id='.$user_data->id.'" class="req_actionBtn sendRequest">envoyer la demande</a>';
                }
                ?>
        
            </div>
        </div>
     
        
    </div>
</body>
</html>