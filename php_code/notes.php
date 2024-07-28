<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    return;
}

include 'sql.php';

$notes_document = '';
$rows = readNotes($_SESSION['username']);
foreach ($rows as $row) { // TODO: Add support for other format of notes
    $notes_document .= '
    <div class="note">
        <h3 class="note-title">' . htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8') . '</h3>
        <p>' . htmlspecialchars($row->body, ENT_QUOTES, 'UTF-8') . '</p>
    </div>';
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
        
        #note-container-container { 
            width: 100%;
            border: 2px solid red;
            display: flex;
            justify-content: center;
        }

        #note-container {
            width: 90vw;
            border: 1px solid green;
            display: flex;
        }

        .note {
            width: 300px;
            border: 2px solid #333;
            padding: 10px;
            margin: 10px;
        }
        textarea {
            border: none;
            overflow: auto;
            outline: none;

            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            -ms-box-shadow: none;
            -o-box-shadow: none;
            box-shadow: none;

            -webkit-appearance: none;
            -moz-apperarance: none;
            -ms-appearance: none;
            -o-appearance: none;
            appearance: none;

            resize: none;
        }
        
        #new-note-body-input {
            width: 100%;
            height: fit-content;
            text-align: left;
            background: #ddd;
            overflow: hidden;
            border: none;
            padding: 5px;
        }
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
    <br>
    <div class="note">
        <input type="text" name="new-note-title" id="new-note-title-input" placeholder="Create new Note">
        <textarea name="new-note-body" id="new-note-body-input" placeholder="Add note here" ></textarea>
        <button onclick="makeNote()">create note</button>
    </div>

    <hr>
    <div id="note-container-container">
        <div id="note-container">
            <!-- <div class="note">
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
            </div> -->
            <!-- <?php echo $rows; ?> -->
            <?php echo $notes_document; ?>
        </div>
    </div>
</body>
<script>
    // (width + padding + 1px for extra space)
    const noteSize = 321; // in px 


    var iso = new Isotope( '#note-container', {
    });

    // Resizes notes container
    function resizeDiv() {
        const div = document.querySelector('#note-container');
        const widthAvailable = document.documentElement.clientWidth;
        
        const columns =  Math.floor(widthAvailable / noteSize);
        var width = columns * noteSize;

        div.style.width = width + 'px';
    }


    window.addEventListener('resize', resizeDiv);

    // Sends a create request to api
    function makeNote() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/api/create-note.php', true);
        xhr.setRequestHeader('Content-Type', 'application/xml');
        xhr.setRequestHeader('Accept', 'application/xml');

        const reqTitle = document.querySelector('#new-note-title-input').value;
        const reqBody = document.querySelector('#new-note-body-input').value;
        const xmlData = `<request><title>${reqTitle}</title><type>text</type><body>${reqBody}</body></request>`;
        // console.log(`Request: ${xmlData}`)

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // console.log(`Response: ${xhr.responseText}`);
                
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xhr.responseText, "application/xml");
                
                // TODO !!!
            } else {
                console.error('POST Request Failed:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network Error');
        };

        xhr.send(xmlData);
    }


    const newNoteInputBody = document.querySelector('#new-note-body-input');
    newNoteInputBody.addEventListener('input', resizeNewNoteBody);
    function resizeNewNoteBody() {
        newNoteInputBody.style.height = 'auto';
        var size = newNoteInputBody.scrollHeight + 5;
        if (size < 200) size = 200; 
        newNoteInputBody.style.height = size + 'px';
    }

    window.addEventListener('load', ()=> {
        resizeNewNoteBody();
        resizeDiv();
    })

</script>
</html>
