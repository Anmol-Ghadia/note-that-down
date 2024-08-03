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
    <style>
        .note {
            width:<?php echo $note_width ?>px;
        }
    </style>
    <link rel="stylesheet" href="/public/styles/pages/notes.css">
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
</script>
<script src="/public/scripts/pages/notes.js"></script>

</html>