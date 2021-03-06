# CompetRunning

## What is it?

CompetRunning is a command which get competitons of running according to criteria of search.
Results are based on website [http://bases.athle.com/](http://bases.athle.com/).

## Installation

```bash
composer install --no-interaction
```

Change values in file `configuration/app.yaml` if you want.

## Using

### Configuration

Change values in configuration file: `configuration/app.yaml`.

* Year is required.
* Distance is required but value can be "~"
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

#### Challenges

* Km vertical
* Trail Découverte (< 21km)
* Trail Court (21 à 41km)
* Trail Long (42 à 80km)
* Ultra Trail (> 80km)
* 10 Km Route
* 15 Km Route
* 20 Km Route
* 1/2 Marathon
* 30km Route
* Marathon
* 24 Heures
* 100 Km Route


### Execution

#### Simple execution

```bash
./bin/app.php
```

* Start date is asked (value is optional).
* End date is asked (value is optional).
* Results are exported in file in directory build.

#### Execution with options

```bash
./bin/app.php --startDate=2018-07-01 --endDate=2018-07-31
```

* Results are exported in file in directory build.
