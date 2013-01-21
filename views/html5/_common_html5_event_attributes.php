<?php
//Window Event Attributes

$events = array(
"onafterprintNew", //Script to be run after the document is printed
"onbeforeprintNew", //Script to be run before the document is printed
"onbeforeonloadNew", //Script to be run before the document loads
"onblur", //Script to be run when the window loses focus
"onerrorNew", //Script to be run when an error occur
"onfocus", //Script to be run when the window gets focus
"onhaschangeNew", //Script to be run when the document has changed
"onload", //Script to be run when the document loads
"onmessageNew", //Script to be run when the message is triggered
"onofflineNew", //Script to be run when the document goes offline
"ononlineNew", //Script to be run when the document comes online
"onpagehideNew", //Script to be run when the window is hidden
"onpageshowNew", //Script to be run when the window becomes visible
"onpopstateNew", //Script to be run when the window's history changes
"onredoNew", //Script to be run when the document performs a redo
"onresizeNew", //Script to be run when the window is resized
"onstorageNew", //Script to be run when the document loads
"onundoNew", //Script to be run when the document performs an undo
"onunloadNew", //Script to be run when the user leaves the document

//Form Events

"onblur",  //Script to be run when an element loses focus
"onchange", //Script to be run when an element changes
"oncontextmenuNew",  //Script to be run when a context menu is triggered
"onfocus",  //Script to be run when an element gets focus
"onformchangeNew",  //Script to be run when a form changes
"onforminputNew",  //Script to be run when a form gets user input
"oninputNew",  //Script to be run when an element gets user input
"oninvalidNew",  //Script to be run when an element is invalid
"onselect",  //Script to be run when an element is selected
"onsubmit", //Script to be run when a form is submitted

//Keyboard Events

"onkeydown",  //Script to be run when a key is pressed down
"onkeypress",  //Script to be run when a key is pressed and released
"onkeyup",  //Script to be run when a key is released

//Mouse Events

"onclick",  //Script to be run on a mouse click
"ondblclick",  //Script to be run on a mouse double-click
"ondragNew",  //Script to be run when an element is dragged
"ondragendNew",  //Script to be run at the end of a drag operation
"ondragenterNew",  //Script to be run when an element has been dragged to a valid drop target
"ondragleaveNew",  //Script to be run when an element leaves a valid drop target
"ondragoverNew",  //Script to be run when an element is being dragged over a valid drop target
"ondragstartNew",  //Script to be run at the start of a drag operation
"ondropNew",  //Script to be run when dragged element is being dropped
"onmousedown",  //Script to be run when a mouse button is pressed
"onmousemove",  //Script to be run when the mouse pointer moves
"onmouseout", //Script to be run when the mouse pointer moves out of an element
"onmouseover", //Script to be run when the mouse pointer moves over an element
"onmouseup",  //Script to be run when a mouse button is released
"onmousewheelNew",  //Script to be run when the mouse wheel is being rotated
"onscrollNew",  //Script to be run when an element's scrollbar is being scrolled

//Media Events

"onabort",  //Script to be run on abort
"oncanplayNew", //Script to be run when a file is ready to start playing (when it has buffered enough to begin)
"oncanplaythroughNew", //Script to be run when a file can be played all the way to the end without pausing for buffering
"ondurationchangeNew",  //Script to be run when the length of the media changes
"onemptiedNew",  //Script to be run when something bad happens and the file is suddenly unavailable (like unexpectedly disconnects)
"onendedNew",  //Script to be run when the media has reach the end (a useful event for messages like "thanks for listening")
"onerrorNew",  //Script to be run when an error occurs when the file is being loaded
"onloadeddataNew", //Script to be run when media data is loaded
"onloadedmetadataNew", //Script to be run when meta data (like dimensions and duration) are loaded
"onloadstartNew", //Script to be run just as the file begins to load before anything is actually loaded
"onpauseNew",  //Script to be run when the media is paused either by the user or programmatically
"onplayNew",  //Script to be run when the media is ready to start playing
"onplayingNew",  //Script to be run when the media actually has started playing
"onprogressNew",  //Script to be run when the browser is in the process of getting the media data
"onratechangeNew",  //Script to be run each time the playback rate changes (like when a user switches to a slow motion or fast forward mode)
"onreadystatechangeNew",  //Script to be run each time the ready state changes (the ready state tracks the state of the media data)
"onseekedNew",  //Script to be run when the seeking attribute is set to false indicating that seeking has ended
"onseekingNew",  //Script to be run when the seeking attribute is set to true indicating that seeking is active
"onstalledNew",  //Script to be run when the browser is unable to fetch the media data for whatever reason
"onsuspendNew", //Script to be run when fetching the media data is stopped before it is completely loaded for whatever reason
"ontimeupdateNew", //Script to be run when the playing position has changed (like when the user fast forwards to a different point in the media)
"onvolumechangeNew", //Script to be run each time the volume is changed which (includes setting the volume to "mute")
"onwaitingNew", //Script to be run when the media has paused but is expected to resume (like when the media pauses to buffer more data)

);

foreach($events as $ev) {
	echo ($vars[$ev] ? ' ' . $ev . '="' . htmlspecialchars($vars[$ev]) . '"' : '');
}

?>
