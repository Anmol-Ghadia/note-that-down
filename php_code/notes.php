<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    return;
}

include 'sql.php';

function createNoteDiv(stdClass $data): string {
    return '
    <div class="note"
        data-note-id="' . htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8') .'"
        data-note-title="' . htmlspecialchars($data->title, ENT_QUOTES, 'UTF-8') .'"
        data-note-type="' . htmlspecialchars($data->type, ENT_QUOTES, 'UTF-8') .'"
        data-note-body="' . htmlspecialchars($data->body, ENT_QUOTES, 'UTF-8') .'"
        data-note-time-created="' . htmlspecialchars($data->created_at, ENT_QUOTES, 'UTF-8') .'"
        data-note-time-updated="' . htmlspecialchars($data->updated_at, ENT_QUOTES, 'UTF-8') .'"
    >
        <h3 class="note-title"></h3>
        <p class="note-body"></p>
    </div>';
}

$notes_document = '';
$rows = readNotes($_SESSION['username']);
foreach ($rows as $row) { // TODO: Add support for other format of notes
    $notes_document .= createNoteDiv($row);
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

        .note, 
        .new-note {
            width: 300px;
            border: 2px solid #333;
            padding: 10px;
            margin: 10px;
            background: white;
            transition: transform 75ms ease-in;
        }
        
        /* .note:hover {
            z-index: 1000;
            transform: scale(120%); 
        } */

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
    <div class="new-note">
        <input type="text" name="new-note-title" id="new-note-title-input" placeholder="Create new Note">
        <textarea name="new-note-body" id="new-note-body-input" placeholder="Add note here" ></textarea>
        <button onclick="submitNewNote()">create note</button>
    </div>

    <hr>
    <div id="note-container-container">
        <div id="note-container">
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

    // Resizes the note container to fit exactly inside the viewport
    function resizeNoteContainer() {
        const div = document.querySelector('#note-container');
        const widthAvailable = document.documentElement.clientWidth;
        
        const columns =  Math.floor(widthAvailable / noteSize);
        var width = columns * noteSize;

        div.style.width = width + 'px';
    }

    // Sends a create request to backend
    function submitNewNote() {
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
                
                appendNoteToDocument(reqTitle, reqBody);
            } else {
                console.error('POST Request Failed:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network Error');
        };

        xhr.send(xmlData);
    }

    // Add a new note to the container
    function appendNoteToDocument(title, body) {
        const note = document.createElement('div');
        note.classList.add('note');
        
        const header = document.createElement('h3');
        header.classList.add('note-title');
        header.textContent = title;
        note.appendChild(header);
        
        const paragraph = document.createElement('p');
        paragraph.textContent = body;
        note.appendChild(paragraph);
        
        const noteContainer = document.querySelector('#note-container');
        noteContainer.prepend(note);
        
        iso.prepended(note);
        iso.layout();
    }

    const newNoteInputBody = document.querySelector('#new-note-body-input');
    newNoteInputBody.addEventListener('input', resizeNewNoteBody);
    // Resizes the New note's body size
    function resizeNewNoteBody() {
        newNoteInputBody.style.height = 'auto';
        var size = newNoteInputBody.scrollHeight + 5;
        if (size < 200) size = 200; 
        newNoteInputBody.style.height = size + 'px';
    }

    // Populate the notes using data attributes
    function renderNotes() {
        var noteDivs = document.getElementsByClassName('note');
        for (var i = 0; i < noteDivs.length; i++) {
            const noteDiv = noteDivs[i];

            var noteTitle = noteDiv.dataset.noteTitle;
            var noteBody = noteDiv.dataset.noteBody;
            
            var noteTitleElement = noteDiv.getElementsByClassName('note-title')[0];
            var noteBodyElement = noteDiv.getElementsByClassName('note-body')[0];
            
            noteTitleElement.innerHTML = noteTitle;
            noteBodyElement.innerHTML = noteBody;
        }
        
    }

    window.addEventListener('resize', resizeNoteContainer);
    window.addEventListener('load', ()=> {
        renderNotes(); // populates the notes by reading data attributes
        resizeNoteContainer(); // resizes the notes container to fit exactly by screen size
        iso.layout(); // re-render layout
    })

</script>
</html>
