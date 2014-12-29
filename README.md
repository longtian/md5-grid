md5-grid
========

## Description
```
There are 10000 cells in the browsers, each will represent a md5 result of a integer.
User can hover mouse on it to see the result.  
```

## Highlights
- The calculation is done in the backend using multiplexing to save time.And no duplicated caluation will happen.

- Data is stored using ES6 [WeakMap](http://kangax.github.io/compat-table/es6/#WeakMap)

- The algrithm to predict user's movement is guaranted by  in [Jasmine](http://jasmine.github.io/).

- [Promise](http://caniuse.com/#feat=promises) is used to ger rid of callback hells.

- Developed using [Vanilla JS](http://vanilla-js.com/).

- Devtool profiling used to ensure JankFree(http://jankfree.org/) 

## Latest profiling result
![Result](https://cloud.githubusercontent.com/assets/171947/5568640/60f9b53e-8f9b-11e4-9bd7-da7cc7e314e0.png)

Check the Releases page for [history](https://github.com/wyvernnot/md5-grid/releases)
