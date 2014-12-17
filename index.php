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
                box-shadow: 0 0 10px;
            }
        </style>
        <script type="text/javascript" src="algrithim/findAdjacent.js"></script>
        <script type="text/javascript" src="app/Stage.js"></script>
        <script type="text/javascript">
            var processedSet = new WeakSet();

            function bindListener() {
                document.body.addEventListener("mousemove", function (e) {
                    var target = e.target;
                    if (target.id && !processedSet.has(target)) {
                        processedSet.add(target);
                        target.classList.add("pending");
                        getMd5(target);
                    }
                }, false);

                document.body.addEventListener("mouseover", function (e) {
                    var target = e.target;
                    var focus = findAdjacent(parseInt(target.id.replace("_", ""), 10), 3, boxHorizonalCount);
                    removeClass(document.querySelectorAll(".focus"), "focus")

                    var focus_nodes = document.querySelectorAll("#_" + focus.join(",#_"));
                    addClass(focus_nodes, "focus");
                    loadFocus(focus_nodes);

                }, false);

                calculateStageWidth();
                window.addEventListener("resize", calculateStageWidth);
            }
            
            function loadFocus(items) {
                for (var i = 0; i < items.length; i++) {
                    var target = items[i];
                    if (target.id && !processedSet.has(target)) {
                        processedSet.add(target);
                        target.classList.add("pending");
                        getMd5(target);
                    }
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

            function getMd5(target) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        target.classList.remove("pending");
                        target.classList.add("complete");
                        target.title = xhr.responseText;
                    }
                };
                xhr.open("GET", "md5.php?text=" + parseInt(target.id.replace("_", ""), 10));
                xhr.send();
            }

        </script>
    </head>
    <body onload="initStage(1E4);
            bindListener();">
    </body>
</html>
