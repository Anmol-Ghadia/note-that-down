var initialTextAnimationComplete = false;
var dataText = ["Simple.", "Quick.", "Organized.", "Presenting", "...", "Note That Down!"];

var iso = new Isotope('#note-container', {
    transitionDuration: '1s',
});

function triggerAnimationStage2() {
    document.querySelector('#note-container').dataset.stage = "2";
    resizeNoteContainerHelper(
        noteSize,
        noteSize + 2, '#note-container');
    getDemoNotes();
}

function triggerAnimation() {
    document.querySelector('.try-now-button-container').dataset.stage = "1";
    document.querySelector('.try-now-button').dataset.stage = "1";
    document.querySelector('.typewriter-text').dataset.stage = "1";
    document.querySelector('main').dataset.stage = "1";
    document.querySelector('body').dataset.stage = "1";
    setTimeout(() => { triggerAnimationStage2() }, 3500);
}

// Updates the note on backend by reading input fields
// if backend succeds, updates data attributes
function getDemoNotes() {

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/public/data/demo-notes.xml', true);
    xhr.setRequestHeader('Content-Type', 'application/xml');
    xhr.setRequestHeader('Accept', 'application/xml');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log(`Response: ${xhr.responseText}`);

            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xhr.responseText, "application/xml");

            AddNotesToDocument(xmlDoc);

        } else {
            console.error('GET Request Failed:', xhr.statusText);
        }
    };

    xhr.onerror = function () {
        console.error('Network Error');
    };

    xhr.send();

}

async function AddNotesToDocument(notesXML) {
    const users = notesXML.querySelectorAll('note');
    var titleDelay = 250;
    var bodyDelay = 50;
    for (let i = 0; i < users.length; i++) {
        await delay(500 + Math.random() * 1500);
        const user = users[i];

        // const note = getNoteElementFromXML(user);
        const note = getEmptyNoteElement(noteColors, noteColors[i % noteColors.length]);
        initializeNote(note);
        // Add note to container

        document.querySelector('#note-container').append(note);
        note.dataset.stage = "2";
        iso.appended(note);
        isotopeUpdate();

        title = user.querySelector('title').innerHTML;
        for (let titleChar = 0; titleChar < title.length; titleChar++) {
            await delay(Math.random() * titleDelay);
            const char = title[titleChar];
            note.querySelector('.note-title').value += char;
        }
        body = user.querySelector('body').innerHTML;
        if (bodyDelay != 0) {
            // First Note
            for (let bodyChar = 0; bodyChar < body.length; bodyChar++) {
                await delay(Math.random() * 50);
                const char = body[bodyChar];
                bodyElement = note.querySelector('.note-body');
                bodyElement.value += char;
                expandTextArea(bodyElement);
                if (((body.length > 16) && ((bodyChar + 16) == body.length)) || ((body.length < 16) && (bodyChar == 0))) resizeNoteContainer();
            }
            setInterval(() => {
                resizeNoteContainer();
                isotopeUpdate();
            }, 1000);

        } else if (i < 2) {
            // Second note
            let bodyChar = 0;
            for (; bodyChar < body.length; bodyChar += 5) {
                await delay(Math.random() * 50);
                bodyElement = note.querySelector('.note-body');
                bodyElement.value += body.substring(bodyChar, bodyChar + 5);
                expandTextArea(bodyElement);
            }
            bodyElement = note.querySelector('.note-body');
            bodyElement.value += body.substring(bodyChar, body.length);
            expandTextArea(bodyElement);
        } else {
            bodyElement = note.querySelector('.note-body');
            bodyElement.value = body;
            expandTextArea(bodyElement);
        }
        titleDelay = 50;
        bodyDelay = 0;
    }
}

// Removes the note from page
function deleteNote(noteDiv) {
    iso.remove(noteDiv);
    isotopeUpdate();
}

// Populate single note using data attributes
function initializeNote(noteDiv) {
    initializeNoteHelper(
        noteDiv,
        () => { deleteNote(noteDiv) },
        noteColors,
        () => { })
}

function isotopeUpdate() {
    iso.arrange();
}

// Resizes the note container to fit exactly inside the viewport
function resizeNoteContainer() {
    resizeNoteContainerHelper(
        noteSize,
        document.querySelector('#note-container-area').clientWidth, '#note-container');
    isotopeUpdate();
}

document.addEventListener('DOMContentLoaded', function (event) {
    StartTextAnimation(0);
});

window.addEventListener('resize', () => {
    resizeNoteContainer();
});

// type one text in the typwriter
// keeps calling itself until the text is finished
function typeWriter(text, i, fnCallback) {
    if (i < (text.length)) {
        document.querySelector(".typewriter-text").innerHTML = text.substring(0, i + 1) +
            '<span class="typewriter-span" aria-hidden="true"></span>';

        setTimeout(function () {
            typeWriter(text, i + 1, fnCallback)
        }, 100);
    } else if (typeof fnCallback == 'function') {
        setTimeout(fnCallback, 700);
    }
}

function StartTextAnimation(i) {
    if (typeof dataText[i] == 'undefined') {
        if (!initialTextAnimationComplete) {
            initialTextAnimationComplete = true;
            triggerAnimation()
        }
        setTimeout(function () {
            StartTextAnimation(0);
        }, 30000);
    }
    if (i < dataText.length) {
        typeWriter(dataText[i], 0, function () {
            StartTextAnimation(i + 1);
        });
    }
}