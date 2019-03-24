<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>shush - Create</title>
        <link rel="icon" href="favicon.ico">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    </head>
    <body>
        <div class="content-wraper flex-horizontal-center">
            <!-- Top-bar -->
            <div class="pre-app-bar hero-bar flex-between">
                <img src="assets/images/logo_500x500_color-dark.png" class="logo-container flex-center">
                <a href="index.html" class="color-dark bold"><i class="fas fa-arrow-left"></i>Home page</a>
            </div>
            <!-- Main part -->
            <div class="main-container-wraper flex-horizontal-center flex-column">
                <div class="header-content">
                    <h1 class="large-txt-plus color-dark">Getting things setup...</h1>
                    <h2 class="medium-txt color-dark">Just simply choose between a chat session and a one time message.</h2>
                </div>
                <div class="selector-container" id="selector">
                    <span id="select-1" class="button-round-selected">SESSION</span>
                    <span id="select-2" class="button-round">MESSAGE</span>
                </div>
                <div class="main-conteiner flex-horizontal-center flex-wrap">
                    <div class="triangle"></div>
                    <div class="main-content-conteiner">
                        <!-- SESSION -->
                        <div class="session"  id="session">
                            <div id="session_temp">
                                <p>All you need to do is click <span class="bold">create</span>.</p>
                                <form onSubmit="return false">
                                    <input type="text" class="input-field-white" name="alias" placeholder="Type your alias..." autofocus>
                                    <div class="options-conteiner">
                                        <div class="option0">
                                            <span class="option-name small-txt" data-tooltip="Quick join - test">quick join:</span>
                                            <label class="switch"><input class="option0-input" name="quick-join" type="checkbox"><span class="slider round"></span></label>
                                        </div>
                                    </div>
                                    <input type="submit" class="button-round-selected" onclick="creation('session')" name="create" value="Create">
                                </form>
                                <!-- Options -->
                                <div class="options-wraper" hidden>
                                    <div class="options-title-conteiner flex-vertical-center flex-around">
                                        <div class="line"></div>
                                        <span class="small-txt">Options</span>
                                        <div class="line"></div>
                                    </div>
                                    <div class="options-conteiner">
                                        <div>
                                            <div class="option1">
                                                <span class="option-name small-txt">strict session:</span>
                                                <label class="switch"><input name="strict-session" type="checkbox"><span class="slider round"></span></label>
                                            </div>
                                            <div class="option2">
                                                <span class="option-name small-txt">burner messages:</span>
                                                <label class="switch">
                                                    <input name="burner-messages" class="option2-input" type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                                <div class="life-span" style="display:none">
                                                    <span class="option-name small-txt">life span:</span>
                                                    <input name="life-span" class="life-span-input small-txt" type="number" min="1" max="7" pattern="[0-9]*" value="7">
                                                    <span class="option-name small-txt">days</span>
                                                </div>
                                            </div>
                                            <div class="option3">
                                                <span class="option-name small-txt">auto show:</span>
                                                <label class="switch"><input name="auto-show" type="checkbox" checked><span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Option toggle button -->
                                <div class="options-button small-txt flex flex-column">
                                    <span>Options</span>
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </div>
                            <!-- PRE-CONNECT -->
                            <div class="pre-connect-session" hidden>
                                <p>Hooray! Here’s your unique link.<br>Just <span class="bold">share</span> it with yout buddy and you're all set.</p>
                                <form class="pre-link-conteiner" onSubmit="return false">
                                    <i onclick="copy(this)" class="far fa-clone pre-link-icon"></i>
                                    <textarea rows="1" class="input-field-link" name="session-link-field" readonly></textarea>
                                </form>
                                <input type="submit" class="button-round-selected" name="join" value="Join">
                            </div>
                        </div>
                        <!-- MESSAGE -->
                        <div class="message" id="message" hidden>
                            <div id="message_temp">
                                <p>Type in you message and click <span class="bold">done</span>.</p>
                                <form onsubmit="return false;">
                                    <textarea rows="4" class="input-field-white" placeholder="Message..." name="message-textarea" autofocus></textarea>
                                    <input type="submit" class="button-round-selected" onclick="creation('message')" name="done" value="Done">
                                </form>
                                <!-- Options -->
                                <div class="options-wraper" hidden>
                                    <div class="options-title-conteiner flex-vertical-center flex-around">
                                        <div class="line"></div>
                                        <span class="small-txt">Options</span>
                                        <div class="line"></div>
                                    </div>
                                    <div class="options-conteiner">
                                        <div class="option2">
                                            <span class="option-name small-txt">burner message:</span>
                                            <label class="switch">
                                                <input name="burner-message" class="option2-input" type="checkbox" checked>
                                                <span class="slider round"></span>
                                            </label>
                                            <div class="life-span"  style="display:none">
                                                <span class="option-name small-txt">life span:</span>
                                                <input name="life-span" class="life-span-input small-txt" type="number" min="1" max="7" pattern="[0-9]*" value="7">
                                                <span class="option-name small-txt">days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Option toggle button -->
                                <div class="options-button small-txt flex flex-column">
                                    <span>Options</span>
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </div>
                            <!-- PRE-CONNECT -->
                            <div class="pre-connect-message" hidden>
                                <p>Hooray! Here’s your unique link.<br>Just <span class="bold">share</span> it with yout buddy and you're all set.</p>
                                <div class="pre-link-conteiner">
                                    <i onclick="copy(this)" class="far fa-clone pre-link-icon"></i>
                                    <textarea rows="1" class="input-field-link" name="session-link-field" readonly></textarea>
                                </div>
                                <input type="submit" class="button-round-selected" name="join" value="Join">
                            </div>
                        </div>
                    </div>
                </div>
                <span style="font-size:0.85em;color:grey;text-align:center">*settings and "message" are work in progress and currently, don't function</span>
            </div>
        </div>
        
        <script>
            //OPTIONS HIDE/SHOW
            let buttons = document.querySelectorAll(".options-button");
            for(i of buttons){
                i.addEventListener('click', function(){
                    let prevEl = this.previousElementSibling;
                    let spanEl = this.querySelector('span');
                    let iEl = this.querySelector('i');
                    if (prevEl.hidden){
                        prevEl.hidden = false;
                        spanEl.innerHTML = "Hide";
                        spanEl.style.order = '2';
                        iEl.className = "fas fa-angle-up";
                    }else{
                        prevEl.hidden = true;
                        spanEl.innerHTML = "Options";
                        iEl.style.order = '2';
                        iEl.className = "fas fa-angle-down";
                    }
                })
            }

            //"BURNER MESSAGE" NESTING
            let switches = document.querySelectorAll('.option2-input');
            for (i of switches){
                i.addEventListener('change', function(){
                    let el = this.parentElement.nextElementSibling;
                    this.checked ? el.style.display = 'none' : el.style.display = 'block';
                }) 
            }

            //"QUICK JOIN" NESTING
            document.querySelector('.option0-input').addEventListener('change', function(){
                let el = document.querySelector('input[name="alias"]');
                this.checked ? el.disabled = true : el.disabled = false;
            })

            //SELECTOR
            document.getElementById('selector').addEventListener('click', function(elem){
                if(elem.target.className == 'button-round'){
                    document.querySelector('.button-round-selected').className = 'button-round';
                    elem.target.className = 'button-round-selected';

                    if(elem.target.id == 'select-1'){
                        document.querySelector('.triangle').style.transform = 'translateX(-55px)';
                        document.getElementById('message').hidden = true;
                        document.getElementById('session').hidden = false;
                    }else if(elem.target.id == 'select-2'){
                        document.querySelector('.triangle').style.transform = 'translateX(50px)';
                        document.getElementById('message').hidden = false;
                        document.getElementById('session').hidden = true;
                    }
                }
            })
        </script>
        <script src="assets/scripts/script.js"></script>
    </body>
</html>