function findAdjacent(index, distance, width) {
    var result = [];

    // row
    for (var row = -distance; row <= distance; row++) {

        // column
        for (var column = -(Math.min((index - 1) % width, distance)); column <= Math.min((width - (index % width)) % width, distance); column++) {

            result.push((index - width * row) + column);

        }
    }
    result.sort(function (a, b) {
        return a - b;
    });
    return result;
}