var yearBool;
var termBool;
var courseBool;


(function () {
    var httpRequest;
    yeardropper = document.getElementById("yeardrop");

        //tempyear = false;
        yeardropper.onchange = function () {
            yearBool = true;
            resetDefaults();
            //alert(dropper.value);
            makeRequest('http://localhost:8000/public_html/mike/index.php?/administration/ajaxdrop?year=' + yeardropper.value);
        };


        //temp = false;
        termdropper = document.getElementById("termdrop");
        termdropper.onchange = function () {
            termBool = true;
            //alert(dropper.value);
            makeRequest('http://localhost:8000/public_html/mike/index.php?/administration/ajaxdrop?term=' + termdropper.value);
        };

    coursedropper = document.getElementById("coursedrop");

    //tempyear = false;
    coursedropper.onchange = function () {
        courseBool = true;
        //alert(dropper.value);
        makeRequest('http://localhost:8000/public_html/mike/index.php?/administration/ajaxdrop?course=' + coursedropper.value);
    };


    function makeRequest(url) {
        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            httpRequest = new XMLHttpRequest();
        } else if (window.ActiveXObject) { // IE
            try {
                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e) {
                try {
                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch (e) {
                }
            }
        }

        if (!httpRequest) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        httpRequest.onreadystatechange = alertContents;
        httpRequest.open('GET', url);
        httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        httpRequest.send();
    }

    function alertContents() {
        if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
                var data = JSON.parse(httpRequest.response);
                if (yearBool) {
                    var select = document.getElementById('termdrop');
                    if (emptySelect(select)) {
                        for (var i = 0; i < data.term_list.length; i++) {
                           // var el = document.createElement("option");

                            //el.textContent = data.term_list[i].term;
                           // el.value = data.term_list[i].term;
                           // select.appendChild(el);
                            AddItem(data.term_list[i].term, data.term_list[i].term, select);
                            yearBool = false;

                        }
                    }

                }
                else if (termBool) {
                    var select = document.getElementById('coursedrop');
                    if (emptySelect(select)) {
                        for (var i = 0; i < data.course_list.length; i++) {
                            var el = document.createElement("option");

                            el.textContent = data.course_list[i].courseName;
                            el.value = data.course_list[i].courseName;
                            select.appendChild(el);
                            termBool = false;

                        }
                    }

                }
                else if (courseBool) {
                    var select = document.getElementById('sectiondrop');
                    if (emptySelect(select)) {
                        for (var i = 0; i < data.section_list.length; i++) {
                            var el = document.createElement("option");

                            el.textContent = data.section_list[i].section;
                            el.value = data.section_list[i].section;
                            select.appendChild(el);
                            courseBool = false;

                        }
                    }

                }
            } else {
                alert('There was a problem with the request.');
            }
        }
    }

    function emptySelect(select_object) {
        while (select_object.options.length > 0) {
            select_object.remove(0);
        }

        return 1;
    }
    function resetDefaults(){
        var select = document.getElementById('termdrop');
        emptySelect(select);
        AddItem('select year', 'select year', select);
        var select = document.getElementById('coursedrop');
        emptySelect(select);
        AddItem('select year', 'select year', select);
        var select = document.getElementById('sectiondrop');
        emptySelect(select);
        AddItem('select year', 'select year', select);
    }

    function AddItem(Text,Value, selection)
    {

        var opt = document.createElement("option");

        // Add an Option object to Drop Down/List Box
        selection.options.add(opt);

        // Assign text and value to Option object
        opt.text = Text;
        opt.value = Value;

    }
})();
