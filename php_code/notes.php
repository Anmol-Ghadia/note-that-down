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
        data-note-color="' . htmlspecialchars($data->color, ENT_QUOTES, 'UTF-8') .'"
        data-note-title="' . htmlspecialchars($data->title, ENT_QUOTES, 'UTF-8') .'"
        data-note-type="' . htmlspecialchars($data->type, ENT_QUOTES, 'UTF-8') .'"
        data-note-body="' . htmlspecialchars($data->body, ENT_QUOTES, 'UTF-8') .'"
        data-note-time-created="' . htmlspecialchars($data->created_at, ENT_QUOTES, 'UTF-8') .'"
        data-note-time-updated="' . htmlspecialchars($data->updated_at, ENT_QUOTES, 'UTF-8') .'"
        data-note-unsaved-changes="0"
    >
        <input type="text" placeholder="Add Title Here" class="note-title" disabled></input>
        <textarea type="text" class="note-body" placeholder="Your note here" disabled></textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button">Del</div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                </div>
            <div class="note-edit-button">Edit</div>
        </div>
    </div>'; // Make the note-color-ball procedural in the initializeNotes function instead of hardcoding here
}

$notes_document = '';
$rows = readNotes($_SESSION['username']);
foreach ($rows as $row) { // TODO: Add support for other format of notes
    $notes_document .= createNoteDiv($row);
}


$note_colors = [
    0 => "FFDFD6",
    1 => "B692C2",
    2 => "80C4E9",
    3 => "FB6D48",
    4 => "A6FF96"
]

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
        #new-note {
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
        
        #new-note-color-bar,
        .note-toolbar {
            width: 100%;
            height: 30px;
            display: flex;
            justify-content: right;
            align-items: center;
        }

        .new-note-color {
            margin-left: 10px;
            height: 100%;
            aspect-ratio: 1;
            border: 1px solid gray;
            filter: brightness(110%);
            border-radius: 50%;
            transition: all 150ms linear;
        }
        
        
        .new-note-color[data-checked="1"] {
            border-radius: 40%;
            border: 2px solid black;
        }

        /* New Below this */
        
        .note[data-note-unsaved-changes="1"] {
            border: 4px solid #000;
        }
        
        .note-title {
            color: black;
            padding: 5px;
            margin: 5px;
            width: 100%;
            font-size: x-large;
            background: transparent;
            border: none;
        }

        .note-body {
            width: 100%;
            background: transparent;
            font-size: 1em;
        }

        .note-toolbar {
            width: 100%;
            height: 30px;
            display: flex;
            justify-content: right;
            align-items: center;
            /* border: 1px solid orange; */
        }

        .note-delete-button {
            border: 1px solid red;
            height: 100%;
            aspect-ratio: 1;
            overflow: hidden;
        }

        .note[data-note-unsaved-changes="1"] .note-edit-container {
            width: auto;
        }

        .note-edit-container {
            width: 0px;
            overflow: hidden;
            height: 100%;
            /* border: 1px solid red; */
            display: flex;
            transition: all 150ms linear;
        }

        .note-color-ball {
            margin-left: 10px;
            height: 100%;
            aspect-ratio: 1;
            border: 1px solid gray;
            filter: brightness(110%);
            border-radius: 50%;
            transition: all 150ms linear;
        }

        .note-color-ball[data-color-checked="1"] {
            border-radius: 40%;
            border: 2px solid black;
        }

        .note-edit-button {
            height: 100%;
            aspect-ratio: 1;
            background: #888;
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
    <div id="new-note">
        <input type="text" name="new-note-title" id="new-note-title-input" placeholder="Create new Note">
        <textarea name="new-note-body" id="new-note-body-input" placeholder="Add note here" ></textarea>
        <div id="new-note-color-bar">
            <div
                data-checked="1"
                onclick="selectColorForNewNote(0)"
                class="new-note-color"
                id="new-note-color-0"
                style="background-color: #<?php echo $note_colors['0'] ?>"></div>
            <div
                data-checked="0"
                onclick="selectColorForNewNote(1)"
                class="new-note-color"
                id="new-note-color-1"
                style="background-color: #<?php echo $note_colors['1'] ?>"></div>
            <div
                data-checked="0"
                onclick="selectColorForNewNote(2)"
                class="new-note-color"
                id="new-note-color-2"
                style="background-color: #<?php echo $note_colors['2'] ?>"></div>
            <div
                data-checked="0"
                onclick="selectColorForNewNote(3)"
                class="new-note-color"
                id="new-note-color-3"
                style="background-color: #<?php echo $note_colors['3'] ?>"></div>
            <div
                data-checked="0"
                onclick="selectColorForNewNote(4)"
                class="new-note-color"
                id="new-note-color-4"
                style="background-color: #<?php echo $note_colors['4'] ?>"></div>
        </div>
    </div>
    <button onclick="submitNewNote()">create note</button>

    <hr>
    <div id="note-container-container">
        <div id="note-container">
            <!-- <?php echo $rows; ?> -->
            <?php echo $notes_document; ?>
        </div>
    </div>
</body>
<script>
    const noteColors = <?php echo json_encode($note_colors); ?>;

    // (width + padding + 1px for extra space)
    const noteSize = 321; // in px 


    var iso = new Isotope( '#note-container', {
        getSortData : {
            sortValue: '[data-note-time-updated]'
        }
    });

    // Resizes the note container to fit exactly inside the viewport
    function resizeNoteContainer() {
        const div = document.querySelector('#note-container');
        const widthAvailable = document.documentElement.clientWidth;
        
        const columns =  Math.floor(widthAvailable / noteSize);
        var width = columns * noteSize;

        div.style.width = width + 'px';
    }

    // Sets the color for new note
    function selectColorForNewNote(newColor) {
        const colors = document.querySelectorAll('.new-note-color');
        for (let i = 0; i < colors.length; i++) {
            const colorBox = colors[i];
            colorBox.dataset.checked = "0";
            if (i == newColor) {
                colorBox.dataset.checked = "1";           
            }
        }
        document.querySelector('#new-note').style.backgroundColor = `#${noteColors[newColor]}`;
    }


    // Sends a create request to backend
    function submitNewNote() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/api/create-note.php', true);
        xhr.setRequestHeader('Content-Type', 'application/xml');
        xhr.setRequestHeader('Accept', 'application/xml');

        const reqTitle = document.querySelector('#new-note-title-input').value;
        const reqBody = document.querySelector('#new-note-body-input').value;
        const reqColor = getNewNoteSelectedColor();
        const xmlData = 
        `<request>
            <title>${reqTitle}</title>
            <type>text</type>
            <body>${reqBody}</body>
            <color>${reqColor}</color>
        </request>`;
        // console.log(`Request: ${xmlData}`)

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // console.log(`Response: ${xhr.responseText}`);
                
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xhr.responseText, "application/xml");
                
                appendNoteToDocument(reqTitle, reqBody, reqColor);
            } else {
                console.error('POST Request Failed:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network Error');
        };

        xhr.send(xmlData);
    }

    // Returns the string representing color of new note
    function getNewNoteSelectedColor() {
        const colors = document.querySelectorAll('.new-note-color');
        for (let i = 0; i < colors.length; i++) {
            const colorDiv = colors[i];
            if (colorDiv.dataset.checked == "1") {
                return noteColors[i];
            }
            
        }
    }

    // Add a new note to the container
    function appendNoteToDocument(title, body, color) {
        const note = document.createElement('div');
        note.classList.add('note');
        note.style.backgroundColor = `#${color}`;
        
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
        isotopeUpdate();
    }

    const newNoteInputBody = document.querySelector('#new-note-body-input');
    newNoteInputBody.addEventListener('input', ()=>{ expandTextArea(newNoteInputBody) });
    // Resizes the New note's body size
    function expandTextArea(textArea) {
        textArea.style.height = 'auto';
        var size = textArea.scrollHeight + 5;
        if (size < 200) size = 200; 
        textArea.style.height = size + 'px';
    }

    // Populate the notes using data attributes
    function initializeNotes() {
        var noteDivs = document.getElementsByClassName('note');
        for (var i = 0; i < noteDivs.length; i++) {
            const noteDiv = noteDivs[i];

            var noteColor = noteDiv.dataset.noteColor;
            noteDiv.style.backgroundColor = `#${noteColor}`;
            
            var noteTitle = noteDiv.dataset.noteTitle;
            const noteTitleElement = noteDiv.querySelector('.note-title');
            noteTitleElement.value = noteTitle;
            
            var noteBody = noteDiv.dataset.noteBody;
            const noteBodyElement = noteDiv.querySelector('.note-body');
            noteBodyElement.value = noteBody;
            expandTextArea(noteBodyElement);
            noteBodyElement.addEventListener('input',()=>{ expandTextArea(noteBodyElement) });
            
            const noteDeleteElement = noteDiv.querySelector('.note-delete-button');
            noteDeleteElement.addEventListener('click',()=>{ deleteNote(noteDiv) });

            const colorDivs = noteDiv.querySelectorAll('.note-color-ball');
            for (let i = 0; i < colorDivs.length; i++) {
                const colorDiv = colorDivs[i];
                
                if (noteColors[i] == noteColor) {
                    colorDiv.dataset.colorChecked = "1";
                }

                colorDiv.style.background = `#${noteColors[i]}`;
                colorDiv.addEventListener('click', ()=>{onClickHandleColorChange(noteDiv, i)});

            }

            noteDiv.querySelector('.note-edit-button').addEventListener('click',()=>{ onClickEditNote(noteDiv) });
        }
    }

    // Handles radio button for note colors
    function onClickHandleColorChange(noteDiv, newColorNumber) {
        const colorDivs = noteDiv.querySelectorAll('.note-color-ball');
        for (let i = 0; i < colorDivs.length; i++) {
            const colorDiv = colorDivs[i];
            colorDiv.dataset.colorChecked = "0";
            if (i == newColorNumber) {
                colorDiv.dataset.colorChecked = "1";
                noteDiv.style.background = `#${noteColors[i]}`;
            }
        }
    }

    // Handles click on edit button
    function onClickEditNote(noteDiv) {
        const noteTitleElement = noteDiv.querySelector('.note-title');
        const noteBodyElement = noteDiv.querySelector('.note-body');

        if (noteDiv.dataset.noteUnsavedChanges == "0") { // start Edit mode 
            noteTitleElement.disabled = false;
            noteBodyElement.disabled = false;

            // TODO !!!

            noteDiv.querySelector('.note-edit-button').innerHTML = "Save";
            noteDiv.dataset.noteUnsavedChanges = '1';
        } else { // Save data
            noteTitleElement.disabled = true;
            noteBodyElement.disabled = true;
        
            // TODO !!!
            
            // Send details to update record on backend.
            // Response should be the updatenote.
            // Use the response data to update data attributes of note
            // Force isotope to rearrange if necessary

            // noteDiv.dataset.noteTitle = noteDiv.querySelector('.note-title').value;
            // noteDiv.dataset.noteBody = noteDiv.querySelector('.note-body').value;
            // noteDiv.dataset.noteColor = getSelectedNoteColor(noteDiv);

            
            noteDiv.querySelector('.note-edit-button').innerHTML = "Edit";
            noteDiv.dataset.noteUnsavedChanges = '0';
            
        }
    }

    // Returns the selected note color for given note
    function getSelectedNoteColor(noteDiv) {
        const colorDivs = noteDiv.querySelectorAll('.note-color-ball');
        for (let i = 0; i < colorDivs.length; i++) {
            if (colorDiv.dataset.colorChecked == "1") {
                return noteColors[i];
            }
        }
    }

    window.addEventListener('resize', resizeNoteContainer);
    window.addEventListener('load', ()=> {
        selectColorForNewNote(0);
        initializeNotes(); // populates the notes by reading data attributes
        resizeNoteContainer(); // resizes the notes container to fit exactly by screen size
        isotopeUpdate();// re-render layout
    })

    function isotopeUpdate() {
        iso.arrange({
            sortBy: 'sortValue',
            sortAscending: false // true for ascending, false for descending
        });
    }

    // Deletes the note specified by div
    function deleteNote(noteDiv) {

        const xhr = new XMLHttpRequest();
        xhr.open('DELETE', '/api/delete-note.php', true);
        xhr.setRequestHeader('Content-Type', 'application/xml');
        xhr.setRequestHeader('Accept', 'application/xml');

        const reqId = noteDiv.dataset.noteId;
        const xmlData = 
        `<request>
            <id>${reqId}</id>
        </request>`;
        // console.log(`Request: ${xmlData}`)

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // console.log(`Response: ${xhr.responseText}`);
                
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xhr.responseText, "application/xml");
                
                iso.remove(noteDiv);
                isotopeUpdate();
            } else {
                console.error('DELETE Request Failed:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network Error');
        };

        xhr.send(xmlData);
    }

</script>
</html>
