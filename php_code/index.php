<?php include 'helpers/notes_params.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note That Down!</title>
    <link rel="stylesheet" href="/public/styles/note.css">
    <link rel="stylesheet" href="/public/styles/pages/index.css">
    <style>
        .note {
            width:<?php echo $note_width ?>px;
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
<script src="public/scripts/isotope.pkgd.min.js"></script>
<script src="public/scripts/note.js"></script>
<script>
    const noteColors = <?php echo json_encode($note_colors); ?>;
    const noteSize = <?php echo $note_width ?> + 20 + 5;
</script>
<script src="/public/scripts/pages/index.js"></script>

</html>