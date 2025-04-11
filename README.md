# Insurrection
Tentative de faire un site pour aider au partie du JDR insurrection

## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.


### Install / update project

You can install project with the following command:
```bash
make install
```

And update with the following command:
```bash
make update
```

NB: For the components, the `composer.lock` file is not committed.

### Docker commands (for mercure & postgres database)
Starting docker:
```bash
make start
```

Stopping docker:
```bash
make stop
```

Restarting docker:
```bash
make restart
```

### Symfony commands
Clearing cache:
```bash
make cc # clear cache
```

Compiling assets:
```bash
make compile
```

Migrating db:
```bash
make migrate
```

### Testing & CI (Continuous Integration)

#### Tests
You can run unit tests (with coverage) on your side with following command:
```bash
make tests # no available for now
```

For prettier output (but without coverage), you can use the following command:
```bash
make testdox # no available for now - run tests without coverage reports but with prettified output
```

#### Code Style
You also can run code style check with following commands:
```bash
make phpcs
```

You also can run code style fixes with following commands:
```bash
make phpcbf
```

#### Static Analysis
To perform a static analyze of your code (with phpstan, lvl 9 at default), you can use the following command:
```bash
make phpstan
```

To ensure you code still compatible with current supported version at Deezer and futures versions of php, you need to
run the following commands (both are required for full support):

Minimal supported version:
```bash
make phpmin-compatibility
```

Maximal supported version:
```bash
make phpmax-compatibility
```

#### CI Simulation
And the last "helper" commands, you can run before commit and push, is:
```bash
make ci  
```
