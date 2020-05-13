<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/pro-profile.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">


    <title>Profile</title>
</head>
<body>
<div id="page-container">
    <?php include('includes/header-signed-in.php'); ?>

    <main class="center">
        <div class="shadow">
            <div class="user flex-container">
                <img src="images/default-user.png" alt="default-user-image">
                <div>
                    <h3>Name Surname</h3>
                    <p>Sarajevo, Bosnia and Herzegovina</p>
                    <p>#(user-id)</p>
                </div>
            </div>
            <div class="about">
                <h4>About: </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad aliquam aliquid aut consectetur consequatur cum dolorum excepturi facere harum ipsam ipsum magni, minima mollitia numquam, porro quia quod recusandae similique sit suscipit tenetur vitae voluptate voluptatum! Adipisci dolorem ea earum eius, eligendi harum id obcaecati, omnis quibusdam quis tenetur, vero.</p>
                <h4>Areas Served: </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, est maiores modi officiis perspiciatis recusandae totam vel veritatis voluptatem voluptatibus?</p>
                <h4>Categories </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, est maiores modi officiis perspiciatis recusandae totam vel veritatis voluptatem voluptatibus?</p>
                <h4>Contact:</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis, tempore!</p>
            </div>
        </div>

        <div class="request-box shadow">
            <p>Request Service Form</p>
            <form class="flex-container">
                <div class="request-details flex-container">
                    <label>Full name <span>*</span></label>
                    <div class="flex-container">
                        <input type="text" id="fname" name="fname" placeholder="First name" required>
                        <input type="text" id="lname" name="lname" placeholder="Last name" required>
                    </div>

                    <label>City and Service <span>*</span></label>
                    <div class="flex-container">
                        <select name="cities" id="cities" required>
                            <option disabled selected value>City</option>
                            <option value="tuzla">Tuzla</option>
                            <option value="zivinice">Zivinice</option>
                            <option value="bihac">Bihac</option>
                            <option value="sarajevo">Sarajevo</option>
                            <option value="mostar">Mostar</option>
                        </select>

                        <select name="services-dropdown" id="services-dropdown" required>
                            <option disabled selected value>Service</option>
                            <option value="furniture-repair">Furniture repair</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="electricity">Electricity</option>
                            <option value="toilets">Toilets</option>
                            <option value="moving-help">Moving help</option>
                        </select>
                    </div>

                    <label for="phone">Phone number <span>*</span></label>
                    <input type="tel" id="phone" placeholder="Phone number" required>

                </div>
                <div class="description flex-container">
                    <label for="problem-description">Description of the problem:</label>
                    <textarea name="problem-description" id="problem-description" placeholder="Please describe your problem here"></textarea>
                </div>
            </form>
            <div class="flex-container">
                <button type="submit">Submit</button>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>