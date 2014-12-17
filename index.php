<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style type="text/css">
            body{
                padding: 0;
                margin: 0;
                cursor: crosshair;
            }
            .box{
                float:left;
                width:10px;
                height:10px;
                background: #cccccc;
                margin:1px;
            }
            .pending{
                background: red;
                cursor: wait;
            }
            .complete{
                background: blue;
            }
            .focus{
                box-shadow: 0 0 1px;
            }
        </style>
        <script type="text/javascript" src="algrithim/findAdjacent.js"></script>
        <script type="text/javascript" src="app/Stage.js"></script>
        <script type="text/javascript">
            var processedSet = new WeakSet();

            function immediatelyUpdateNode(node) {
                if (!processedSet.has(node)) {
                    processedSet.add(node);
                    node.classList.add("pending");
                    var number = +node.id.replace("_", "");
                    md5Service(number).then(function (md5String) {
                        node.classList.remove("pending");
                        node.classList.add("complete");
                        node.title = md5String;
                    });
                }
            }

            function getAdjacentNode(node) {
                var focus = findAdjacent(parseInt(node.id.replace("_", ""), 10), 3, boxHorizonalCount);
                return document.querySelectorAll("#_" + focus.join(",#_"));
            }

            function bindListener() {
                document.body.addEventListener("mousemove", function (e) {
                    immediatelyUpdateNode(e.target);
                }, false);

                document.body.addEventListener("mouseover", function (e) {
                    removeClass(document.querySelectorAll(".focus"), "focus");
                    var focus_nodes = getAdjacentNode(e.target);
                    addClass(focus_nodes, "focus");
                    loadFocus(focus_nodes);
                }, false);

                window.addEventListener("resize", calculateStageWidth);
            }

            function loadFocus(items) {
                for (var i = 0; i < items.length; i++) {
                    var target = items[i];
                    immediatelyUpdateNode(target);
                }
            }

            function addClass(items, className) {
                for (var i = 0; i < items.length; i++) {
                    items[i].classList.add(className);
                }
            }

            function removeClass(items, className) {
                for (var i = 0; i < items.length; i++) {
                    items[i].classList.remove(className);
                }
            }

            function md5Service(number) {
                var xhrPromise = new Promise(function (resolve, reject) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                resolve(xhr.responseText);
                            } else {
                                reject(xhr.status);
                            }
                        }
                    };
                    xhr.open("GET", "md5.php?text=" + number);
                    xhr.send();
                });
                return xhrPromise;
            }

        </script>
    </head>
    <body onload="initStage(1E4);
            bindListener();
            calculateStageWidth();">
    </body>
</html>
