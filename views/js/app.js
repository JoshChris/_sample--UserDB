(function (window, document, undefined) {
    'use strict';

    function appInit() {
        if (document.getElementById('submit_btn')) {
            addEvent(
                document.getElementById('submit_btn'),
                'click',
                validate
            );
        }

        if (document.getElementById('keyword')) {
            addEvent(
                document.getElementById('keyword'),
                'keyup',
                search
            );
        }

        if (document.getElementsByClassName('deleteUser')) {
            var classnames = document.getElementsByClassName('deleteUser')
            for(var i=0;i<classnames.length;i++){
                addEvent(
                    classnames[i],
                    'click',
                    function() {
                        console.log(this.getAttribute('data-id'));
                        deleteUser(this.getAttribute('data-id'));
                    }
                );
            }
        }

        if (document.getElementById('country')) {
            var countries = [];
            getAjax('https://restcountries.eu/rest/v2/all?fields=alpha2Code;name', function(data){
                var json = JSON.parse(data);
                for(var i in json) {
                    countries[+i] = [json[i].name, json[i].alpha2Code];
                }
            });

            var demo1 = new autoComplete({
                selector: '#country',
                minChars: 1,
                source: function(term, suggest){
                    term = term.toLowerCase();
                    var choices = countries;
                    var suggestions = [];
                    for (i=0;i<choices.length;i++)
                        if (~(choices[i][0]+' '+choices[i][1]).toLowerCase().indexOf(term)) suggestions.push(choices[i]);
                    suggest(suggestions);
                },
                renderItem: function (item, search){
                    search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&amp;');
                    var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                    return '<div class="autocomplete-suggestion" data-name="'+item[0]+'" data-code="'+item[1]+'" data-val="'+search+'">'+item[0].replace(re, "<b>$1</b>")+'</div>';
                },
                onSelect: function(e, term, item){
                    document.getElementById('country').value = item.getAttribute('data-name');
                    document.getElementById('country_code').value = item.getAttribute('data-code');
                }
            });
        }
    }

    function processform() {
        var data = serialize(document.getElementById("api_form"));
        if (document.getElementById("form_action").value == "create") {
            postAjax("/tonic3-dev-test/app/api/insert/", data, function() {
                document.getElementById("message").innerHTML = "<p class='success'>Successfully added new user!</p>";
            });
        } else if (document.getElementById("form_action").value == "edit") {
            var url = "/tonic3-dev-test/app/api/update/"+document.getElementById("user_id").value+"/";
            putAjax(url, data, function() {
                document.getElementById("message").innerHTML = "<p class='success'>Successfully updated user!</p>";
            });
        }
    }

    function validate() {
        document.getElementById("message").innerHTML = "";
        var errors = 0;
        //validate name
        if (!document.getElementById("firstname").value.length > 0) {
            addClass(document.getElementById("firstname_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Name is a required field</p>';
            errors++;
        }
        //validate surname
        if (!document.getElementById("surname").value.length > 0) {
            addClass(document.getElementById("surname_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Surname is a required field</p>';
            errors++;
        }

        //validate email
        if (!document.getElementById("email").value.length > 0) {
            addClass(document.getElementById("email_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Email is a required field</p>';
            errors++;
        }

        if (!validateEmail(document.getElementById("email").value)) {
            addClass(document.getElementById("email_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Email does not appear to be valid</p>';
            errors++;
        }

        //validate password
        if (document.getElementById("form_action").value == "create") {
            if (!document.getElementById("password").value.length > 0) {
                addClass(document.getElementById("password_element"), "error");
                document.getElementById("message").innerHTML += '<p class="error">Password is a required field</p>';
                errors++;
            }
            var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,64}$/;
            if (!document.getElementById("password").value.match(passw)) {
                addClass(document.getElementById("password_element"), "error");
                document.getElementById("message").innerHTML += '<p class="error">Password must be more than 6 characters and contain at least one number and one uppercase letter.</p>';
                errors++;
            }
        }

        if (document.getElementById("password").value != document.getElementById("confirm_password").value) {
            addClass(document.getElementById("password_element"), "error");
            addClass(document.getElementById("confirm_password_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Passwords do not match</p>';
            errors++;
        }

        //validate country
        if (!document.getElementById("country").value.length > 0) {
            addClass(document.getElementById("country_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Country is a required field</p>';
            errors++;
        }

        //validate phone
        if (!document.getElementById("phone").value.length > 0) {
            addClass(document.getElementById("phone_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Phone is a required field</p>';
            errors++;
        }
        var phoneUtil = i18n.phonenumbers.PhoneNumberUtil.getInstance();
        try {
            var number = phoneUtil.parseAndKeepRawInput(document.getElementById("phone").value, document.getElementById("country_code").value);
            var isNumberValid = phoneUtil.isValidNumber(number);
        } catch (e) {
            addClass(document.getElementById("phone_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">'+e.toString()+'</p>';
            errors++;
        }
        
        if (!isNumberValid) {
            addClass(document.getElementById("phone_element"), "error");
            document.getElementById("message").innerHTML += '<p class="error">Phone number is not a valid '+document.getElementById("country_code").value+' phone number</p>';
            errors++;
        }

        if (errors > 0) {
            return false;
        } else {
            processform();
        }
    }

    var search = debounce(function() {
        document.getElementById('ajax_list').innerHTML = "";
        if (document.getElementById("keyword").value.length) {
            getAjax('/tonic3-dev-test/app/api/search/'+document.getElementById("keyword").value, function(data){
                data = JSON.parse(data);
                var template = document.getElementById('user_template').innerHTML
                var anchor = document.createElement('div')
                // Loop through each object in the people array and create an
                // element based on #user_template HTML
                if (data.length > 0) {
                    data.forEach(function (user) {
                        // Create element containing the HTML included in #user_template
                        var el = document.createElement('div')
                        el.innerHTML = template
                        // Add content to elements idefntified by class name
                        el.getElementsByClassName('user')[0].setAttribute("id", "user"+user.id);
                        el.getElementsByClassName('name')[0].appendChild(document.createTextNode(user.firstname));
                        el.getElementsByClassName('surname')[0].appendChild(document.createTextNode(user.surname));
                        el.getElementsByClassName('email')[0].appendChild(document.createTextNode(user.email));
                        var optionsNode = document.createElement("div");
                            optionsNode.innerHTML = '<a class="editUser" href="/tonic3-dev-test/app/edit/?id='+user.id+'">Edit</a> <a href="#delete" data-id="'+user.id+'" class="deleteUser">Delete</a>';
                        el.getElementsByClassName('options')[0].appendChild(optionsNode);

                        // Add element to anchor, to be rendered when loop has finished
                        // This is used to avoid unnecesary document reflow
                        anchor.appendChild(el)
                    });
                } else {
                    document.getElementById('ajax_list').innerHTML = "No results found";
                }
                // Add anchor to DOM
                document.getElementById('ajax_list').appendChild(anchor);
                document.getElementById('searchlist').style.display = "block";
                document.getElementById('userlist').style.display = "none";
            });
        } else {
            document.getElementById('searchlist').style.display = "none";
            document.getElementById('userlist').style.display = "block";
        }
    }, 250);

    function deleteUser(id) {
        deleteAjax('/tonic3-dev-test/app/api/delete/'+id+'/', function(data){
            document.getElementById("user"+id).remove();
        });
    }

    //run it
    appInit();
})(window, document);


// unscoped helper functions
function addEvent(element, evnt, funct){
    if (element.attachEvent) {
        return element.attachEvent('on'+evnt, funct);
    } else {
        return element.addEventListener(evnt, funct, false);
    }
}

function validateEmail(email) {
    // Now validate the email format using Regex
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i;
    return re.test(email);
}

function addClass(el, className) {
    if (el.classList) el.classList.add(className);
    else if (!hasClass(el, className)) el.className += ' ' + className;
}

function removeClass(el, className) {
    if (el.classList) el.classList.remove(className);
    else el.className = el.className.replace(new RegExp('\\b'+ className+'\\b', 'g'), '');
}

function getAjax(url, success) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('GET', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) success(xhr.responseText);
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send();
    return xhr;
}

function postAjax(url, data, success) {
    var params = typeof data == 'string' ? data : Object.keys(data).map(
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');

    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xhr.open('POST', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
    return xhr;
}

function putAjax(url, data, success) {
    var params = typeof data == 'string' ? data : Object.keys(data).map(
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');

    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xhr.open('PUT', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
    return xhr;
}


function deleteAjax(url, success) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('DELETE', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) success(xhr.responseText);
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send();
    return xhr;
}

// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};