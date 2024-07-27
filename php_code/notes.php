<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Project</title>
    <script src="js/isotope.pkgd.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0px; padding: 0px;}
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        #note-container-container { width: 100%; border: 2px solid red; display: flex; justify-content: center; }
        #note-container { width: 90vw; border: 1px solid green; display: flex; }
        .note { width: 300px; border: 2px solid #333; padding: 10px; margin: 10px; }
    </style>
</head>
<body>

    <h1>Notes will be displayed here</h1>
    <form action="logout.php" method="post">
        <button type="submit">Log out</button>
    </form>
    
    <br>
    <a href="index.php">index page</a>
    <br>
    <a href="sql.php">example SQL page</a>
    <br>
    <a href="signup.php">sign up page</a>
    <br>
    <a href="login.php">log in page</a>
    <br>
    <a href="notes.php">notes page</a>

    <hr>
    <div id="note-container-container">
        <div id="note-container">
            <div class="note">
                <h3 class="note-title">Title here 1</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 2</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 3</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 4</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 5</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ea, eius accusamus odio veritatis corrupti nobis a! Iste minus veritatis dolorem natus iure ratione, ex ducimus ad, rerum magnam similique beatae! rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 6</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 7</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, Lorem ipsum dolor sit, amet co lorem
                    nsectetur adipisicing elit. Ea, eius accusamus odio veritatis corrupti nobis a! Iste minus veritatis dolorem natus iure ratione, ex ducimus ad, rerum magnam similique beatae! rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 8</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, sae Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis maiores amet recusandae. Vitae ipsam fugit autem obcaecati consectetur delectus iste culpa. Eaque fugiat doloribus saepe totam pariatur! Odio, sapiente debitis! pe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 9</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliqing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 10</h3>
                <p>Lorem, ipsum dolor sit amet conspisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 11</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet conslorem Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis a facere inventore quasi libero ducimus iure nesciunt corporis quos soluta nisi repellat distinctio veniam dolorem culpa incidunt, est tenetur totam? Lorem ipsum dolor sit amet consectetur adipisicing elit. A laudantium aut natus! Temporibus eos perferendis labore enim qui atque velit voluptates ut, minus repudiandae asperiores natus quam fugit dignissimos similique. Lorem ipsum dolor sit amet consectetur adipisicing elit. Autem accusamus nulla et consequatur, voluptatibus vitae unde excepturi perspiciatis harum quo maiores voluptatum, facere nam ipsa mollitia cumque tenetur a illo? Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi sunt quae aut, omnis expedita nulla iste est recusandae nostrum ipsum temporibus totam repellat nemo, esse, vero atque cumque doloribus mollitia. ectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 12</h3>
                <p>Lorem, ipsum dolor sitlorem Lorem ipsum, dolor sit amet consectetur adipisicing elit. Tenetur voluptatum eveniet autem impedit. Doloremque suscipit maxime vero quaerat velit, excepturi at minima, facilis asperiores ipsam debitis vitae minus, sequi dolorum. amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 13</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Labore hic laboriosam aperiam assumenda consectetur minima corporis tenetur vitae aut omnis quibusdam vero nesciunt, deserunt laudantium. Nisi harum ad consequatur amet? Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae atque labore animi explicabo amet exercitationem dignissimos temporibus maxime saepe quidem ex unde incidunt eaque, ut consequuntur voluptas ea eum maiores.est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 14</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 15</h3>
                <p>Lorem, ipsum dolor Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis delectus est cum quis assumenda, dignissimos non fugiat quibusdam adipisci aperiam. Voluptate minima, dicta repellendus doloremque reprehenderit maiores culpa omnis eveniet!dis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 16</h3>
                <p>Lorem, em et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 17</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe aliquid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 18</h3>
                <p>Lorem, ipsum dolor sit amet consecteuos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
            <div class="note">
                <h3 class="note-title">Title here 19</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laudantium similique incidunt assumenda natus quasi? Laudantium natus officia est, saepe ali Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maxime dolor omnis unde? Laudantium eius non quasi esse aliquid, vitae nostrum consectetur molestiae, fuga necessitatibus officiis repudiandae delectus laboriosam! Ea, suscipit. quid quas a corrupti aut eos, porro, placeat numquam inventore ex. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officia voluptatum labore blanditiis obcaecati optio quod cupiditate sunt perferendis laudantium, quos dolorum quam officiis! Corrupti corporis laboriosam, rem et nisi hic.</p>
            </div>
        </div>
    </div>
</body>
<script>
    // (width + padding + 1px for extra space)
    const noteSize = 321; // in px 


    var iso = new Isotope( '#note-container', {
    });

    function resizeDiv() {
        const div = document.querySelector('#note-container');
        const widthAvailable = document.documentElement.clientWidth;
        
        const columns =  Math.floor(widthAvailable / noteSize);
        var width = columns * noteSize;
        console.log(width);

        div.style.width = width + 'px';
    }

    resizeDiv();

    window.addEventListener('resize', resizeDiv);

</script>
</html>
