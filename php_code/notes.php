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
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                <div data-color-checked="0" class="note-color-ball"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>'; // Make the note-color-ball procedural in the initializeNotes function instead of hardcoding here
}

$notes_document = '';
$rows = readNotes($_SESSION['username']);
foreach ($rows as $row) {
    $notes_document .= createNoteDiv($row);
}

$note_width = 325; // pixels
$note_colors = [
    0 => "ffd6a5",
    1 => "fdffb6",
    2 => "caffbf",
    3 => "a0c4ff",
    4 => "ffc6ff"
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
        h1 { color: #333; }
        
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
        @font-face {
            font-family: 'Handlee';
            src: url('public/Handlee-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            margin: min(2vh,2vw);
            font-family: 'Handlee', Arial, sans-serif;
            background-color: #03346E;
            background-image: 
                            radial-gradient(ellipse at top, #021526 35%, transparent),
                            radial-gradient(ellipse at center, #03346E, transparent),
                            linear-gradient(to top, #6EACDA, transparent);
            color: white;
        }

        #header {
            display: grid;
            grid-template-columns: 5vh auto 5vh;
            user-select: none;
        }
        
        #header p {
            width: 100%;
            font-size: 3em;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 5vh;
        }

        #start-new-note-button {
            position: fixed;
            top: min(2vh,2vw);
            left: min(2vh,2vw);
            width: 5vh;
            height: 5vh;
            z-index: 75;
            background-image: url('public/square-plus-solid.svg');
        }

        #logout-button {
            background-image: url('public/right-from-bracket-solid.svg');
        }

        .control-button {
            height: 100%;
            aspect-ratio: 1;
            background-color: transparent;
            border:none;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            transition: background-size 50ms ease-in-out;
        }

        .control-button:hover,
        .control-button:active {
            background-size: 90% 90%;
        }

        #create-new-note-button {
            width: 100%;
            height: 30px;
            margin-top: 10px;
            font-size: 20px;
            font-family: Georgia sans-serif;
            border-radius: 0px 0px 15px 15px;
            border: none;
            background-color: transparent;
            backdrop-filter: brightness(0.85);
            color: black;
        }

        #create-new-note-button:hover,
        #create-new-note-button:active {
            backdrop-filter: brightness(0.7);
            color: white;
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

        input:disabled {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            appearance: none;
        }

        #new-note-container {
            position: fixed;
            top: 0%;
            left: 0%;
            right: 0%;
            bottom: 0%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            overflow: hidden;
            backdrop-filter: brightness(75%);
        }

        .hide-new-note-container {
            transform: scale(0%);
        }

        #note-container-wide {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        #new-note,
        .note {
            width: <?php echo $note_width ?>px;
            border: 4px solid rgba(255,255,255,0.4);
            padding: 10px;
            margin: 10px;
            background: white;
            border-radius: 20px;
            transition: box-shadow 150ms ease-out;
        }
        
        .note:hover,
        .note[data-note-unsaved-changes="1"] {
            border-color: white;
            box-shadow: 
                0 0 10px rgba(255, 204, 0, 0.65);
            animation: note-edit-animation 1s linear 0s infinite;
        }

        @keyframes note-edit-animation {
            0% {
                border-color: white;
            }
            70% {
                border-color: white;
            }
            80% {
                border-color: #777;
            }
            100% {
                border-color: white;
            }
        }

        #new-note-title,
        .note-title,
        .note-title:disabled {
            -webkit-appearance: none;
            outline: none;
            color: black;
            /* margin: 5px; */
            padding-left: 10px;
            width: 100%;
            font-family: 'Handlee', serif;
            font-weight: 600;
            font-size: 2em;
            background: transparent;
            border: none;
            overflow-x: scroll;
        }

        #new-note-title::selection,
        .note-title::selection {
            background-color: rgba(100,100,100,0.5); Background color for selected text
            color: white;
        }

        #new-note-body,
        .note-body {
            width: 100%;
            background: transparent;
            font-size: 1.25em;
            font-family: Georgia sans-serif;
            color: black;
        }

        #new-note-body::selection,
        .note-body::selection {
            background-color: rgba(100,100,100,0.5);
            color: white;
        }

        .note[data-note-unsaved-changes="1"] .note-toolbar,
        .note:hover .note-toolbar,
        .note:active .note-toolbar {
            opacity: 1;
            visibility: visible;
        }

        .note-toolbar {
            width: 100%;
            height: 30px;
            display: flex;
            justify-content: right;
            align-items: center;
            opacity: 0;
            transition: opacity 400ms ease-in-out; 
            visibility: hidden; 
        }

        @media (pointer: coarse) {
        /* Styles for touch devices */
            .note-toolbar {
                opacity: 1;
                visibility: visible;
            }
        }

        .note-delete-button {
            height: 100%;
            aspect-ratio: 1;
            overflow: hidden;
            background-image: url('public/trash-can-solid.svg');
            background-size: 60% 60%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .note[data-note-unsaved-changes="1"] .note-edit-container {
            width: auto;
        }

        .note-edit-container {
            width: 0px;
            overflow: hidden;
            height: 100%;
            display: flex;
            transition: all 150ms linear;
        }

        .note-color-ball {
            margin: 0px 5px;
            height: 100%;
            aspect-ratio: 1;
            border: 1px solid gray;
            filter: brightness(110%);
            border-radius: 50%;
            transition: all 150ms linear;
        }

        .note-color-ball:hover,
        .note-color-ball:active {
            border-radius: 45%;
            border: 2px solid black;
        }

        .note-color-ball[data-color-checked="1"] {
            border-radius: 40%;
            border: 2px solid black;
        }

        .note[data-note-unsaved-changes="1"] .note-edit-button {
            background-image: url('public/floppy-disk-regular.svg');
            background-size: 70% 70%;
        }

        .note-edit-button {
            height: 100%;
            aspect-ratio: 1;
            background-image: url('public/pen-to-square-solid.svg');
            background-size: 65% 65%;
            background-position: center;
            background-repeat: no-repeat;
        }

        #footer {
            width: 100%;
            height: 20vh;
        }
    </style>
</head>
<body>

    <div id='header'>
        <div></div>
        <p>Your Noteboard</p>
        <form action="logout.php" method="post">
            <button id="logout-button" class='control-button' type="submit"></button>
        </form>
    </div>
    <button id='start-new-note-button' class='control-button' onclick="startNewNote()"></button>
    

    <div id="new-note-container" class="hide-new-note-container">
        <div id="new-note">
            <input type="text"  id="new-note-title" placeholder="Create new Note">
            <textarea  id="new-note-body" placeholder="Add note here" ></textarea>
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
            <button id='create-new-note-button' onclick="submitNewNote()">Add Note</button>
        </div>
    </div>
    
    <div id="note-container-wide">
        <div id="note-container">
            <?php echo $notes_document; ?>
        </div>
    </div>
    <div id='footer'></div>
</body>
<script>
    const noteColors = <?php echo json_encode($note_colors); ?>;

    // (width + padding + 5px for extra space)
    const noteSize = <?php echo $note_width ?> + 20 + 5; // in px 


    var iso = new Isotope( '#note-container', {
        transitionDuration: '0.4s',
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


    // Handler for click when new note banner is shown
    function onClickNewNoteBannerShown(event) {
            if (!document.querySelector('#new-note').contains(event.target)) {
                document.querySelector('#new-note-container').classList.add('hide-new-note-container');
                document.removeEventListener('click',onClickNewNoteBannerShown);
            }
        }

    // Displays A new note on the screen.
    function startNewNote() {
        document.querySelector('#new-note-container').classList.remove('hide-new-note-container');
        
        setTimeout(() => {
            document.addEventListener('click',onClickNewNoteBannerShown)
        }, 1000);
    }

    // Sends a create request to backend
    function submitNewNote() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/api/create-note.php', true);
        xhr.setRequestHeader('Content-Type', 'application/xml');
        xhr.setRequestHeader('Accept', 'application/xml');

        const reqTitle = document.querySelector('#new-note-title').value;
        const reqBody = document.querySelector('#new-note-body').value;
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
                console.log(`Response: ${xhr.responseText}`);
                
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xhr.responseText, "application/xml");
                
                appendXMLNoteToDocument(xmlDoc);
                resetNewNote();
            } else {
                console.error('POST Request Failed:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network Error');
        };

        xhr.send(xmlData);
    }

    // sets all fields for new note to empty(initial value)
    // Also hides it
    function resetNewNote() {
        document.querySelector('#new-note-title').value = '';
        document.querySelector('#new-note-body').value = '';
        document.querySelector('#new-note-container').classList.add('hide-new-note-container');
        document.removeEventListener('click',onClickNewNoteBannerShown);
        expandTextArea(document.querySelector('#new-note-body'));
        console.log(document.querySelector('#new-note-body'))
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
    function appendXMLNoteToDocument(xmlDoc) {
        const note = document.createElement('div');
        note.classList.add('note');
        note.dataset.noteId = xmlDoc.querySelector('id').innerHTML;
        note.dataset.noteColor = xmlDoc.querySelector('color').innerHTML;
        note.dataset.noteTitle = xmlDoc.querySelector('title').innerHTML;
        note.dataset.noteBody = xmlDoc.querySelector('body').innerHTML;
        note.dataset.noteTimeUpdated = xmlDoc.querySelector('timeUpdated').innerHTML;
        note.dataset.noteTimeCreated = xmlDoc.querySelector('timeCreated').innerHTML;
        note.dataset.noteUnsavedChanges = "0";
        
        const titleElement = document.createElement('input');
        titleElement.type = "text";
        titleElement.placeholder = "Add Title Here";
        titleElement.classList.add('note-title');
        titleElement.disabled = true;
        note.appendChild(titleElement);
        
        const bodyElement = document.createElement('textarea');
        bodyElement.type = "text";
        bodyElement.placeholder = "Your note Here";
        bodyElement.classList.add('note-body');
        bodyElement.disabled = true;
        note.appendChild(bodyElement);
        
        const noteEditContainer = document.createElement('div');
        noteEditContainer.classList.add('note-edit-container');

        const noteDeleteButton = document.createElement('div');
        noteDeleteButton.classList.add('note-delete-button');
        noteEditContainer.appendChild(noteDeleteButton);

        for (let i = 0; i < noteColors.length; i++) {
            const colorBall = noteColors[i];
            
            const noteColorBall = document.createElement('div');
            noteColorBall.classList.add('note-color-ball');
            noteEditContainer.appendChild(noteColorBall);
            
        }

        const noteToolbar = document.createElement('div');
        noteToolbar.classList.add('note-toolbar');
        noteToolbar.appendChild(noteEditContainer);

        const noteEditButton = document.createElement('div');
        noteEditButton.classList.add('note-edit-button');
        noteToolbar.appendChild(noteEditButton);

        note.appendChild(noteToolbar);
        
        const noteContainer = document.querySelector('#note-container');
        noteContainer.prepend(note);
        iso.prepended(note);
        initializeNote(note);
        isotopeUpdate();
    }

    // Resizes the New note's body size
    function expandTextArea(textArea) {
        textArea.style.height = 'auto';
        var size = textArea.scrollHeight + 15;
        if (size < 200) size = 200; 
        textArea.style.height = size + 'px';
        isotopeUpdate();
    }

    // Populate all the notes using data attributes
    function initializeNotes() {
        var noteDivs = document.getElementsByClassName('note');
        for (var i = 0; i < noteDivs.length; i++) {
            const noteDiv = noteDivs[i];
            initializeNote(noteDiv);
        }
    }

    // Populate single note using data attributes
    function initializeNote(noteDiv) {

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

            noteDiv.dataset.noteUnsavedChanges = '1';
        } else { // Save data
            noteTitleElement.disabled = true;
            noteBodyElement.disabled = true;
        
            updateNote(noteDiv);
            
            noteDiv.dataset.noteUnsavedChanges = '0';
            
        }
    }

    // Updates the note on backend by reading input fields
    // if backend succeds, updates data attributes
    function updateNote(noteDiv) {
        const reqId = noteDiv.dataset.noteId;
        const newTitle = noteDiv.querySelector('.note-title').value;
        const newBody = noteDiv.querySelector('.note-body').value;
        const newColor = getSelectedNoteColor(noteDiv);

        // Send Update Request
        const xhr = new XMLHttpRequest();
        xhr.open('UPDATE', '/api/update-note.php', true);
        xhr.setRequestHeader('Content-Type', 'application/xml');
        xhr.setRequestHeader('Accept', 'application/xml');

        const xmlData = 
        `<request>
            <id>${reqId}</id>
            <title>${newTitle}</title>
            <body>${newBody}</body>
            <type>text</type>
            <color>${newColor}</color>
        </request>`;
        console.log(`Request: ${xmlData}`)

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                console.log(`Response: ${xhr.responseText}`);
                
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xhr.responseText, "application/xml");
                
                const oldLastUpdated = noteDiv.dataset.noteTimeUpdated;
                const newLastUpdated = xmlDoc.getElementsByTagName('timeUpdated')[0].innerHTML;
                if (oldLastUpdated == newLastUpdated) {
                    return;
                } 

                noteDiv.dataset.noteTitle = xmlDoc.getElementsByTagName('title')[0].innerHTML;
                noteDiv.dataset.noteBody = xmlDoc.getElementsByTagName('body')[0].innerHTML;
                noteDiv.dataset.noteColor = xmlDoc.getElementsByTagName('color')[0].innerHTML;
                noteDiv.dataset.noteType = xmlDoc.getElementsByTagName('type')[0].innerHTML;
                noteDiv.dataset.noteTimeUpdated = xmlDoc.getElementsByTagName('timeUpdated')[0].innerHTML;
                noteDiv.dataset.noteTimeCreated = xmlDoc.getElementsByTagName('timeCreated')[0].innerHTML;

                renderNote(noteDiv);
            } else {
                console.error('UPDATE Request Failed:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network Error');
        };

        xhr.send(xmlData);
        
    }

    // Updates note using data attributes
    // assumes note is already added to isotope
    function renderNote(noteDiv) {
        noteDiv.style.backgroundColor = `#${noteDiv.dataset.noteColor}`;

        noteDiv.querySelector('.note-title').value = noteDiv.dataset.noteTitle

        var noteBody = noteDiv.dataset.noteBody;
        const noteBodyElement = noteDiv.querySelector('.note-body');
        noteBodyElement.value = noteBody;
        expandTextArea(noteBodyElement);

        const colorDivs = noteDiv.querySelectorAll('.note-color-ball');
        for (let i = 0; i < colorDivs.length; i++) {
            const colorDiv = colorDivs[i];
            
            colorDiv.dataset.colorChecked = "0";
            if (noteColors[i] == noteDiv.dataset.noteColor) {
                colorDiv.dataset.colorChecked = "1";
            }
        }
        isotopeUpdate();
    }

    // Returns the selected note color for given note
    function getSelectedNoteColor(noteDiv) {
        const colorDivs = noteDiv.querySelectorAll('.note-color-ball');
        for (let i = 0; i < colorDivs.length; i++) {
            const colorDiv = colorDivs[i];
            if (colorDiv.dataset.colorChecked == "1") {
                return noteColors[i];
            }
        }
    }

    const newNoteInputBody = document.querySelector('#new-note-body')
    newNoteInputBody.addEventListener('input', ()=>{expandTextArea(newNoteInputBody) });
    window.addEventListener('resize', resizeNoteContainer);
    window.addEventListener('load', ()=> {
        expandTextArea(newNoteInputBody); // Expand the text area of new note
        selectColorForNewNote(0);
        initializeNotes(); // populates the notes by reading data attributes
        resizeNoteContainer(); // resizes the notes container to fit exactly by screen size
        isotopeUpdate();// re-render layout
    })

    function isotopeUpdate() {
        iso.updateSortData();
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
