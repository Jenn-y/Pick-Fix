<!doctype html>
<html lang="en">
<head>
    <?php include('Includes/head.php'); ?>
    <link rel="stylesheet" href="CSS/editProfessionalsProfile.css">
    <link href="CSS/index-header.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/footer.css">
    <title>Edit Profile</title>
</head>
<body>

<div id="page-container">
    <?php include('Includes/header-signed-in.php'); ?>

    <main>
        <div class="main center">
            <div id="profilePhoto">
                <img src="Images/default-user.png" alt="default-user-image">
                <button class="buttonStyle">Change Profile Photo</button>
            </div>
            <form>
                <fieldset>
                    <legend>General</legend>
                    <div>
                        <label for="firstName">First name</label>
                        <input id="firstName" placeholder="Change your first name" type="text">
                    </div>
                    <div>
                        <label for="lastName">Last name</label>
                        <input id="lastName" placeholder="Change your last name" type="text">
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" placeholder="Change your email" type="email">
                    </div>
                    <div>
                        <label for="phoneNum">Phone number</label>
                        <input id="phoneNum" placeholder="+387663837655" type="number">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>

                <fieldset>
                    <legend>Credit card</legend>
                    <button class="buttonStyle">Update Credit Card Information</button>
                </fieldset>

                <fieldset>
                    <legend>Category</legend>
                    <div>
                        <label for="dropdown1" id="dropdown2-label">Add category</label>
                        <select id="dropdown2">
                            <option value="select" disabled>Select one of the categories</option>
                            <option value="fax1">Furniture repairment</option>
                            <option value="fax2">Plumbing</option>
                            <option value="fax3">Electricity</option>
                            <option value="fax4">Toilets</option>
                            <option value="fax5">Moving help</option>
                        </select>
                    </div>
                    <div>
                        <label for="dropdown1" id="dropdown2-label">Delete category</label>
                        <select id="dropdown2">
                            <option value="select" disabled>Select one of the categories</option>
                            <option value="fax1">Furniture repairment</option>
                            <option value="fax2">Plumbing</option>
                            <option value="fax3">Electricity</option>
                            <option value="fax4">Toilets</option>
                            <option value="fax5">Moving help</option>
                        </select>
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>

                <fieldset>
                    <legend>City</legend>
                    <div>
                        <label for="dropdown1" id="dropdown1-label">Add city</label>
                        <select id="dropdown1">
                            <option value="select" disabled>Select one of the cities</option>
                            <option value="fax1">Tuzla</option>
                            <option value="fax2">Zivinice</option>
                            <option value="fax3">Bihac</option>
                            <option value="fax4">Sarajevo</option>
                            <option value="fax5">Mostar</option>
                        </select>
                    </div>
                    <div>
                        <label for="dropdown1" id="dropdown1-label">Delete city</label>
                        <select id="dropdown1">
                            <option value="select" disabled>Select one of the cities</option>
                            <option value="fax1">Tuzla</option>
                            <option value="fax2">Zivinice</option>
                            <option value="fax3">Bihac</option>
                            <option value="fax4">Sarajevo</option>
                            <option value="fax5">Mostar</option>
                        </select>
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>

                <fieldset>
                    <legend>Password</legend>
                    <div>
                        <label for="currentPass">Current Password</label>
                        <input id="currentPass" placeholder="Enter your current password" type="password">
                        <h5><a href="#">  Forgot your password?</a></h5>
                    </div>
                    <div>
                        <label for="newPass">New password</label>
                        <input id="newPass" placeholder="Enter your new password" type="password">
                    </div>
                    <div>
                        <label for="repeatPass">Repeat New Password</label>
                        <input id="repeatPass" placeholder="Repeat your new password" type="password">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>
            </form>
        </div>
    </main>
    <?php include('Includes/footer.php'); ?>
</div>
</body>
</html>