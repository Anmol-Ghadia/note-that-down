<?php
// index.php

// Display a simple web page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Project</title>
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
        /* body { */
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
            /* border: 1px solid red; */
            width: auto;
            height: auto;
            overflow: hidden;
        }

        #note-container {
            /* border: 1px solid green; */
            width: 100%;
            display: flex;
            justify-content: center;
            transition: opacity 4s;
            opacity: 0;
            height: auto;
        }

        #note-container[data-stage="2"] {
            opacity: 1;
        }

        /* NOTE STYLES BELOW THIS */
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

        .note {
            width: 325px;
            border: 4px solid rgba(255,255,255,0.4);
            padding: 10px;
            margin: 10px;
            background: white;
            border-radius: 20px;
            transition: 
                box-shadow 150ms ease-out,
                opacity 2s;
            opacity: 0;
        }

        .note[data-stage="3"] {
            opacity: 1;
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

        .note-title::selection {
            background-color: rgba(100,100,100,0.5); Background color for selected text
            color: white;
        }

        .note-body {
            width: 100%;
            background: transparent;
            font-size: 1.25em;
            font-family: Georgia sans-serif;
            color: black;
        }

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
        <div id="note-container">
            <!-- !!!!!!!!!!!!!!!!!!!! -->
             <div class="note-container-column-1">
            <div class="note" id="note-1-1" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Healthy Eating" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 200px;">
Eating a balanced diet is crucial for maintaining good health. Incorporating a variety of fruits, vegetables, whole grains, lean proteins, and healthy fats can provide essential nutrients and support overall well-being. It's important to listen to your body and make mindful food choices that nourish you.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 214, 165);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(253, 255, 182);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(202, 255, 191);"></div>
                <div data-color-checked="1" class="note-color-ball" style="background: rgb(160, 196, 255);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 198, 255);"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-1-2" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Importance of Sleep" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 300px;">
Sleep is vital for physical and mental health. It allows the body to repair itself and the brain to consolidate memories. Adults typically need 7-9 hours of sleep each night. Poor sleep can lead to various health issues, including weakened immune function and increased stress levels. Establishing a relaxing bedtime routine and maintaining a consistent sleep schedule can greatly improve sleep quality.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 214, 165);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(253, 255, 182);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(202, 255, 191);"></div>
                <div data-color-checked="1" class="note-color-ball" style="background: rgb(160, 196, 255);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 198, 255);"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-1-3" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Tips for Studying" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 300px;">
To maximize your study sessions, create a dedicated study space free from distractions. Use active learning techniques, like summarizing information in your own words or teaching it to someone else. Break study time into focused intervals using the Pomodoro Technique—25 minutes of studying followed by a 5-minute break. This approach can enhance retention and reduce burnout.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 214, 165);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(253, 255, 182);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(202, 255, 191);"></div>
                <div data-color-checked="1" class="note-color-ball" style="background: rgb(160, 196, 255);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 198, 255);"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-1-4" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Tips for Studying" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 300px;">
To maximize your study sessions, create a dedicated study space free from distractions. Use active learning techniques, like summarizing information in your own words or teaching it to someone else. Break study time into focused intervals using the Pomodoro Technique—25 minutes of studying followed by a 5-minute break. This approach can enhance retention and reduce burnout.
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 214, 165);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(253, 255, 182);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(202, 255, 191);"></div>
                <div data-color-checked="1" class="note-color-ball" style="background: rgb(160, 196, 255);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 198, 255);"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>


    <!-- end of column 1 -->
    </div>

    <div class="note-container-column-2">
            <div class="note"  id="note-2-1" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 475px;">
can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast  can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast 
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 214, 165);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(253, 255, 182);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(202, 255, 191);"></div>
                <div data-color-checked="1" class="note-color-ball" style="background: rgb(160, 196, 255);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 198, 255);"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-2-2" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 475px;">
can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast  can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast knowledge and with information from the web.Browse the web
ChatGPT can answer your questions using its vast 
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 214, 165);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(253, 255, 182);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(202, 255, 191);"></div>
                <div data-color-checked="1" class="note-color-ball" style="background: rgb(160, 196, 255);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 198, 255);"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>

    <div class="note"  id="note-2-3" data-note-type="text" data-note-unsaved-changes="0" style="background-color: rgb(160, 196, 255);">
        <input type="text" placeholder="Add Title Here" value="Title Here" class="note-title" disabled="">
        <textarea type="text" class="note-body" placeholder="Your note here" disabled="" style="height: 200px;">
can answer your questions using its vast knowledge and with information from the web.Browse the web
        </textarea>
        <div class="note-toolbar">
            <div class="note-edit-container">
                <div class="note-delete-button"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 214, 165);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(253, 255, 182);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(202, 255, 191);"></div>
                <div data-color-checked="1" class="note-color-ball" style="background: rgb(160, 196, 255);"></div>
                <div data-color-checked="0" class="note-color-ball" style="background: rgb(255, 198, 255);"></div>
                </div>
            <div class="note-edit-button"></div>
        </div>
    </div>

    </div>
    <!-- End of column 2 -->




            <!-- !!!!!!!!!!!!!!!!!!!! -->
        </div>
    </div>
</body>
<script>

    function triggerAnimationStage2() {
        document.querySelector('#note-container').dataset.stage = "2";
        setTimeout(() => { document.querySelector('#note-1-1').dataset.stage = "3"; }, Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-1-2').dataset.stage = "3"; }, Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-1-3').dataset.stage = "3"; }, Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-1-4').dataset.stage = "3"; }, Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-1').dataset.stage = "3"; }, Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-2').dataset.stage = "3"; }, Math.random()*1000);
        setTimeout(() => { document.querySelector('#note-2-3').dataset.stage = "3"; }, Math.random()*1000);
    }

    function triggerAnimation() {
        document.querySelector('.try-now-button-container').dataset.stage = "1";
        document.querySelector('.try-now-button').dataset.stage = "1";
        document.querySelector('.typewriter-text').dataset.stage = "1";
        document.body.dataset.stage = "1";
        setTimeout(()=>{triggerAnimationStage2()}, 4500);
    }

    var initialTextAnimationComplete = false;
    // var dataText = [ "Simple.", "Quick.", "Organized." , "Presenting" , "...", "Note That Down!"];
    var dataText = [ "...", "Note That Down!"];

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

    document.addEventListener('DOMContentLoaded',function(event){
        StartTextAnimation(0);
    });
</script>
</html>
