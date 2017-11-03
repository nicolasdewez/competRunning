# CompetRunning

## What is it?

CompetRunning is a command which get competitons of running according to criteria of search.
Results are based on website [http://bases.athle.com/](http://bases.athle.com/).

## Installation

```bash
composer install
```

## Using

### Configuration

Change values in configuration file: `configuration/app.yaml`.

* Year is required.
* Others fields are optional.


#### Data available

#### Type

* Cross
* Hors Stade
* Marche Route
* Salle
* Stade

#### Ligue

* ARA
* BFC
* BRE
* CEN 
* COR
* G-E
* GUA
* GUY
* H-F
* I-F
* MAR
* MAY
* N-A
* N-C
* NOR
* OCC
* PCA
* P-F
* P-L
* REU
* W-F

#### Department

All France's departments on 3 digits.


### Execution

```bash
./bin/app.php
```

* Start date is asked (value is optional).
* End date is asked (value is optional).
* Results are exported in file in directory build.
