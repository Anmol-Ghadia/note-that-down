<?php
// index.php
include 'helpers/notes_params.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Project</title>
    <link rel="stylesheet" href="public/note.css">
    <style>
        @font-face {
            font-family: 'Handlee';
            src: url('public/Handlee-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-family: Georgia, sans-serif;
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        body {
            background-color: #03346E;
            background-image: linear-gradient(to top right, #021526, #03346E, #6EACDA);
            background-size: 200% 100%;
            background-position: 0% 50%;
            width: 100vw;
            height: 100vh;
            font-family: Georgia, sans;
            overflow: hidden;
            padding: 0px;
            margin: 0px;
        }

        main {
            width: 100%;
            height: 100%;
            padding: 0px;
            margin: 0px;
            display: grid;
        }

        @media (orientation: portrait) {
            main {
                grid-template-rows: 100% 0%;
                grid-template-columns: auto;
                overflow-x: hidden;
                overflow-y: scroll;
            }

            main[data-stage="1"] {
                animation: body-grid-row-expansion 4s;
                animation-fill-mode: forwards;
            }

            @keyframes body-grid-row-expansion {
                0% {
                    grid-template-rows: 100% 0%;
                    background-position: 0% 50%;
                }
                100% {
                    grid-template-rows: 30% 70%;
                    background-position: 100% 50%;
                }
            }

            .typewriter-container {
                width: 100%;
            }

            .typewriter-text {
                font-size: 8vw
            }

            .typewriter-text::before {
                top: 150%;
            }
        }
        
        @media (orientation: landscape) {
            main {
                grid-template-columns: 100% 0%;
                grid-template-rows: auto;
            }

            main[data-stage="1"] {
                animation: body-grid-column-expansion 4s;
                animation-fill-mode: forwards;
            }

            @keyframes body-grid-column-expansion {
                0% {
                    grid-template-columns: 100% 0%;
                    background-position: 0% 50%;
                }
                100% {
                    grid-template-columns: 50% 50%;
                    background-position: 100% 50%;
                }
            }

            .typewriter-container {
                height: 100%;
            }

            #note-container-area {
                overflow: hidden;
                overflow-y: scroll;
            }

            .typewriter-text {
                font-size: 6vw
            }

            .typewriter-text::before {
                top: 300%;
            }
        }

        .typewriter-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .typewriter-text {
            user-select: none;
            position: relative;
            font-weight: 1000;
            color: white;
            text-transform: uppercase;
            font-family: Georgia, 'tradegothiclt-bold', sans-serif;
            transition: color 4s;
            text-align: center;
        }

        .typewriter-text[data-stage="1"] {
            color: black;
        }

        .typewriter-text::before {
            content: '';
            height: 80%;
            position: absolute;
            left: 0%;
            right: 0%;
            background-image: radial-gradient(ellipse, rgba(0,0,0,0.3), transparent 50%);
        }

        .typewriter-span {
            border-right: .05em solid;
            animation: typewriter-caret-animation 1s steps(1) infinite;
        }

        @keyframes typewriter-caret-animation {
            50% {
                border-color: transparent;
            }
        }

        .try-now-button-container {
            width: 100%;
            position: relative;
        }

        .try-now-button {
            position: absolute;
            left: 0%;
            top: 0%;
            background: black;
            color: white;
            font-size: 1.25em;
            transition: 
                background-color 250ms,
                color 250ms,
                opacity 3s ease 5s,
                transform 2s ease 4s,
                left 2s ease 4s;
            opacity: 0;
            padding: min(1vh,1vw) min(2vh,2vw);
            border-radius: 100px;
            user-select: none;
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .try-now-button[data-stage="1"] {
            left: 50%;
            transform: translateX(-50%);
            opacity: 1;
        }

        .try-now-button:hover {
            background: #6EACDA;
            color: black;
        }

        .try-now-button:hover .try-now-arrow-image {
            animation: none;
            margin-left: 1.5vw;
            margin-right: 0vw;
        }

        .try-now-arrow-image {
            margin-left: 0.5vw;
            margin-right: 1vw;
            height: 1em;
            aspect-ratio: 1;
            background-image: url('public/arrow-right-long-solid.svg');
            background-size: 100% 100%;
            background-position: 50% 50%;
            background-repeat: no-repeat;
            animation: try-now-image-animation 3s cubic-bezier(0.68,-0.55,0.265,1.55) ;
            animation-iteration-count: infinite;
            animation-direction: alternate;
            transition: margin 150ms;
        }

        @keyframes try-now-image-animation {
            0% {
                margin-left: 0.5vw;
                margin-right: 1vw;
            }
            90% {
                margin-left: 0.5vw;
                margin-right: 1vw;
            }
            100% {
                margin-left: 1.5vw;
                margin-right: 0vw;
            }
        }

        #note-container-area {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
        }
        
        ::-webkit-scrollbar {
            width: 1vw;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border: 0px solid black;
            border-radius: 1vw;
            height: 35vh;
            opacity: 0;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }

        #note-container {
            width: 100%;
            display: flex;
            justify-content: center;
            transition: height 500ms ease-out,width 1.500s ease-in;
            height: 100vh;
        }

        #note-container[data-stage="2"] {
            width: auto;
        }

        .note {
            width: <?php echo $note_width?>px;
            transition: opacity 1s ease-in-out;
            opacity: 1;
        }

    </style>
</head>
<body>
    <main>
        <div class='typewriter-container'>
            <div class="typewriter-text">
                Note That Down!
            </div>
            <div class='try-now-button-container'>
                <div class="try-now-button" onclick="window.location.href='signup.php'">
                    Try now
                    <div class="try-now-arrow-image"></div>
                </div>
            </div>
        </div>
        <div id="note-container-area">
            <div id="note-container">            
            </div>
        </div>
    </main>
</body>
<script src="js/isotope.pkgd.min.js"></script>
<script src="public/note.js"></script>
<script>

    const noteColors = <?php echo json_encode($note_colors); ?>;
    const noteSize = <?php echo $note_width?> + 20 + 5;
    var initialTextAnimationComplete = false;
    var dataText = [ "Simple.", "Quick.", "Organized." , "Presenting" , "...", "Note That Down!"];

    var iso = new Isotope( '#note-container', {
        transitionDuration: '1s',
    });

    function triggerAnimationStage2() {
        document.querySelector('#note-container').dataset.stage = "2";
        resizeNoteContainerHelper(
            noteSize,
            noteSize+2,'#note-container');
        getDemoNotes();
    }

    function triggerAnimation() {
        document.querySelector('.try-now-button-container').dataset.stage = "1";
        document.querySelector('.try-now-button').dataset.stage = "1";
        document.querySelector('.typewriter-text').dataset.stage = "1";
        document.querySelector('main').dataset.stage = "1";
        setTimeout(()=>{triggerAnimationStage2()}, 3500);
    }

    // Updates the note on backend by reading input fields
    // if backend succeds, updates data attributes
    function getDemoNotes() {

        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/api/demo-notes.php', true);
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
            await delay(500+Math.random()*1500);
            const user = users[i];
            
            // const note = getNoteElementFromXML(user);
            const note = getEmptyNoteElement(noteColors,noteColors[i%noteColors.length]);
            initializeNote(note);
            // Add note to container
            
            document.querySelector('#note-container').append(note);
            note.dataset.stage = "2";
            iso.appended(note);
            isotopeUpdate();
            
            title = user.querySelector('title').innerHTML;
            for (let titleChar = 0; titleChar < title.length; titleChar++) {
                await delay(Math.random()*titleDelay);
                const char = title[titleChar];
                note.querySelector('.note-title').value += char;
            }
            body = user.querySelector('body').innerHTML;
            if (bodyDelay != 0) {
                // First Note
                for (let bodyChar = 0; bodyChar < body.length; bodyChar++) {
                    await delay(Math.random()*50);
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
                for (;bodyChar < body.length; bodyChar+=5) {
                    await delay(Math.random()*50);
                    bodyElement = note.querySelector('.note-body');
                    bodyElement.value += body.substring(bodyChar,bodyChar+5);
                    expandTextArea(bodyElement);
                }
                bodyElement = note.querySelector('.note-body');
                bodyElement.value += body.substring(bodyChar,body.length);
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
            ()=>{deleteNote(noteDiv)},
            noteColors,
            ()=>{  })
    }

    function isotopeUpdate() {
        iso.arrange();
    }

    // Resizes the note container to fit exactly inside the viewport
    function resizeNoteContainer() {
        resizeNoteContainerHelper(
            noteSize,
            document.querySelector('#note-container-area').clientWidth,'#note-container');
        isotopeUpdate();
    }

    document.addEventListener('DOMContentLoaded',function(event){
        StartTextAnimation(0);
    });

    window.addEventListener('resize', ()=>{
        resizeNoteContainer();
    });

    // type one text in the typwriter
    // keeps calling itself until the text is finished
    function typeWriter(text, i, fnCallback) {
    if (i < (text.length)) {
        document.querySelector(".typewriter-text").innerHTML = text.substring(0, i+1) +
            '<span class="typewriter-span" aria-hidden="true"></span>';

        setTimeout(function() {
        typeWriter(text, i + 1, fnCallback)
        }, 100);
    } else if (typeof fnCallback == 'function')
        {
            setTimeout(fnCallback, 700);
        }
    }

    function StartTextAnimation(i) {
        if (typeof dataText[i] == 'undefined'){
            if (!initialTextAnimationComplete) {
                initialTextAnimationComplete = true;
                triggerAnimation()
            }
            setTimeout(function() {
                StartTextAnimation(0);
            }, 30000);
        }
        if (i < dataText.length) {
            typeWriter(dataText[i], 0, function(){
                StartTextAnimation(i + 1);
            });
        }
    }
</script>
</html>
