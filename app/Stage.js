function initStage(count) {
    var _html = "";
    for (var i = 1; i <= count; i++) {
        _html += "<div class='box' id='_" + i + "'></div>";
    }
    document.body.innerHTML += _html;
}
function calculateStageWidth() {
    var boxStyle = window.getComputedStyle(document.querySelector(".box"));

    var boxMargin = parseInt(boxStyle.margin, 10);
    var boxWidth = parseInt(boxStyle.width, 10);

    var bodyWidth = parseInt(getComputedStyle(document.body).width, 10);

    window.boxHorizonalCount = Math.floor(bodyWidth / (boxWidth + 2 * boxMargin));
}