<?php
//Window Event Attributes

$events = array(
"onafterprint", //Script to be run after the document is printed
"onbeforeprint", //Script to be run before the document is printed
"onbeforeonload", //Script to be run before the document loads
"onblur", //Script to be run when the window loses focus
"onerror", //Script to be run when an error occur
"onfocus", //Script to be run when the window gets focus
"onhaschange", //Script to be run when the document has changed
"onload", //Script to be run when the document loads
"onmessage", //Script to be run when the message is triggered
"onoffline", //Script to be run when the document goes offline
"ononline", //Script to be run when the document comes online
"onpagehide", //Script to be run when the window is hidden
"onpageshow", //Script to be run when the window becomes visible
"onpopstate", //Script to be run when the window's history changes
"onredo", //Script to be run when the document performs a redo
"onresize", //Script to be run when the window is resized
"onstorage", //Script to be run when the document loads
"onundo", //Script to be run when the document performs an undo
"onunload", //Script to be run when the user leaves the document

//Form Events

"onchange", //Script to be run when an element changes
"oncontextmenu",  //Script to be run when a context menu is triggered
"onfocus",  //Script to be run when an element gets focus
"onformchange",  //Script to be run when a form changes
"onforminput",  //Script to be run when a form gets user input
"oninput",  //Script to be run when an element gets user input
"oninvalid",  //Script to be run when an element is invalid
"onselect",  //Script to be run when an element is selected
"onsubmit", //Script to be run when a form is submitted

//Keyboard Events

"onkeydown",  //Script to be run when a key is pressed down
"onkeypress",  //Script to be run when a key is pressed and released
"onkeyup",  //Script to be run when a key is released

//Mouse Events

"onclick",  //Script to be run on a mouse click
"ondblclick",  //Script to be run on a mouse double-click
"ondrag",  //Script to be run when an element is dragged
"ondragend",  //Script to be run at the end of a drag operation
"ondragenter",  //Script to be run when an element has been dragged to a valid drop target
"ondragleave",  //Script to be run when an element leaves a valid drop target
"ondragover",  //Script to be run when an element is being dragged over a valid drop target
"ondragstart",  //Script to be run at the start of a drag operation
"ondrop",  //Script to be run when dragged element is being dropped
"onmousedown",  //Script to be run when a mouse button is pressed
"onmousemove",  //Script to be run when the mouse pointer moves
"onmouseout", //Script to be run when the mouse pointer moves out of an element
"onmouseover", //Script to be run when the mouse pointer moves over an element
"onmouseup",  //Script to be run when a mouse button is released
"onmousewheel",  //Script to be run when the mouse wheel is being rotated
"onscroll",  //Script to be run when an element's scrollbar is being scrolled

//Media Events

"onabort",  //Script to be run on abort
"oncanplay", //Script to be run when a file is ready to start playing (when it has buffered enough to begin)
"oncanplaythrough", //Script to be run when a file can be played all the way to the end without pausing for buffering
"ondurationchange",  //Script to be run when the length of the media changes
"onemptied",  //Script to be run when something bad happens and the file is suddenly unavailable (like unexpectedly disconnects)
"onended",  //Script to be run when the media has reach the end (a useful event for messages like "thanks for listening")
"onloadeddata", //Script to be run when media data is loaded
"onloadedmetadata", //Script to be run when meta data (like dimensions and duration) are loaded
"onloadstart", //Script to be run just as the file begins to load before anything is actually loaded
"onpause",  //Script to be run when the media is paused either by the user or programmatically
"onplay",  //Script to be run when the media is ready to start playing
"onplaying",  //Script to be run when the media actually has started playing
"onprogress",  //Script to be run when the browser is in the process of getting the media data
"onratechange",  //Script to be run each time the playback rate changes (like when a user switches to a slow motion or fast forward mode)
"onreadystatechange",  //Script to be run each time the ready state changes (the ready state tracks the state of the media data)
"onseeked",  //Script to be run when the seeking attribute is set to false indicating that seeking has ended
"onseeking",  //Script to be run when the seeking attribute is set to true indicating that seeking is active
"onstalled",  //Script to be run when the browser is unable to fetch the media data for whatever reason
"onsuspend", //Script to be run when fetching the media data is stopped before it is completely loaded for whatever reason
"ontimeupdate", //Script to be run when the playing position has changed (like when the user fast forwards to a different point in the media)
"onvolumechange", //Script to be run each time the volume is changed which (includes setting the volume to "mute")
"onwaiting", //Script to be run when the media has paused but is expected to resume (like when the media pauses to buffer more data)

);

foreach($events as $ev) {
	echo ($vars[$ev] ? ' ' . $ev . '="' . htmlspecialchars($vars[$ev]) . '"' : '');
}

?>
