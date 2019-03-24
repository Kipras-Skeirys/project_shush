"use strict"


var audio = new Audio('assets/sounds/stairs.mp3');




// - - - PRE-CHAT - - -


// AJAX REQUEST - GETTING PRE-CONNECT URL
function creation(selector){
    let xhttps = new XMLHttpRequest;
    
    // SESSION
    if(selector == 'session'){
        var q = {
            selector: selector,
            quick_join: document.querySelector('#session input[name="quick-join"]').checked ? document.querySelector('#session input[name="buddy-alias"]').value : false,
            alias: document.querySelector('#session input[name="alias"]').value,
            strict_session: document.querySelector('#session input[name="strict-session"]').checked,
            burner_messages: document.querySelector('#session input[name="burner-messages"]').checked ? true : parseInt(document.querySelector('#session input[name="life-span"]').value),
            auto_show: document.querySelector('#session input[name="auto-show"]').checked
        };
        xhttps.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200) {
                if(this.responseText != ""){
                    var s = this.responseText;
                    document.getElementById('session_temp').remove();
                    document.querySelector('#session .pre-connect-session').hidden = false;
                    document.querySelector('#session .pre-connect-session textarea[name="session-link-field"]').innerHTML = window.location.hostname.replace('www.','')+window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/')+1)+'chat.php?q='+this.responseText;
                    document.querySelector('#session .pre-connect-session input[name="join"]').addEventListener('click', function(){
                        document.location = 'chat.php?q='+s;
                    })
                }
            }
        }
        xhttps.open("POST", "process.php", true);
        xhttps.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttps.send('create='+JSON.stringify(q));
    }
    // MESSAGE
    else if(selector == 'message'){
        var q = {
            selector: selector,
            message: document.querySelector('#message textarea[name="message-textarea"]').value,
            burner_message: document.querySelector('#message input[name="burner-message"]').checked ? true : parseInt(document.querySelector('#message input[name="life-span"]').value)
        };
        xhttps.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200) {
                if(this.responseText != ""){
                    var s = this.responseText;
                    document.getElementById('message_temp').remove();
                    document.querySelector('#message .pre-connect-message').hidden = false;
                    document.querySelector('#message .input-field-link').innerHTML = window.location.hostname.replace('www.','')+window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/')+1)+'chat.php?q='+this.responseText;
                    document.querySelector('#message .pre-connect-message input[name="join"]').addEventListener('click', function(){
                        document.location = 'chat.php?q='+s;
                    })
                }
            }
        }
        xhttps.open("POST", "process.php", true);
        xhttps.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttps.send('create='+JSON.stringify(q));
    }
    
}


// COPY TO CLIPBOARD
function copy(el){
    el.nextElementSibling.select();
    document.execCommand("copy");
}




// - - - CHAT - - -


// NON-FUNCTION SCRIPTS ONLY FOR CHAT PAGE
if(window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1) == 'chat.php'){
    
    
    // PINGING SEREVER EVERY X SECONDS
    window.setInterval(pingDB, 1000);
    
    
    //USER STATUS - LOAD
    window.addEventListener('load', function(){statusChange(1);});

    
    // USER STATUS - EXIT
    window.addEventListener('beforeunload', function(){statusChange(0);});
    window.addEventListener('onbeforeunload', function(){statusChange(0);});
    window.addEventListener('unload', function(){statusChange(0);});
    window.addEventListener('pagehide', function(){statusChange(0);});
    
    
    // KEY "ENTER" handler
    let inputEl = document.getElementById("msg-input");
    if(inputEl != null){
        inputEl.addEventListener("keydown", function(el){
            // IF "ENTER" - SEND
            if(event.keyCode == 13 && event.shiftKey == false){
                event.preventDefault();
                sendMsg();
            }

            // EXPANDING TEXT AREA
            setTimeout(function(){
                if (inputEl.scrollHeight <= parseInt(window.getComputedStyle(inputEl, null).getPropertyValue("font-size")) *7){
                    inputEl.style.cssText = "height: auto ";
                    inputEl.style.cssText = 'height:'+ inputEl.scrollHeight + "px";
                };
            },0);
        });
}
    }


// AJAX REQUEST - REVEALING HIDDEN MESSAGES
function querySelectorUpdare(){
    document.querySelectorAll(".show").forEach(function(elem){
        elem.addEventListener("click", function(){
            let msg_id = elem.parentNode.id;
            let dataQuery = "reveal_id="+msg_id;
            let xhttps = new XMLHttpRequest;
            // Responce message
            xhttps.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                    // Replaces Lorem Ipsum with actual message
                    console.log(this.responseText);
//                    let span = elem.nextElementSibling;
//                    let res = JSON.parse(this.responseText);
//                    for (let i of res){
//                        span.innerHTML = '';
//                        span.appendChild(document.createTextNode(i['message']))
//                    }
                    
                    // Remove blur effect
                    span.classList.remove("blur")
                    // Removing "show" button
                    elem.parentNode.removeChild(elem);
                }
            }
            xhttps.open("POST", "process.php", true);
            xhttps.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttps.send(dataQuery);
        }); 
    })
}


// AJAX REQUEST - SENDIND MESSAGES
function sendMsg(){
    let el = document.getElementById("msg-input");
    let msg = el.value;
    el.value = '';
    
    if(msg.replace(/ |\n/g, "") != ""){
        let xhttps = new XMLHttpRequest;
        xhttps.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200) {
                // Pinging db right after sending the message
                pingDB();
            }
        }
        xhttps.open("POST", "process.php", true);
        xhttps.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttps.send("msg="+encodeURIComponent(msg));

    }else{
        //console.log("Empty");
    }
}


// AJAX REQUEST - RECEIVING MESSAGES
function pingDB(){
    let xhttps = new XMLHttpRequest;
     // Responce message
    xhttps.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText != ""){
                console.log(this.responseText);
                let res = JSON.parse(this.responseText);
                if(res['status'] != '' && res['status'] != null){
                    statusCheck(res['status']);
                    delete res['status'];
                }else{
                    delete res['status'];
                }
                for (let i of Object.keys(res)){ 
                    newBubble(res[i]['user_id'], res[i]['message'], res[i]['local_user'], res[i]['id'], res[i]['auto_show'], res[i]['friend_alias']);
                }
            }
        }
    }
    xhttps.open("POST", "process.php", true);
    xhttps.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttps.send("get=true");
}


// CHAT BUBBLE GENERATOR
function newBubble(user_id, message, local_user, msg_id, auto_show, friend_alias){
    // Creating all needed elements for the "bubble"
    let chat_c = document.getElementById("chat-c");
    let firstDiv = document.createElement("div");
    let secondDiv = document.createElement("div");
    let span = document.createElement("span");
    let txtNode = document.createTextNode(message);
    
    // ALIAS ELEMENTS
    let aliasEl = document.createElement("span");
    let aliasNode = document.createTextNode(friend_alias);
    aliasEl.className = 'alias';
    aliasEl.appendChild(aliasNode);
    firstDiv.appendChild(aliasEl);
    
    secondDiv.id = msg_id;
    firstDiv.appendChild(secondDiv);
    secondDiv.appendChild(span);
    span.appendChild(txtNode);
    
    let lastBubble = document.getElementById("chat-c").lastElementChild;
    
    // IS MESSAGE FROM CURRENT USER
    if (user_id == local_user){
        
        // MINI NESTING
        if (lastBubble != null && lastBubble.className == "user0-c"){
            secondDiv.className = 'user0';
            lastBubble.appendChild(secondDiv);
        }else{
            firstDiv.className = 'user0-c';
            secondDiv.className = 'user0';
            chat_c.appendChild(firstDiv);
        }
    }else{
        
        // BLUR TEXT AND CREATE "SHOW" BUTTON
        if (!auto_show){
            let txtNode = document.createTextNode("Show");
            let btnDiv = document.createElement("div");
            let btnSpan = document.createElement("span");
            btnSpan.appendChild(txtNode);
            secondDiv.appendChild(btnDiv);
            btnDiv.appendChild(btnSpan);
            span.className = 'blur';
            btnDiv.className = 'show';
            btnSpan.className = 'chatBtn';
            secondDiv.appendChild(span);
            
        }
        
        // MINI NESTING
        if (lastBubble != null && lastBubble.className == "user1-c"){
            secondDiv.className = 'user1';
            lastBubble.appendChild(secondDiv);
        }else{
            
            
            firstDiv.className = 'user1-c';
            secondDiv.className = 'user1';
            chat_c.appendChild(firstDiv);
        }
        
        // EVENT LISTENERS FOR NEW BUBBLES
        querySelectorUpdare();
        //playSound();
        
    }
    
    jumpToBottom();
}


// JUMP TO BOTTOM OF CHAT WALL
function jumpToBottom(){
    let el = document.getElementById('chat-area');
    el.scrollTop = el.scrollHeight;
}


// CHAT ALERT MESSAGE GENERATOR
function chatAlert(msg){
    let base = document.getElementById("chat-c");
    let span = document.createElement("span");
    span.className = 'in-chat-alert';
    let txt = document.createTextNode(msg);
    span.appendChild(txt);
    base.appendChild(span);
    jumpToBottom();
}


// SOUND EFFECTS
function playSound(){
    document.getElementById('sound-new-msg').play();
    console.log("sound!!");
}


// STATUS CHANGES - USED ONLY ON LOAD OR UNLOAD
function statusChange(status){
    let xhttps = new XMLHttpRequest;
    xhttps.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            chatAlert('You have joined');
            statusCheck(this.responseText);
        }
    }
    xhttps.open("POST", "process.php", false);
    xhttps.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttps.send("status="+status);
}


// STATUS CHECKER - SENDS CORRECT MESSAGE TO chatAlert FUNCTION
function statusCheck(status){
    if(status == '3'){
        chatAlert("Your friend hasn't connected yet...");
    }else if(status == '1'){
        chatAlert('Your friend has connected');
    }else if(status == '0'){
        chatAlert('Your friend has disconnected');
    }
}



