# FinalCountdown

Countdown to a given date with real time update.

[![Build Status](https://travis-ci.org/Mathou54/finalCountdown.svg?branch=master)](https://travis-ci.org/Mathou54/finalCountdown)

[Demo page](https://mathou54.github.io/finalCountdown/).

## Usage

### Required parameters

```html
<final-countdown [endDate]="endDate"></final-countdown>
```
Where `endDate` is anything that can be passed to JavaScript's `new Date(endDate)`.

### Optionals parameters

```html
<final-countdown 
  [endDate]="endDate"
  overdue="Overdue!"
  days="days"
  hours="h"
  minutes="m"
  seconds="s"
  left="left."
></final-countdown>
```
Where each optional parameter is a `string` used to compose the countdown display.
The values here are de default one (if not specified) and can be modified.
