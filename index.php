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
            var promises = [];

            function immediatelyUpdateNode(node) {
                if (!processedSet.has(node)) {
                    processedSet.add(node);
                    node.classList.add("pending");
                    var number = +node.id.replace("_", "");
                    queueMd5Service(number).then(function (md5String) {
                        node.classList.remove("pending");
                        node.classList.add("complete");
                        node.title = md5String;
                    });
                }
            }

            function getAdjacentNode(node) {
                var focus = findAdjacent(parseInt(node.id.replace("_", ""), 10), 4, boxHorizonalCount);
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

            function queueMd5Service(number) {
                var p = new Promise(function (resolve) {
                    promises.push({
                        number: number,
                        resolve: resolve
                    });
                });
                return p;
            }

            function batchMd5Service(callObject) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            var data = JSON.parse(xhr.responseText);
                            for (var i = 0; i < data.length; i++) {
                                var item = data[i];
                                if (callObject[item["number"]]) {
                                    callObject[item["number"]].resolve(item["md5"]);
                                }
                            }
                        }
                    }
                };
                xhr.open("GET", "md5_batch.php?text=" + Object.keys(callObject).join(","));
                xhr.send();
            }

            window.setInterval(function () {
                var l = promises.length;
                if (l === 0) {
                    return;
                }

                var callObject = {};
                for (var i = 0; i < l; i++) {
                    var item = promises[i];
                    callObject[item.number] = item;
                }

                promises.length = 0;

                batchMd5Service(callObject);

            }, 100)
        </script>
    </head>
    <body onload="initStage(1E4);
                    bindListener();
                    calculateStageWidth();">
    </body>
</html>
