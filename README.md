# ACCENT - API Platform route access-control checker

ACCENT (Access Control Checker Easy Neat Thorough) is a Symfony command to check that all your API Platform routes have an access control.

## Installation

### Make sure composer knows how to access the bundle

Add the path to the private repository in your composer.json:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/theodo/accent"
    }
]
```

### Require the bundle

```bash
composer require --dev forge/accent-bundle
```

### Run the command

```bash
bin/console forge:access-control
```
