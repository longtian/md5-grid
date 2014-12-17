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
                will-change:backgroundColor;
                float:left;
                width:10px;
                height:10px;
                background-color: #cccccc;
                margin:1px;
            }
        </style>
        <script type="text/javascript" src="algrithim/findAdjacent.js"></script>
        <script type="text/javascript" src="app/Stage.js"></script>
        <script type="text/javascript">
            var processedSet = new WeakSet();
            var promises = [];

            function processNode(node) {
                if (!processedSet.has(node)) {
                    processedSet.add(node);
                    node.style.backgroundColor = "red";
                    var number = +node.id.replace("_", "");
                    queueMd5Service(number).then(getSuccessNodeRender(node));
                }
            }

            function getSuccessNodeRender(node) {
                return function (md5String) {
                    node.style.backgroundColor = "blue";
                    node.title = md5String;
                }
            }

            function getAdjacentNode(node) {
                var focus = findAdjacent(parseInt(node.id.replace("_", ""), 10), 10, boxHorizonalCount);
                return document.querySelectorAll("#_" + focus.join(",#_"));
            }

            function bindListener() {
                document.body.addEventListener("mouseover", function (e) {
                    var focus_nodes = getAdjacentNode(e.target);
                    loadFocus(focus_nodes);
                }, false);

                window.addEventListener("resize", calculateStageWidth);
            }

            function loadFocus(items) {
                for (var i = 0; i < items.length; i++) {
                    var target = items[i];
                    processNode(target);
                }
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

            function resolveQueue() {
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
            }

            window.setInterval(resolveQueue, 100);
        </script>
    </head>
    <body onload="initStage(1E4);
            bindListener();
            calculateStageWidth();">
    </body>
</html>
