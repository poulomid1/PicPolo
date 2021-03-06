<?php
//start php session to store information
if (session_id() === '')
    session_start();

// session variable to track current page
$_SESSION['ACTIVE_PAGE'] = "profile";

if (isset($_SESSION['username']))
    $current_user = $_SESSION['username'];
else {
    header('Location: ' . $_COOKIE['HOME']);
}
// signout
if (isset($_SESSION['username']) && isset($_GET['signout'])) {
    // remove all session variables
    session_unset();

    // destroy the session
    session_destroy();

    // redirect to home page
    header('Location: ' . $_COOKIE['HOME']);
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Profile</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
        // put your code here
        ?>

        <!-- Header -->
        <?php include 'includes/header.php'; ?>
        <!-- Body Contents -->
        <div class="container push-bottom">
            <div class="row">
                <h2 style="margin-top: -80px">Artist Profile Page</h2>
                <?php include 'includes/accountTabs.php'; ?>


                <?php
                // require_once 'db/dbHandler.php';
                $usr_profile = dbHandler::getInstance();
                $userId = $usr_profile->getArtistId($_SESSION['username']);
                $usr_profile->displayUserProfile($_SESSION['username']);
                $usr_profile->displayArtistDetail($userId);

                /*
                 * handles user account edits
                 */
                if (isset($_POST["nameSubmit"])) {
                    $newFirstName = $_POST["firstnameEdit"];
                    $newLastName = $_POST["lastnameEdit"];

                    $usr_profile->editFirstName($_SESSION['username'], $newFirstName);
                    $usr_profile->editLastName($_SESSION['username'], $newLastName);
                    echo "<meta http-equiv='refresh' content='0'>";
                }
                if (isset($_POST["emailSubmit"])) {
                    $newEmail = $_POST["emailEdit"];
                    $usr_profile->editEmail($_SESSION['username'], $newEmail);
                    echo "<meta http-equiv='refresh' content='0'>";
                }

                if (isset($_POST["phoneSubmit"])) {
                    $newPhone = $_POST["phoneEdit"];
                    $usr_profile->editPhoneNumber($_SESSION['username'], $newPhone);
                }
                if (isset($_POST["pswSubmit"])) {
                    $currPw = $_POST["currentPsw"];
                    $newPw = $_POST["newPsw"];
                    $newPwc = $_POST["newPswc"];
                    $check = $usr_profile->getPassword($_SESSION['username']);
                    if ($currPw === $check && $newPw === $newPwc) {
                        $usr_profile->editPassword($_SESSION['username'], $newPw);
                    } else if ($currPw !== $check && $newPw !== $newPwc) {
                        echo "Current password and password confirmation did not match!";
                    } else if ($currPw !== $check) {
                        echo "Current Password did not match!";
                    } else {
                        echo "new password confirmation did not match your new password!";
                    }
                }
                if (isset($_POST["pseudonymSubmit"])) {
                    $newPseudonym = $_POST["pseudonymEdit"];
                    $usr_profile->editPseudonym($userId, $newPseudonym);
                }
                 if (isset($_POST["bioSubmit"])) {
                    $newBio = $_POST["bioEdit"];
                    $usr_profile->editBiography($userId, $newBio);
                }
                ?>

            </div>
        </div>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>

    </body>
    <!--Edit Name Modal-->
    <div id="editName" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-style">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title"><i class="glyphicon glyphicon-edit" style="float:left"></i>Edit Name</h2>
                </div>
                <div class="modal-content-block">
                    <div class="modal-body">
                        <form action="profile.php" method="POST" enctype="multipart/form-data" id="formAnchor">
                            <div class="form-group">
                                <input type="text" class="form-control" pattern="[a-zA-Z\s]*" oninvalid="setCustomValidity('Letters and white space only.')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" id="firstnameEdit" name="firstnameEdit" value="<?php echo $usr_profile->getFirstName($_SESSION['username']); ?>"placeholder="First Name" required />
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" pattern="[a-zA-Z\s]*" oninvalid="setCustomValidity('Letters and white space only.')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" id="lastnameEdit" name="lastnameEdit" value="<?php echo $usr_profile->getLastName($_SESSION['username']); ?>"placeholder="Last Name" required />
                            </div>
                            <div class="form-group text-right">
                                <input type="button" class="btn btn-warning" style="float:left"  data-dismiss="modal" value="Cancel" >
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="btn btn-primary" name ="nameSubmit" value="Update" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of edit name modal-->

    <!--Edit Email Modal-->
    <div id="editEmail" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-style">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title"><i class="glyphicon glyphicon-edit" style="float:left"></i>Edit Email</h2>
                </div>
                <div class="modal-content-block">
                    <div class="modal-body">
                        <form action="profile.php" method="POST" enctype="multipart/form-data" id="formAnchor">
                            <div class="form-group">
                                <input type="text" class="form-control" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" oninvalid="setCustomValidity('Please enter a valid email.')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" name="emailEdit"id="emailEdit" value="<?php echo $usr_profile->getEmail($_SESSION['username']); ?>"placeholder="Email" required />
                            </div>

                            <div class="form-group text-right">
                                <input type="button" class="btn btn-warning" style="float:left"  data-dismiss="modal" value="Cancel" >
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="btn btn-primary" name ="emailSubmit" value="Update" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of edit email modal-->

    <!--Edit password Modal-->
    <div id="editPassword" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-style">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title"><i class="glyphicon glyphicon-edit" style="float:left"></i>Edit Password</h2>
                </div>
                <div class="modal-content-block">
                    <div class="modal-body">
                        <form action="profile.php" method="POST" enctype="multipart/form-data" id="formAnchor">
                            <div class="form-group">
                                <input type="password" pattern="[a-z A-Z 0-9 \s]*" oninvalid="setCustomValidity('Letters, numbers, and white space only.')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" class="form-control" name="currentPsw" id="currentPsw" placeholder="Current password" required />
                            </div>
                            <div class="form-group">
                                <input type="password" pattern="[a-z A-Z 0-9 \s]*" oninvalid="setCustomValidity('Letters, numbers, and white space only.')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" class="form-control" name="newPsw" id="newPsw" placeholder="New Password" required />
                            </div>
                            <div class="form-group">
                                <input type="password" pattern="[a-z A-Z 0-9 \s]*" oninvalid="setCustomValidity('Letters, numbers, and white space only.')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" class="form-control" name="newPswc" id="newPswc" placeholder="Confirm New Password" required />
                            </div>
                            <div class="form-group text-right">
                                <input type="button" class="btn btn-warning" style="float:left"  data-dismiss="modal" value="Cancel" >
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="btn btn-primary" name ="pswSubmit" value="Update" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of edit password modal-->

    <!--Edit phone Modal-->
    <div id="editPhone" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-style">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title"><i class="glyphicon glyphicon-edit" style="float:left"></i>Edit Phone Number</h2>
                </div>
                <div class="modal-content-block">
                    <div class="modal-body">
                        <form action="profile.php" method="POST" enctype="multipart/form-data" id="formAnchor">
                            <div class="form-group">
                                <input type="text" pattern="[0-9 \s]*" oninvalid="setCustomValidity('Numbers only, number must be at least 9 digits and no longer than 15 digits .')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" class="form-control" name="phoneEdit" id="phoneEdit" value="<?php echo $usr_profile->getPhone($_SESSION['username']); ?>"placeholder="Phone Number" required maxlength="15" minlength="9" />
                            </div>
                            <div class="form-group text-right">
                                <input type="button" class="btn btn-warning" style="float:left"  data-dismiss="modal" value="Cancel" >
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="btn btn-primary" name ="phoneSubmit" value="Update" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of edit phone modal-->

    <!--Edit Pseudonym Modal-->
    <div id="editPseudonym" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-style">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title"><i class="glyphicon glyphicon-edit" style="float:left"></i>Edit Pseudonym</h2>
                </div>
                <div class="modal-content-block">
                    <div class="modal-body">
                        <form action="profile.php" method="POST" enctype="multipart/form-data" id="formAnchor">
                            <div class="form-group">
                                <input type="text" class="form-control" pattern="[a-zA-Z\s]*" oninvalid="setCustomValidity('Letters and white space only.')" onchange="try {
                                            setCustomValidity('')
                                        } catch (e) {
                                        }" id="pseudonymEdit" name="pseudonymEdit" value="<?php echo $usr_profile->getPseudonym($userId); ?>"placeholder="Pseudonym" required />
                            </div>
                            <div class="form-group text-right">
                                <input type="button" class="btn btn-warning" style="float:left"  data-dismiss="modal" value="Cancel" >
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="btn btn-primary" name ="pseudonymSubmit" value="Update" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of edit Pseudonym modal-->

        <!--Edit Biography Modal-->
    <div id="editBio" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-header-style">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title"><i class="glyphicon glyphicon-edit" style="float:left"></i>Edit Biography</h2>
                </div>
                <div class="modal-content-block">
                    <div class="modal-body">
                        <form action="profile.php" method="POST" enctype="multipart/form-data" id="formAnchor">
                            <div class="form-group">
                                <textarea class="form-control" style="height: 150px;"id="bioEdit" maxlength="500" name="bioEdit" placeholder="Biography" required ><?php echo $usr_profile->getBiography($userId); ?></textarea>
                            </div>

                            <div class="form-group text-right">
                                <input type="button" class="btn btn-warning" style="float:left"  data-dismiss="modal" value="Cancel" >
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" class="btn btn-primary" name ="bioSubmit" value="Update" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of edit Biography modal-->
</html>
