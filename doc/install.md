# Installation

## Make sure composer knows how to access the bundle

Add the path to the private repository in your composer.json:
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/theodo/accent"
    }
]
```

## Require the bundle

```
composer require --dev forge/accent-bundle
```
Github will ask you to create a token and to paste it so that it can download the private Github repository.
