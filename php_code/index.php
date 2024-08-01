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

            height: 100vh;
            overflow: hidden;
            display: grid;
            grid-template-columns: 100% 0%;
            font-family: Georgia, sans;
        }

        body[data-stage="1"] {
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
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .typewriter-text {
            position: relative;
            font-size: 5em;
            font-weight: 1000;
            color: white;
            text-transform: uppercase;
            font-family: Georgia, 'tradegothiclt-bold', sans-serif;
            transition: color 4s;
            text-align: right;
        }

        .typewriter-text[data-stage="1"] {
            color: black;
        }

        .typewriter-text::before {
            content: '';
            height: 80%;
            position: absolute;
            top: 300%;
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

        .try-now-button {
            position: absolute;
            right: 80%;
            top: 0%;
            background: black;
            color: white;
            transition: 
                background-color 250ms,
                color 250ms,
                opacity 3s ease 5s,
                right 4s ease 4s;
            opacity: 0;
            padding: min(1vh,1vw) min(2vh,2vw);
            border-radius: 100px;
        }

        .try-now-button-container {
            width: 100%;
            position: relative;
        }

        .try-now-button[data-stage="1"] {
            opacity: 1;
            right: 10%;
        } 

        .try-now-button:hover {
            background: #6EACDA;
            color: black;
        }

        #note-container-area {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
            overflow: hidden;
            overflow-y: scroll;
        }
        
        #note-container-area::-webkit-scrollbar {
            width: 1vw;
        }

        #note-container-area::-webkit-scrollbar-track {
            background: transparent;
        }

        #note-container-area::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border: 0px solid black;
            border-radius: 1vw;
            height: 35vh;
            opacity: 0;
        }
        
        #note-container-area::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }

        #note-container {
            margin-top: 100vh; 
            width: 100%;
            display: flex;
            justify-content: center;
            transition: margin-top 2s ease-out;
            height: 100vh;
        }

        #note-container[data-stage="2"] {
            margin-top: 0px; 
            width: auto;
        }

        .note {
            width: <?php echo $note_width?>px;
            transition: opacity 1s ease-in-out;
            opacity: 0;
        }

        /* Initial Visibility */
        .note[data-stage="2"] {
            opacity: 1;
        }

    </style>
</head>
<body>
    <div class='typewriter-container'>
        <div class="typewriter-text">
            Note That Down!
        </div>
        <div class='try-now-button-container'>
            <div class="try-now-button" onclick="window.location.href='signup.php'">Try now</div>
        </div>
    </div>
    <div id="note-container-area">
        <!-- <div id="note-container-spacer"></div> -->
        <div id="note-container">
            
            <!-- !!!!!!!!!!!!!!!!!!!! -->
            <div class="note" id="note-1-1" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Healthy Eating" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 250px;">
Eating a balanced diet is crucial for maintaining good health. Incorporating a variety of fruits, vegetables, whole grains, lean proteins, and healthy fats can provide essential nutrients and support overall well-being. It's important to listen to your body and make mindful food choices that nourish you.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-1-2" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(253, 255, 182);">
        <input type="text" placeholder="Add Title Here" value="Importance of Sleep" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 420px;">
Sleep is vital for physical and mental health. It allows the body to repair itself and the brain to consolidate memories.
Adults typically need 7-9 hours of sleep each night.
Poor sleep can lead to various health issues, including weakened immune function and increased stress levels.
Establishing a relaxing bedtime routine and maintaining a consistent sleep schedule can greatly improve sleep quality.
Sleep is vital for physical and mental health. It allows the body to repair itself and the brain to consolidate memories.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-1-3" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(255, 198, 255);">
        <input type="text" placeholder="Add Title Here" value="Tips for Studying" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 330px;">
To maximize your study sessions, create a dedicated study space free from distractions.
Use active learning techniques, like summarizing information in your own words or teaching it to someone else.
Break study time into focused intervals using the Pomodoro Technique—25 minutes of studying followed by a 5-minute break.
This approach can enhance retention and reduce burnout.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-1-4" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(202, 255, 191);">
        <input type="text" placeholder="Add Title Here" value="Tips for Studying" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 350px;">
To maximize your study sessions, create a dedicated study space free from distractions.
Use active learning techniques, like summarizing information in your own words or teaching it to someone else.
Break study time into focused intervals using the Pomodoro Technique—25 minutes of studying followed by a 5-minute break.
This approach can enhance retention and reduce burnout.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>


    <div class="note" id="note-1-5" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Healthy Eating" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 275px;">
Eating a balanced diet is crucial for maintaining good health. Incorporating a variety of fruits, vegetables, whole grains, lean proteins, and healthy fats can provide essential nutrients and support overall well-being. It's important to listen to your body and make mindful food choices that nourish you.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>


    <!-- end of column 1 -->

            <div class="note"  id="note-2-1" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(255, 198, 255);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 540px;">
Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde iste officia harum blanditiis totam pariatur ut deserunt modi asperiores necessitatibus illo hic minus aliquam rerum laboriosam sit repellat, accusantium ad.
Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa repellat voluptates quasi dicta adipisci rem, sint error dolores iure, id odit, repudiandae sapiente perspiciatis saepe exercitationem consectetur laborum doloribus non!
Lorem ipsum dolor sit amet, consectetur adipisicing elit. At doloribus voluptas voluptatum quis pariatur consectetur ullam, reprehenderit voluptate architecto! Autem eligendi laboriosam dicta ipsam laborum neque officia! Debitis, voluptatem accusantium.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-2-2" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(255, 214, 165);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 355px;">
Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatibus eaque possimus architecto fuga quidem magni a facilis doloremque,
dolores aspernatur delectus eligendi! Voluptatibus illum veniam natus animi maiores saepe aspernatur?
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus, impedit culpa. Nemo optio nulla non incidunt ad obcaecati, quibusdam reiciendis
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-2-3" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(255, 198, 255);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 210px;">
Lorem ipsum, dolor sit amet consectetur adipisicing elit. Labore expedita laboriosam ad velit quis! Illo autem nam totam tenetur!

Quas laudantium aperiam provident ullam reiciendis nisi cumque labore modi debitis.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>


    <div class="note"  id="note-2-4" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 315px;">
Lorem ipsum, dolor sit amet consectetur adipisicing elit. Labore expedita laboriosam ad velit quis! Illo autem nam totam tenetur!
Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit laborum aliquid id quam atque omnis, expedita sunt suscipit soluta accusantium dolorum, natus incidunt voluptatibus perspiciatis eos iste voluptas provident ipsum.
Quas laudantium aperiam provident ullam reiciendis nisi cumque labore modi debitis.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-2-5" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(255, 214, 165);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 355px;">
Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatibus eaque possimus architecto fuga quidem magni a facilis doloremque,
dolores aspernatur delectus eligendi! Voluptatibus illum veniam natus animi maiores saepe aspernatur?
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus, impedit culpa. Nemo optio nulla non incidunt ad obcaecati, quibusdam reiciendis
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-button"></div>
        </div>
    </div>










            <!-- !!!!!!!!!!!!!!!!!!!! -->
        </div>
    </div>
</body>
<script src="js/isotope.pkgd.min.js"></script>
<script src="public/note.js"></script>
<script>

    const noteColors = <?php echo json_encode($note_colors); ?>;
    const noteSize = <?php echo $note_width?> + 20 + 5;
    var initialTextAnimationComplete = false;
    var dataText = [ "..."];
    // var dataText = [ "Simple.", "Quick.", "Organized." , "Presenting" , "...", "Note That Down!"];

    var iso = new Isotope( '#note-container', {
        transitionDuration: '0.4s',
    });

    function triggerAnimationStage2() {
        document.querySelector('#note-container').dataset.stage = "2";
        setTimeout(() => { document.querySelector('#note-1-1').dataset.stage = "2"; }, 000  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-1-2').dataset.stage = "2"; }, 900  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-1-3').dataset.stage = "2"; }, 000  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-1-4').dataset.stage = "2"; }, 600  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-1-5').dataset.stage = "2"; }, 900  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-1').dataset.stage = "2"; }, 000  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-2').dataset.stage = "2"; }, 300  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-3').dataset.stage = "2"; }, 600  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-4').dataset.stage = "2"; }, 300  + Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-5').dataset.stage = "2"; }, 600  + Math.random()*1000);
        resizeNoteContainer(); 
        iso.arrange();
    }

    function triggerAnimation() {
        document.querySelector('.try-now-button-container').dataset.stage = "1";
        document.querySelector('.try-now-button').dataset.stage = "1";
        document.querySelector('.typewriter-text').dataset.stage = "1";
        document.body.dataset.stage = "1";
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

    function AddNotesToDocument(notesXML) {
        const users = notesXML.querySelectorAll('note');
        for (let i = 0; i < users.length; i++) {
            const user = users[i];
            
            const note = getNoteElementFromXML(user);
            // Add note to container
            
            document.querySelector('#note-container').append(note);
            note.dataset.stage = "2";
            iso.appended(note);
            initializeNote(note);
            isotopeUpdate();
        }
    }

    // Populate single note using data attributes
    function initializeNote(noteDiv) {
        initializeNoteHelper(
            noteDiv,
            ()=>{  },
            noteColors,
            ()=>{  })
    }

    function isotopeUpdate() {
        iso.arrange();
    }

    // Resizes the note container to fit exactly inside the viewport
    function resizeNoteContainer() {
        resizeNoteContainerHelper(noteSize,document.documentElement.clientWidth/2,'#note-container');
    }

    document.addEventListener('DOMContentLoaded',function(event){
        StartTextAnimation(0);
        getDemoNotes();
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
            }, 15000);
        }
        if (i < dataText.length) {
            typeWriter(dataText[i], 0, function(){
                StartTextAnimation(i + 1);
            });
        }
    }
</script>
</html>
