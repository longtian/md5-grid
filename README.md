md5-grid
========
There are 10000 cells in the browsers, each will represent a md5 result of a integer.

User can hover mouse on it to see the result.

The calculation is done in the backend using multiplexing to save time.And no duplicated caluation will happen.

Data is stored using ES6 [WeakMap](http://kangax.github.io/compat-table/es6/#WeakMap)

The algrithim to predic user is guaranted by unit tests.
