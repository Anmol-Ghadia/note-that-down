<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    return;
}

include 'sql.php';
include 'helpers/notes_params.php';

function createNoteDiv(stdClass $data): string
{
    return '
    <div class="note"
        data-note-id="' . htmlspecialchars($data->id, ENT_QUOTES, 'UTF-8') . '"
        data-note-color="' . htmlspecialchars($data->color, ENT_QUOTES, 'UTF-8') . '"
        data-note-title="' . htmlspecialchars($data->title, ENT_QUOTES, 'UTF-8') . '"
        data-note-type="' . htmlspecialchars($data->type, ENT_QUOTES, 'UTF-8') . '"
        data-note-body="' . htmlspecialchars($data->body, ENT_QUOTES, 'UTF-8') . '"
        data-note-time-created="' . htmlspecialchars($data->created_at, ENT_QUOTES, 'UTF-8') . '"
        data-note-time-updated="' . htmlspecialchars($data->updated_at, ENT_QUOTES, 'UTF-8') . '"
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note That Down!</title>
    <link rel="stylesheet" href="/public/styles/note.css">
</head>

<body>
    <main>

        <div id='header'>
            <div></div>
            <p>Your Noteboard</p>
            <button id="logout-button" class='control-button' onclick="handleExit()"></button>
        </div>
        <button id='start-new-note-button' class='control-button' onclick="startNewNote()"></button>

        <!-- New note -->
        <div id="new-note-container" class="hide-new-note-container">
            <div id="new-note" class="note">
                <input type="text" id="new-note-title" class="note-title" placeholder="Create new Note">
                <textarea id="new-note-body" class="note-body" placeholder="Add note here"></textarea>
                <div id="new-note-edit-container" class="note-edit-container">
                    <div data-checked="1" onclick="selectColorForNewNote(0)" class="new-note-color-ball note-color-ball"
                        style="background-color: #<?php echo $note_colors['0'] ?>"></div>
                    <div data-checked="0" onclick="selectColorForNewNote(1)" class="new-note-color-ball note-color-ball"
                        style="background-color: #<?php echo $note_colors['1'] ?>"></div>
                    <div data-checked="0" onclick="selectColorForNewNote(2)" class="new-note-color-ball note-color-ball"
                        style="background-color: #<?php echo $note_colors['2'] ?>"></div>
                    <div data-checked="0" onclick="selectColorForNewNote(3)" class="new-note-color-ball note-color-ball"
                        style="background-color: #<?php echo $note_colors['3'] ?>"></div>
                    <div data-checked="0" onclick="selectColorForNewNote(4)" class="new-note-color-ball note-color-ball"
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
    </main>
</body>
<script src="/public/scripts/isotope.pkgd.min.js"></script>
<script src="/public/scripts/note.js"></script>
<script>
    const noteColors = <?php echo json_encode($note_colors); ?>;
    // (width + padding + 5px for extra space)
    const noteSize = <?php echo $note_width ?> + 20 + 5; // in px 
    
    var iso = new Isotope('#note-container', {
        transitionDuration: '0.4s',
        getSortData: {
            sortValue: '[data-note-time-updated]'
        }
    });

    // Resizes the note container to fit exactly inside the viewport
    function resizeNoteContainer() {
        resizeNoteContainerHelper(noteSize, document.documentElement.clientWidth, '#note-container');
    }

    // Asks the server to end session
    function handleExit() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/api/logout.php', true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                window.location.href = '/';
            } else {
                console.error('POST Request Failed:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network Error');
        };

        xhr.send();
    }

    // Sets the color for new note
    function selectColorForNewNote(newColor) {
        const colors = document.querySelectorAll('.new-note-color-ball');
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
            document.removeEventListener('click', onClickNewNoteBannerShown);
        }
    }

    // Displays A new note on the screen.
    function startNewNote() {
        document.querySelector('#new-note-container').classList.remove('hide-new-note-container');

        setTimeout(() => {
            document.addEventListener('click', onClickNewNoteBannerShown)
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
                // console.log(`Response: ${xhr.responseText}`);

                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xhr.responseText, "application/xml");

                const note = getNoteElementFromXML(xmlDoc);
                // Add note to container

                document.querySelector('#note-container').prepend(note);
                iso.prepended(note);
                initializeNote(note);
                isotopeUpdate();
                // Reset the new note element
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
        document.removeEventListener('click', onClickNewNoteBannerShown);
        expandTextArea(document.querySelector('#new-note-body'));
    }

    // Returns the string representing color of new note
    function getNewNoteSelectedColor() {
        const colors = document.querySelectorAll('.new-note-color-ball');
        for (let i = 0; i < colors.length; i++) {
            const colorDiv = colors[i];
            if (colorDiv.dataset.checked == "1") {
                return noteColors[i];
            }

        }
    }

    // Populate all the notes using data attributes
    function initializeNotes() {
        var noteDivs = document.getElementsByClassName('note');
        for (var i = 1; i < noteDivs.length; i++) {
            const noteDiv = noteDivs[i];
            initializeNote(noteDiv);
        }
    }

    // Populate single note using data attributes
    function initializeNote(noteDiv) {
        initializeNoteHelper(
            noteDiv,
            () => { deleteNote(noteDiv) },
            noteColors,
            updateNote)
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
        // console.log(`Request: ${xmlData}`)

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // console.log(`Response: ${xhr.responseText}`);

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
    newNoteInputBody.addEventListener('input', () => { expandTextArea(newNoteInputBody) });
    window.addEventListener('resize', resizeNoteContainer);
    window.addEventListener('load', () => {
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
<style>
    .note {
        width:<?php echo $note_width ?>px;
    }

    * {
        box-sizing: border-box;
        margin: 0px;
        padding: 0px;
    }

    h1 {
        color: #333;
    }

    @font-face {
        font-family: 'Handlee';
        src: url('/public/fonts/Handlee-Regular.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    body {
        font-family: 'Handlee', Arial, sans-serif;
        background-color: #03346E;
        background-image:
            radial-gradient(ellipse at top, #021526 35%, transparent),
            radial-gradient(ellipse at center, #03346E, transparent),
            linear-gradient(to top, #6EACDA, transparent);
        background-size: 100vw 100%;
        color: white;
        overflow: hidden;
    }

    main {
        width: 100vw;
        height: 100vh;
        overflow-x: hidden;
        overflow-y: scroll;
        padding: min(2vh, 2vw);
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
        top: min(2vh, 2vw);
        left: min(2vh, 2vw);
        width: 5vh;
        height: 5vh;
        z-index: 75;
        background-image: url('/public/images/square-plus-solid.svg');
    }

    #logout-button {
        background-image: url('/public/images/right-from-bracket-solid.svg');
    }

    .control-button {
        height: 100%;
        aspect-ratio: 1;
        background-color: transparent;
        border: none;
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

    #new-note:hover {
        animation: none 1s linear 0s infinite;
    }

    #new-note-edit-container {
        width: 100%;
        height: 30px;
        display: flex;
        justify-content: right;
        align-items: center;
    }

    #note-container-wide {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    #footer {
        width: 100%;
        height: 20vh;
    }

    main::-webkit-scrollbar {
        width: 1vw;
    }

    main::-webkit-scrollbar-track {
        background: transparent;
    }

    main::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border: 0px solid black;
        border-radius: 1vw;
        height: 35vh;
        opacity: 0;
    }

    main::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
</html>