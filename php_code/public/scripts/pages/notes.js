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
            console.log(`Response: ${xhr.responseText}`);

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
    console.log(document.querySelector('#new-note-body'))
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