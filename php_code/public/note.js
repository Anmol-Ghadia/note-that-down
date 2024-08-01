// Resizes the note container to expand only as much as needed
// noteSize (Number): the width of a note including padding and everything
// maxWidth (Number): the Maximum width available for the note container
// noteContainerSelector (string): valid selector for the container with notes
function resizeNoteContainerHelper(noteSize, maxWidth,noteContainerSelector) {
    const div = document.querySelector(noteContainerSelector);
    
    // Max number of columns that can fit in given size
    const columns =  Math.floor(maxWidth / noteSize);

    // Set width of container
    var width = columns * noteSize;
    div.style.width = width + 'px';
}

// Parse a new single note element from the given XMLdocument
function getNoteElementFromXML(xmlDoc) {
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
    
    return note;
}

// Populates a single note using data attributes
// noteDiv (HTMLElement): The note(HTMLElement) to be initialized
// deleteHandler (function): function that handles delete operation (Delete is handled externally)
// noteColors (Array): Array of strings representing color options (Hash symbol# is not part of string)
// saveHandler (function(HTMLElement)): function that is called upon save with the note Element
function initializeNoteHelper(noteDiv,deleteHandler,noteColors, saveHandler) {

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
    noteDeleteElement.addEventListener('click',deleteHandler);

    const colorDivs = noteDiv.querySelectorAll('.note-color-ball');
    for (let i = 0; i < colorDivs.length; i++) {
        const colorDiv = colorDivs[i];
        
        if (noteColors[i] == noteColor) {
            colorDiv.dataset.colorChecked = "1";
        }

        colorDiv.style.background = `#${noteColors[i]}`;
        colorDiv.addEventListener('click', ()=>{onClickHandleColorChange(noteDiv, i)});

    }

    noteDiv.querySelector('.note-edit-button').addEventListener('click',()=>{onClickEditNote(noteDiv,saveHandler)});
}

// Resizes textarea to display all text without scroll(or scrollbar)
// textArea (HTMLElement): textarea DOM element to be resized
function expandTextArea(textArea) {
    textArea.style.height = 'auto';
    var size = textArea.scrollHeight + 15;
    if (size < 200) size = 200; 
    textArea.style.height = size + 'px';
    isotopeUpdate();
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

// Handles edit toolbar interaction
// noteDiv (HTMLElement): note element whose toolbar is being handled
// saveDataFunction (function(HTMLElement)): function that is called upon save with the note Element
function onClickEditNote(noteDiv, saveDataFunction) {
    const noteTitleElement = noteDiv.querySelector('.note-title');
    const noteBodyElement = noteDiv.querySelector('.note-body');

    if (noteDiv.dataset.noteUnsavedChanges == "0") { // start Edit mode 
        noteTitleElement.disabled = false;
        noteBodyElement.disabled = false;

        noteDiv.dataset.noteUnsavedChanges = '1';
    } else { // Save data
        noteTitleElement.disabled = true;
        noteBodyElement.disabled = true;
        
        saveDataFunction(noteDiv);
        
        noteDiv.dataset.noteUnsavedChanges = '0';
        
    }
}